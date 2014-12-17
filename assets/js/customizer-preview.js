/**============================================================
 * THEME CUSTOMIZER LIVE PREVIEW JAVASCRIPT
 * ============================================================
 * This file contains all custom jQuery plugins and code used on 
 * the WordPress Customizer screen. It contains all of the js
 * code necessary to enable the live real time theme previewer.
 *
 * v1.3.2
 *
 * PLEASE NOTE: The following jQuery plugin dependancies are 
 * required in order for this file to run correctly:
 *
 * 1. jQuery			( http://jquery.com/ )
 * 2. jQuery UI			( http://jqueryui.com/ )
 * 3. Underscore JS
 * 4. JSON2 Library
 *
 * @since 1.2
 * @version 1.3.3
 *
 * @todo : Increase dependancy on underscore.js and backbone.js 
 *     in future versions and avoid tying data to the DOM.
 *     
 * ============================================================ */

/**============================================================
 * FONT CONTROL LIVE PREVIEW
 * ============================================================ */
;( function($, window, document, undefined) {
	$.fn.ttFontPreview = function() {

		var preview = this;

		/**
		 * Init Live Preview for Font Controls
		 * 
		 * @description - Gets all of the settings that have a font
		 *     control, checks if the setting has live preview 
		 *     enabled and sets up the live previewer if the
		 *     setting supports it.
		 *
		 * @uses object ttFontPreviewControls
		 * @uses object _wpCustomizeSettings  
		 *
		 * @since 1.2
		 * @version 1.3.3
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
					var head            =  $('head')
					var selector        = value.selector;               

					/**
					 * Set Live Preview for Font Color
					 *
					 * This is managed separately to increase performance
					 * in the WordPress Customizer.
					 * 
					 */
					wp.customize( 'tt_font_theme_options[' + id + '][font_color]', function( value ) {
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

					/**
					 * Set Live Preview for Background Color
					 * 
					 * This is managed separately to increase performance
					 * in the WordPress Customizer.
					 * 
					 */
					wp.customize( 'tt_font_theme_options[' + id + '][background_color]', function( value ) {
						// Generate unique id for style tag
						var styleId = 'tt-font-' + id + '-background-color';

						value.bind(function(to) {

							if ( to === '' ) {
								$( '#' + styleId ).remove(); 
							} else {
								// Generate inline styles
								var style = '<style id="' + styleId + '" type="text/css">';
								style += selector +' { background-color: ' + to + importance + '; }';
								style += '</style>';

								// Update live preview for element
								$( '#' + styleId ).remove(); 
								$(style).appendTo( head );								
							}
						});
					});

					/**
					 * Set live preview for all other font properties here
					 * 
					 */
					wp.customize( 'tt_font_theme_options[' + id + ']', function( value ) {
						value.bind(function(to) {
							if ( 'string' === $.type(to) ) {
								try {
									to = $.parseJSON( to );
								} catch (e) {
									return;
								}
							}

							if ( null !== to ) {

								switch( to.changed ) {
									case "font" :
										preview.setFontStylesheet( id, to );
										preview.setFontName( id, selector, to, importance );
										preview.setFontWeight( id, selector, to, importance );
										preview.setFontStyle( id, selector, to, importance );
										break;

									case "text-decoration" :
										preview.setTextDecoration( id, selector, to, importance );
										break;

									case "text-transform" :
										preview.setTextTransform( id, selector, to, importance );
										break;

									case "font-size":
										preview.setFontSize( id, selector, to, importance );
										break;

									case "line-height" :
										preview.setLineHeight( id, selector, to, importance );
										break;

									case "letter-spacing" :
										preview.setLetterSpacing( id, selector, to, importance );
										break;

									case "margin-top":
										preview.setMargin( id, selector, to, importance, 'top' );
										break;

									case "margin-bottom":
										preview.setMargin( id, selector, to, importance, 'bottom' );
										break;

									case "margin-left":
										preview.setMargin( id, selector, to, importance, 'left' );
										break;

									case "margin-right":
										preview.setMargin( id, selector, to, importance, 'right' );
										break;

									case "padding-top":
										preview.setPadding( id, selector, to, importance, 'top' );
										break;

									case "padding-bottom":
										preview.setPadding( id, selector, to, importance, 'bottom' );
										break;

									case "padding-left":
										preview.setPadding( id, selector, to, importance, 'left' );
										break;

									case "padding-right":
										preview.setPadding( id, selector, to, importance, 'right' );
										break;

									case "display":
										preview.setDisplay( id, selector, to, importance );
										break;

									default :
										preview.setFontStylesheet( id, to );
										preview.setFontName( id, selector, to, importance );
										preview.setFontWeight( id, selector, to, importance );
										preview.setFontStyle( id, selector, to, importance );
										preview.setTextDecoration( id, selector, to, importance );
										preview.setTextTransform( id, selector, to, importance );
										preview.setFontSize( id, selector, to, importance );
										preview.setLineHeight( id, selector, to, importance );
										preview.setLetterSpacing( id, selector, to, importance );
										preview.setMargin( id, selector, to, importance, 'top' );
										preview.setMargin( id, selector, to, importance, 'bottom' );
										preview.setMargin( id, selector, to, importance, 'left' );
										preview.setMargin( id, selector, to, importance, 'right' );
										preview.setPadding( id, selector, to, importance, 'top' );
										preview.setPadding( id, selector, to, importance, 'bottom' );
										preview.setPadding( id, selector, to, importance, 'left' );
										preview.setPadding( id, selector, to, importance, 'right' );
										preview.setDisplay( id, selector, to, importance );
										break;
								}
							}
						});
					});	
				}
			});
		};

		/**
		 * Enqueue Font Stylesheet into <head>
		 *
		 * @description - Takes the font control object and 
		 *     injects the appropriate stylesheet in the <head>.
		 *     Used to load the appropriate google fonts css
		 *     stylesheet in the previewer.
		 * 
		 * 
		 * @param {string} 	id     Control ID
		 * @param {obj} 	obj    Object containing all of the current settings
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setFontStylesheet = function( id, obj ) {
			var styleId = obj.font_id + '_' + obj.font_weight_style;

			if ( '' !== obj.stylesheet_url && undefined !== obj.stylesheet_url && ( 0 === $( '#' + styleId ).length ) ) {
				$( '<link id="' + styleId + '" type="text/css" media="all" href="' + obj.stylesheet_url + '&subset=' + obj.subset + '" rel="stylesheet">' ).appendTo( $( 'head' ) );
			}
		};

		/**
		 * Set Font Name
		 *
		 * @description - Sets the font family css property for
		 *     the selectors passed in the parameter and injects
		 *     the styles into the <head> of the page.
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setFontName = function( id, selector, obj, importance ) {
			var styleId = 'tt-font-' + id + '-font-family';

			if ( "undefined" !== $.type( obj.font_name ) ) {
				var to = obj.font_name;
				
				if ( to === 'theme-default' || to === '' ) {
					$( '#' + styleId ).remove(); 
				} else {
					// Generate inline styles
					var style = '<style id="' + styleId + '" type="text/css">';
					style += selector +' { font-family: ' + to + importance + '; }';
					style += '</style>';

					// Update live preview for element
					$( '#' + styleId ).remove(); 
					$(style).appendTo( $( 'head' ) );								
				}
			}
		};

		/**
		 * Set Font Weight
		 *
		 * @description - Sets the font weight css property for
		 *     the selectors passed in the parameter and injects
		 *     the styles into the <head> of the page.
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setFontWeight = function( id, selector, obj, importance ) {
			var styleId = 'tt-font-' + id + '-font-weight';

			if ( "undefined" !== $.type( obj.font_weight ) ) {
				var to = obj.font_weight;

				if ( to === 'theme-default' || to === '' ) {
					$( '#' + styleId ).remove();
				} else {
					// Generate inline styles
					var style = '<style id="' + styleId + '" type="text/css">';
					style += selector +' { font-weight: ' + to + importance + '; }';
					style += '</style>';

					// Update live preview for element
					$( '#' + styleId ).remove(); 
					$(style).appendTo( $( 'head' ) );					
				}
			}
		};

		/**
		 * Set Font Style
		 *
		 * @description - Sets the font weight css property for
		 *     the selectors passed in the parameter and injects
		 *     the styles into the <head> of the page.
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setFontStyle = function( id, selector, obj, importance ) {
			var styleId = 'tt-font-' + id + '-font-style';

			if ( "undefined" !== $.type( obj.font_style ) ) {
				var to = obj.font_style;

				if ( to === 'theme-default' || to === '' ) {
					$( '#' + styleId ).remove();
				} else {
					// Generate inline styles
					var style = '<style id="' + styleId + '" type="text/css">';
					style += selector +' { font-style: ' + to + importance + '; }';
					style += '</style>';

					// Update live preview for element
					$( '#' + styleId ).remove(); 
					$(style).appendTo( $( 'head' ) );					
				}
			}
		};
				
		/**
		 * Set Text Decoration
		 *
		 * @description - Takes the font control object and 
		 *     injects the appropriate <style> in the <head>.
		 *     Used to update the text decoration.
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setTextDecoration = function( id, selector, obj, importance ) {
			var styleId = 'tt-font-' + id + '-text-decoration';
			if ( "undefined" !== $.type( obj.text_decoration ) ) {
				var to = obj.text_decoration;

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
					$(style).appendTo( $( 'head' ) );	
				}
			}
		};

		/**
		 * Set Text Transform
		 *
		 * @description - Takes the font control object and 
		 *     injects the appropriate <style> in the <head>.
		 *     Used to update the text transform property.
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setTextTransform = function( id, selector, obj, importance ) {
			var styleId = 'tt-font-' + id + '-text-transform';
			if ( "undefined" !== $.type( obj.text_transform ) ) {
				var to = obj.text_transform;

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
					$(style).appendTo( $( 'head' ) );	
				}
			}
		};

		/**
		 * Set Font Size
		 *
		 * @description - Takes the font control object and 
		 *     injects the appropriate <style> in the <head>.
		 *     Used to update the font size property.
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setFontSize = function( id, selector, obj, importance ) {
			var styleId = 'tt-font-' + id + '-font-size';
			if ( "undefined" !== $.type( obj.font_size ) ) {
				var to = obj.font_size;

				if ( to === 'theme-default' || to === '' ) {
					// Remove any applied styles
					$( '#' + styleId ).remove();
				} else {
					// Generate inline styles
					var style = '<style id="' + styleId + '" type="text/css">';
					style += selector +' { font-size: ' + to.amount + to.unit + '; }';
					style += '</style>';
					
					// Update live preview for element
					$( '#' + styleId ).remove(); 
					$(style).appendTo( $( 'head' ) );	
				}				
			}
		};

		/**
		 * Set Line height
		 *
		 * @description - Takes the font control object and 
		 *     injects the appropriate <style> in the <head>.
		 *     Used to update the line height property.
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setLineHeight = function( id, selector, obj, importance ) {
			var styleId = 'tt-font-' + id + '-line-height';
			if ( "undefined" !== $.type( obj.line_height ) ) {
				var to = obj.line_height;

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
					$(style).appendTo( $( 'head' ) );
				}
			}
		};

		/**
		 * Set Letter Spacing
		 *
		 * @description - Takes the font control object and 
		 *     injects the appropriate <style> in the <head>.
		 *     Used to update the letter spacing property.
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setLetterSpacing = function( id, selector, obj, importance ) {
			var styleId = 'tt-font-' + id + '-letter-spacing';
			if ( "undefined" !== $.type( obj.letter_spacing ) ) {
				var to = obj.letter_spacing;
							
				if ( to === '' ) {
					// Remove any applied styles
					$( '#' + styleId ).remove();
				} else {

					// Generate inline styles
					var style = '<style id="' + styleId + '" type="text/css">';
					style += selector +' { letter-spacing: ' + to.amount + to.unit + importance + '; }';
					style += '</style>';

					// Update live preview for element
					$( '#' + styleId ).remove(); 
					$(style).appendTo( $( 'head' ) );
				}
			}
		};

		/**
		 * Set Margin
		 *
		 * @description - Takes the font control object and 
		 *     injects the appropriate <style> in the <head>.
		 *     Used to update the margin of an element.
		 * 
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 * @param {string} 	position 	Which position to control
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setMargin = function( id, selector, obj, importance, position ) {
			var styleId = 'tt-font-' + id + '-margin-' + position;

			if ( "undefined" !== $.type( obj[ 'margin_' + position ] ) ) {
				var to = obj[ 'margin_' + position ];

				if ( to === 'theme-default' || to === '' ) {
					// Remove any applied styles
					$( '#' + styleId ).remove();
				} else {
					// Generate inline styles
					var style = '<style id="' + styleId + '" type="text/css">';
					style += selector +' { margin-' + position + ': ' + to.amount + to.unit + '; }';
					style += '</style>';
					
					// Update live preview for element
					$( '#' + styleId ).remove(); 
					$(style).appendTo( $( 'head' ) );	
				}
			}
		};

		/**
		 * Set Padding
		 *
		 * @description - Takes the font control object and 
		 *     injects the appropriate <style> in the <head>.
		 *     Used to update the padding of an element.
		 * 
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 * @param {string} 	position 	Which position to control
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setPadding = function( id, selector, obj, importance, position ) {
			var styleId = 'tt-font-' + id + '-padding-' + position;

			if ( "undefined" !== $.type( obj[ 'padding_' + position ] ) ) {
				var to = obj[ 'padding_' + position ];

				if ( to === 'theme-default' || to === '' ) {
					// Remove any applied styles
					$( '#' + styleId ).remove();
				} else {
					// Generate inline styles
					var style = '<style id="' + styleId + '" type="text/css">';
					style += selector +' { padding-' + position + ': ' + to.amount + to.unit + '; }';
					style += '</style>';
					
					// Update live preview for element
					$( '#' + styleId ).remove(); 
					$(style).appendTo( $( 'head' ) );	
				}
			}
		};

		/**
		 * Set Display
		 *
		 * @description - Takes the font control object and 
		 *     injects the appropriate <style> in the <head>.
		 *     Used to update the display property.
		 * 
		 * @param {string} 	id         	Control ID
		 * @param {string} 	selector   	Selector managed by this font control
		 * @param {obj} 	obj        	Object containing all of the current settings
		 * @param {string} 	importance 	Whether to force styles using !important
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		preview.setDisplay = function( id, selector, obj, importance ) {
			var styleId = 'tt-font-' + id + '-display';
			if ( "undefined" !== $.type( obj.display ) ) {
				var to = obj.display;

				if ( to === 'theme-default' || to === '' ) {
					// Remove any applied styles
					$( '#' + styleId ).remove();
				} else {
					// Generate inline styles
					var style = '<style id="' + styleId + '" type="text/css">';
					style += selector +' { display: ' + to + '; }';
					style += '</style>';
					
					// Update live preview for element
					$( '#' + styleId ).remove(); 
					$(style).appendTo( $( 'head' ) );	
				}				
			}

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
