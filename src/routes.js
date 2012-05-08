
exports.index = function(req, res){
	if (forceLogin(req, res))
		return;
	res.render('index', {
		connected:req.session.connected
	});
};

exports.application = function(req, res){
	//if (forceLogin(req, res))
	//	return;
	res.render('application', {
		connected:req.session.connected
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
	var params = {
		connected:req.session.connected
	};
	var client = mysql.createClient({
		user: config.bdd.user,
		password: config.bdd.pass
	});
	client.useDatabase(config.bdd.name);
	client.query(
		'select password from user where login = "'+req.body.login+'" limit 1',
		function(err, results, fields) {
			if (err) {
				throw err;
			}
			var crypto = require('crypto');
			var password = crypto.createHash('sha1').update(req.body.password+'3'+req.body.login).digest('hex');
			if (results.length == 1 && results[0].password == password) {
				req.session.connected = true;
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
	req.session.destroy();
	res.redirect('/');
};

function forceLogin(req, res) {
	if (req.session.connected !== undefined) {
		return false;
	}
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
		url: 'http://localhost:81/Projet-PLIC-EasyTab/src/php/MIDI.php',
		form: {
			encoded: req.body.encoded
		}
	});
	res.send('true');
}
