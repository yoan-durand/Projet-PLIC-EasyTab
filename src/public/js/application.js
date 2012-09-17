function Application(){
	this.popup = $('#splashScreen');
	this.popupContent = $('#splashMessage', this.popup);
}
Application.get = function() {
	if (this.instance == null) {
		this.instance = new Application();
	}
	return this.instance;
};
Application.prototype = {
	appendMidiPlayer: function (midiPath) {
		$(".page").append(
			"<object classid='clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B'"+
			"codebase='http://www.apple.com/qtactivex/qtplugin.cab' width='180'"+
			"height='160' id='demo'>"+
			"<param name='src' value='js/demo.mid'>"+
			"<param name='Autoplay' value='false'>"+
			"<embed width='0' height='0' src='"+midiPath+"' name='demo'"+
			"enablejavascript='true' autostart='false'>"+
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
				+'<li><b>Échap</b> : Ferme la fenêtre actuelement ouverte.<li>'
				+'<li><b>H</b> : Affiche la fenêtre d\'aide.<li>'
				+'<li><b>O</b> : Ouvre la fenêtre de changement de tablature.<li>'
				+'<li><b>P</b> : Démarre/met en pause la lecture.<li>'
				+'<li><b>S</b> : Arrête la lecture.<li>'
				+'<li><b>Début</b> : Revient au début de la tablature.<li>'
				+'</ul>';
			this.popupContent.html(html);
			this.showSplashScreen();
			this.popupName = 'help';
		}
	},
	error: function(err) {
		this.popupContent.html(err);
		this.showSplashScreen();
	}
};