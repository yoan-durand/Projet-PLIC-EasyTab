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
	this.hideSplashScreen = function() {
		$('#splashScreen').fadeOut('slow');
		console.timeEnd(1);
	};

}