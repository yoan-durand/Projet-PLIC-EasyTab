function Application(){
	this.popup = $('#splashScreen');
	this.popupContent = $('#splashMessage', this.popup);
	this.mustRegenerateMidi = true;
	this.comments = [];

	this.bindKeys();
	this.initComments();
}
Application.get = function() {
	if (this.instance == null) {
		this.instance = new Application();
	}
	return this.instance;
};
Application.prototype = {
	midi_ajax: function (callback){
		var _this = this;
		this.mustRegenerateMidi = false;
		this.showSpinner();
		$.ajax({
			type: "POST",
			url: 'midi',
			data: {
				'name': config.tablature,
				'userId': config.userId,
				'encoded': JSON.stringify({ encoded : partition })
			},
			success: function (data, textStatus, jqXHR) {
				try {
					data = JSON.parse(data);
					console.log("la page midi a renvoyé : ", {data:data});
				} catch(ex) {
					console.error("La page midi n'a pas renvoyé de JSON : ", {texte:data});
				}
				_this.appendMidiPlayer(data.filename);
                
				_this.hideSplashScreen('slow');
				if (callback !== undefined) _this.addEventListener(document.demo, "qt_load", callback, false);;
				console.timeEnd('temps de chargement');
			},
			error: function (xhr, status, err) {
				_this.mustRegenerateMidi = true;
				alert("fail");
			}
		});
	},
	appendMidiPlayer: function (midiPath) {
		$("#demo").remove();
		$(".page").append(
			"<object classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B'"+
			"codebase='http://www.apple.com/qtactivex/qtplugin.cab' width='180'"+
			"height='160' id='demo' style='behavior:url(#qt_event_source);'>"+
			"<param name='src' value='js/demo.mid'>"+
			"<param name='postdomevents' value='true' />"+
			"<param name='Autoplay' value='false'>"+
			"<embed id='embed_demo' width='0' height='0' src='"+midiPath+"' name='demo'"+
			"enablejavascript='true' autostart='false' postdomevents='true'>"+
			"</object>"
			);
	},
	hideSplashScreen: function(vitesse) {
		if (vitesse === undefined) {
			vitesse = 'fast';
		}
		$('#splashScreen').fadeOut(vitesse);
		this.popupName = '';
	},
	showSplashScreen: function(nom, css) {
		if (css !== undefined) {
			this.popupContent.css(css);
		}
		this.popup.fadeIn('fast');
		if (nom != undefined) {
			this.popupName = nom;
		}
	},
	showSpinner: function() {
		this.popupContent.html('<img src="image/ajax-loader.gif" alt="loading" style="width:32px; height:32px"><br>'+
		'<span>Chargement de l\'application en cours...</span>');
		this.showSplashScreen('spinner');
	},
	isPopupOpen: function(nom) {
		return $('#splashScreen').css('display') !== 'none' && (nom === undefined || this.popupName === nom);
	},
	bindKeys: function() {
		$('#splashbg').click($.proxy(this.hideSplashScreen, this));
		$(document).bind('keydown', 'esc', $.proxy(this.hideSplashScreen, this));
		$(document).bind('keydown', 'o', $.proxy(this.toggleOpenFile, this));
		$(document).bind('keydown', 'h', $.proxy(this.help, this));
		$(document).bind('keydown', 'c', $.proxy(this.showComments, this));
	},
	toggleOpenFile: function(e) {
		e.preventDefault();
		if (this.popupName === 'open') {
			this.hideSplashScreen();
		} else {
			var _this = this;
			this.showSpinner();
			$.get('/tablatures/get/all', function(data, textStatus, jqXHR) {
				var html = '<h2>Charger une autre partition</h2><ul id="trackList">';
				for (var i = 0; i < data.length; ++i) {
					html += '<li id="tab_'+data[i].id+'" style="text-align:center;"> <a href="application;'+data[i].nom+'.xml">'+data[i].titre+' - '+data[i].artiste
					+'</a></li>';
				}
				html += '</ul>'
				_this.popupContent.css().html(html);
				$('#trackList > li').dblclick(function(e){
					location.href = $('a', this).attr('href');
				});
				this.showSplashScreen('open', {width: '600px'});
			}, 'json');
		}
	},
	help: function(e) {
		e.preventDefault();
		if (this.popupName === 'help') {
			this.hideSplashScreen();
			this.popupName = '';
		} else {
			var html = '<h2>Raccourcis</h2>'
				+'<ul id="raccourcis">'
				+'<li><b>C</b> : Affiche les commentaires.<li>'
				+'<li><b>H</b> : Affiche la fenêtre d\'aide.<li>'
				+'<li><b>O</b> : Ouvre la fenêtre de changement de tablature.<li>'
				+'<li><b>P</b> : Démarre/met en pause la lecture.<li>'
				+'<li><b>S</b> : Arrête la lecture.<li>'
				+'<li><b>Échap</b> : Ferme la fenêtre actuelement ouverte.<li>'
				+'<li><b>Début</b> : Revient au début de la tablature.<li>'
				+'<li><b>-</b> : Baisse le son.<li>'
				+'<li><b>+</b> : Augmente le son.<li>'
				+'</ul>';
			this.popupContent.html(html);
			this.showSplashScreen('help', {width: '310px'});
		}
	},
	error: function(err) {
		this.popupContent.html(err);
		this.showSplashScreen();
	},
	addEventListener: function(obj, evt, handler, captures) {
		if (document.addEventListener) {
			if (document.addEventListener){
				obj.addEventListener(evt, handler, captures);
			} else { //IE
				obj.attachEvent('on' + evt, handler);
			}
		}
	},
	initComments: function() {
		var _this = this;
		this.getComments();
		window.ws=ws=new WebSocket("ws://localhost:8081")//FIXME window.ws
		ws.onclose=function(){
			console.log('commentaire: close');
			// _this.popupContent.html("Déconnexion des commentaires");
			// _this.showSplashScreen();
		};
		ws.onopen=function(){
			console.log('commentaire: open');
		};
		ws.onerror=function(){
			console.log('commentaire: error');
			// _this.popupContent.html("erreur: Déconnexion des commentaires");
			// _this.showSplashScreen();
		};
		ws.onmessage=function(data){
			_this.addComment(JSON.parse(data.data));
		};
	},
	addComment: function(data) {
		this.comments.push(data);
	},
	showComments: function() {
		if (this.isPopupOpen('comments')) {
			this.hideSplashScreen();
		} else {
			var html = '<h2>Commentaires</h2>'
					+'<div id="ajoutComment"><input placeholder="Ajouter un commentaire"></div>'
					+'<ul id="comments">';
			for(var i = 0; i < this.comments.length; ++i) {
				var data = this.comments[i];
				html += '<li><div><a href="/user/'+data.auteurId+'/'+data.login+'">'+data.login+'</a></div><div class="message">'+data.texte+'</div></li>';
			}
			html += '</ul>';
			this.popupContent.empty().append(html);
			this.showSplashScreen('comments', {width: '600px'});
		}
	},
	getComments: function() {
		var _this = this;
		$.post('/commentaire/'+config.tablatureId, function(data, textStatus, jqXHR){
			for (var i = 0; i < data.length; ++i) {
				_this.addComment(data[i]);
			}
			// _this.showComments();
		}, 'json');
	}
};