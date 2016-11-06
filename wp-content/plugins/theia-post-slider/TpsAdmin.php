<?php
/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

add_action( 'admin_init', 'TpsAdmin::admin_init' );
add_action( 'admin_menu', 'TpsAdmin::admin_menu' );

class TpsAdmin {
	public static function admin_init() {
		register_setting( 'tps_options_general', 'tps_general', 'TpsAdmin::validate' );
		register_setting( 'tps_options_nav', 'tps_nav', 'TpsAdmin::validate' );
		register_setting( 'tps_options_advanced', 'tps_advanced', 'TpsAdmin::validate' );
		register_setting( 'tps_options_advanced', 'tps_advanced_post_types', 'TpsAdmin::validate' );
		register_setting( 'tps_options_about', 'tps_about', 'TpsAdmin::validate' );
	}

	public static function admin_menu() {
		if ( TPS_USE_AS_STANDALONE ) {
			add_options_page( 'Theia Post Slider Settings', 'Theia Post Slider', 'manage_options', 'tps', 'TpsAdmin::do_page' );
		}
	}

	public static function do_page() {
		$tabs = array(
			'dashboard'     => array(
				'title' => __( "Dashboard", 'theia-post-slider' ),
				'file'  => dirname( __FILE__ ) . '/TpsAdmin_Dashboard.php',
				'class' => 'TpsAdmin_Dashboard'
			),
			'general'       => array(
				'title' => __( "General", 'theia-post-slider' ),
				'file'  => dirname( __FILE__ ) . '/TpsAdmin_General.php',
				'class' => 'TpsAdmin_General'
			),
			'navigationBar' => array(
				'title' => __( "Navigation Bar", 'theia-post-slider' ),
				'file'  => dirname( __FILE__ ) . '/TpsAdmin_NavigationBar.php',
				'class' => 'TpsAdmin_NavigationBar'
			),
			'advanced'      => array(
				'title' => __( "Advanced", 'theia-post-slider' ),
				'file'  => dirname( __FILE__ ) . '/TpsAdmin_Advanced.php',
				'class' => 'TpsAdmin_Advanced'
			),
			'about'         => array(
				'title' => __( "About", 'theia-post-slider' ),
				'file'  => dirname( __FILE__ ) . '/TpsAdmin_About.php',
				'class' => 'TpsAdmin_About'
			)
		);
		$tabs = apply_filters( 'tps_admin_tabs', $tabs );

		if ( array_key_exists( 'tab', $_GET ) && array_key_exists( $_GET['tab'], $tabs ) ) {
			$current_tab = $_GET['tab'];
		} else {
			$current_tab = 'dashboard';
		}
		?>

		<div class="wrap">
			<?php
			if ($current_tab != 'about') {
				?>
				<a class="theiaPostSlider_adminLogo"
				   href="http://wecodepixels.com/?utm_source=theia-post-slider-for-wordpress"
				   target="_blank"><img src="<?php echo plugins_url( '/images/wecodepixels-logo.png', __FILE__ ); ?>"></a>
				<?php
			}
			?>

			<h2 class="theiaPostSlider_adminTitle">
				<a href="http://wecodepixels.com/theia-post-slider-for-wordpress/?utm_source=theia-post-slider-for-wordpress"
				   target="_blank"><img src="<?php echo plugins_url( '/images/theia-post-slider-thumbnail.png', __FILE__ ); ?>"></a>
				Theia Post Slider
			</h2>

			<h2 class="nav-tab-wrapper">
				<?php
				foreach ( $tabs as $id => $tab ) {
					$class = 'nav-tab';
					if ( $id == $current_tab ) {
						$class .= ' nav-tab-active';
					}
					?>
					<a href="?page=tps&tab=<?php echo $id; ?>"
					   class="<?php echo $class; ?>"><?php echo $tab['title']; ?></a>
				<?php
				}
				?>
			</h2>

			<?php
			require( $tabs[ $current_tab ]['file'] );
			$class = $tabs[ $current_tab ]['class'];
			$page  = new $class;
			?>

			<div class="theiaPostSlider_adminContainer <?php echo $page->showPreview ? 'hasPreview' : ''; ?>">
				<div class="theiaPostSlider_adminContainer_left">
					<?php
					$page->echoPage();
					?>
				</div>

				<div class="theiaPostSlider_adminContainer_right">
					<?php
					if ( $page->showPreview == true ) {
						TpsMisc::echo_inline_styles( true );

						?>
						<h3><?php _e( "Live Preview", 'theia-post-slider' ); ?></h3>
						<div class="theiaPostSlider_adminPreview">
							<?php
							echo TpsNavigationBar::get_navigation_bar( array(
								'currentSlide' => 1,
								'totalSlides'  => 3,
								'id'           => 'tps_nav_upper',
								'class'        => '_upper',
								'style'        => in_array( TpsOptions::get( 'nav_vertical_position' ), array(
									'top_and_bottom',
									'top'
								) ) ? '' : 'display: none'
							) );
							?>
							<div id="tps_slideContainer" class="theiaPostSlider_slides">
								<?php include dirname( __FILE__ ) . '/preview-slider.php'; ?>
							</div>
							<?php
							echo TpsNavigationBar::get_navigation_bar( array(
								'currentSlide' => 1,
								'totalSlides'  => 3,
								'id'           => 'tps_nav_lower',
								'class'        => '_lower',
								'style'        => in_array( TpsOptions::get( 'nav_vertical_position' ), array(
									'top_and_bottom',
									'bottom'
								) ) ? '' : 'display: none'
							) );
							$sliderOptions = array(
								'slideContainer'    => '#tps_slideContainer',
								'nav'               => array( '#tps_nav_upper', '#tps_nav_lower' ),
								'navText'           => TpsOptions::get( 'navigation_text' ),
								'helperText'        => TpsOptions::get( 'helper_text' ),
								'transitionEffect'  => TpsOptions::get( 'transition_effect' ),
								'transitionSpeed'   => (int) TpsOptions::get( 'transition_speed' ),
								'keyboardShortcuts' => true,
								'themeType'         => TpsOptions::get( 'theme_type' ),
								'prevText'          => TpsOptions::get( 'prev_text' ),
								'nextText'          => TpsOptions::get( 'next_text' ),
								'prevFontIcon'      => TpsOptions::get_font_icon( 'left' ),
								'nextFontIcon'      => TpsOptions::get_font_icon( 'right' ),
								'buttonWidth'       => TpsOptions::get( 'button_width' ),
								'numberOfSlides'    => 3
							);
							?>
							<script type='text/javascript'>
								var slider, theme;
								jQuery(document).ready(function () {
									slider = new tps.createSlideshow(<?php echo json_encode($sliderOptions); ?>);
								});
							</script>
						</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	<?php
	}

	public static function validate( $input ) {
		return $input;
	}
}
