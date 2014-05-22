(function ($) {
	"use strict";

	$(function () {

		// Define variables
		var formOverlay = $('<div class="formbox-overlay"></div>'),
			formOverlaySpeed = typeof formfocus.speed == 'undefined' ? 400 : parseInt(formfocus.speed, 10),
			form = typeof formfocus.selector == 'undefined' ? $('form') : $(formfocus.selector);

		// @TODO: Random z-index and adjust so that no other elements are highlighted

		// When form has focus
		form.each(function (index, el) {
			var $this = $(this);
			$this.on('focusin click', function (event) {
				event.stopPropagation();
				$this.addClass('formbox-active');
				formOverlay.appendTo('body').fadeIn(formOverlaySpeed);
			});
		});

		// When clicking outside the form
		$('html').click(function () {
			formOverlay.fadeOut(formOverlaySpeed, function () {
				formOverlay.remove();
				form.removeClass('formbox-active');
			});
		});

	});

}(jQuery));