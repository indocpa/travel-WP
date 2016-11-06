<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

add_action( 'init', 'TpsShortCodes::init' );
add_action( 'init', 'TpsShortCodes::add_button' );

class TpsShortCodes {
	public static function init() {
		add_shortcode( 'tps_header', 'TpsShortCodes::shortcode_header_handler' );
		add_shortcode( 'tps_title', 'TpsShortCodes::shortcode_title_handler' );
		add_shortcode( 'tps_footer', 'TpsShortCodes::shortcode_footer_handler' );
	}

	public static function shortcode_header_handler( $atts, $content = null ) {
		global $post;

		if ( property_exists( $post, 'theiaPostSlider' ) ) {
			return TpsMisc::$begin_header_short_code . $content . TpsMisc::$end_header_short_code;
		}

		return $content;
	}

	public static function shortcode_title_handler( $atts, $content = null ) {
		global $post;

		if ( property_exists( $post, 'theiaPostSlider' ) ) {
			return TpsMisc::$begin_title_short_code . $content . TpsMisc::$end_title_short_code;
		}

		return $content;
	}

	public static function shortcode_footer_handler( $atts, $content = null ) {
		global $post;

		if ( property_exists( $post, 'theiaPostSlider' ) ) {
			return TpsMisc::$begin_footer_short_code . $content . TpsMisc::$end_footer_short_code;
		}

		return $content;
	}

	// Add buttons to the post editor
	public static function add_button() {
		if ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_external_plugins', 'TpsShortCodes::add_plugin' );
			add_filter( 'mce_buttons', 'TpsShortCodes::register_button' );
		}
	}

	public static function add_plugin( $plugin_array ) {
		$plugin_array['theiaPostSlider'] = TPS_PLUGINS_URL . 'js/tps-tinymce-customcodes.js';

		return $plugin_array;
	}

	public static function register_button( $buttons ) {
		array_push( $buttons, 'separator', 'tps_header', 'tps_title', 'tps_footer' );

		return $buttons;
	}

	// Extract a shortcode from a string.
	public static function extract_short_code( &$content, $beginShortCode, $endShortCode ) {
		// Find the opening tag
		$begin = TpsMisc::mb_strpos( $content, $beginShortCode );
		if ( $begin === false ) {
			return null;
		}

		// Find the closing tag
		$end = TpsMisc::mb_strpos( $content, $endShortCode, $begin );
		if ( $end === false ) {
			return null;
		}

		// Cache some string lengths
		$lenBegin = TpsMisc::mb_strlen( $beginShortCode );
		$lenEnd   = TpsMisc::mb_strlen( $endShortCode );

		// If the shortcodes are surrounded by header tags, then extract them too.
		$beginHeadingTag = $endHeadingTag = '';
		if (
			preg_match( '(<h([1-6])>)', TpsMisc::mb_substr( $content, $begin - 4, 4 ), $beginMatches ) &&
			preg_match( '(</h([1-6])>)', TpsMisc::mb_substr( $content, $end + $lenEnd, 5 ), $endMatches ) &&
			$beginMatches[1] === $endMatches[1]
		) {
			$beginHeadingTag = $beginMatches[0];
			$endHeadingTag   = $endMatches[0];
		}

		// Extract shortcode content.
		$shortCode = $beginHeadingTag . trim( TpsMisc::mb_substr( $content, $begin + $lenBegin, $end - $begin - $lenBegin ) ) . $endHeadingTag;

		// Parse newlinews.
		$shortCode = wpautop( $shortCode );

		// Get resulting post content by removing the shortcode and its content.
		{
			$split1 = $begin - TpsMisc::mb_strlen( $beginHeadingTag );
			$split2 = $end + $lenEnd + TpsMisc::mb_strlen( $endHeadingTag );

			// Remove trailing paragraph tags.
			if (
				TpsMisc::mb_substr( $content, $split1 - 3, 3 ) == '<p>' &&
				TpsMisc::mb_substr( $content, $split2, 4 ) == '</p>'
			) {
				$split1 -= 4;
				$split2 += 4;
			}

			$content = trim( TpsMisc::mb_substr( $content, 0, $split1 ) ) . trim( TpsMisc::mb_substr( $content, $split2 ) );
		}

		return $shortCode;
	}

	public static function tps_header( $atts, $content = null ) {
		return $content;
	}

	public static function tps_footer( $atts, $content = null ) {
		return $content;
	}

	public static function tps_title( $atts, $content = null ) {
		return $content;
	}
}
