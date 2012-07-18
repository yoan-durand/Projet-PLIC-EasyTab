
exports.index = function(req, res){
	if (forceLogin(req, res))
		return;
	var params = {
		connected: req.session.connected
	}
	if (req.session.error) {
		params.error = req.session.error;
		delete req.session.error;
	}
	if (req.session.success) {
		params.success = req.session.success;
		delete req.session.success;
	}
	tablatureSearch(req, res, undefined, true, function(results){
		params.pistes = results;
		res.render('index', params);
	});
};

exports.application = function(req, res){
	if (forceLogin(req, res))
		return;
	var config = require('./config');
	var tablature = config.upload.dir;
	var path = require('path');
	if (req.params.tablature) {
		if (!path.existsSync('./public/'+tablature+req.params.tablature)) {
			// redirection si le fichier n'existe pas
			res.redirect('/tablatures');
			return;
		}
		tablature += req.params.tablature;
	} else {
		tablature = 'demo';
	}
	var userId = req.session.user.id;
	var crypto = require('crypto');
	var midiPath = config.midi.dir+crypto.createHash('md5').update(tablature+'||'+userId).digest('hex')+'.mid';
	if (!path.existsSync('./public/'+midiPath)) {
		midiPath = '';
	}
	var bdd = mysql_connect();
	bdd.query(
		'SELECT titre, artiste FROM `tablature` where nom = ?',
		[req.params.tablature.slice(0,-4)],
		function(err, results, fields) {
			bdd.end(); // close sql connection
			if (err) {
				throw err;
			}
			if (results.length = 0) {
				throw 'gestion des erreurs';
			}
			res.render('application', {
				connected: req.session.connected,
				tablature: tablature,
				userId: userId,
				midiPath: midiPath,
				tablatureInfo: results[0]
			});
		}
		);
};

