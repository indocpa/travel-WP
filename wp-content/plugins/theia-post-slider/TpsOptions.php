<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

add_action( 'init', 'TpsOptions::init' );

class TpsOptions {
	// Get all available transition effects.
	public static function get_transition_effects() {
		$options = array(
			'none'   => 'None',
			'simple' => 'Simple',
			'slide'  => 'Slide',
			'fade'   => 'Fade'
		);
		return $options;
	}

	// Get button horizontal positions.
	public static function get_button_horizontal_positions() {
		$options = array(
			'left'        => 'Left',
			'center'      => 'Center',
			'center_full' => 'Center, Full',
			'right'       => 'Right'
		);

		return $options;
	}

	// Get button vertical positions.
	public static function get_button_vertical_positions() {
		$options = array(
			'top_and_bottom' => 'Top and bottom',
			'top'            => 'Top',
			'bottom'         => 'Bottom',
			'none'           => 'None'
		);

		return $options;
	}

	// Get all available themes.
	public static function get_themes() {
		$themes = array();

		// Special files to ignore
		$ignore = array( 'admin.css' );

		// Get themes corresponding to .css files.
		$dir = dirname( __FILE__ ) . '/css';
		if ( $handle = opendir( $dir ) ) {
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				if ( in_array( $entry, $ignore ) ) {
					continue;
				}

				$file = $dir . '/' . $entry;
				if ( ! is_file( $file ) ) {
					continue;
				}

				// Beautify name
				$name = substr( $entry, 0, - 4 ); // Remove ".css"
				$name = str_replace( '--', ', ', $name );
				$name = str_replace( '-', ' ', $name );
				$name = ucwords( $name );

				// Add theme
				$themes[ $entry ] = $name;
			}
			closedir( $handle );
		}

		$themes['none'] = 'None';

		// Sort alphabetically
		asort( $themes );

