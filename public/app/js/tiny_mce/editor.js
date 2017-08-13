// JavaScript Document
var EDITOR_OPTIONS = {
    // General options
    mode : "exact",
    theme : "advanced",
    elements : "content",
    language : "ch",
    pagebreak_separator : "<!-- my page break -->",
    convert_urls : false,
    plugins : "safari,style,ct_image,advlink,preview,searchreplace,contextmenu,paste,template,inlinepopups,onekeyclear,fullscreen",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    //theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : false,
    // Example content CSS (should be your site CSS)
    content_css : "css/content.css",
    extended_valid_elements : "a[class|name|href|target|title|onclick|rel],script[type|src],iframe[src|style|width|height|scrolling|marginwidth|marginheight|frameborder],img[class|src|border=0|width|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],$elements"
};
function editor(textarea_id, options, type){
    switch(type || '') {
    case 'none':
        ed_plugins = "safari,pagebreak,style,advimage,advlink,preview,contextmenu,paste,template,inlinepopups,onekeyclear,fullscreen,ct_source,ct_save,ct_image";
        ed_theme_advanced_buttons1 = '';
        ed_theme_advanced_buttons2 = '';
        ed_theme_advanced_buttons3 = '';
        break;
    default:
        ed_plugins = "safari,style,ct_image,advlink,preview,searchreplace,contextmenu,paste,template,inlinepopups,onekeyclear,fullscreen";
        ed_theme_advanced_buttons1 = "undo,bold,italic,underline,fontsizeselect,forecolor,|,link,unlink,|,justifyleft,justifycentr,justifyright,|,ctImage,media,onekeyclear";
        ed_theme_advanced_buttons2 = "";
        ed_theme_advanced_buttons3 = "";
    }
	options = $.extend({
		elements : textarea_id,
        theme_advanced_buttons1 : ed_theme_advanced_buttons1,
        theme_advanced_buttons2 : ed_theme_advanced_buttons2,
        theme_advanced_buttons3 : ed_theme_advanced_buttons3
	}, EDITOR_OPTIONS, options||{});
	tinymce.EditorManager.init(options);
}
$.fn.editor = function(options, type)
{
    var frm = $(this[0].form);
    var textarea = this;
    var id = this[0].id;
    editor(id, options, type);
  	frm.submit(function(){
        textarea.val(tinyMCE.get(id).getContent());
    });
    return this;
};
