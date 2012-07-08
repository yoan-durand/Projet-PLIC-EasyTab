
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
/*
	// file upload
	app.use(connect.multipart({
		uploadDir: __dirname + 'upload'
	}));
*/
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
app.get('/application;?:tablature?', routes.application);
app.get('/compte', routes.compte);
app.post('/compte', routes.comptePost);
app.get('/login', routes.login);
app.post('/login', routes.loginPost);
app.get('/logout', routes.logout);
app.get('/midi', routes.midi);
app.post('/midi', routes.midi);
app.get('/testP', routes.testP);
app.get('/tablatures', routes.tablatures);
app.get('/tablatures/:id/visibility/:visibility', routes.tablaturesVisibility);
app.post('/search/:search?', routes.search);
app.get('/upload', routes.upload);
app.post('/upload', routes.uploadPost);

app.listen(8080, function(){
	console.log("Express server listening on port %d in %s mode", app.address().port, app.settings.env);
});
