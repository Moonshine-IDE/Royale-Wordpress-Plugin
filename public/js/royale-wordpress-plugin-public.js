(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$( window ).load(function() {
		var $iframes = $('.rwp-iframe');
		$iframes.each(function(){
			var $iframe = $(this);
			var $html = $iframe.contents().find('html');
			var htmlHeight = $html.outerHeight(true);
			var htmlScrollHeight = $html.prop('scrollHeight');
			var $body = $iframe.contents().find('body');
			var bodyHeight = $body.outerHeight(true);
			var bodyScrollHeight = $body.prop('scrollHeight');
			var height = Math.max(htmlHeight, htmlScrollHeight, bodyHeight, bodyScrollHeight);
			$iframe.height(height);
			$iframe.removeClass('hidden');
		});
	});
	

})( jQuery );
