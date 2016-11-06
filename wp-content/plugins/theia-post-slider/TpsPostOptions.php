<?php

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

add_action( 'add_meta_boxes', 'TpsPostOptions::add_meta_boxes' );
add_action( 'save_post', 'TpsPostOptions::save_post' );

class TpsPostOptions {
	public static function add_meta_boxes() {
		add_meta_box(
			'tps_options', // id, used as the html id att
			__( 'Theia Post Slider' ), // meta box title
			'TpsPostOptions::add_meta_boxes_callback', // callback function, spits out the content
			null, // post type or page. This adds to posts only
			'side', // context, where on the screen
			'low' // riority, where should this go in the context
		);
	}

	public static function add_meta_boxes_callback( $post ) {
		$options = self::get_post_options( $post->ID );

		?>
		<p>
			<label for="tps_options_enabled">Enable slider:</label>
			<select id="tps_options_enabled" name="tps_options[enabled]">
				<?php
				foreach ( TpsPostOptions::get_enabled_options() as $key => $value ) {
					$output = '<option value="' . $key . '"' . ( $key == $options['enabled'] ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
					echo $output;
				}
				?>
			</select>
		</p>
		<?php

		do_action( 'tps_add_meta_boxes_callback', $post, $options );
	}

	public static function save_post( $postId ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_page', $postId ) ) {
			return;
		}
		if ( empty( $postId ) ) {
			return;
		}

		$defaults = self::get_post_defaults();
		$options  = array_key_exists( 'tps_options', $_REQUEST ) ? $_REQUEST['tps_options'] : array();
		foreach ( $options as $optionKey => $option ) {
			if ( ! array_key_exists( $optionKey, $defaults ) ) {
				unset( $options[ $optionKey ] );
			} else {
				// Sanitize.
				if ( is_bool( $defaults[ $optionKey ] ) ) {
					$options[ $optionKey ] = ( $options[ $optionKey ] === true || $options[ $optionKey ] === 'true' ) ? true : false;
				}
			}
		}
		$options = array_merge( $defaults, $options );

		update_post_meta( $postId, 'tps_options', $options );
	}

	public static function get_post_defaults() {
		$defaults = array(
			'enabled' => 'global'
		);
		$defaults = apply_filters( 'tps_get_post_defaults', $defaults );

		return $defaults;
	}

	// Get post options.
	public static function get_post_options( $postId ) {

		$defaults = self::get_post_defaults();
		$options = get_post_meta( $postId, 'tps_options', true );
		if ( ! is_array( $options ) ) {
			$options = array();
		} else {
			if ( array_key_exists( 'enable', $options ) ) {
				if ( $options['enable'] === true ) {
					$options['enable'] = 'enabled';
				} elseif ( $options['enable'] === false ) {
					$options['enable'] = 'disabled';
				}
			}
		}
		$options = array_merge( $defaults, $options );
		return $options;
	}

	public static function get_enabled_options() {
		$options = array(
			'global'   => 'Use global setting',
			'enabled'  => 'Enabled',
			'disabled' => 'Disabled'
		);

		return $options;
	}

	public static function get_post_option_enabled( $postId ) {
		$postOptions = TpsPostOptions::get_post_options( $postId );
		if ( $postOptions['enabled'] == 'global' ) {
			return TpsOptions::get( 'default_activation_behavior' ) == 1;
		}

		return $postOptions['enabled'] == 'enabled';
	}
}
