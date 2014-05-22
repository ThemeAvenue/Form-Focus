(function ($) {
	"use strict";

	$(function () {

		/////////////////////////////
		// WordPress Colorpicker //
		/////////////////////////////
		if (jQuery().wpColorPicker) {
			$('.tav-colorpicker').wpColorPicker();
		}

		/////////////////////////
		// Input Range HTML5 //
		/////////////////////////
		if ($('.overlayOpacity').length > 0 || $('.overlayOpacityVal').length > 0) {
			$('.overlayOpacityVal').val($('.overlayOpacity').val() + '%');
			$('.overlayOpacity').on('change input', function (e) {
				e.preventDefault();
				$('.overlayOpacityVal').val($(this).val() + '%');
			});
		}

		////////////////////
		// Radio Toggle //
		////////////////////
		if ($('input[name="form-focus_options[enable]"]').length > 0) {
			$('input[name="form-focus_options[enable]"]').change(function (event) {
				event.preventDefault();
				var checked = $('input[name="form-focus_options[enable]"]:checked').val();
				var specificFormsRow = $('input[name="form-focus_options[specific_forms]"]').parents('tr');
				if (checked === 'specific') {
					specificFormsRow.fadeIn('fast');
				} else {
					specificFormsRow.fadeOut('fast');
				}
			}).trigger('change');
		}

	});

}(jQuery));