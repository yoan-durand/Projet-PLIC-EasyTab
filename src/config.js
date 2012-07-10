
exports.bdd = {
	user : 'root',
	pass : '',
	name : 'easytab',
	salt : '3'
};
exports.upload = {
	dir: 'upload/'
};
exports.midi = {
	dir: 'midi/'
};
exports.PHP = {
	port: 80
}
try {
	var local = require('./localConfig');
	for (var c in local) {
		exports[c] = local[c];
	}
} catch(e){
}