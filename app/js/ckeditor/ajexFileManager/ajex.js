var ajexFileManager = function(editor, path, params) {
	if ('undefined' == typeof(params)) params = {};

	this.width = params.width || 990;
	this.height = params.height || 660;

	this.path = path;
	this.lang = params.lang || 'ru';
	this.connector = params.connector || 'php';

	var type = ['file', 'flash', 'image'];
	for (var i in type) {
		editor.config[type[i] + 'browserWindowWidth'] = this.width;
		editor.config[type[i] + 'browserWindowHeight'] = this.height;
		editor.config[type[i] + 'browserBrowseUrl'] = 'app/js/ckeditor/ajexFileManager/index.html?type=' + type[i] + '&connector=' + this.connector + '&lang=' + this.lang;
		editor.config[type[i] + 'browserUploadUrl'] = this.path + 'app/js/ckeditor/ajexFileManager/ajax/php/ajax.php?mode=QuickUpload&type=' + type[i];
	}

}

ajexFileManager.prototype = {};
