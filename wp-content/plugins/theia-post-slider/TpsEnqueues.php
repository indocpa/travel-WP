<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

add_action( 'wp_enqueue_scripts', 'TpsEnqueues::wp_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'TpsEnqueues::admin_enqueue_scripts' );

class TpsEnqueues {
	// Enqueue the required JavaScript for a given transition effect.
	public static function enqueue_transition( $transition ) {
		wp_register_script( 'theiaPostSlider-transition-' . $transition . '.js', TPS_PLUGINS_URL . 'js/tps-transition-' . $transition . '.js', array( 'jquery' ), TPS_VERSION );
		wp_enqueue_script( 'theiaPostSlider-transition-' . $transition . '.js' );
	}

	// Enqueue JavaScript and CSS.
	public static function wp_enqueue_scripts() {
		// Do not load unless necessary.
		if ( ! is_admin() && ! TpsMisc::is_compatible_post() ) {
			return;
		}

		// Theme.
		$theme = TpsOptions::get( 'theme_type' ) == 'font' ? 'font-theme.css' : TpsOptions::get( 'theme' );
		if ( $theme != 'none' ) {
			wp_register_style( 'theiaPostSlider', TPS_PLUGINS_URL . 'css/' . $theme, array(), TPS_VERSION );
			wp_enqueue_style( 'theiaPostSlider' );
		}

		// Font icons.
		if ( is_admin() || TpsOptions::get( 'theme_type' ) == 'font' ) {
			wp_register_style( 'theiaPostSlider-font', TPS_PLUGINS_URL . 'fonts/style.css', array(), TPS_VERSION );
			wp_enqueue_style( 'theiaPostSlider-font' );
		}

		if ( ! is_admin() ) {
			// history.js
			wp_register_script( 'history.js', TPS_PLUGINS_URL . 'js/balupton-history.js/jquery.history.js', array( 'jquery' ), '1.7.1' );
			wp_enqueue_script( 'history.js' );
		}

		// async.js
		wp_register_script( 'async.js', TPS_PLUGINS_URL . 'js/async.min.js', array(), '14.09.2014' );
		wp_enqueue_script( 'async.js' );

		// Hammer.js
		if ( TpsOptions::get( 'enable_touch_gestures', 'tps_advanced' ) ) {
			wp_register_script( 'hammer.js', TPS_PLUGINS_URL . 'js/hammer.min.js', array(), '2.0.4' );
			wp_enqueue_script( 'hammer.js' );
		}

		// The slider
		wp_register_script( 'theiaPostSlider.js', TPS_PLUGINS_URL . 'js/tps.js', array( 'jquery' ), TPS_VERSION );
		wp_enqueue_script( 'theiaPostSlider.js' );

		// The selected transition effect
		self::enqueue_transition( TpsOptions::get( 'transition_effect' ) );
	}

	// Enqueue JavaScript and CSS for the admin interface.
	public static function admin_enqueue_scripts( $hookSuffix ) {
		if ( $hookSuffix != 'settings_page_tps' ) {
			return;
		}

		self::wp_enqueue_scripts();

		// Enqueue all transition scripts for live preview.
		foreach ( TpsOptions::get_transition_effects() as $key => $value ) {
			self::enqueue_transition( $key );
		}

		// CSS, even if there is no theme, so we can change the path via JS.
		if ( TpsOptions::get( 'theme' ) == 'none' ) {
			wp_register_style( 'theiaPostSlider', TPS_PLUGINS_URL . 'css/' . TpsOptions::get( 'theme' ), TPS_VERSION );
			wp_enqueue_style( 'theiaPostSlider' );
		}

		// Admin CSS
		wp_register_style( 'theiaPostSlider-admin', TPS_PLUGINS_URL . 'css/admin.css', array(), TPS_VERSION );
		wp_enqueue_style( 'theiaPostSlider-admin' );

		// Color picker
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}
}
