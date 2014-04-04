jQuery(document).ready(function($) {
	tinymce.create('tinymce.plugins.wpse_vidsy_plugin', {
		init: function(ed, url) {

			ed.addButton('wpse_vidsy_button', {
				title: 'Insert Vidsy shortcode',
				cmd: 'wpse_vidsy_insert_shortcode',
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
});

jQuery(window).resize(function() {
	vidsy_resize_thickbox();
});

function vidsy_resize_thickbox() {
	jQuery('#TB_window').css('height', 'auto');
}