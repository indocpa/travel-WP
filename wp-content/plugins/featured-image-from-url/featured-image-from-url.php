<?php

/*
 * Plugin Name: Featured Image From URL
 * Description: Allows to use an external image as Featured Image of your post, page or Custom Post Type, such as WooCommerce Product (supports Product Gallery also).
 * Version: 1.4.5
 * Author: Marcel Jacques Machado 
 * Author URI: http://marceljm.com/wordpress/featured-image-from-url-premium/ 
 */

define('FIFU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FIFU_INCLUDES_DIR', FIFU_PLUGIN_DIR . '/includes');
define('FIFU_ADMIN_DIR', FIFU_PLUGIN_DIR . '/admin');

require_once( FIFU_INCLUDES_DIR . '/thumbnail.php' );
require_once( FIFU_INCLUDES_DIR . '/thumbnail-category.php' );

if (is_admin()) {
    require_once( FIFU_ADMIN_DIR . '/meta-box.php' );
    require_once( FIFU_ADMIN_DIR . '/menu.php' );
    require_once( FIFU_ADMIN_DIR . '/column.php' );
    require_once( FIFU_ADMIN_DIR . '/category.php' );
}

register_deactivation_hook(__FILE__, 'fifu_desactivate');

function fifu_desactivate() {
    update_option('fifu_woocommerce', 'toggleoff');
    update_option('fifu_hope', 'toggleoff');
    shell_exec('sh ../wp-content/plugins/featured-image-from-url/scripts/disableWoocommerce.sh');
    fifu_disable_nonstandard_compatibility();
}

