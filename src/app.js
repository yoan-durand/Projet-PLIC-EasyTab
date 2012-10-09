
/**
 * Module dependencies.
 */

var express = require('express'),
swig = require('swig'),
routes = require('./routes');

var app = module.exports = express.createServer();

// Configuration

app.configure(function(){
	// template engine
	app.register('.html', swig);
	app.set('view engine', 'html');
	swig.init({
		root: __dirname + '/views',
		allowErrors: true
	});
	app.set('views', __dirname + '/views');
	app.set('view options', {
		layout: false
	});
	app.set('view cache', true);

	// cookies
	app.use(express.cookieParser());
	app.use(express.session({
		secret: 'secret u20acQzs^$~{eZSn'
	}));

	app.use(express.bodyParser());
	app.use(express.methodOverride());
	app.use(app.router);
	app.use(express.static(__dirname + '/public'));
});

app.configure('development', function(){
	app.use(express.errorHandler({
		dumpExceptions: true,
		showStack: true
	}));
});

app.configure('production', function(){
	app.use(express.errorHandler());
});

// Routes

app.get('/', routes.index);
app.get('/crash', routes.crash);
app.post('/crash', routes.crashPost);
app.get('/application;?:tablature?', routes.application);
app.get('/compte/creer', routes.creerCompte);
app.post('/compte/creer', routes.creerComptePost);
app.get('/compte', routes.compte);
app.post('/compte', routes.comptePost);
app.get('/login', routes.login);
app.post('/login', routes.loginPost);
app.get('/logout', routes.logout);
app.get('/midi', routes.midi);
app.post('/midi', routes.midi);
app.get('/tablatures', routes.tablatures);
app.get('/tablatures/get/:type', routes.getTablatures);
app.get('/tablatures/:id/visibility/:visibility', routes.tablaturesVisibility);
app.get('/tablatures/:id/suppression', routes.tablaturesSuppression);
app.get('/search/u\::user/o\::option/:search?', routes.search2);
app.post('/search/u\::user/o\::option/:search?', routes.search);
app.post('/search/u\::user/:search?', routes.search);
app.post('/search/u;:user/o;:option/:search?', routes.search);
app.post('/search/o;:option/:search?', routes.search);
app.post('/search/:search?', routes.search);
app.get('/upload', routes.upload);
app.post('/upload', routes.uploadPost);
app.get('/user/:userId/:username', routes.profil);

app.listen(8080, function(){
	console.log("Express server listening on port %d in %s mode", app.address().port, app.settings.env);
	var config = require('./config');
	var fs = require('fs');
	var path = require('path');
	if (!path.existsSync('./public/'+config.upload.dir)) {
		fs.mkdir('./public/'+config.upload.dir);
	}
	if (!path.existsSync('./public/'+config.midi.dir)) {
		fs.mkdir('./public/'+config.midi.dir);
	}
});
