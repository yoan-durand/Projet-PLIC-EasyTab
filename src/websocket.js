exports.init = function() {
	var WebSocketServer = require('ws').Server
	  , wss = new WebSocketServer({port: 8081})
	  , routes = require('./routes');
	var clients = {};
	var uniqueId = 0;
	var dispatchToAll = function(message) {
		message = JSON.stringify(message);
		for (var client in clients) {
			clients[client].send(message);
		}
	}
	wss.on('connection', function(ws) {
		var clientId = ++uniqueId;
		clients[clientId] = ws;
		ws.on('message', function(message) {
			message = JSON.parse(message);
			routes.addCommentaire(message, function(params){
				dispatchToAll(params);
			});
		});
		ws.on('close', function() {
			delete clients[clientId];
			console.log('Le client '+uniqueId+' a fermé la connexion.');
		});
		// ws.send(JSON.stringify('connecté'));
	});
}
