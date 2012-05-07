
exports.index = function(req, res){
	if (forceLogin(req, res))
		return;
	res.render('index', {
		connected:req.session.connected
	});
};

exports.application = function(req, res){
	if (forceLogin(req, res))
		return;
	var config = require('./config');
	var tablature = config.upload.dir;
	if (req.params.tablature) {
		tablature += req.params.tablature;
	} else {
		tablature += 'demo.xml'
	}
	res.render('application', {
		connected: req.session.connected,
		tablature: tablature
	});
};

exports.compte = function(req, res){
	if (forceLogin(req, res))
		return;

	var mysql = require('mysql');
	var config = require('./config');
	var params = {
		connected:req.session.connected
	};
	var client = mysql.createClient({
		user: config.bdd.user,
		password: config.bdd.pass
	});
	client.useDatabase(config.bdd.name);
	client.query(
		'select * from user limit 1',
		function(err, results, fields) {
			if (err) {
				throw err;
			}
			params.pseudo = results[0].login;
			params.dateInscription = results[0].dateInscription;
			client.end();
			res.render('compte', params);
		}
		);

};

exports.login = function(req, res){
	req.session.regenerate(function(e){
		req.session.connected = false;
		req.session.redirect = '/';
		res.render('login', {
			connected:req.session.connected
		});
	});
};

exports.loginPost = function(req, res){
	if (!req.body.login) {
		return;
	}
	var mysql = require('mysql');
	var config = require('./config');
	var client = mysql.createClient({
		user: config.bdd.user,
		password: config.bdd.pass
	});
	client.useDatabase(config.bdd.name);
	client.query(
		'select id, password from user where login = "'+req.body.login+'" limit 1',
		function(err, results, fields) {
			if (err) {
				throw err;
			}
			var crypto = require('crypto');
			var password = crypto.createHash('sha1').update(req.body.password+config.bdd.salt+req.body.login).digest('hex');
			if (results.length == 1 && results[0].password == password) {
				req.session.connected = true;
				req.session.user = {
					id: results[0].id
				}
				delete req.session.error;
			} else {
				req.session.error = "Nom d'utilisateur ou mot de passe incorrect.";
			}
			client.end(); // close sql connection
			res.redirect(req.session.redirect);
		}
		);
};

exports.logout = function(req, res){
	req.session.regenerate(function(e){
		req.session.connected = false;
		res.redirect('/');
	});
};

function forceLogin(req, res) {
	if (req.session.connected === true) {
		return false;
	}
	//( connexion automatique à la première visite (pour faciliter le dev)
	if (req.session.connected === undefined) {
		req.session.connected = true;
		req.session.user = {
			id: 1
		};
		return false;
	}
	//*/
	var params = {
		connected: req.session.connected
	}
	if (req.session.error) {
		params.error = req.session.error;
		delete req.session.error;
	}
	res.render('login', params);
	req.session.redirect = req.url;
	return true;
}

exports.midi = function(req, res) {
	var request = require('request');
	request.post({
		url: 'http://localhost/Projet-PLIC-EasyTab/src/php/MIDI.php',
		form: {
			encoded: req.body.encoded
		}
	});
	res.send('true');
}

exports.upload = function(req, res) {
	if (forceLogin(req, res))
		return;
	res.render('upload', {
		connected:req.session.connected
	});
}
exports.uploadPost = function(req, res) {
	if (req.files.tablature && req.files.tablature.type !== 'text/xml') {
		res.send('Erreur : vous devez uploader un fichier XML');
		return;
	}
	var fs = require('fs');
	fs.rename(req.files.tablature.path, __dirname + '/public/upload/'+req.files.tablature.name, function(err) {
		if(err) {
			throw err;
		}
		var client = mysql_connect();
		client.query(
			'INSERT INTO `tablature` (`userId` ,`nom` ,`chemin`) values (?, ?, ?)',
			[req.session.user.id, req.files.tablature.name, 'upload/'],
			function(err, results, fields){
				client.end();
				if(err) {
					throw err;
				}
				res.send('ok');
			});
	});
}

exports.tablatures = function(req, res) {
	if (forceLogin(req, res))
		return;
	var client = mysql_connect();
	client.query(
		'select nom, chemin from tablature where userId = ?',
		[req.session.user.id],
		function(err, results, fields) {
			if (err) {
				throw err;
			}
			client.end(); // close sql connection
			res.render('tablatures', {
				pistes: results,
				connected: req.session.connected
			});
		}
		);
}

function mysql_connect() {
	var mysql = require('mysql');
	var config = require('./config');
	var client = mysql.createClient({
		user: config.bdd.user,
		password: config.bdd.pass
	});
	client.useDatabase(config.bdd.name);
	return client;
}