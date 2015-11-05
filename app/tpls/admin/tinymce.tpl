<script language="javascript" type="text/javascript" src="/<?=PATH;?>app/js/tiny_mce/jquery.tinymce.js"></script>
<script language="javascript" type="text/javascript" src="/<?=PATH;?>app/js/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
// General options
mode : "textareas",
theme : "advanced",
editor_selector : "content",
language : "en",
inline_styles: true,
convert_urls : false,
relative_urls : false,
remove_script_host : false,
cleanup: true,
extended_valid_elements:"noindex, strong/b,  em/i, sup, sub, ul, ol, li, div[class | id | style | name | title | align | width | height], span[class | id | style | name | title], hr[class | id | style | name | title | align | width | height], img[class | id | style | name | title | src | align | alt | hspace | vspace | width | height | border=0], a[class | id | style | name | title | src | href | rel | target | ], iframe[class | id | style | name | title | src | align | width | height | marginwidth | marginheight | scrolling | frameborder | border | bordercolor], embed[class | id | style | name | title | align | width | height | hspace | vspace | type | pluginspage | src], object[class | id | style | name | title | align | width | height | hspace | vspace | type | classid | code | codebase | codetype | data]",
 
plugins : "codehighlight, rj_insertcode, pagebreak, style, layer, table, save, advhr, advimage, advlink, emotions, iespell, inlinepopups, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template, wordcount, advlist, autosave,images",
 
// Theme options
theme_advanced_buttons1 : "codehighlight, rj_insertcode, code, |,undo, redo, |, bold, italic, underline, strikethrough, |, justifyleft, justifycenter, justifyright, justifyfull, styleselect, formatselect, fontselect, fontsizeselect, sub, sup, |, forecolor, backcolor",
theme_advanced_buttons2 : "cut, copy, paste, pastetext, pasteword, removeformat, cleanup, |, search, replace, |, bullist, numlist, |, outdent, indent, blockquote, |, link, unlink, image, images, |, insertdate, inserttime, hr, |, charmap, emotions, iespell",
theme_advanced_buttons3 : "tablecontrols, |, visualaid, |,styleprops, |, cite, abbr, acronym, del, ins, |, visualchars, nonbreaking, |, print, preview, |, fullscreen",
theme_advanced_buttons4 :"",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
theme_advanced_resizing : true,
protect: [
    /\<\/?(if|endif)\>/g, // Protect <if> & </endif>
    /\<xsl\:[^>]+\>/g, // Protect <xsl:...>
    /<\?php.*?\?>/g, // Protect php code
    /<\?=.*?\?>/g // Protect php code
]
});

tinyMCE.init({
// General options
mode : "textareas",
theme : "advanced",
editor_selector : "preview",
language : "en",
inline_styles: true,
convert_urls : false,
relative_urls : false,
remove_script_host : false,
cleanup: true,
extended_valid_elements:"noindex, strong/b,  em/i, sup, sub, ul, ol, li, div[class | id | style | name | title | align | width | height], span[class | id | style | name | title], hr[class | id | style | name | title | align | width | height], img[class | id | style | name | title | src | align | alt | hspace | vspace | width | height | border=0], a[class | id | style | name | title | src | href | rel | target | ], iframe[class | id | style | name | title | src | align | width | height | marginwidth | marginheight | scrolling | frameborder | border | bordercolor], embed[class | id | style | name | title | align | width | height | hspace | vspace | type | pluginspage | src], object[class | id | style | name | title | align | width | height | hspace | vspace | type | classid | code | codebase | codetype | data]",
 
plugins : "rj_insertcode, pagebreak, style, layer, table, save, advhr, advimage, advlink, emotions, iespell, inlinepopups, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template, wordcount, advlist, autosave,images",
 
// Theme options
theme_advanced_buttons1 : "rj_insertcode, code, |,undo, redo, |, bold, italic, underline, strikethrough, |, justifyleft, justifycenter, justifyright, justifyfull, styleselect, formatselect, fontselect, fontsizeselect, sub, sup, |, forecolor, backcolor",
theme_advanced_buttons2 : "cut, copy, paste, pastetext, pasteword, removeformat, cleanup, |, search, replace, |, bullist, numlist, |, outdent, indent, blockquote, |, link, unlink, image, images, |, insertdate, inserttime, hr, |, charmap, emotions, iespell",
theme_advanced_buttons3 : "tablecontrols, |, visualaid, |,styleprops, |, cite, abbr, acronym, del, ins, |, visualchars, nonbreaking, |, print, preview, |, fullscreen",
theme_advanced_buttons4 :"",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
theme_advanced_resizing : true,
/*
setup : function(ed) {        
          ed.onBeforeSetContent.add(function(ed, o) {
                o.content = o.content.replace(/<\?/gi, "&lt?");
                o.content = o.content.replace(/\?>/gi, "?&gt");
          });
     }
*/       
});
</script>