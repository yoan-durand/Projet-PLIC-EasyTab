
exports.crash = function(req, res, next){//TODO à supprimer pour la mise en production
	res.send('<form method="post"><input type="submit"></form>');
}
exports.crashPost = function(req, res, next){//TODO à supprimer pour la mise en production
	process.exit(Math.floor(Math.random()*10));
}

exports.index = function(req, res, next){
	if (forceLogin(req, res))
		return;
	var params = getRenderParams(req, true);
	tablatureSearch(req, res, next, undefined, true, function(results){
		params.pistes = results;
		res.render('index', params);
	});
};

exports.application = function(req, res, next){
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
		res.redirect('/application;demo.xml');
		return;
	}
	var userId = req.session.user.id;
	var crypto = require('crypto');
	var midiPath = config.midi.dir+crypto.createHash('md5').update(tablature+'||'+userId).digest('hex')+'.mid';
	if (!path.existsSync('./public/'+midiPath)) {
		midiPath = '';
	}
	var bdd = mysql_connect();
	bdd.query(
		'SELECT id, titre, artiste FROM `tablature` where nom = ?',
		[req.params.tablature.slice(0,-4)],
		function(err, results, fields) {
			bdd.end(); // close sql connection
			if (err) {
				next(new Error(JSON.stringify(err)));
				return;
			}
			if (results.length === 0) {
				next(new Error("La tablature n'est plus dans la base de données"));
				return;
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

exports.creerCompte = function(req, res, next) {
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
exports.creerComptePost = function(req, res, next) {
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
	var encryptedPassword = encryptPassword(password, pseudo);
	var client = mysql_connect();
	client.query(
		'insert `user` (dateInscription, login, password) values (?, ?, ?)',
		[now(), pseudo, encryptedPassword],
		function(err){
			client.end();
			if (err) {
				next(new Error(JSON.stringify(err)));
				return;
			}
			req.session.success = 'Le compte "'+pseudo+'" a été créé avec succès.';
			res.redirect('/');
		});
}
exports.compte = function(req, res, next){
	if (forceLogin(req, res))
		return;
	var dateInscription = new Date(parseInt(req.session.user.dateInscription));
	var params = {
		connected: req.session.connected,
		pseudo : req.session.user.login,
		dateInscription : (dateInscription.getDate() < 10 ? '0'+dateInscription.getDate() : dateInscription.getDate())+"/"+(dateInscription.getMonth() < 10 ? '0'+dateInscription.getMonth() : dateInscription.getMonth())+"/"+dateInscription.getFullYear()
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
exports.comptePost = function(req, res, next){
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
					next(new Error(JSON.stringify(err)));
					return;
				}
				req.session.success = 'Le mot de passe a bien été modifié.';
				res.redirect(req.url);
			});
	}
}

exports.login = function(req, res, next){
	req.session.regenerate(function(e){
		req.session.connected = false;
		req.session.redirect = '/';
		res.render('login', {
			connected:req.session.connected
		});
	});
};
exports.loginPost = function(req, res, next){
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
				next(new Error(JSON.stringify(err)));
				return;
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
exports.logout = function(req, res, next){
	req.session.regenerate(function(e){
		req.session.connected = false;
		res.redirect('/');
	});
};
function forceLogin(req, res, next) {
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

exports.midi = function(req, res, next) {
	var request = require('request');
	var config = require('./config');
	request.post({
		url: 'http://localhost:'+config.PHP.port+'/Projet-PLIC-EasyTab/src/php/MIDI.php',
		form: req.body
	}, function(error, response, body) {
		if (error) {
			next(new Error(JSON.stringify(error)));
			return;
		}
		//if (response.statusCode == 404) {}
		res.send(response.body);
	});
}

exports.upload = function(req, res, next) {
	if (forceLogin(req, res))
		return;
	res.render('upload', {
		connected:req.session.connected
	});
}
exports.uploadPost = function(req, res, next) {
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
			next(new Error(JSON.stringify(err)));
			return;
		}
		var client = mysql_connect();
		client.query(
			'INSERT INTO `tablature` (`userId` ,`nom` ,`chemin`, `titre`, `artiste`, `public`) values (?, ?, ?, ?, ?, ?)',
			[req.session.user.id, name, 'upload/', titre, artiste, visibilitéPublique],
			function(err, results, fields){
				client.end();
				if(err) {
					next(new Error(JSON.stringify(err)));
					return;
				}
				res.redirect('/tablatures');
			});
	});
}

exports.tablatures = function(req, res, next) {
	if (forceLogin(req, res))
		return;
	tablatureSearch(req, res, next, undefined, false, function(results) {
		res.render('tablatures', {
			pistes: results,
			connected: req.session.connected
		});
	}, undefined, req.session.user.id);
}
exports.getTablatures = function(req, res, next) {
	if (forceLogin(req, res))
		return;
	tablatureSearch(req, res, next, undefined, true, function(results){
		res.send(JSON.stringify(results));
	}, undefined, req.session.user.id);
}
exports.tablaturesVisibility = function(req, res, next) {
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
				next(new Error(JSON.stringify(err)));
				return;
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
							next(new Error(JSON.stringify(err)));
							return;
						}
						res.redirect('/tablatures');
					});
			}
		});
}
exports.tablaturesSuppression = function(req, res, next) {
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
				next(new Error(JSON.stringify(err)));
				return;
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
							next(new Error(JSON.stringify(err)));
							return;
						}
						res.redirect('/tablatures');
					});
			}
		});
}

