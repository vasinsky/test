/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.PluginManager.requireLangPack('advedit');
	tinymce.create('tinymce.plugins.AdveditPlugin', {
		init : function(ed, url) {
    		 	 var t = this;
			     t.editor = ed;
      ed.addCommand('Sourceplus', function() {

				ed.windowManager.open({
					file : url + '/editarea.htm',
				  width : parseInt(ed.getParam("theme_advanced_editarea_editor_width", 720)),
				  height : parseInt(ed.getParam("theme_advanced_editarea_editor_height", 580)),
				  inline : true,
				  resizable : true,
				  maximizable : true
				}, {
					plugin_url : url
				});
			});
			ed.addButton('advedit', {
				title : 'advedit.source_desc',
				cmd : 'Sourceplus',
				image : url + '/img/highlight.png'
			});
		},
		getInfo : function() {
			return {
				longname : 'Advedit',
				author : 'Red Owl',
				authorurl : '',
				infourl : '',
				version : '1.0'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('advedit', tinymce.plugins.AdveditPlugin);
})();
