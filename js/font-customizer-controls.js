/**===============================================================
 * THEME CUSTOMIZER CONTROLS JAVASCRIPT
 * ===============================================================
 * This file contains all custom jQuery plugins and code used on 
 * the WordPress Customizer screen. It contains all of the js
 * code necessary to enable the custom controls used in the live
 * previewer.
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
 * @todo increase dependancy on JS in future releases.
 * 
 * @since 1.0
 * @version 1.1.1
 *
 * =============================================================== */

/**===============================================================
 * FONT CONTROL
 * =============================================================== */
;( function($, window, document, undefined) {
	$.fn.ttFontControls = function() {
		var option = this;
		
		/**
		 * Init Custom Font Controls
		 * 
		 * @desc - Initialises all slider controls
		 *     used on the customizer options page.
		 * 
		 * @return void
		 *
		 * @since 1.0
		 * @version 1.1.1
		 */
		option.init = function() {
			option.initToggle();
			option.initTabs();
			option.initFontSelection();
			option.initFontControls();
			
		};

		/**
		 * Toggle Font Properties
		 * 
		 * @desc - Used to show / hide the font 
		 *     properties in the customizer. 
		 * 
		 * @return void
		 *
		 * @since 1.0
		 * @version 1.1.1
		 */
		option.initToggle = function() {

			$( '.tt-font-control' ).each( function(e) {
				var control        = $(this);
				var reset          = control.parent().find( '.tt-reset-font' );
				var toggle         = control.find( '.dropdown.preview-thumbnail' );
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
		 * @desc - Used to switch between properties
		 *     for each font control. 
		 * 
		 * @return void
		 *
		 * @since 1.0
		 * @version 1.1.1
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
		 * @return {[type]} [description]
		 * 
		 * @since 1.0
		 * @version 1.1.1
		 */
		option.initFontControls = function() {
			
			$.each( ttFontCustomizeSettings, function( key, value ) {
                var id        = key;                    // setting name
                var type      = value.type;             // setting control type
                var obj       = this;					// current object

                if ( 'font' === type ) {
                	option.initColorControls();
              		option.initFontSliders( id );
              		option.resetFontSliders( id );
              		option.resetFontControl( id );           	
                }
			});			
		}
		
		/**
		 * Reset Entire Font Control
		 *
		 * @desc - Resets the entire control to its default 
		 *     values.
		 * 
		 * @param  {string} setting - The setting id of the control
		 * 
		 * @return {void}
		 * 
		 * @since 1.0
		 * @version 1.1.1
		 */
		option.resetFontControl = function( setting ) {

			var control     = $( '[data-font-control-id="' + setting + '"]' );
			var fontControl = option.getFontControl( setting );

			if ( ! $.isEmptyObject( fontControl ) )	{
				
				var resetControl         = control.parent().find( '.tt-reset-font' );
				var fontSize             = control.find( '.font-size-slider' );
				var fontSizeReset        = fontSize.find( '.tt-font-slider-reset' );
				var lineHeight           = control.find( '.line-height-slider' );
				var lineHeightReset      = lineHeight.find( '.tt-font-slider-reset' );
				var letterSpacing        = control.find( '.letter-spacing-slider' );
				var letterSpacingReset   = letterSpacing.find( '.tt-font-slider-reset' );
				var colorReset           = control.find( '.tt-font-color-container .wp-picker-clear, .tt-font-color-container .wp-picker-default' );
				var backgroundColorReset = control.find( '.tt-font-background-color-container .wp-picker-clear, .tt-font-background-color-container .wp-picker-default' );
				var marginReset          = control.find( '.margin-top-slider .tt-font-slider-reset, .margin-bottom-slider .tt-font-slider-reset, .margin-left-slider .tt-font-slider-reset, .margin-right-slider .tt-font-slider-reset' );
				var paddingReset         = control.find( '.padding-top-slider .tt-font-slider-reset, .padding-bottom-slider .tt-font-slider-reset, .padding-left-slider .tt-font-slider-reset, .padding-right-slider .tt-font-slider-reset' );

				resetControl.on( 'click', function(e) {
					e.preventDefault();
					option.resetFontStyles( setting );
					fontSizeReset.trigger( 'click' );
					lineHeightReset.trigger( 'click' );
					letterSpacingReset.trigger( 'click' );
					colorReset.trigger( 'click' );
					backgroundColorReset.trigger( 'click' );
					marginReset.trigger( 'click' );
					paddingReset.trigger( 'click' );
					return false;
				});
			}

		};

		/**
		 * Register Font Change Event Listener
		 * 
		 * @desc - Used to load the appropriate font
		 * 
		 * @return void
		 *
		 * @since 1.0
		 * @version 1.1.1
		 */
		option.initFontSelection = function() {
			$( '.tt-font-control' ).each( function(e) {
				var control        = $(this);
				var setting        = control.data( 'font-control-id' );
				var fontFamily     = control.find( '.tt-font-family' );
				var fontWeight     = control.find( '.tt-font-weight' );
				var stylesheetUrl  = control.find( '.tt-font-stylesheet-url' );
				var fontWeightVal  = control.find( '.tt-font-weight-val' );
				var fontStyleVal   = control.find( '.tt-font-style-val' );
				var fontNameVal    = control.find( '.tt-font-name-val' );
				var textDecoration = control.find( '.tt-text-decoration' );
				var textTransform  = control.find( '.tt-text-transform' );

				// Font family change event
				fontFamily.on( 'keyup change', function() {
					
					var selected          = $(this).find( ':selected' );	
					var fontId            = $(this).val();
					var fontType          = selected.data( 'font-type' );
					var fontObj           = option.getFontObject( setting, fontId, fontType );
					var fontControl       = option.getFontControl( setting );
					var fontWeightOptions = '';
					
					/**
					 * Update Font Weight Options
					 * 
					 * @desc - Checks there is a valid font object and 
					 *     changes the font weight options accordingly.
					 *     
					 */
					if ( ! $.isEmptyObject( fontObj ) ) {
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


							fontWeightOptions += '<option value="' + value + '" data-stylesheet-url="' + url + '" data-font-weight="' + weight + '" data-font-style="' + style + '">';
							fontWeightOptions += value;
							fontWeightOptions += '</option>';
						});

						// Change font weight select options
						fontWeight.empty().append( fontWeightOptions );
						fontWeight.trigger( 'change' );

						// Update hidden inputs and trigger the change event
						stylesheetUrl.val( '' ).val( fontWeight.find( ':selected' ).data( 'stylesheet-url' ) ).trigger( 'change' );
						fontWeightVal.val( '' ).val( fontWeight.find( ':selected' ).data( 'font-weight' ) ).trigger( 'change' );
						fontStyleVal.val( '' ).val( fontWeight.find( ':selected' ).data( 'font-style' ) ).trigger( 'change' );
						fontNameVal.val( '' ).val( $.trim( fontFamily.find( ':selected' ).text() ) ).trigger( 'change' );

					
					} else {
						/**
						 * Reset Font Family and Font Weight to Defaults
						 * 
						 * @desc - Checks there is a valid default font 
						 *     object and reset the Font Family and the
						 *     Font Weight/Style controls back to their
						 *     defaults.
						 *     
						 */
						
						// Determine defaultFontObj						
						var defaultFontObj = option.getFontObject( setting, fontControl.default_values.font_id, 'google' );

						if ( $.isEmptyObject( defaultFontObj ) ) {
							defaultFontObj = option.getFontObject( setting, fontControl.default_values.font_id, 'default' );
						}

						// If it is still an unvalid font object then reset controls to defaults
						if ( $.isEmptyObject( defaultFontObj ) ) {
							
							// Build Font Weight/Style options
							fontWeightOptions += '<option value="">';
							fontWeightOptions += ttFontTranslation.themeDefault;
							fontWeightOptions += '</option>';

							// Reset the existing control
							fontWeight.empty().append( fontWeightOptions );
							fontWeight.trigger( 'change' );
						
						} else {
							fontFamily.val( fontControl.default_values.font_id ).trigger( 'change' );
							fontWeight.val( fontControl.default_values.font_weight_style ).trigger( 'change' );
						}
						
					}


				});

				// Font weight change event
				fontWeight.on( 'keyup change', function() {
					var selected  = $(this).find( ':selected' );
						
						// Update hidden inputs and trigger the change event
						stylesheetUrl.val( '' ).val( fontWeight.find( ':selected' ).data( 'stylesheet-url' ) ).trigger( 'change' );
						fontWeightVal.val( '' ).val( fontWeight.find( ':selected' ).data( 'font-weight' ) ).trigger( 'change' );
						fontStyleVal.val( '' ).val( fontWeight.find( ':selected' ).data( 'font-style' ) ).trigger( 'change' );
						
						if ( fontFamily.val() !== '' ) {
							fontNameVal.val( '' ).val( $.trim( fontFamily.find( ':selected' ).text() ) ).trigger( 'change' );
						} else {
							fontNameVal.val( '' ).trigger( 'change' );
						}

						
				});
				
				// Text decoration change event
				textDecoration.on( 'keyup', function(e) {
					$(this).trigger( 'change' );
				});

				// Text transform change event
				textTransform.on( 'keyup', function(e) {
					$(this).trigger( 'change' );
				});


			});
		};

		/**
		 * Init Color Controls
		 * 
		 * @desc - Register event listener for the color field
		 *     and initalise the control.
		 *
		 * @return {void}
		 *
		 * @since 1.0
		 * @version 1.1.1
		 */		
		option.initColorControls = function() {
			$( '.tt-font-control' ).each( function(e) {
				var control              = $(this);
				var color                = control.find( '.tt-color-picker-hex' );
				var colorInput           = control.find( '.tt-font-color' );
				var backgroundColor      = control.find( '.tt-background-color-picker-hex' );
				var backgroundColorInput = control.find( '.tt-font-background-color' );
				
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

				// Background Color Picker
				backgroundColor.wpColorPicker({
					width : 240,
					change : function( event, ui ) {
						backgroundColorInput.val( ui.color.toString() ).trigger( 'change' );
					},
					clear : function() {
						backgroundColorInput.val('').trigger( 'change' );
					}
				});				


			});	
		};
		
		/**
		 * Get Font Object JSON Object
		 * 
		 * @desc - Used to get a JSON font object which
		 *     contains all of its associated properties.
		 *
		 * @uses ttFontCustomizeSettings 
		 *  
		 * @param  {string} setting  - The font control id
		 * @param  {string} fontId   - The id of the font to retrieve
		 * @param  {string} fontType - The type of font (google/default)
		 * @return {json} fontObj - The font object if it exists
		 *
		 * @since 1.0
		 * @version 1.1.1
		 */
		option.getFontObject = function( setting, fontId, fontType ) {
			var fontObj = {};

			$.each( ttFontCustomizeSettings, function( key, value ) {
                
                var id        = key;                     // setting name
                var type      = value.type;              // setting control type

                if ( 'font' === type && value.id === setting ) {
                	if ( 'google' === fontType && typeof value.google_fonts[ fontId ] !== 'undefined' ) {
                		fontObj = value.google_fonts[ fontId ];
                	} else if ( 'default' === fontType && typeof value.default_fonts[ fontId ] !== 'undefined' ) {
                		fontObj = value.default_fonts[ fontId ];
                	}
                }

			});

			return fontObj;
		};

		/**
		 * Get Font Control JSON Object
		 * 
		 * @desc - Used to get a JSON font object which
		 *     contains all of its associated properties.
		 *
		 * @uses ttFontCustomizeSettings 
		 *  
		 * @param  {string} setting  - The font control id
		 * 
		 * @return {json} fontControl - The font control object if it exists
		 *
		 * @since 1.0
		 * @version 1.1.1
		 */
		option.getFontControl = function( setting ) {
			var fontControl = {};

			$.each( ttFontCustomizeSettings, function( key, value ) {
                var id        = key;                    // setting name
                var type      = value.type;             // setting control type
                var obj       = this;					// current object

                if ( 'font' === type && value.id === setting ) {
              		fontControl = obj;                	
                }
			});

			return fontControl;
		};

		/**
		 * Initialise Font Sliders
		 * 
		 * @desc - Initialises all slider controls
		 *     in a particular font control.
		 * 
		 * @return {void}
		 *
		 * @since 1.0
		 * @version 1.1.1
		 */
		option.initFontSliders = function( setting ) {

			var control     = $( '[data-font-control-id="' + setting + '"]' );
			var fontControl = option.getFontControl( setting );
			
			if ( ! $.isEmptyObject( fontControl ) )	{

				/**
				 * Initialise Font Size Slider
				 * 
				 * Sets up the font size control using the
				 * jQuery UI Slider plugin.
				 * 
				 */
				var fontSize       = control.find( '.font-size-slider' );
				var fontSizeSlider = fontSize.find( '.tt-slider' );
				var fsAmount       = fontSize.find( '.tt-font-slider-amount' );
				var fsUnit         = fontSize.find( '.tt-font-slider-unit' );
				var fsDefaultUnit  = fontControl.default_values.font_size.unit.toString();
				var fsDisplay      = fontSize.find( '.tt-font-slider-display span' );
				var fsValue        = fsAmount.val();
				var fsMin          = fontSizeSlider.data( 'min-range' ) ? fontSizeSlider.data( 'min-range' ) : 10;
				var fsMax          = fontSizeSlider.data( 'max-range' ) ? fontSizeSlider.data( 'max-range' ) : 100;

				if ( '' === fsAmount.val() ) {
					fsValue = fsAmount.data( 'default-value' );
					fsAmount.val( fsValue );
				}
				
				fsDisplay.text( fsValue + fsDefaultUnit );

				fontSizeSlider.slider({
					min   : fsMin,
					max   : fsMax,
					value : fsValue,
					slide : function( event, ui ) {
						fsDisplay.text( ui.value + fsDefaultUnit );
						fsAmount.val( ui.value ).trigger('change');
						fsUnit.val( fsDefaultUnit );
					}
				});

				/**
				 * Initialise Line Height Slider
				 * 
				 * Sets up the line height control using the
				 * jQuery UI Slider plugin.
				 * 
				 */
				var lineHeight       = control.find( '.line-height-slider' );
				var lineHeightSlider = lineHeight.find( '.tt-slider' );
				var lhAmount         = lineHeight.find( '.tt-font-slider-amount' );
				var lhDisplay        = lineHeight.find( '.tt-font-slider-display span' );
				var lhValue          = lhAmount.val();
				var lhMin            = lineHeightSlider.data( 'min-range' ) ? lineHeightSlider.data( 'min-range' ) : 0.8;
				var lhMax            = lineHeightSlider.data( 'max-range' ) ? lineHeightSlider.data( 'max-range' ) : 4.0;
				var lhStep           = lineHeightSlider.data( 'step' )      ? lineHeightSlider.data( 'step' )      : 0.1;

				if ( '' === lhAmount.val() ) {
					lhValue = lhAmount.data( 'default-value' );
				}

				lhDisplay.text( lhValue );

				lineHeightSlider.slider({
					min   : lhMin,
					max   : lhMax,
					value : lhValue,
					slide : function( event, ui ) {
						lhDisplay.text( ui.value );
						lhAmount.val( ui.value ).trigger('change');
					},
					step  : lhStep 
				});
				
				/**
				 * Initialise Letter Spacing Slider
				 * 
				 * Sets up the letter spacing control using the
				 * jQuery UI Slider plugin.
				 * 
				 */
				var letterSpacing       = control.find( '.letter-spacing-slider' );
				var letterSpacingSlider = letterSpacing.find( '.tt-slider' );
				var lsAmount            = letterSpacing.find( '.tt-font-slider-amount' );
				var lsUnit              = letterSpacing.find( '.tt-font-slider-unit' );
				var lsDefaultUnit       = fontControl.default_values.letter_spacing.unit.toString();
				var lsDisplay           = letterSpacing.find( '.tt-font-slider-display span' );
				var lsValue             = lsAmount.val();
				var lsMin               = letterSpacingSlider.data( 'min-range' ) ? letterSpacingSlider.data( 'min-range' ) : -5;
				var lsMax               = letterSpacingSlider.data( 'max-range' ) ? letterSpacingSlider.data( 'max-range' ) : 20;
				var lsStep              = letterSpacingSlider.data( 'step' )      ? letterSpacingSlider.data( 'step' )      : 1;

				if ( '' === lsAmount.val() ) {
					lsValue = lsAmount.data( 'default-value' );
				}
				
				lsDisplay.text( lsValue + lsDefaultUnit );

				letterSpacingSlider.slider({
					min   : lsMin,
					max   : lsMax,
					value : lsValue,
					slide : function( event, ui ) {
						lsDisplay.text( ui.value + lsDefaultUnit );
						lsAmount.val( ui.value ).trigger('change');
						lsUnit.val( lsDefaultUnit );
					},
					step  : lsStep 
				});

				/**
				 * Initialise Margin Slider
				 * 
				 * Sets up the margin control using the
				 * jQuery UI Slider plugin.
				 * 
				 */
				control.find( '.margin-slider' ).each( function(e){

					var marginControl = $(this);
					var marginSlider  = marginControl.find( '.tt-slider' );
					var marginAmount  = marginControl.find( '.tt-font-slider-amount' );
					var marginUnit    = marginControl.find( '.tt-font-slider-unit' );
					var marginDisplay = marginControl.find( '.tt-font-slider-display span' );
					var marginValue   = marginAmount.val();
					var marginMin     = marginSlider.data( 'min-range' ) ? marginSlider.data( 'min-range' ) : 0;
					var marginMax     = marginSlider.data( 'max-range' ) ? marginSlider.data( 'max-range' ) : 300;
					var marginStep    = marginSlider.data( 'step' )      ? marginSlider.data( 'step' )      : 1;

					if ( marginControl.hasClass( 'margin-top-slider' ) ) {
						var marginDefaultUnit  = fontControl.default_values.margin_top.unit.toString();

					} else if ( marginControl.hasClass( 'margin-bottom-slider' ) ) {
						var marginDefaultUnit  = fontControl.default_values.margin_bottom.unit.toString();

					} else if ( marginControl.hasClass( 'margin-left-slider' ) ) {
						var marginDefaultUnit  = fontControl.default_values.margin_left.unit.toString();

					} else if ( marginControl.hasClass( 'margin-right-slider' ) ) {
						var marginDefaultUnit  = fontControl.default_values.margin_right.unit.toString();

					}

					if ( '' === marginAmount.val() ) {
						marginValue = marginAmount.data( 'default-value' );
					}

					marginDisplay.text( marginValue + marginDefaultUnit );

					marginSlider.slider({
						min   : marginMin,
						max   : marginMax,
						value : marginValue,
						slide : function( event, ui ) {
							marginDisplay.text( ui.value + marginDefaultUnit );
							marginAmount.val( ui.value ).trigger('change');
							marginUnit.val( marginDefaultUnit );
						},
						step  : marginStep 
					});
				});

				/**
				 * Initialise Padding Slider
				 * 
				 * Sets up the margin control using the
				 * jQuery UI Slider plugin.
				 * 
				 */
				control.find( '.padding-slider' ).each( function(e){

					var paddingControl = $(this);
					var paddingSlider  = paddingControl.find( '.tt-slider' );
					var paddingAmount  = paddingControl.find( '.tt-font-slider-amount' );
					var paddingUnit    = paddingControl.find( '.tt-font-slider-unit' );
					var paddingDisplay = paddingControl.find( '.tt-font-slider-display span' );
					var paddingValue   = paddingAmount.val();
					var paddingMin     = paddingSlider.data( 'min-range' ) ? paddingSlider.data( 'min-range' ) : 0;
					var paddingMax     = paddingSlider.data( 'max-range' ) ? paddingSlider.data( 'max-range' ) : 300;
					var paddingStep    = paddingSlider.data( 'step' )      ? paddingSlider.data( 'step' )      : 1;

					if ( paddingControl.hasClass( 'padding-top-slider' ) ) {
						var paddingDefaultUnit  = fontControl.default_values.padding_top.unit.toString();

					} else if ( paddingControl.hasClass( 'padding-bottom-slider' ) ) {
						var paddingDefaultUnit  = fontControl.default_values.padding_bottom.unit.toString();

					} else if ( paddingControl.hasClass( 'padding-left-slider' ) ) {
						var paddingDefaultUnit  = fontControl.default_values.padding_left.unit.toString();

					} else if ( paddingControl.hasClass( 'padding-right-slider' ) ) {
						var paddingDefaultUnit  = fontControl.default_values.padding_right.unit.toString();

					}

					if ( '' === paddingAmount.val() ) {
						paddingValue = paddingAmount.data( 'default-value' );
					}

					paddingDisplay.text( paddingValue + paddingDefaultUnit );

					paddingSlider.slider({
						min   : paddingMin,
						max   : paddingMax,
						value : paddingValue,
						slide : function( event, ui ) {
							paddingDisplay.text( ui.value + paddingDefaultUnit );
							paddingAmount.val( ui.value ).trigger('change');
							paddingUnit.val( paddingDefaultUnit );
						},
						step  : paddingStep 
					});
				});

			}
		};

		/**
		 * Reset All Font Sliders in Control
		 * 
		 * @desc - Resets all slider controls of the
		 *     control with the data id passed in the
		 *     parameter back to their default values.
		 *
		 * @param {string} setting - The control setting id
		 * @return void
		 *
		 * @since 1.0
		 * @version 1.1.1
		 */
		option.resetFontSliders = function( setting ) {
			var control     = $( '[data-font-control-id="' + setting + '"]' );
			var fontControl = option.getFontControl( setting );

			if ( ! $.isEmptyObject( fontControl ) )	{
				
				/**
				 * Reset Font Size Slider
				 * 
				 * Resets the font size control back to
				 * it's default values.
				 * 
				 */
				var fontSize       = control.find( '.font-size-slider' );
				var fontSizeReset  = fontSize.find( '.tt-font-slider-reset' );
				var fontSizeSlider = fontSize.find( '.tt-slider' );
				var fsDisplay      = fontSize.find( '.tt-font-slider-display span' );
				var fsAmount       = fontSize.find( '.tt-font-slider-amount' );
				var fsUnit         = fontSize.find( '.tt-font-slider-unit' );
				var fsDefaultUnit  = fontControl.default_values.font_size.unit.toString();

				fontSizeReset.on('click', function(e){
					e.preventDefault();
					var defaultValue = fontSizeSlider.data( 'default-value' );
					fontSizeSlider.slider({ value : defaultValue });
					fsDisplay.text( defaultValue + fsDefaultUnit );
					fsAmount.val( defaultValue ).trigger( 'change' );
					return false;
				});

				/**
				 * Reset Line Height Slider
				 * 
				 * Resets the line height control back to
				 * it's default values.
				 * 
				 */
				var lineHeight       = $(this).find( '.line-height-slider' );
				var lineHeightReset  = lineHeight.find( '.tt-font-slider-reset' );
				var lineHeightSlider = lineHeight.find( '.tt-slider' );
				var lhAmount         = lineHeight.find( '.tt-font-slider-amount' );
				var lhDisplay        = lineHeight.find( '.tt-font-slider-display span' );

				lineHeightReset.on('click', function(e){
					e.preventDefault();
					var defaultValue = lineHeightSlider.data( 'default-value' );
					lineHeightSlider.slider({ value : defaultValue });
					lhDisplay.text( defaultValue );
					lhAmount.val( defaultValue ).trigger( 'change' );
					return false;
				});

				/**
				 * Reset Letter Spacing Slider
				 * 
				 * Resets the letter spacing control back to
				 * it's default values.
				 * 
				 */
				var letterSpacing       = $(this).find( '.letter-spacing-slider' );
				var letterSpacingReset  = letterSpacing.find( '.tt-font-slider-reset' );
				var letterSpacingSlider = letterSpacing.find( '.tt-slider' );
				var lsAmount            = letterSpacing.find( '.tt-font-slider-amount' );
				var lsUnit              = letterSpacing.find( '.tt-font-slider-unit' );
				var lsDefaultUnit       = fontControl.default_values.letter_spacing.unit.toString();
				var lsDisplay           = letterSpacing.find( '.tt-font-slider-display span' );

				letterSpacingReset.on('click', function(e){
					e.preventDefault();
					var defaultValue = letterSpacingSlider.data( 'default-value' );
					letterSpacingSlider.slider({ value : defaultValue });
					lsDisplay.text( defaultValue + lsDefaultUnit );
					lsAmount.val( defaultValue ).trigger( 'change' );
					return false;
				});
				
				/**
				 * Margin Sliders
				 * 
				 * Resets the margin controls back to
				 * it's default values.
				 * 
				 */
				control.find( '.margin-slider' ).each( function(e){
					var marginControl = $(this);
					var marginReset   = marginControl.find( '.tt-font-slider-reset' );
					var marginSlider  = marginControl.find( '.tt-slider' );
					var marginDisplay = marginControl.find( '.tt-font-slider-display span' );
					var marginAmount  = marginControl.find( '.tt-font-slider-amount' );
					var marginUnit    = marginControl.find( '.tt-font-slider-unit' );

					if ( marginControl.hasClass( 'margin-top-slider' ) ) {
						var marginDefaultUnit  = fontControl.default_values.margin_top.unit.toString();

					} else if ( marginControl.hasClass( 'margin-bottom-slider' ) ) {
						var marginDefaultUnit  = fontControl.default_values.margin_bottom.unit.toString();

					} else if ( marginControl.hasClass( 'margin-left-slider' ) ) {
						var marginDefaultUnit  = fontControl.default_values.margin_left.unit.toString();

					} else if ( marginControl.hasClass( 'margin-right-slider' ) ) {
						var marginDefaultUnit  = fontControl.default_values.margin_right.unit.toString();

					}

					// Reset Event
					marginReset.on('click', function(e){
						e.preventDefault();
						var defaultValue = marginSlider.data( 'default-value' );
						marginSlider.slider({ value : defaultValue });
						marginDisplay.text( defaultValue + marginDefaultUnit );
						marginAmount.val( defaultValue ).trigger( 'change' );
						return false;
					});					
				});

				/**
				 * Padding Sliders
				 * 
				 * Resets the margin controls back to
				 * it's default values.
				 * 
				 */
				control.find( '.padding-slider' ).each( function(e){
					var paddingControl = $(this);
					var paddingReset   = paddingControl.find( '.tt-font-slider-reset' );
					var paddingSlider  = paddingControl.find( '.tt-slider' );
					var paddingDisplay = paddingControl.find( '.tt-font-slider-display span' );
					var paddingAmount  = paddingControl.find( '.tt-font-slider-amount' );
					var paddingUnit    = paddingControl.find( '.tt-font-slider-unit' );

					if ( paddingControl.hasClass( 'padding-top-slider' ) ) {
						var paddingDefaultUnit  = fontControl.default_values.padding_top.unit.toString();

					} else if ( paddingControl.hasClass( 'padding-bottom-slider' ) ) {
						var paddingDefaultUnit  = fontControl.default_values.padding_bottom.unit.toString();

					} else if ( paddingControl.hasClass( 'padding-left-slider' ) ) {
						var paddingDefaultUnit  = fontControl.default_values.padding_left.unit.toString();

					} else if ( paddingControl.hasClass( 'padding-right-slider' ) ) {
						var paddingDefaultUnit  = fontControl.default_values.padding_right.unit.toString();

					}

					// Reset Event
					paddingReset.on('click', function(e){
						e.preventDefault();
						var defaultValue = paddingSlider.data( 'default-value' );
						paddingSlider.slider({ value : defaultValue });
						paddingDisplay.text( defaultValue + paddingDefaultUnit );
						paddingAmount.val( defaultValue ).trigger( 'change' );
						return false;
					});					
				});

			}
		};

		/**
		 * Reset All Font Properties in Font Styles Tab
		 * 
		 * @desc - Resets all of the dropdown controls in
		 *     the Font Styles tab back to their defaults.
		 * 
		 * @return void
		 *
		 * @since 1.0
		 * @version 1.1.1
		 */		
		option.resetFontStyles = function( setting ) {
			var control     = $( '[data-font-control-id="' + setting + '"]' );
			var fontControl = option.getFontControl( setting );

			if ( ! $.isEmptyObject( fontControl ) ) {

				var fontFamily     = control.find( '.tt-font-family' );
				var fontWeight     = control.find( '.tt-font-weight' );
				var stylesheetUrl  = control.find( '.tt-font-stylesheet-url' );
				var fontWeightVal  = control.find( '.tt-font-weight-val' );
				var fontStyleVal   = control.find( '.tt-font-style-val' );
				var fontNameVal    = control.find( '.tt-font-name-val' );
				var textDecoration = control.find( '.tt-text-decoration' );
				var textTransform  = control.find( '.tt-text-transform' );

				// Reset select options
				fontFamily.val( fontControl.default_values.font_id ).trigger( 'change' );
				fontWeight.val( fontControl.default_values.font_weight_style ).trigger( 'change' );
				textDecoration.val( fontControl.default_values.text_decoration ).trigger( 'change' );
				textTransform.val( fontControl.default_values.text_transform ).trigger( 'change' );
 
				// Reset hidden inputs
				stylesheetUrl.val( fontControl.default_values.stylesheet_url ).trigger( 'change' );
				fontWeightVal.val( fontControl.default_values.font_weight ).trigger( 'change' );
				fontStyleVal.val( fontControl.default_values.font_style ).trigger( 'change' );
				fontNameVal.val( fontControl.default_values.font_name ).trigger( 'change' );

			}
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
	$(this).ttFontControls();
});