		return $themes;
	}

	/**
	 * @param $direction 'left' or 'right'
	 *
	 * @return string
	 */
	public static function get_font_icon( $direction ) {
		$class = $direction == 'left' ? TpsOptions::get( 'theme_font_leftClass' ) : TpsOptions::get( 'theme_font_rightClass' );

		return '<span aria-hidden="true" class="' . $class . '"></span>';
	}

	public static function get( $optionId, $optionGroups = array( 'tps_general', 'tps_nav', 'tps_advanced' ) ) {
		if ( ! is_array( $optionGroups ) ) {
			$optionGroups = array( $optionGroups );
		}

		foreach ( $optionGroups as $groupId ) {
			$options = get_option( $groupId );

			if ( ! is_array( $options ) ) {
				continue;
			}

			if ( array_key_exists( $optionId, $options ) ) {
				return $options[ $optionId ];
			}
		}

		return null;
	}

	// Initialize options.
	public static function init() {
		$defaults = array(
			'tps_general'  => array(
				'transition_effect'     => 'slide',
				'transition_speed'      => 400,
				'theme_type'            => 'font',
				'theme'                 => 'buttons-orange.css',
				'theme_font_name'       => 'chevron-circle',
				'theme_font_leftClass'  => 'tps-icon-chevron-circle-left',
				'theme_font_rightClass' => 'tps-icon-chevron-circle-right',
				'theme_font_color'      => '#f08100',
				'theme_font_size'       => 48
			),
			'tps_nav'      => array(
				'navigation_text'               => '%{currentSlide} of %{totalSlides}',
				'helper_text'                   => 'Use your &leftarrow; &rightarrow; (arrow) keys to browse',
				'prev_text'                     => 'Prev',
				'next_text'                     => 'Next',
				'button_width'                  => 0,
				'prev_text_post'                => 'Prev post',
				'next_text_post'                => 'Next post',
				'button_width_post'             => 0,
				'button_behaviour'              => 'standard',
				'post_navigation_inverse'       => false,
				'post_navigation_same_category' => false,
				'nav_horizontal_position'       => 'right',
				'nav_vertical_position'         => 'top_and_bottom',
				'disable_keyboard_shortcuts'    => false,
				'scroll_after_refresh'          => true,
                'scroll_top_offset'             => 0,
			),
			'tps_advanced' => array(
				'default_activation_behavior'         => 1,
				'post_types'                          => array( 'post', 'page' ),
				'slide_loading_mechanism'             => 'ajax',
				'refresh_ads'                         => false,
				'ad_refreshing_mechanism'             => 'javascript',
				'refresh_ads_every_n_slides'          => 1,
				'do_not_check_for_multiple_instances' => true,
				'do_not_cache_rendered_html'          => true,
				'enable_touch_gestures'               => false
			),
			'tps_about'    => array()
		);
		$defaults = apply_filters( 'tps_init_options_defaults', $defaults );

		// Reset settings.
		$about_options = get_option( 'tps_about' );
		if ( is_array( $about_options ) ) {
			// Reset global settings to default.
			$reset = array_key_exists( 'reset_global_settings_to_default', $about_options ) && $about_options['reset_global_settings_to_default'];
			if ( $reset ) {
				foreach ( $defaults as $groupId => $groupValues ) {
					delete_option( $groupId );
				}
			}

			// Reset post settings to default.
			$reset = array_key_exists( 'reset_all_post_settings_to_default', $about_options ) && $about_options['reset_all_post_settings_to_default'];
			if ( $reset ) {
				$query = new WP_Query( 'posts_per_page=-1' );
				while ( $query->have_posts() ) {
					$query->the_post();
					delete_post_meta( get_the_ID(), 'tps_options' );
				}
			}
		}

		// Transfer legacy options, if applicable.
		{
			$overwrites             = array(
				'tps_general'  => array(),
				'tps_nav'      => array(),
				'tps_advanced' => array(),
				'tps_about'    => array()
			);
			$refreshPageOnEachSlide = false;

			$options = get_option( 'tps_general' );
			// New versions have "font" as the default button theme type. Older versions that have a classic theme chosen, will have the "classic" option chosen by default.
			if ( is_array( $options ) && array_key_exists( 'theme', $options ) && ! array_key_exists( 'theme_type', $options ) ) {
				$overwrites['tps_general']['theme_type'] = 'classic';
			}

			$options = get_option( 'tps_nav' );
			if ( is_array( $options ) ) {
				if ( array_key_exists( 'refresh_page_on_slide', $options ) && $options['refresh_page_on_slide'] == true ) {
					$refreshPageOnEachSlide = true;
				}
				if ( array_key_exists( 'enable_on_pages', $options ) && $options['enable_on_pages'] == true ) {
					$overwrites['tps_advanced']['post_types'] = array( 'post', 'page' );
				}
				if ( array_key_exists( 'post_navigation', $options ) && $options['post_navigation'] == true ) {
					$overwrites['tps_nav']['button_behaviour'] = 'post';
				}
			}

			$options = get_option( 'tps_advanced' );
			if ( is_array( $options ) && array_key_exists( 'slide_loading_mechanism', $options ) && $options['slide_loading_mechanism'] == 'refresh' ) {
				$refreshPageOnEachSlide = true;
			}

			if ( $refreshPageOnEachSlide ) {
				$overwrites['tps_advanced']['slide_loading_mechanism'] = 'ajax';
				$overwrites['tps_advanced']['refresh_ads']             = true;
				$overwrites['tps_advanced']['ad_refreshing_mechanism'] = 'page';
			}
		}

		// Transfer multiple selects.
		$postTypes = get_option( 'tps_advanced_post_types' );
		if ( $postTypes !== false ) {
			$overwrites['tps_advanced']['post_types'] = $postTypes;
			delete_option( 'tps_advanced_post_types' );
		}

		// Sanitize, validate.
		foreach ( $defaults as $groupId => $groupValues ) {
			$options = get_option( $groupId );

			if ( ! is_array( $options ) ) {
				$options = array();
				$changed = true;
			} else {
				$changed = false;
			}

			// Add missing options.
			foreach ( $groupValues as $key => $value ) {
				if ( isset( $options[ $key ] ) == false ) {
					$changed         = true;
					$options[ $key ] = $value;
				}
			}

			// Remove surplus options.
			foreach ( $options as $key => $value ) {
				if ( isset( $defaults[ $groupId ][ $key ] ) == false ) {
					$changed = true;
					unset( $options[ $key ] );
				}
			}

			// Overwrite options.
			if ( array_key_exists( $groupId, $overwrites ) ) {
				foreach ( $overwrites[ $groupId ] as $overwriteKey => $overwriteValue ) {
					$options[ $overwriteKey ] = $overwriteValue;
					$changed                  = true;
				}
			}

			// Sanitize options.
			foreach ( $options as $key => $value ) {
				if ( is_bool( $defaults[ $groupId ][ $key ] ) ) {
					$options[ $key ] = ( $options[ $key ] === true || $options[ $key ] === 'true' ) ? true : false;
					$changed         = true;
				}

				if ( is_array( $defaults[ $groupId ][ $key ] ) ) {
					$options[ $key ] = is_array( $options[ $key ] ) ? $options[ $key ] : $defaults[ $groupId ][ $key ];
					$changed         = true;
				}
			}

			// Validate options.
			if ( $groupId == 'tps_general' ) {
				if ( array_key_exists( $options['transition_effect'], TpsOptions::get_transition_effects() ) == false ) {
					$options['transition_effect'] = $groupValues['transition_effect'];
					$changed                      = true;
				}

				if ( $options['transition_speed'] < 0 ) {
					$options['transition_speed'] = $groupValues['transition_speed'];
					$changed                     = true;
				}
			}

			if ( $groupId == 'tps_nav' ) {
				if ( $options['button_width'] < 0 ) {
					$options['button_width'] = $groupValues['button_width'];
					$changed                 = true;
				}
			}

			if ( $groupId == 'tps_advanced' ) {
				if ( $options['refresh_ads_every_n_slides'] < 1 ) {
					$options['refresh_ads_every_n_slides'] = 1;
					$changed                               = true;
				}
			}

			// Save options.
			if ( $changed ) {
				update_option( $groupId, $options );
			}
		}
	}

	// Get font icons.
	public static function get_font_icons() {
		// Get icon classes from CSS file.
		$iconsCss = file_get_contents( dirname( __FILE__ ) . '/fonts/style.css' );
		preg_match_all( '/.tps-icon-([^{]*):before {(.*?)content: "\\\\([a-zA-Z0-9]+)"(.*?)}/s', $iconsCss, $matches );

		// Group left and right icons.
		$icons = array();
		foreach ( $matches[0] as $key => $value ) {
			$class = $matches[1][ $key ];
			$name  = str_replace( array( '-left', '-right' ), '', $class );
			$class = 'tps-icon-' . $class;
			$code  = $matches[3][ $key ];

			if ( ! array_key_exists( $name, $icons ) ) {
				$icons[ $name ] = array();
			}

			if ( strpos( $class, 'left' ) !== false ) {
				$icons[ $name ]['leftCode']  = $code;
				$icons[ $name ]['leftClass'] = $class;
			} else {
				$icons[ $name ]['rightCode']  = $code;
				$icons[ $name ]['rightClass'] = $class;
			}
		}
		ksort( $icons );

		return $icons;
	}
}
