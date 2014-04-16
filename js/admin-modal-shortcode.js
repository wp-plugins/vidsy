jQuery(document).ready(function($) {
	//Si playerplaylist esta seleccionado...
	jQuery("#vidsyshortcode_type").on('change', function() {
		if (jQuery(this).val() == 'playerplaylist') {
			jQuery('#vshortc_playlist').prop("disabled", false);
		} else {
			jQuery('#vshortc_playlist').prop("disabled", true);
		}
	});
	//Type
	var vshortc_type = jQuery('#vidsyshortcode_type').val();
	jQuery("#vidsyshortcode_type").on('change', function() {
		vshortc_type = jQuery('#vidsyshortcode_type').val();
	});
	//Playlist
	var vshortc_playlist = jQuery('#vshortc_playlist').val();
	jQuery("#vshortc_playlist").on('change', function() {
		vshortc_playlist = jQuery('#vshortc_playlist').val();
	});
	//With
	var vshortc_width = jQuery('#vshortc_width').val();
	jQuery("#vshortc_width").on('change', function() {
		vshortc_width = jQuery('#vshortc_width').val();
	});
	//Widthperorpix
	var vshortc_width_perorpix = jQuery('.vshortc_width_perorpix:checked').val();
	jQuery(".vshortc_width_perorpix").on('change', function() {
		vshortc_width_perorpix = jQuery('.vshortc_width_perorpix:checked').val();
	});
	//Height
	var vshortc_height = jQuery('#vshortc_height').val();
	jQuery("#vshortc_height").on('change', function() {
		vshortc_height = jQuery('#vshortc_height').val();
	});
	//Heightperorpix
	var vshortc_height_perorpix = jQuery('.vshortc_height_perorpix:checked').val();
	jQuery(".vshortc_height_perorpix").on('change', function() {
		vshortc_height_perorpix = jQuery('.vshortc_height_perorpix:checked').val();
	});
	//Theme
	var vshortc_theme = jQuery('#vidsyshortcode_theme').val();
	jQuery("#vidsyshortcode_theme").on('change', function() {
		vshortc_theme = jQuery('#vidsyshortcode_theme').val();
	});
	//Boton Cerrar
	jQuery('#vshortc_closebutton').click(function(event) {
		self.parent.tb_remove();
		return false;
	});
	//Boton Añadir Shortcode
	jQuery('#vshortc_addbutton').click(function(event) {
		if (vshortc_type == 'playerplaylist') {
			var vshortc_playlist_insert = ' playlist="' + vshortc_playlist + '"'; //ojo con el espacio antes de...
		} else {
			var vshortc_playlist_insert = ' '; //mantener el espacio :)
		}
		var shortcode = '[vidsy type="' + vshortc_type + '"' + vshortc_playlist_insert + ' width="' + vshortc_width + vshortc_width_perorpix + '" height="' + vshortc_height + vshortc_height_perorpix + '" theme="' + vshortc_theme + '"]';
		//Editor activo?
		is_tinyMCE_active = false;
		if (typeof(tinyMCE) != "undefined") {
			if (tinyMCE.activeEditor != null) {
				is_tinyMCE_active = true;
			}
		}
		//Insertar el shortcode en el contexto del editor
		if (is_tinyMCE_active) {
			tinymce.execCommand('mceInsertContent', false, shortcode);
			self.parent.tb_remove();
			return false;
		} else {
			var wpEditor = jQuery('.wp-editor-area');
			wpEditor.append(shortcode);
			self.parent.tb_remove();
			return false;
		}
	});
});

jQuery(window).resize(function() {
	vidsy_resize_thickbox();
});

function vidsy_resize_thickbox() {
	jQuery('#TB_window').css('height', 'auto');
}