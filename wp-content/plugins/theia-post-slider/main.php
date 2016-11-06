<?php
/*
Plugin Name: Theia Post Slider
Plugin URI: http://wecodepixels.com/theia-post-slider-for-wordpress/?utm_source=theia-post-slider-for-wordpress
Description: Display multi-page posts using a slider, as a slideshow.
Author: WeCodePixels
Author URI: http://wecodepixels.com/?utm_source=theia-post-slider-for-wordpress
Version: 1.9.0
Copyright: WeCodePixels
*/

/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

/*
 * Plugin version. Used to forcefully invalidate CSS and JavaScript caches by appending the version number to the
 * filename (e.g. "style.css?ver=TPS_VERSION").
 */
define( 'TPS_VERSION', '1.9.0' );

// Include other files.
include( dirname( __FILE__ ) . '/TpsMisc.php' );
include( dirname( __FILE__ ) . '/TpsNavigationBar.php' );
include( dirname( __FILE__ ) . '/TpsContent.php' );
include( dirname( __FILE__ ) . '/TpsColors.php' );
include( dirname( __FILE__ ) . '/TpsEnqueues.php' );
include( dirname( __FILE__ ) . '/TpsShortCodes.php' );
include( dirname( __FILE__ ) . '/TpsOptions.php' );
include( dirname( __FILE__ ) . '/TpsPostOptions.php' );
include( dirname( __FILE__ ) . '/TpsAjax.php' );
include( dirname( __FILE__ ) . '/TpsHelper.php' );
include( dirname( __FILE__ ) . '/TpsAdmin.php' );
