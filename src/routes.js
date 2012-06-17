
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
		var path = require('path');
		if (!path.existsSync('./public/'+tablature+req.params.tablature)) {
			// redirection si le fichier n'existe pas
			res.redirect('/tablatures');
			return;
		}
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
	var params = {
		connected: req.session.connected,
		pseudo : req.session.user.login,
		dateInscription : req.session.user.dateInscription
	}
	if (req.session.error) {
		params.error = req.session.error;
		delete req.session.error;
	}
	if (req.session.success) {
		params.success = req.session.success;
		delete req.session.success;
	}
	res.render('compte', params);
};
exports.comptePost = function(req, res){
	if (forceLogin(req, res))
		return;
	if (!req.body.password || !req.body.confirmPassword) {
		// cancel connection
		res.send();
		return;
	}
	var password = req.body.password;
	if (req.body.confirmPassword !== password) {
		req.session.error = "Les deux mots de passe doivent être identiques.";
		res.redirect(req.url);
	} else {
		var config = require('./config');
		var crypto = require('crypto');
		var encryptedPassword = crypto.createHash('sha1').update(password+config.bdd.salt+req.session.user.login).digest('hex');
		var client = mysql_connect();
		client.query(
			'UPDATE `user` SET `password` = ? WHERE `user`.`id` = ?',
			[encryptedPassword, req.session.user.id],
			function(err){
				client.end();
				if (err) {
					throw err;
				}
				req.session.success = 'Le mot de passe a bien été modifié.';
				res.redirect(req.url);
			});
	}
}

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
		'select id, password, dateInscription from user where login = "'+req.body.login+'" limit 1',
		function(err, results, fields) {
			client.end(); // close sql connection
			if (err) {
				throw err;
			}
			var crypto = require('crypto');
			var password = crypto.createHash('sha1').update(req.body.password+config.bdd.salt+req.body.login).digest('hex');
			if (results.length == 1 && results[0].password == password) {
				req.session.connected = true;
				req.session.user = {
					id: results[0].id,
					login: req.body.login,
					dateInscription: results[0].dateInscription
				}
				delete req.session.error;
			} else {
				req.session.error = "Nom d'utilisateur ou mot de passe incorrect.";
			}
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
			id: 1,
			login: 'Admin',
			dateInscription : '14 juillet 1789'
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
	return (true);
}

exports.midi = function(req, res) {
	var request = require('request');
	var config = require('config');
	request.post({
		url: 'http://localhost:'+config.PHP.port+'/Projet-PLIC-EasyTab/src/php/MIDI.php',
		form: {
			encoded: req.body.encoded
		}
	});
	res.send('true');
}

exports.testP = function(req, res) {
	var request = require('request');
	var myresult = request.get({
		url: 'http://localhost:80/Projet-PLIC-EasyTab/src/php/testP.php',
		form:
		{
			encoded: req.body.encoded
		}
	}
	, function (error, response, body)
	{
		res.send(response);
	});
}


exports.upload = function(req, res) {
	if (forceLogin(req, res))
		return;
	res.render('upload', {
		connected:req.session.connected
	});
}
exports.uploadPost = function(req, res) {
	if (forceLogin(req, res))
		return;
	if (req.files.tablature && req.files.tablature.type !== 'text/xml') {
		res.send('Erreur : vous devez uploader un fichier XML');
		return;
	}
	var titre = req.body.titre; //TODO escape?
	var artiste = req.body.artiste; //TODO escape?
	var fs = require('fs');
	fs.rename(req.files.tablature.path, __dirname + '/public/upload/'+req.files.tablature.name, function(err) {
		if(err) {
			throw err;
		}
		var client = mysql_connect();
		client.query(
			'INSERT INTO `tablature` (`userId` ,`nom` ,`chemin`, `titre`, `artiste`) values (?, ?, ?, ?, ?)',
			[req.session.user.id, req.files.tablature.name, 'upload/', titre, artiste],
			function(err, results, fields){
				client.end();
				if(err) {
					throw err;
				}
				res.redirect('/tablatures');
			});
	});
}

exports.tablatures = function(req, res) {
	if (forceLogin(req, res))
		return;
	tablatureSearch(req, res, undefined, false);
}

exports.search = function(req, res) {
	if (forceLogin(req, res))
		return;
	var recherche = req.params.search;
	tablatureSearch(req, res, recherche, true);
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

function tablatureSearch(req, res, recherche, json) {
	var userId = req.session.user.id;
	var sql = 'SELECT nom, titre, artiste FROM `tablature` WHERE `userid` = ?';
	var match = [userId];
	if (recherche !== undefined) {
		sql += ' AND (`nom` LIKE ? OR `titre` LIKE ? OR `artiste` LIKE ?)';
		recherche = '%'+recherche+'%';
		match.push(recherche, recherche, recherche);
	}
	var client = mysql_connect();
	var q = client.query(
		sql,
		match,
		function(err, results, fields) {
			client.end(); // close sql connection
			if (err) {
				throw err;
			}
			if (json) {
				res.send(JSON.stringify(results));
			} else {
				res.render('tablatures', {
					pistes: results,
					connected: req.session.connected
				});
			}
		}
		);
}
