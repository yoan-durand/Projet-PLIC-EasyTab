function Application(){
	this.popup = $('#splashScreen');
	this.popupContent = $('#splashMessage', this.popup);
	this.mustRegenerateMidi = true;
	this.comments = [];

	this.bindKeys();
	this.initComments(true);
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
		this.popup.fadeOut(vitesse);
		this.popupName = '';
	},
	showSplashScreen: function(nom, css) {
		var close = $('#popupClose', this.popupContent);
		if (!close.length) {
			close = $('<div id="popupClose"><span class="ui-icon ui-icon-closethick"></span></div>');
			close.button();
			close.css({'float': 'right'});
			$('>span', close).css({'padding': '0.2em 0.3em'});
			close.click($.proxy(this.hideSplashScreen, this));
			this.popupContent.prepend(close);
		}
		this.popupContent.removeAttr('style');
		if (css !== undefined) {
			this.popupContent.css(css);
		}
		this.popup.fadeIn('fast');
		var height = $(window).height() - $('#splashMessage').height() - 44;
		if (height < 0) {
			this.popupContent.css('margin', '0 auto');
			this.popupContent.css('height', ($('#splashMessage').height()+height)+'px')
		} else if (height < 200) {
			this.popupContent.css('margin-top', height+'px');
		} else {
			this.popupContent.css('margin-top', '200px');
		}
		if (nom != undefined) {
			this.popupName = nom;
		}
	},
	showSpinner: function() {
		this.popupContent.html('<img src="image/ajax-loader.gif" alt="loading" style="width:43px; height:11px"><br>'+
		'<span>Chargement de l\'application en cours...</span>');
		this.showSplashScreen('spinner');
		$('#popupClose', this.popupContent).remove();
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
				+'<li><b>C</b> : Affiche les commentaires.</li>'
				+'<li><b>H</b> : Affiche la fenêtre d\'aide.</li>'
				+'<li><b>O</b> : Ouvre la fenêtre de changement de tablature.</li>'
				+'<li><b>P</b> : Démarre/met en pause la lecture.</li>'
				+'<li><b>S</b> : Arrête la lecture.</li>'
				+'<li><b>Échap</b> : Ferme la fenêtre actuelement ouverte.</li>'
				+'<li><b>Début</b> : Revient au début de la tablature.</li>'
				+'<li><b>-</b> : Baisse le son.</li>'
				+'<li><b>+</b> : Augmente le son.</li>'
				+'</ul>';
			this.popupContent.html(html);
			this.showSplashScreen('help', {width:'310px'});
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
	initComments: function(first) {
		var _this = this;
		if (first) {
			this.getComments();
			$('#comments-icon').click($.proxy(this.showComments, this));
		}
		this.webSocket = ws = new WebSocket("ws://localhost:8081")//FIXME window.ws
		ws.onclose=function(err){
			console.log('commentaires: connexion fermée.', err);
			_this.initComments();
		};
		ws.onopen=function(){
			console.log('commentaires: connexion établie.');
		};
		ws.onerror=function(err){
			console.log('commentaires: erreur de connexion', err);
			_this.initComments();
		};
		ws.onmessage=function(data){
			var param = JSON.parse(data.data);
			if (param.tablatureId === config.tablatureId) {
				// nous sommes sur la même tablature affichons donc le commentaire
				_this.addComment(param);
				_this.showComments(true);
			}
		};
	},
	addComment: function(data) {
		this.comments.push(data);
		this.updateCommentNumber();
	},
	clearComments: function() {
		this.comments = [];
	},
	showComments: function(forceShow) {
		if (this.isPopupOpen('comments') && forceShow !== true) {
			this.hideSplashScreen();
		} else {
			this.popupContent.html('<h2>Commentaires</h2>'
					+'<div id="ajoutComment"><input class="ajout float-left" placeholder="Ajouter un commentaire"><input class="custom-button float-right" type="submit" value="Envoyer"></div>'
					+'<ul id="comments"></ul>');
			var _this = this;
			var submit = function(e) {
				e.preventDefault();
				var commentaire = $.trim($('#ajoutComment .ajout', _this.popupContent).val());
				if (commentaire.length === 0) {
					return;
				}
				var param = {
					auteurId: config.userId,
					tablatureId: config.tablatureId,
					texte: commentaire
				};
				_this.webSocket.send(JSON.stringify(param));
			}
			$('#ajoutComment', this.popupContent).buttonset();
			$('#ajoutComment .ajout', this.popupContent)
				.bind('keyup', 'return', submit)
				.next().click(submit);
			$('#ajoutComment form input:eq(0)', this.popupContent).bind('keyup', 'esc', $.proxy(this.hideSplashScreen, this));
			var commentList = $('#comments', this.popupContent);
			var buildClickEvent = function(url){
				return function(e){
					e.preventDefault();
					$(this).parent().remove();
					$.post(url, function() {
						_this.getComments();
					});
					_this.showSplashScreen('comments', {width: '600px'});
				}
			};
			for(var i = this.comments.length - 1; i >= 0; --i) {
				var data = this.comments[i];
				var url = '/commentaire/delete/'+data.id;
				var elem = $('<li><a href="'+url+'">X</a><div><a href="/user/'+data.auteurId+'/'+data.login+'">'+data.login+'</a></div><div class="message">'+data.texte+'</div></li>');
				$('>a', elem).click(buildClickEvent(url));
				commentList.append(elem);
			}
			this.showSplashScreen('comments', {width: '600px'});
		}
	},
	getComments: function() {
		var _this = this;
		this.clearComments();
		$.post('/commentaire/'+config.tablatureId, function(data, textStatus, jqXHR){
			for (var i = 0; i < data.length; ++i) {
				_this.addComment(data[i]);
			}
			_this.updateCommentNumber();
		}, 'json');
	},
	updateCommentNumber: function() {
		$('#comments-icon > span').text(this.comments.length);
	}
};
function now() {
	return (new Date()).getTime();
}