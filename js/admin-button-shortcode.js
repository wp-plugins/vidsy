//jQuery(document).ready(function($) {
(function() {
	//http://wordpress.stackexchange.com/questions/139163/add-custom-tinymce-4-button-usable-since-wordpress-3-9-beta1
	/*
	tinymce.PluginManager.add('wpse_vidsy_button', function(editor, url) {
		editor.addButton('wpse_vidsy_button', {
			title: 'Insert Vidsy shortcode',
			image: url + '/../images/vidsylogoonly_16.png',
			onclick: function() {
				var url = "#TB_inline?width=650&height=500&inlineId=thickbox_vidsyshortcode_window";
				tb_show("Vidsy Wizard", url);
				vidsy_resize_thickbox();
			}
		});
	});
	*/

	tinymce.create('tinymce.plugins.wpse_vidsy_plugin', {
		init: function(editor, url) {
			editor.addButton('wpse_vidsy_button', {
				title: 'Insert Vidsy shortcode',
				image: url + '/../images/vidsylogoonly_16.png',
				onclick: function() {
					var url = "#TB_inline?width=650&height=500&inlineId=thickbox_vidsyshortcode_window";
					tb_show("Vidsy Wizard", url);
					vidsy_resize_thickbox();
				}
			});
		},
	});
	//Registrar el plugin TinyMCE
	//Primer parametro, la ID del boton
	//Segundo parametro, debe coincidir con el primer parametro pasado a tinymce.create()
	tinymce.PluginManager.add('wpse_vidsy_button', tinymce.plugins.wpse_vidsy_plugin);
})();