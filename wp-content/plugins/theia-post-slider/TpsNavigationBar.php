<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

class TpsNavigationBar {
	// Get HTML for a navigation bar.
	public static function get_navigation_bar( $options ) {
		$defaults = array(
			'currentSlide' => null,
			'totalSlides'  => null,
			'prevPostId'   => null,
			'nextPostId'   => null,
			'prevPostUrl'  => null,
			'nextPostUrl'  => null,
			'id'           => null,
			'class'        => array(),
			'style'        => null,
			'title'        => null

		);
		$options  = array_merge( $defaults, $options );
		if ( ! is_array( $options['class'] ) ) {
			$options['class'] = array( $options['class'] );
		}

		if ($options['totalSlides'] == 1 && in_array(TpsOptions::get('button_behaviour', 'tps_nav'), array('standard', 'loop'))) {
			return '';
		}

		// Get text
		if ( $options['totalSlides'] == 1 ) {
			$text = '';
		} else {
			$text = TpsOptions::get( 'navigation_text' );
			$text = str_replace( '%{currentSlide}', $options['currentSlide'], $text );
			$text = str_replace( '%{totalSlides}', $options['totalSlides'], $text );
		}


		// Get button URLs
		$prev = TpsNavigationBar::get_navigation_barButton( $options, false );
		$next = TpsNavigationBar::get_navigation_barButton( $options, true );

		// Title
		if ( ! $options['title'] ) {
			$options['title'] = '<span class="_helper">' . TpsOptions::get( 'helper_text' ) . '</span>';
		}

		// Final HTML
		$class   = array( 'theiaPostSlider_nav' );
		$class[] = '_' . TpsOptions::get( 'nav_horizontal_position' );
		if ( TpsOptions::get( 'theme_type' ) == 'font' ) {
			$class[] = 'fontTheme';
		}
		foreach ( $options['class'] as $c ) {
			$class[] = $c;
		}

		$html =
			'<div' . ( $options['id'] !== null ? ' id="' . $options['id'] . '"' : '' ) . ( $options['style'] !== null ? ' style="' . $options['style'] . '"' : '' ) . ' class="' . implode( $class, ' ' ) . '">' .
			'<div class="_buttons">' . $prev . '<span class="_text">' . $text . '</span>' . $next . '</div>' .
			'<div class="_title">' . $options['title'] . '</div>' .
			'</div>';

		return $html;
	}

	/*
	 * Get a button for a navigation bar.
	 * @param direction boolean False = prev; True = next;
	 */
	public static function get_navigation_barButton( $options, $direction ) {
		global $page, $pages;

		$direction_name = $direction ? 'next' : 'prev';
		$url            = TpsMisc::get_post_page_url( $options['currentSlide'] + ( $direction ? 1 : - 1 ) );
		$left_text      = null;
		$right_text     = null;

		// Functionality: Last Slide's Next Goes to the First Slide and vice-versa.
		// Used the URLs directly compared to the previous version.
		if ( ! $url && TpsOptions::get( 'button_behaviour', 'tps_nav' ) == 'loop' ) {
			if ( $direction_name == 'next') {
				$url = TpsMisc::get_post_page_url( 1 );
			}
			else {
				$url = TpsMisc::get_post_page_url( count( $pages ) );
			}
		}

		// Check if there isn't another page but there is another post.
		$url_is_another_post = ! $url && $options[ $direction_name . 'PostUrl' ];
		if ( $url_is_another_post && TpsOptions::get( 'button_behaviour', 'tps_nav' ) == 'post' ) {
			$url = $options[ $direction_name . 'PostUrl' ];
		}

		// Check what text we should display on the buttons.
		if ( TpsOptions::get( 'button_behaviour' ) === 'post'  && ( ( $direction == false && $page == 1 ) || ( $direction == true && $page == count( $pages ) ) ) ) {
			$button_text = TpsOptions::get( $direction_name . '_text_post' );
		} else {
			$button_text = TpsOptions::get( $direction_name . '_text' );
		}

		switch ( TpsOptions::get( 'theme_type' ) ) {
			case 'font':
				$text  = $direction_name == 'next' ? TpsOptions::get_font_icon( 'right' ) : TpsOptions::get_font_icon( 'left' );
				$width = 0;

				if ( $direction_name == 'next' ) {
					$left_text = $button_text;
				} else {
					$right_text = $button_text;
				}
				break;

			case 'classic':
				$text       = $button_text;
				$left_text  = '';
				$right_text = '';

				if ( $url_is_another_post ) {
					$width = TpsOptions::get( 'button_width_post' );
				} else {
					$width = TpsOptions::get( 'button_width' );
				}
				break;

			default:
				return '';
		}

		$style = $width == 0 ? '' : 'style="width: ' . $width . 'px"';

		$html_part_1 = '<span class="_1">' . $left_text . '</span><span class="_2" ' . $style . '>';
		$html_part_2 = '</span><span class="_3">' . $right_text . '</span>';

		// HTML
		$html  = $html_part_1 . $text . $html_part_2;
		$class = $url_is_another_post ? ' _another_post' : '';
		if ( $url ) {
			$button = '<a href="' . esc_url($url) . '" class="_button _' . $direction_name . $class . '">' . $html . '</a>';
		} else {
			$button = '<span class="_button _' . $direction_name . $class . ' _disabled">' . $html . '</span>';
		}

		return $button;
	}
}