<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

class TpsColors {
	public static function get_variations( $hex_color ) {
		$color       = TpsColors::rgb_to_hsl( TpsColors::hex_to_rgb( $hex_color ) );
		$hover_color = $disabled_color = $color;

		if ( $color[2] >= 0.2 && $color[2] <= 0.8 ) {
			$disabled_color[1] = 0;
		} elseif ( $color[2] < 0.2 ) {
			// A very dark color.
			$disabled_color[1] = 0;;
			$disabled_color[2] += 0.4;
		} else {
			// A very bright color.
			$disabled_color[1] = 0;
			$disabled_color[2] -= 0.1;
		}
		if ( $color[2] <= 0.8 ) {
			$hover_color[2] += 0.1 + ( 0.1 * ( ( 0.8 - $hover_color[2] ) / 0.8 ) );
		} else {
			$hover_color[2] -= 0.1;
		}

		return array(
			'hover_color'    => $hover_color,
			'disabled_color' => $disabled_color
		);
	}

	public static function hex_to_rgb( $hex ) {
		list( $r, $g, $b ) = sscanf( $hex, "#%02x%02x%02x" );

		return array( $r, $g, $b );
	}

	public static function rgb_to_hex( $rgb ) {
		$r = $rgb[0];
		$g = $rgb[1];
		$b = $rgb[2];

		$r = dechex( $r );
		if ( strlen( $r ) < 2 ) {
			$r = '0' . $r;
		}

		$g = dechex( $g );
		if ( strlen( $g ) < 2 ) {
			$g = '0' . $g;
		}

		$b = dechex( $b );
		if ( strlen( $b ) < 2 ) {
			$b = '0' . $b;
		}

		return '#' . $r . $g . $b;
	}

	public static function rgb_to_hsl( $rgb ) {
		$r = $rgb[0];
		$g = $rgb[1];
		$b = $rgb[2];

		$r /= 255;
		$g /= 255;
		$b /= 255;

		$max = max( $r, $g, $b );
		$min = min( $r, $g, $b );

		$h = 0;
		$l = ( $max + $min ) / 2;
		$d = $max - $min;

		if ( $d == 0 ) {
			$h = $s = 0; // achromatic
		} else {
			$s = $d / ( 1 - abs( 2 * $l - 1 ) );

			switch ( $max ) {
				case $r:
					$h = 60 * fmod( ( ( $g - $b ) / $d ), 6 );
					if ( $b > $g ) {
						$h += 360;
					}
					break;

				case $g:
					$h = 60 * ( ( $b - $r ) / $d + 2 );
					break;

				case $b:
					$h = 60 * ( ( $r - $g ) / $d + 4 );
					break;
			}
		}

		return array( round( $h, 2 ), round( $s, 2 ), round( $l, 2 ) );
	}

	public static function hsl_to_rgb( $hsl ) {
		$h = $hsl[0];
		$s = $hsl[1];
		$l = $hsl[2];

		$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
		$x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
		$m = $l - ( $c / 2 );

		if ( $h < 60 ) {
			$r = $c;
			$g = $x;
			$b = 0;
		} else if ( $h < 120 ) {
			$r = $x;
			$g = $c;
			$b = 0;
		} else if ( $h < 180 ) {
			$r = 0;
			$g = $c;
			$b = $x;
		} else if ( $h < 240 ) {
			$r = 0;
			$g = $x;
			$b = $c;
		} else if ( $h < 300 ) {
			$r = $x;
			$g = 0;
			$b = $c;
		} else {
			$r = $c;
			$g = 0;
			$b = $x;
		}

		$r = ( $r + $m ) * 255;
		$g = ( $g + $m ) * 255;
		$b = ( $b + $m ) * 255;

		return array( floor( $r ), floor( $g ), floor( $b ) );
	}
}