exports.creerCompte = function(req, res) {
	var params = {
		connected: req.session.connected
	}
	if (req.session.error) {
		params.error = req.session.error;
		delete req.session.error;
	}
	if (req.session.success) {
		params.success = req.session.success;
		delete req.session.success;
	}
	res.render('creerCompte', params);
}
exports.creerComptePost = function(req, res) {
	var pseudo = req.body.pseudo;
	var password = req.body.password;
	var confirmPassword = req.body.confirmPassword;
	if (!pseudo || !password || !confirmPassword) {
		// cancel connection
		res.send();
		return;
	}
	if (confirmPassword !== password) {
		req.session.error = "Les deux mots de passe doivent être identiques.";
		res.redirect(req.url);
		return;
	}
	var now = (new Date()).getTime();
	var encryptedPassword = encryptPassword(password, pseudo);
	var client = mysql_connect();
	client.query(
		'insert `user` (dateInscription, login, password) values (?, ?, ?)',
		[now, pseudo, encryptedPassword],
		function(err){
			client.end();
			if (err) {
				throw err;
			}
			req.session.success = 'Le compte "'+pseudo+'" a été créé avec succès.';
			res.redirect('/');
		});
}
exports.compte = function(req, res){
	if (forceLogin(req, res))
		return;
	var dateInscription = new Date(parseInt(req.session.user.dateInscription));
	var params = {
		connected: req.session.connected,
		pseudo : req.session.user.login,
		dateInscription : dateInscription
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
		var encryptedPassword = encryptPassword(password, pseudo);
		var userId = req.session.user.id;
		var client = mysql_connect();
		client.query(
			'UPDATE `user` SET `password` = ? WHERE `user`.`id` = ?',
			[encryptedPassword, userId],
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
			dateInscription : '-5694963725000'
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
	if (req.session.success) {
		params.success = req.session.success;
		delete req.session.success;
	}
	res.render('login', params);
	req.session.redirect = req.url;
	return (true);
}

exports.midi = function(req, res) {
	var request = require('request');
	var config = require('./config');
	request.post({
		url: 'http://localhost:'+config.PHP.port+'/Projet-PLIC-EasyTab/src/php/MIDI.php',
		form: req.body
	}, function(error, response, body) {
		if (error) {
			throw error;
		}
		//if (response.statusCode == 404) {}
		res.send(response.body);
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
	var name = req.files.tablature.name.slice(0,-4);
	var titre = req.body.titre; //TODO escape?
	var artiste = req.body.artiste; //TODO escape?
	var visibilitéPublique = req.body.visibilité == 'publique';
	var fs = require('fs');
	fs.rename(req.files.tablature.path, __dirname + '/public/upload/'+name+'.xml', function(err) {
		if(err) {
			throw err;
		}
		var client = mysql_connect();
		client.query(
			'INSERT INTO `tablature` (`userId` ,`nom` ,`chemin`, `titre`, `artiste`, `public`) values (?, ?, ?, ?, ?, ?)',
			[req.session.user.id, name, 'upload/', titre, artiste, visibilitéPublique],
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
	tablatureSearch(req, res, undefined, false, function(results) {
		res.render('tablatures', {
			pistes: results,
			connected: req.session.connected
		});
	});
}
exports.getTablatures = function(req, res) {
	if (forceLogin(req, res))
		return;
	tablatureSearch(req, res, undefined, true, function(results){
		res.send(JSON.stringify(results));
	});
}
exports.tablaturesVisibility = function(req, res) {
	if (forceLogin(req, res))
		return;
	var id = parseInt(req.params.id, 10);
	var visibility = parseInt(req.params.visibility, 10);
	if (!id) {
		res.redirect('/tablatures');
	}
	var userId = req.session.user.id;
	var bdd = mysql_connect();
	bdd.query('SELECT count(*) FROM `tablature` where `id`=? and `userId`=?',
		[id, userId],
		function (err, results, fields){
			if(err) {
				bdd.end();
				throw err;
			}
			if (results[0]['count(*)'] == 0) {
				bdd.end();
				res.redirect('/tablatures');
			} else {
				bdd.query('UPDATE `easytab`.`tablature` SET `public` = ? WHERE `tablature`.`id` =?;',
					[visibility, id],
					function (err, results, fields){
						bdd.end();
						if(err) {
							throw err;
						}
						res.redirect('/tablatures');
					});
			}
		});
}
exports.tablaturesSuppression = function(req, res) {
	if (forceLogin(req, res))
		return;
	var id = parseInt(req.params.id, 10);
	if (!id) {
		res.redirect('/tablatures');
	}
	var userId = req.session.user.id;
	var bdd = mysql_connect();
	bdd.query('SELECT count(*) FROM `tablature` where `id`=? and `userId`=?',
		[id, userId],
		function (err, results, fields){
			if(err) {
				bdd.end();
				throw err;
			}
			if (results[0]['count(*)'] == 0) {
				bdd.end();
				res.redirect('/tablatures');
			} else {
				bdd.query('delete from `tablature` where `id`=?',
					[id],
					function (err, results, fields){
						bdd.end();
						if(err) {
							throw err;
						}
						res.redirect('/tablatures');
					});
			}
		});
}

exports.search = function(req, res) {
	if (forceLogin(req, res))
		return;
	var recherche = req.params.search;
	tablatureSearch(req, res, recherche, false, function(results) {
		res.send(JSON.stringify(results));
	});
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

function tablatureSearch(req, res, filter, publicOnly, callback) {
	var sql = 'SELECT id, nom, titre, artiste, public FROM `tablature` WHERE ';
	var match = [];
	if (publicOnly) {
		sql += '`public` = 1';
	} else {
		sql += '`userid` = ?';
		var userId = req.session.user.id;
		match.push(userId);
	}
	if (filter !== undefined) {
		sql += ' AND (`nom` LIKE ? OR `titre` LIKE ? OR `artiste` LIKE ?)';
		filter = '%'+filter+'%';
		match.push(filter, filter, filter);
	}
	var client = mysql_connect();
	client.query(
		sql,
		match,
		function(err, results, fields) {
			client.end(); // close sql connection
			if (err) {
				throw err;
			}
			callback(results);
		}
		);
}

function encryptPassword(password, login) {
	var config = require('./config');
	var crypto = require('crypto');
	return crypto.createHash('sha1').update(password+config.bdd.salt+login).digest('hex');
}