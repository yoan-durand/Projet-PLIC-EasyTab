function Application(){
	this.popup = $('#splashScreen');
	this.popupContent = $('#splashMessage', this.popup);
	this.mustRegenerateMidi = true;
	this.bindKeys();
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
		// this.showSpinner();
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
	showSplashScreen: function(vitesse) {
		if (vitesse === undefined) {
			vitesse = 'fast';
		}
		$('#splashScreen').fadeIn(vitesse);
	},
	isPopupOpen: function() {
		return $('#splashScreen').css('display') !== 'none'
	},
	bindKeys: function() {
		$('#splashbg').click($.proxy(this.hideSplashScreen, this));
		$(document).bind('keydown', 'esc', $.proxy(this.hideSplashScreen, this));
		$(document).bind('keydown', 'o', $.proxy(this.toggleOpenFile, this));
		$(document).bind('keydown', 'h', $.proxy(this.help, this));
	},
	toggleOpenFile: function(e) {
		e.preventDefault();
		if (this.popupName === 'open') {
			this.hideSplashScreen();
			this.popupName = '';
		} else {
			$.get('/tablatures/get/all', function(data, textStatus, jqXHR) {
				var html = '<h2>Charger une autre partition</h2><ul id="trackList">';
				for (var i = 0; i < data.length; ++i) {
					html += '<li id="tab_'+data[i].id+'" style="text-align:center;"> <a href="application;'+data[i].nom+'.xml">'+data[i].titre+' - '+data[i].artiste
					+'</a></li>';
				}
				html += '</ul>'
				$('#splashMessage').css({
					width: '600px'
				}).html(html);
				$('#trackList > li').dblclick(function(e){
					location.href = $('a', this).attr('href');
				});
			}, 'json');
			this.showSplashScreen();
			this.popupName = 'open';
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
			this.showSplashScreen();
			this.popupName = 'help';
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
     }
};