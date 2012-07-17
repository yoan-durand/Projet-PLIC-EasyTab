function Application(){

	this.appendMidiPlayer = function (midiPath) {
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
	};
	this.hideSplashScreen = function(vitesse) {
		$('#splashScreen').fadeOut(vitesse);
		console.timeEnd('temps de chargement');
	};
	this.showSplashScreen = function() {
		$('#splashScreen').fadeIn('fast');
	};
	this.bindKeys = function() {
		$(document).bind('keydown', 'o', $.proxy(this.toggleOpenFile, this));
		$(document).bind('keydown', 'h', $.proxy(this.showHelp, this));
	};
	this.toggleOpenFile = function(e) {
		e.preventDefault();
		if ($('#splashScreen').css('display') == 'none') {
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
		} else {
			this.hideSplashScreen('fast');
		}

	};
	
	this.showHelp = function(e) {
		e.preventDefault();
		console.warn('fixMe: Application.showHelp')
	}
}