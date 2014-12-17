/**===============================================================
 * Customizer Controls
 * ===============================================================
 * 
 * This file contains all custom jQuery plugins and code used on 
 * the WordPress Customizer screen. It contains all of the js
 * code necessary to enable the custom controls used in the live
 * previewer. Big performance enhancement in this version, this
 * file has been completely rewritten from the ground up.
 *
 * v1.3.3
 *
 * PLEASE NOTE: The following jQuery plugin dependancies are required
 * in order for this file to run correctly:
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
 * =============================================================== */

/**===============================================================
 * FONT CONTROL PLUGIN
 * =============================================================== */
;( function($, window, document, undefined) {
	$.fn.ttFontControls = function() {
		var option = this;
		/**
		 * Init Custom Font Controls
		 * 
		 * @description - Initialises all slider controls
		 *     used on the customizer options page.
		 * 
		 * @return void
		 *
		 * @since 1.2
		 * @version 1.3.3
		 */
		option.init = function() {
			option.initToggle();		
			option.initTabs();
			option.initFontControls();
		};

		/**
		 * Toggle Font Properties
		 * 
		 * @description - Used to show / hide the font 
		 *     properties in the customizer. 
		 * 
		 * @return void
		 *
		 * @since 1.2
		 * @version 1.3.3
		 */
		option.initToggle = function() {
			$( '.tt-font-control' ).each( function(e) {
				var control        = $(this);
				var reset          = control.parent().find( '.tt-reset-font' );
				var toggle         = control.find( '.egf-font-toggle-trigger' );
				var properties     = control.find( '.tt-font-properties' );
				var controlToggles = control.find( '.tt-font-toggle' );

				toggle.on( 'click', function(e) {
					e.preventDefault();
					properties.toggleClass( 'selected' );
					reset.toggle();	
				});

				controlToggles.each( function(e) {
					e.preventDefault;
					var t     = $(this);
					var title = t.find( '.toggle-section-title' );

					title.on( 'click', function(e) {
						e.preventDefault();
						t.toggleClass( 'selected' );
					});
				});
			});
		};

		/**
		 * Font Properties Tabs
		 * 
		 * @description - Used to switch between properties
		 *     for each font control. 
		 * 
		 * @return void
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initTabs = function() {
			$( '.tt-font-control' ).each( function(e) {		
				var control = $(this);
				var tabs    = control.find( '.tt-customizer-tabs li' );	
				var panels  = control.find( '.tt-font-content' );

				tabs.on( 'click', function(e) {
					e.preventDefault();
					
					var tab    = $(this);
					var target = tab.data( 'customize-tab' );
					
					// Set selected tab
					tabs.removeClass( 'selected' );
					tab.addClass( 'selected' );

					// Show/Hide panels. 
					panels.each( function(e) {
						var panel = $(this);

						if ( panel.data( 'customize-tab' ) === target ) {
							panel.addClass( 'selected');
						} else {
							panel.removeClass( 'selected' );
						}
					});
				});
			});
		};
		
		/**
		 * Initialise Each Font Control
		 *
		 * @description - Sets up each control in a single 
		 *     font control.
		 * 
		 * @return {void}
		 * 
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initFontControls = function() {
			$.each( ttFontCustomizeSettings, function( key, value ) {
				var id              = key;             					// setting name
				var type            = value.type;       				// setting control type
				var obj             = this;								// current object
				var settings        = option.initSettings( id );        // Initialise current settings
				var defaultSettings = option.getDefaultSettings( id ); 	// default settings

				// Setup Font Controls - Styles Tab
				option.initFontSelection( id, settings, defaultSettings );
				
				// Setup Font Controls - Appearance Tab
				option.initFontColor( id, settings, defaultSettings );
				option.initBackgroundColor( id, settings, defaultSettings );
				option.initFontSizeSlider( id, settings, defaultSettings );
				option.initLineHeightSlider( id, defaultSettings );
				option.initLetterSpacingSlider( id, defaultSettings );
				
				// Setup Font Controls - Positioning Tab
				option.initMarginSliders( id, defaultSettings );
				option.initPaddingSliders( id, defaultSettings );
				option.initDisplay( id, defaultSettings );

				// Register Reset Events
				option.resetFontControl( id, settings, defaultSettings );
			});	
		};

		/**
		 * Initialise Font Selection
		 *
		 * @description - Used to set up the font selection options
		 *     on the styles tab in the customizer.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} settings        The current settings object
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initFontSelection = function( id, settings, defaultSettings ) {
			var control        = $( '#' + id );
			var subsets        = control.find( '.tt-font-subsets' );
			var fontFamily     = control.find( '.tt-font-family' );
			var fontWeight     = control.find( '.tt-font-weight' );
			var stylesheetUrl  = control.find( '.tt-font-stylesheet-url' );
			var fontWeightVal  = control.find( '.tt-font-weight-val' );
			var fontStyleVal   = control.find( '.tt-font-style-val' );
			var fontNameVal    = control.find( '.tt-font-name-val' );
			var textDecoration = control.find( '.tt-text-decoration' );
			var textTransform  = control.find( '.tt-text-transform' );

			/**
			 * Subset Change Event
			 * 
			 * @description - Allows the user to narrow the fonts down
			 *     based on its script/subset.
			 *     
			 */
			subsets.on( 'keyup change', function() {
				var selected    = $(this).find( ':selected' );
				var subset      = selected.data( 'subset' );
				var fontOptions = option.getFontFamilyOptions( subset );

				fontFamily.empty().append( fontOptions );
				fontFamily.trigger( 'change' );
			});

			/**
			 * Font Family Change Event
			 * 
			 * @description - Allows the user to narrow the fonts down
			 *     based on its script/subset.
			 *     
			 */
			fontFamily.on( 'keyup change', function() {
				var selected  = $(this).find( ':selected' );
				var fontId    = $(this).val();
				var fontType  = selected.data( 'font-type' );
				var fontObj   = option.getFont( fontId );
				var weightOpt = '';

				if ( ! $.isEmptyObject( fontObj ) ) {

					/**
					 * Update Font Weight Options
					 * 
					 * @description - Checks there is a valid font object and 
					 *     changes the font weight options accordingly.
					 *     
					 */
					$.each( fontObj.font_weights, function( key, value ) {
						var url    = fontObj.urls[ value ];
						var weight = parseInt( value, 10 );
						var style  = 'normal';

						// Set default font weight if weight is NaN
						if ( ( ! weight ) || value.indexOf( 'regular' ) !== -1 ) {
							weight = 400;
						}

						// Set font style attribute
						if ( 'italic' === value || value.indexOf( 'italic' ) !== -1 ) {
							style = 'italic';
						}

						weightOpt += '<option value="' + value + '" data-stylesheet-url="' + url + '" data-font-weight="' + weight + '" data-font-style="' + style + '">';
						weightOpt += value;
						weightOpt += '</option>';
					});

					// Change font weight select options
					fontWeight.empty().append( weightOpt );

					// Update hidden inputs and trigger the change event
					stylesheetUrl.val( '' ).val( fontWeight.find( ':selected' ).data( 'stylesheet-url' ) );
					fontWeightVal.val( '' ).val( fontWeight.find( ':selected' ).data( 'font-weight' ) );
					fontStyleVal.val( '' ).val( fontWeight.find( ':selected' ).data( 'font-style' ) );
					fontNameVal.val( '' ).val( $.trim( fontFamily.find( ':selected' ).text() ) );

					option.updateSettings( id, 'font' );

				} else {
					weightOpt += '<option value="">&mdash; Theme Default &mdash;</option>';

					// Change font weight select options
					fontWeight.empty().append( weightOpt );
					stylesheetUrl.val( '' );
					fontWeightVal.val( '' );
					fontStyleVal.val( '' );
					fontNameVal.val( '' );

					option.updateSettings( id, 'font' );
				}
			});


			/**
			 * Font Weight Change Event
			 *
			 * @description - Allows the user to change the font weight
			 *     of an element.
			 *     
			 */
			fontWeight.on( 'keyup change', function() {
				var selected  = $(this).find( ':selected' );
					
				// Update hidden inputs and trigger the change event
				stylesheetUrl.val( '' ).val( fontWeight.find( ':selected' ).data( 'stylesheet-url' ) );
				fontWeightVal.val( '' ).val( fontWeight.find( ':selected' ).data( 'font-weight' ) );
				fontStyleVal.val( '' ).val( fontWeight.find( ':selected' ).data( 'font-style' ) );
				
				if ( fontFamily.val() !== '' ) {
					fontNameVal.val( '' ).val( $.trim( fontFamily.find( ':selected' ).text() ) );
				} else {
					fontNameVal.val( '' );
				}

				option.updateSettings( id, 'font' );

			});

			/**
			 * Text Decoration Change Event
			 *
			 * @description - Allows the user to change the text decoration
			 *     of an element.
			 *     
			 */
			textDecoration.on( 'keyup change', function() {
				option.updateSettings( id, 'text-decoration' );
			});

			/**
			 * Text Transform Change Event
			 *
			 * @description - Allows the user to change the text decoration
			 *     of an element.
			 *     
			 */
			textTransform.on( 'keyup change', function() {
				option.updateSettings( id, 'text-transform' );
			});
		};

		/**
		 * Master Font Control Reset
		 * 
		 * @description - Resets the complete font control to
		 *     its default state.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} settings        The current settings object
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */	
		option.resetFontControl = function( id, settings, defaultSettings ) {
			var control = $( '#customize-control-' + id );
			var reset   = control.find( '.tt-reset-font' );
			
			reset.on( 'click', function(e) {
				e.preventDefault();
				option.resetFontStyles( id, settings, defaultSettings );
				option.resetFontAppearance( id, settings, defaultSettings );
				option.resetFontPositioning( id, settings, defaultSettings );
				return false;
			});	
		};

		/**
		 * Reset All Font Properties in Font Styles Tab
		 * 
		 * @description - Resets all of the dropdown controls in
		 *     the font styles tab back to their defaults.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} settings        The current settings object
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */		
		option.resetFontStyles = function( id, settings, defaultSettings ) {
			var control     = $( '#' + id );
			var fontControl = option.getFont( id );

			var subsets        = control.find( '.tt-font-subsets' );
			var fontFamily     = control.find( '.tt-font-family' );
			var fontWeight     = control.find( '.tt-font-weight' );
			var stylesheetUrl  = control.find( '.tt-font-stylesheet-url' );
			var fontWeightVal  = control.find( '.tt-font-weight-val' );
			var fontStyleVal   = control.find( '.tt-font-style-val' );
			var fontNameVal    = control.find( '.tt-font-name-val' );
			var textDecoration = control.find( '.tt-text-decoration' );
			var textTransform  = control.find( '.tt-text-transform' );

			// Reset select options
			subsets.val( defaultSettings.subset ).trigger( 'change' );
			fontFamily.val( defaultSettings.font_id ).trigger( 'change' );
			fontWeight.val( defaultSettings.font_weight_style ).trigger( 'change' );
			textDecoration.val( defaultSettings.text_decoration ).trigger( 'change' );
			textTransform.val( defaultSettings.text_transform ).trigger( 'change' );

			// Reset hidden inputs
			stylesheetUrl.val( defaultSettings.stylesheet_url ).trigger( 'change' );
			fontWeightVal.val( defaultSettings.font_weight ).trigger( 'change' );
			fontStyleVal.val( defaultSettings.font_style ).trigger( 'change' );
			fontNameVal.val( defaultSettings.font_name ).trigger( 'change' );

			// Update Live Preview
			option.updateSettings( id );
		};

		/**
		 * Reset All Font Properties in Font Appearance Tab
		 * 
		 * @description - Resets all of the dropdown controls in
		 *     the Font Appearance tab back to their defaults.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} settings        The current settings object
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */		
		option.resetFontAppearance = function( id, settings, defaultSettings ) {
			var control            = $( '#' + id );
			var colorReset         = control.find( '.tt-font-color-container .wp-picker-clear, .tt-font-color-container .wp-picker-default' );
			var backgroundReset    = control.find( '.tt-background-color-container .wp-picker-clear, .tt-background-color-container .wp-picker-default' );
			var fontSizeReset      = control.find( '.font-size-slider .tt-font-slider-reset' );
			var lineHeightReset    = control.find( '.line-height-slider .tt-font-slider-reset' );
			var letterSpacingReset = control.find( '.letter-spacing-slider .tt-font-slider-reset' );

			colorReset.trigger( 'click' );
			backgroundReset.trigger( 'click' );
			fontSizeReset.trigger( 'click' );
			lineHeightReset.trigger( 'click' );
			letterSpacingReset.trigger( 'click' );

			// Update Live Preview
			option.updateSettings( id );
		};

		/**
		 * Reset All Font Properties in Positioning Tab
		 * 
		 * @description - Resets all of the dropdown controls in
		 *     the positioning tab back to their defaults.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} settings        The current settings object
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.resetFontPositioning = function( id, settings, defaultSettings ) {
			var control      = $( '#' + id );
			var display      = control.find( '.tt-display-element' );
			var marginReset  = control.find( '.margin-top-slider .tt-font-slider-reset, .margin-bottom-slider .tt-font-slider-reset, .margin-left-slider .tt-font-slider-reset, .margin-right-slider .tt-font-slider-reset' );
			var paddingReset = control.find( '.padding-top-slider .tt-font-slider-reset, .padding-bottom-slider .tt-font-slider-reset, .padding-left-slider .tt-font-slider-reset, .padding-right-slider .tt-font-slider-reset' );

			display.val( defaultSettings.display ).trigger( 'change' );
			marginReset.trigger( 'click');
			paddingReset.trigger( 'click');

			// Update Live Preview
			option.updateSettings( id );
		};

		/**
		 * Init Font Color Control
		 *
		 * @description - Implements the new iris color picker in order
		 *     to control the font color of an element.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} settings        The current settings object
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initFontColor = function( id, settings, defaultSettings ) {
			var control       = $( '#' + id );
			var settingsInput = $( '#' + id + '-settings' );
			var settings      = $( '#' + id + '-settings' ).val();
			var color         = control.find( '.tt-font-color-container .tt-color-picker-hex' );
			var colorInput    = control.find( '.tt-font-color' );
			// Font Color Picker
			color.wpColorPicker({
				width : 240,
				change : function( event, ui ) {
					colorInput.val( ui.color.toString() ).trigger( 'change' );
				},
				clear : function() {
					colorInput.val('').trigger( 'change' );
				}
			});
		};

		/**
		 * Init Background Color Control
		 *
		 * @description - Implements the new iris color picker in order
		 *     to control the background color of an element.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} settings        The current settings object
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initBackgroundColor = function( id, settings, defaultSettings ) {
			var control       = $( '#' + id );
			var color         = control.find( '.tt-background-color-container .tt-color-picker-hex' );
			var colorInput    = control.find( '.tt-font-background-color' );

			// Font Color Picker
			color.wpColorPicker({
				width : 240,
				change : function( event, ui ) {
					colorInput.val( ui.color.toString() ).trigger( 'change' );
				},
				clear : function() {
					colorInput.val('').trigger( 'change' );
				}
			});
		};

		/**
		 * Init Font Size Slider
		 *
		 * @description - Initialises a new jQuery UI Slider control
		 *     to manage the font size of an element.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initFontSizeSlider = function( id, settings, defaultSettings ) {
			var control     = $( '#' + id );
			var slider      = control.find( '.font-size-slider .tt-slider' );
			var reset       = control.find( '.font-size-slider .tt-font-slider-reset' );
			var amount      = control.find( '.font-size-slider .tt-font-slider-amount' );
			var unit        = control.find( '.font-size-slider .tt-font-slider-unit' );
			var defaultUnit = slider.data( 'default-unit' );
			var display     = control.find( '.font-size-slider .tt-font-slider-display span' );
			var value       = amount.val();
			var min         = slider.data( 'min-range' ) ? slider.data( 'min-range' ) : 30;
			var max         = slider.data( 'max-range' ) ? slider.data( 'max-range' ) : 100;

			// Set value to default if it hasn't been set
			if ( '' === amount.val() ) {
				value = amount.data( 'default-value' );
				amount.val( value );
			}
			
			display.text( value + defaultUnit );

			// Init jQuery UI slider
			slider.slider({
				min   : min,
				max   : max,
				value : value,
				slide : function( event, ui ) {
					display.text( ui.value + defaultUnit );
					amount.val( ui.value ).trigger('change');
					unit.val( defaultUnit );
					option.updateSettings( id, 'font-size' );
				}
			});

			/**
			 * Init Reset Event for the font
			 * size slider
			 */
			reset.on( 'click', function(e) {
				e.preventDefault();
				var defaultValue = amount.data( 'default-value' );
				slider.slider({ value : defaultValue });
				display.text( defaultValue + defaultUnit );
				amount.val( defaultValue ).trigger( 'change' );
				option.updateSettings( id, 'font-size' );
				return false;
			});
		};

		/**
		 * Init Letter Spacing Slider
		 *
		 * @description - Initialises a new jQuery UI Slider control
		 *     to manage the letter spacing property of an element.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initLetterSpacingSlider = function( id, defaultSettings ) {
			var control     = $( '#' + id );
			var slider      = control.find( '.letter-spacing-slider .tt-slider' );
			var reset       = control.find( '.letter-spacing-slider .tt-font-slider-reset' );
			var amount      = control.find( '.letter-spacing-slider .tt-font-slider-amount' );
			var unit        = control.find( '.letter-spacing-slider .tt-font-slider-unit' );
			var defaultUnit = slider.data( 'default-unit' );
			var display     = control.find( '.letter-spacing-slider .tt-font-slider-display span' );
			var value       = amount.val();
			var min         = slider.data( 'min-range' ) ? slider.data( 'min-range' ) : 30;
			var max         = slider.data( 'max-range' ) ? slider.data( 'max-range' ) : 100;

			// Set value to default if it hasn't been set
			if ( '' === amount.val() ) {
				value = amount.data( 'default-value' );
				amount.val( value );
			}
			
			display.text( value + defaultUnit );

			// Init jQuery UI slider
			slider.slider({
				min   : min,
				max   : max,
				value : value,
				slide : function( event, ui ) {
					display.text( ui.value + defaultUnit );
					amount.val( ui.value ).trigger('change');
					unit.val( defaultUnit );
					option.updateSettings( id, 'letter-spacing' );
				}
			});

			/**
			 * Init Reset Event for the font
			 * size slider
			 */
			reset.on( 'click', function(e) {
				e.preventDefault();
				var defaultValue = amount.data( 'default-value' );
				slider.slider({ value : defaultValue });
				display.text( defaultValue + defaultUnit );
				amount.val( defaultValue ).trigger( 'change' );
				option.updateSettings( id, 'letter-spacing' );
				return false;
			});
		};

		/**
		 * Init Font Size Slider
		 *
		 * @description - Initialises a new jQuery UI Slider control
		 *     to manage the line height of an element.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initLineHeightSlider = function( id, defaultSettings ) {
			var control     = $( '#' + id );
			var slider      = control.find( '.line-height-slider .tt-slider' );
			var reset       = control.find( '.line-height-slider .tt-font-slider-reset' );
			var amount      = control.find( '.line-height-slider .tt-font-slider-amount' );
			var display     = control.find( '.line-height-slider .tt-font-slider-display span' );
			var value       = amount.val();
			var min         = slider.data( 'min-range' ) ? slider.data( 'min-range' ) : 0.8;
			var max         = slider.data( 'max-range' ) ? slider.data( 'max-range' ) : 4.0;
			var step        = slider.data( 'step' )      ? slider.data( 'step' )      : 0.1;

			// Set value to default if it hasn't been set
			if ( '' === amount.val() ) {
				value = amount.data( 'default-value' );
				amount.val( value );
			}
			
			display.text( value );

			// Init jQuery UI slider
			slider.slider({
				min   : min,
				max   : max,
				value : value,
				slide : function( event, ui ) {
					display.text( ui.value );
					amount.val( ui.value ).trigger('change');
					option.updateSettings( id, 'line-height' );
				},
				step: step
			});

			/**
			 * Init Reset Event for the font
			 * size slider
			 */
			reset.on( 'click', function(e) {
				e.preventDefault();
				var defaultValue = amount.data( 'default-value' );
				slider.slider({ value : defaultValue });
				display.text( defaultValue );
				amount.val( defaultValue ).trigger( 'change' );
				option.updateSettings( id, 'line-height' );
				return false;
			});
		};
		
		/**
		 * Init Margin Sliders
		 *
		 * @description - Initialises a new jQuery UI Slider control
		 *     to manage the margin properties of an element. This
		 *     function creates new control to manage the top, bottom,
		 *     left and right margin properties.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initMarginSliders = function( id, defaultSettings ) {
			var control     = $( '#' + id );

			// Set up every margin slider in this control
			control.find( '.margin-slider' ).each( function(e){
				var marginControl = $(this);
				var slider        = marginControl.find( '.tt-slider' );
				var reset         = marginControl.find( '.tt-font-slider-reset' );
				var amount        = marginControl.find( '.tt-font-slider-amount' );
				var unit          = marginControl.find( '.tt-font-slider-unit' );
				var display       = marginControl.find( '.tt-font-slider-display span' );
				var value         = amount.val();
				var min           = slider.data( 'min-range' ) ? slider.data( 'min-range' ) : 0;
				var max           = slider.data( 'max-range' ) ? slider.data( 'max-range' ) : 400;
				var step          = slider.data( 'step' )      ? slider.data( 'step' )      : 1;
				var defaultUnit   = slider.data( 'default-unit' );

				// Determine Position
				var position  = '';

				if ( marginControl.hasClass( 'margin-top-slider' ) ) {
					position = 'top';
				} else if ( marginControl.hasClass( 'margin-bottom-slider' ) ) {
					position = 'bottom';
				} else if ( marginControl.hasClass( 'margin-left-slider' ) ) {
					position = 'left';
				} else if ( marginControl.hasClass( 'margin-right-slider' ) ) {
					position = 'right';
				}

				// Set value if it hasn't been set
				if ( '' === amount.val() ) {
					value = amount.data( 'default-value' );
				}

				display.text( value + defaultUnit );

				// Init Slider
				slider.slider({
					min   : min,
					max   : max,
					value : value,
					slide : function( event, ui ) {
						display.text( ui.value + defaultUnit );
						amount.val( ui.value ).trigger('change');
						unit.val( defaultUnit );
						
						option.updateSettings( id, 'margin-' + position );
					},
					step  : step 
				});

				/**
				 * Reset Event for Margin Sliders
				 */
				reset.on('click', function(e){
					e.preventDefault();
					var defaultValue = slider.data( 'default-value' );
					slider.slider({ value : defaultValue });
					display.text( defaultValue + defaultUnit );
					amount.val( defaultValue ).trigger( 'change' );
					option.updateSettings( id, 'margin-' + position );
					return false;
				});	
			});
		};

		/**
		 * Init Padding Sliders
		 *
		 * @description - Initialises a new jQuery UI Slider control
		 *     to manage the margin properties of an element. This
		 *     function creates new control to manage the top, bottom,
		 *     left and right margin properties.
		 * 
		 * @param  {string} id              The font control id
		 * @param  {object} defaultSettings The default settings object
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initPaddingSliders = function( id, defaultSettings ) {
			var control     = $( '#' + id );

			// Set up every padding slider in this control
			control.find( '.padding-slider' ).each( function(e){
				var paddingControl = $(this);
				var slider        = paddingControl.find( '.tt-slider' );
				var reset         = paddingControl.find( '.tt-font-slider-reset' );
				var amount        = paddingControl.find( '.tt-font-slider-amount' );
				var unit          = paddingControl.find( '.tt-font-slider-unit' );
				var display       = paddingControl.find( '.tt-font-slider-display span' );
				var value         = amount.val();
				var min           = slider.data( 'min-range' ) ? slider.data( 'min-range' ) : 0;
				var max           = slider.data( 'max-range' ) ? slider.data( 'max-range' ) : 400;
				var step          = slider.data( 'step' )      ? slider.data( 'step' )      : 1;
				var defaultUnit   = slider.data( 'default-unit' );

				// Determine Position
				var position  = '';

				if ( paddingControl.hasClass( 'padding-top-slider' ) ) {
					position = 'top';
				} else if ( paddingControl.hasClass( 'padding-bottom-slider' ) ) {
					position = 'bottom';
				} else if ( paddingControl.hasClass( 'padding-left-slider' ) ) {
					position = 'left';
				} else if ( paddingControl.hasClass( 'padding-right-slider' ) ) {
					position = 'right';
				}

				// Set value if it hasn't been set
				if ( '' === amount.val() ) {
					value = amount.data( 'default-value' );
				}

				display.text( value + defaultUnit );

				// Init Slider
				slider.slider({
					min   : min,
					max   : max,
					value : value,
					slide : function( event, ui ) {
						display.text( ui.value + defaultUnit );
						amount.val( ui.value ).trigger('change');
						unit.val( defaultUnit );
						option.updateSettings( id, 'padding-' + position );
					},
					step  : step 
				});

				/**
				 * Reset Event for Margin Sliders
				 */
				reset.on('click', function(e){
					e.preventDefault();
					var defaultValue = slider.data( 'default-value' );
					slider.slider({ value : defaultValue });
					display.text( defaultValue + defaultUnit );
					amount.val( defaultValue ).trigger( 'change' );
					option.updateSettings( id, 'padding-' + position );
					return false;
				});	
			});
		};

		/**
		 * Init Display Select Menu
		 *
		 * @description - A select menu used to control the
		 *     display property of an element.
		 * 
		 * @param  {string} id 					Control ID
		 * @param  {object} defaultSettings 	The default settings for this control
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initDisplay = function( id, defaultSettings ) {
			var control = $( '#' + id );
			var display = control.find( '.tt-display-element' );

			display.on( 'keyup change', function() {
				option.updateSettings( id, 'display' );
			});
		};

		/**
		 * Get Font By ID
		 *
		 * @description - Gets the appropriate font object with the id
		 *     passed in the parameter if it exists. Returns false if
		 *     no font object was found. Requires the ttFontAllFonts js 
		 *     object to be enqueued on the page.
		 *
		 * @uses   {object} ttFontAllFonts
		 * @param  {string} id 		The id of the font to retrieve
		 * @return {object} font 	The font object if it exists
		 * 
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.getFont = function( id ) {
			if ( _.has( ttFontAllFonts, id ) ) {
				return ttFontAllFonts[ id ];
			} else {
				return false;
			}
		};

		/**
		 * Get Font Family Select Menu
		 *
		 * @description - Builds the inner select menu output. 
		 *
		 * @uses   {object} ttFontAllFonts
		 * 
		 * @param  {string} id 		The id of the font to retrieve
		 * @return {object} font 	The font object if it exists
		 * 
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.getFontFamilyOptions = function( subset ) {
			
			if ( ! subset ) {
				subset = 'all';
			}

			var output           = '';
			var defaultFonts     = option.getDefaultFonts();
			var googleFonts      = option.getGoogleFontsBySubset( subset );
			var displayFonts     = {};
			var handwritingFonts = {};
			var monospaceFonts   = {};
			var serifFonts       = {};
			var sansSerifFonts   = {};

			output += '<option value="">' + ttFontTranslation.themeDefault + '</option>';
			
			if ( 'all' === subset ) {
				output += '<optgroup label="' + ttFontTranslation.standardFontLabel + '" class="css_label">';

				// Default font output
				$.each( defaultFonts, function( key, value ) {
					output += '<option data-font-type="default" value="' + key + '">' + value.name + '</option>';
				});

				output += '</optgroup>';
			}
	
			// Sort google fonts according to category
			$.each( googleFonts, function( key, value ) {
				switch( value.category ) {
					case 'display' :
						displayFonts[ key ] = value;
						break;

					case 'handwriting' :
						handwritingFonts[ key ] = value;
						break;

					case 'monospace' :
						monospaceFonts[ key ] = value;
						break;

					case 'sans-serif' :
						sansSerifFonts[ key ] = value;
						break;

					case 'serif' :
						serifFonts[ key ] = value;
						break;
				}
			});

			// Build font group html markup
			output += option.buildGoogleFontGroupOutput( ttFontTranslation.serifFontLabel, serifFonts );
			output += option.buildGoogleFontGroupOutput( ttFontTranslation.sansSerifFontLabel, sansSerifFonts );
			output += option.buildGoogleFontGroupOutput( ttFontTranslation.displayFontLabel, displayFonts );
			output += option.buildGoogleFontGroupOutput( ttFontTranslation.handwritingFontLabel, handwritingFonts );
			output += option.buildGoogleFontGroupOutput( ttFontTranslation.monospaceFontLabel, monospaceFonts );

			return output;
		};

		/**
		 * Build Google Font Option Markup
		 *
		 * @description - Builds a string containing the html 
		 *     markup for an option group. Designed to be used
		 *     in a select menu.
		 * 
		 * @param  {string} label  - To be used as the option group label
		 * @param  {object} obj    - JSON font object
		 * @return {string} output - HTML markup if fonts exist, empty string if empty object
		 *
		 * @since 1.3.2
		 * @version 1.3.3
		 * 
		 */
		option.buildGoogleFontGroupOutput = function( label, obj ) {
			
			// Fallback
			label = label || ttFontTranslation.fallbackFontLabel;
			obj   = obj   || {};

			// Build output
			var output = '';

			if ( ! $.isEmptyObject( obj ) ) {
				output += '<optgroup label="' + label + '" class="google_label">';

				$.each( obj, function( key, value ) {
					output += '<option data-font-type="google" value="' + key + '">' + value.name + '</option>';
				});

				output += '</optgroup>';
			}

			return output;
		};

		/**
		 * Get Google Fonts By Subset
		 *
		 * @description - New feature that allows the user to narrow
		 *     the list of fonts based on the subset.
		 *
		 * @uses   {object} ttFontAllFonts
		 * 
		 * @param  {string} subset 	The subset we want the fonts for
		 * @return {object} font 	The font object if it exists
		 * 
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.getGoogleFontsBySubset = function( subset ) {

			if ( ! subset ) {
				subset = 'all';
			}

			var fonts = {};

			if ( 'all' === subset ) {
				$.each( ttFontAllFonts, function( key, value ) {
					if ( 'google' === value.font_type ) {
						fonts[ key ] = value;
					}
				});
			} else {
				$.each( ttFontAllFonts, function( key, value ) {
					if ( 'google' === value.font_type && _.contains( value.subsets, subset ) ) {
						fonts[ key ] = value;
					}
				});
			}

			return fonts;
		};

		/**
		 * Get Default Fonts
		 *
		 * @description - Returns an object containing all of the 
		 *     default fonts.
		 *
		 * @uses   {object} ttFontAllFonts
		 * @param  {string} id 		The id of the font to retrieve
		 * @return {object} fonts 	The default font objects
		 * 
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */		
		option.getDefaultFonts = function() {
			var fonts = {};

			$.each( ttFontAllFonts, function( key, value ) {
				if ( 'default' === value.font_type ) {
					fonts[ key ] = value;
				}
			});

			return fonts;
		};

		/**
		 * Get Default Settings Object
		 *
		 * @description - Returns the default settings as an object
		 * 
		 * @return {object} The default settings object
		 * 
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */	
		option.getDefaultSettings = function( id ) {
			return ttFontCustomizeSettings[id].setting['default'];
		};

		/**
		 * Get Current Settings
		 *
		 * @description - Gets the current settings, parses it
		 *     with the default settings and returns it. Used
		 *     upon font control initialisation.
		 * 
		 * @param  {string} id 				Control ID
		 * @return {object} newSettings 	Current settings parsed with defaults
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.initSettings = function( id ) {
			var control         = $( '#' + id );
			var defaults        = option.getDefaultSettings( id );
			var currentSettings = $( '#' + id + '-settings' ).val();
			var settings        = option.getSettings( id );

			// Parse with defaults
			var newSettings = _.defaults( settings, defaults );
			
			// Set hidden input value to the parsed value, 
			// but don't trigger change event
			$( '#' + id + '-settings' ).val( JSON.stringify( newSettings ) );

			return newSettings;
		};

		/**
		 * Get Settings
		 *
		 * @description - Gets all of the current settings 
		 *     for the control with the id passed in the 
		 *     parameter.
		 * 
		 * @param  {string} id [description]
		 * @return {object} settings   Object containing the current settings
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.getSettings = function( id, changed ) {

			if ( ! changed ) {
				changed = 'all';
			}

			var control  = $( '#' + id );
			var settings = {};

			// Store the attribue that has been updated
			settings.changed = changed;
			
			// Get font style attributes	
			settings.subset            = control.find( '.tt-font-subsets' ).val();
			settings.font_id           = control.find( '.tt-font-family' ).val();
			settings.font_name         = control.find( '.tt-font-name-val' ).val();
			settings.font_weight       = control.find( '.tt-font-weight-val' ).val();
			settings.font_style        = control.find( '.tt-font-style-val' ).val();
			settings.font_weight_style = control.find( '.tt-font-weight' ).val();
			settings.stylesheet_url    = control.find( '.tt-font-weight' ).find( ':selected' ).data( 'stylesheet-url' );
			settings.text_decoration   = control.find( '.tt-text-decoration' ).val();
			settings.text_transform    = control.find( '.tt-text-transform' ).val();

			// Get font appearence attributes
			settings.font_color       = control.find( '.tt-font-color' ).val();
			settings.background_color = control.find( '.tt-font-background-color' ).val();

			settings.font_size             = {};
			settings.font_size.amount      = control.find( '.font-size-slider .tt-font-slider-amount' ).val();
			settings.font_size.unit        = control.find( '.font-size-slider .tt-font-slider-unit' ).val();
			settings.line_height           = control.find( '.line-height-slider .tt-font-slider-amount' ).val();
			settings.letter_spacing        = {};
			settings.letter_spacing.amount = control.find( '.letter-spacing-slider .tt-font-slider-amount' ).val();
			settings.letter_spacing.unit   = control.find( '.letter-spacing-slider .tt-font-slider-unit' ).val();

			// Get font position attributes - Margin
			settings.margin_top           = {};
			settings.margin_top.amount    = control.find( '.margin-top-slider .tt-font-slider-amount' ).val();
			settings.margin_top.unit      = control.find( '.margin-top-slider .tt-font-slider-unit' ).val();
			
			settings.margin_bottom        = {};
			settings.margin_bottom.amount = control.find( '.margin-bottom-slider .tt-font-slider-amount' ).val();
			settings.margin_bottom.unit   = control.find( '.margin-bottom-slider .tt-font-slider-unit' ).val();
			
			settings.margin_left          = {};
			settings.margin_left.amount   = control.find( '.margin-left-slider .tt-font-slider-amount' ).val();
			settings.margin_left.unit     = control.find( '.margin-left-slider .tt-font-slider-unit' ).val();
			
			settings.margin_right         = {};
			settings.margin_right.amount  = control.find( '.margin-right-slider .tt-font-slider-amount' ).val();
			settings.margin_right.unit    = control.find( '.margin-right-slider .tt-font-slider-unit' ).val();

			// Get font position attributes - Padding
			settings.padding_top           = {};
			settings.padding_top.amount    = control.find( '.padding-top-slider .tt-font-slider-amount' ).val();
			settings.padding_top.unit      = control.find( '.padding-top-slider .tt-font-slider-unit' ).val();
			
			settings.padding_bottom        = {};
			settings.padding_bottom.amount = control.find( '.padding-bottom-slider .tt-font-slider-amount' ).val();
			settings.padding_bottom.unit   = control.find( '.padding-bottom-slider .tt-font-slider-unit' ).val();
			
			settings.padding_left          = {};
			settings.padding_left.amount   = control.find( '.padding-left-slider .tt-font-slider-amount' ).val();
			settings.padding_left.unit     = control.find( '.padding-left-slider .tt-font-slider-unit' ).val();
			
			settings.padding_right         = {};
			settings.padding_right.amount  = control.find( '.padding-right-slider .tt-font-slider-amount' ).val();
			settings.padding_right.unit    = control.find( '.padding-right-slider .tt-font-slider-unit' ).val();

			settings.display = control.find( '.tt-display-element' ).val();
			
			// Return settings
			return settings;
		};

		/**
		 * Get Settings JSON Value as String
		 *
		 * @description - Converts the settings as a json string so that
		 *     it can be transferred to the live previewer.
		 * 
		 * @param  {string} id The control id
		 * @return {string}    The json settings object as a string
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.getSettingsValue = function( id, changed ) {
			if ( ! changed ) {
				changed = 'all';
			}
			return JSON.stringify( option.getSettings( id, changed ) );
		};

		/**
		 * Update Settings
		 *
		 * @description Updates the hidden settings input
		 *     with the new settings object passed as the
		 *     parameter.
		 *
		 * Note: In this version we are forcing the 'changed' variable
		 * to 'all' regardless of the value passed in the parameter. 
		 * This is to allow persistant settings as the user changes 
		 * the page within the customizer with a minimal performance
		 * impact.
		 *
		 * @todo - Explore an alternative to forcing the changed value.
		 * 
		 * @param  {string} id       		The id of the control to update.
		 * @param  {string} changed    		Flag to indicate the field that has been changed.
		 * @param  {boolean} triggerChange 	Wether to trigger the change event for the previewer
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.updateSettings = function( id, changed, triggerChange ) {
			if ( ! changed ) {
				changed = 'all';
			}

			changed = 'all';

			// Update hidden input
			$( '#' + id + '-settings' ).val( option.getSettingsValue( id, changed ) );

			if ( undefined === triggerChange ) {
				triggerChange = true;
			}

			if ( triggerChange ) {
				option.triggerChange( id );
			}
		};

		/**
		 * Trigger Font Control Change
		 *
		 * @description  - Trigger the change event on the font 
		 *     control so that it is picked up by the live 
		 *     preview controller.
		 * 
		 * @param  {string} id The control id
		 * @return {void}
		 *
		 * @since 1.2
		 * @version 1.3.3
		 * 
		 */
		option.triggerChange = function( id ) {
			$( '#' + id + '-settings' ).trigger( 'change' );
		};
		
		// Run init on plugin initialisation
		option.init();
		return option;			
	};
}(jQuery, window, document));

/**============================================================
 * INITIALISE PLUGINS & JS ON DOCUMENT READY EVENT
 * ============================================================ */
jQuery(document).ready(function($) {"use strict";
	/**
	 * Temprorary WordPress 4.1+ fix
	 *
	 * As WordPress has developed the customizer js api this
	 * is a temporary patch for the plugin before it is 
	 * completely rewritten in backbone.
	 * 
	 */
	setTimeout(function(){ $(this).ttFontControls(); }, 200);
});	