exports.search = function(req, res, next) {
	if (forceLogin(req, res))
		return;
	var recherche = req.params.search;
	var option = req.params.option;
	var user = parseInt(req.params.user);
	if (!user) {
		user = undefined;
	}
	tablatureSearch(req, res, next, recherche, false, function(results) {
		res.send(JSON.stringify(results));
	}, option, user);
}
exports.search2 = function(req, res, next) {
	if (forceLogin(req, res))
		return;
	var recherche = req.params.search;
	var option = req.params.option;
	var user = parseInt(req.params.user);
	if (!user) {
		user = undefined;
	}

	tablatureSearch(req, res, next, recherche, false, function(results) {
		var params = getRenderParams(req, true);
		params.pistes = results;
		params.userId = user;
		res.render('search', params);
	}, option, user);
}

exports.profil = function(req, res, next) {
	if (forceLogin(req, res))
		return;
	var params = getRenderParams(req, true);
	var client = mysql_connect();
	var id = req.params.userId;
	params.userId = id;
	client.query(
		'SELECT `login`,`dateInscription`, count(*) as nbTablature FROM `user` join tablature on tablature.`userId` = `user`.`id` WHERE `user`.`id` = ?',
		[id],
		function(err, results, fields) {
			client.end(); // close sql connection
			if (err) {
				next(new Error(JSON.stringify(err)));
				return;
			}
			params.pseudo = results[0].login;
			if (results[0].dateInscription === 0) {
				params.inscritDepuis = 'Toujours';
			} else {
				var dateInscription = new Date(parseInt(results[0].dateInscription))
				params.inscritDepuis = (dateInscription.getDate() < 10 ? '0'+dateInscription.getDate() : dateInscription.getDate())+"/"+(dateInscription.getMonth() < 10 ? '0'+dateInscription.getMonth() : dateInscription.getMonth())+"/"+dateInscription.getFullYear();
			}
			params.nbTablature = results[0].nbTablature;
			res.render('profil', params);
		}
	);
}

exports.commentaire = function(req, res, next) {
	if (forceLogin(req, res))
		return;
	var client = mysql_connect();
	var tablatureId = req.params.tablatureId;
	client.query(
		'SELECT comment.`id`,`auteurId`,`texte`, date, login FROM `comment` JOIN user ON user.id = comment.auteurId WHERE `tablatureId` = ? ORDER BY `comment`.`id` ASC',
		[tablatureId],
		function(err, results, fields) {
			client.end(); // close sql connection
			if (err) {
				next(new Error(JSON.stringify(err)));
				return;
			}
			results.date = conversionTemps(now() - results.date);
			res.send(JSON.stringify(results));
		}
	);
};
exports.addCommentaire = function(params, callback) {
	var client = mysql_connect();
	client.query(
		'INSERT INTO `easytab`.`comment` (`auteurId`, `tablatureId`, `texte`, `date`) VALUES (?, ?, ?, ?)',
		[params.auteurId, params.tablatureId, params.texte, now()],
		function(err, results, fields) {
			if (err) {
				console.error(err);
			}
			params.id = results.insertId;
			client.query(
				'SELECT `login` FROM `user` WHERE `id` = ? LIMIT 1',
				[params.auteurId],
				function(err, results, fields) {
					client.end(); // close sql connection
					if (err) {
						console.error(err);
					}
					params.login = results[0].login;
					callback(params);
				}
			);
		}
	);
};
exports.supprCommentaire = function(req, res, next) {
	var id = parseInt(req.params.commentId);
	if (id >= 0) {
		var client = mysql_connect();
		client.query(
			'DELETE from `comment` WHERE id = ?',
			[id],
			function(err, results, fields) {
				client.end(); // close sql connection
				if (err) {
					console.error(err);
				}
				res.send(JSON.stringify(true));
			}
		);
	} else {
		res.send(JSON.stringify(false));
	}
};


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

function tablatureSearch(req, res, next, filter, publicOnly, callback, option, user) {
	var sql = 'SELECT tablature.id, nom, titre, artiste, public, userId, user.login FROM `tablature` JOIN `user` ON tablature.userId = user.id WHERE ';
	var match = [];
	sql += '((`public` = 0 AND `userId` = ?) OR (`public` = 1))';
	match.push(req.session.user.id);
	if (user) {
		sql += ' AND `userid` = ?';
		match.push(user);
	}
	if (filter !== undefined) {
		sql += ' AND (`nom` LIKE ? OR `titre` LIKE ? OR `artiste` LIKE ?)';
		filter = '%'+filter+'%';
		match.push(filter, filter, filter);
	}
	if (option !== undefined) {
		if (option === 'alpha') {
			sql += ' ORDER BY nom';
		} else if (option === 'date') {
			sql += ' ORDER BY id';
		}
	}
	var client = mysql_connect();
	client.query(
		sql,
		match,
		function(err, results, fields) {
			client.end(); // close sql connection
			if (err) {
				next(new Error(JSON.stringify(err)));
				return;
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
/**
 * @param {bool} gestionErreurs (optionnel) indique si les erreurs sont gérée par cette page
 */
function getRenderParams(req, gestionErreurs) {
	var params = {
		connected: req.session.connected
	};
	if (gestionErreurs) {
		if (req.session.error) {
			params.error = req.session.error;
			delete req.session.error;
		}
		if (req.session.success) {
			params.success = req.session.success;
			delete req.session.success;
		}
	}
	return params;
}
function conversionTemps(temps) {
	// repassons en secondes
	temps /= 1000;
	var nbJours=Math.floor(temps/(3600*24));
	if (nbJours) {
		return nbJours + ' jours';
	}
	var nbHeures=Math.floor(temps/(3600));
	if (nbHeures) {
		return nbHeures + ' heures';
	}
	var nbMinutes=Math.floor(temps/(60));
	if (nbMinutes) {
		return nbMinutes + ' minutes';
	}
	return temps + ' secondes';
}
function now() {
	return (new Date()).getTime();
}

