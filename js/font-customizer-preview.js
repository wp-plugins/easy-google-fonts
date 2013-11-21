/**===============================================================
 * THEME CUSTOMIZER LIVE PREVIEW JAVASCRIPT
 * ===============================================================
 * This file contains all custom jQuery plugins and code used on 
 * the WordPress Customizer screen. It contains all of the js
 * code necessary to enable the live real time theme previewer.
 *
 * v1.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, 
 * software distributed under the License is distributed on an 
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, 
 * either express or implied. See the License for the specific 
 * language governing permissions and
 * limitations under the License.
 *
 * PLEASE NOTE: The following jQuery plugin dependancies are required
 * in order for this file to run correctly:
 *
 * 1. jQuery			( http://jquery.com/ )
 * 2. jQuery UI			( http://jqueryui.com/ )
 *
 * @version 1.0
 *
 * =============================================================== */
/**============================================================
 * FONT CONTROL LIVE PREVIEW
 * ============================================================ */
;( function($, window, document, undefined) {
	$.fn.ttFontPreview = function() {

		var preview = this;

		/**
		 * Init Live Preview for Font Controls
		 * 
		 * @desc - Gets all of the settings that have a font
		 *     control, checks if the setting has live preview 
		 *     enabled and sets up the live previewer if the
		 *     setting supports it.
		 *
		 * @uses object ttFontPreviewControls
		 * @uses object _wpCustomizeSettings  
		 *
		 * @since 1.0
		 * @version 1.0
		 * 
		 */
		preview.init = function() {
			$.each( ttFontPreviewControls, function( key, value ) {
				
				var id         = key;                     // setting name
				var type       = value.type;              // setting control type
				var transport  = value.setting.transport; // transport type
				var valueObj   = value;
				var importance = value.force_styles ? '!important' : '';

				if ( 'font' === type && 'postMessage' === transport ) {
					var head          =  $('head')
					var selector      = value.selector;
					var fontId        = 'tt_font_theme_options[' + id + '][font_id]';
					var fontName      = 'tt_font_theme_options[' + id + '][font_name]';
					var fontColor     = 'tt_font_theme_options[' + id + '][font_color]';
					var fontWeight    = 'tt_font_theme_options[' + id + '][font_weight]';
					var fontStyle     = 'tt_font_theme_options[' + id + '][font_style]';
					var fontSize      = 'tt_font_theme_options[' + id + '][font_size][amount]';
					var lineHeight    = 'tt_font_theme_options[' + id + '][line_height]';
					var letterSpacing = 'tt_font_theme_options[' + id + '][letter_spacing][amount]';
					var textDec       = 'tt_font_theme_options[' + id + '][text_decoration]';
					var textTransform = 'tt_font_theme_options[' + id + '][text_transform]';
					var stylesheetUrl = 'tt_font_theme_options[' + id + '][stylesheet_url]';


					// Enqueue Stylesheet in Head
					wp.customize( stylesheetUrl, function( value ) {
						value.bind(function(to) {
							$( '<link type="text/css" media="all" href="' + to + '" rel="stylesheet">' ).appendTo( head );
						});
					});                    

					// Font Color Live Preview
					wp.customize( fontColor, function( value ) {
						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-color';

						value.bind(function(to) {

							if ( to === '' ) {
								$( '#' + styleId ).remove(); 
							} else {
								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { color: ' + to + importance + '; }';
								style += '</style>';

								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );								
							}


						});
					});

					// Font Family Live Preview
					wp.customize( fontName, function( value ) {
						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-font-family';

						value.bind(function(to) {
							if ( to === 'theme-default' || to === '' ) {
								$( '#' + styleId ).remove(); 
							} else {
								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { font-family: ' + to + importance + '; }';
								style += '</style>';

								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );								
							}
							

						});
					});

					// Font Weight Live Preview
					wp.customize( fontWeight, function( value ) {
						
						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-font-weight';

						value.bind(function(to) {
							if ( to === 'theme-default' || to === '' ) {
								$( '#' + styleId ).remove();
							} else {
								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { font-weight: ' + to + importance + '; }';
								style += '</style>';

								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );
							}
						});
					});

					// Font Style Live Preview
					wp.customize( fontStyle, function( value ) {
						
						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-font-style';

						value.bind(function(to) {
							if ( to === 'theme-default' || to === '' ) {
								// Remove any applied styles
								$( '#' + styleId ).remove();
							} else {
								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { font-style: ' + to + importance + '; }';
								style += '</style>';
								
								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );	
							}
						});
					});

					// Text Decoration Live Preview
					wp.customize( textDec, function( value ) {

						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-text-decoration';

						value.bind(function(to) {
							if ( to === 'theme-default' || to === '' ) {
								// Remove any applied styles
								$( '#' + styleId ).remove();
							} else {
								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { text-decoration: ' + to + importance + '; }';
								style += '</style>';
								
								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );	
							}
						   
						});
					});


					// Text Transform Live Preview
					wp.customize( textTransform, function( value ) {
						
						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-text-transform';

						value.bind(function(to) {
							if ( to === 'theme-default' || to === '' ) {
								// Remove any applied styles
								$( '#' + styleId ).remove(); 
							} else {

								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { text-transform: ' + to + importance + '; }';
								style += '</style>';
								
								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );								
							}
						});
					});

					// Font Size Live Preview
					wp.customize( fontSize, function( value ) {

						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-font-size';

						value.bind(function(to) {
							if ( to === '' ) {
								// Remove any applied styles
								$( '#' + styleId ).remove(); 
							} else {
								 
								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { font-size: ' + to + valueObj.default_values.font_size.unit + importance + '; }';
								style += '</style>';
								
								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );
							}
						});
					});

					// Line Height Live Preview
					wp.customize( lineHeight, function( value ) {
						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-line-height';

						value.bind(function(to) {
							if ( to === '' ) {
								// Remove any applied styles
								$( '#' + styleId ).remove();
							} else {
								
								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { line-height: ' + to + importance + '; }';
								style += '</style>';
								
								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );
							}
						});
					});  

					// Letter Spacing Live Preview
					wp.customize( letterSpacing, function( value ) {
						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-letter-spacing';

						value.bind(function(to) {
							if ( to === '' ) {
								// Remove any applied styles
								$( '#' + styleId ).remove();
							} else {

								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { letter-spacing: ' + to + valueObj.default_values.letter_spacing.unit + importance + '; }';
								style += '</style>';

								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );
							}
						   
						});
					});                    
				}



			});
		};

		// Run init on plugin initialisation
		preview.init();
		return preview;        
	};
}(jQuery, window, document));

/**============================================================
 * INITIALISE PLUGINS & JS ON DOCUMENT READY EVENT
 * ============================================================ */
jQuery(document).ready(function($) {"use strict";
	$(this).ttFontPreview();
});
