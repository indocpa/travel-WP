<?php

add_action('admin_menu', 'fifu_insert_menu');

function fifu_insert_menu() {
	add_menu_page(
			'Featured Image From URL', 
			'Featured Image From URL', 
			'administrator', 
			'featured-image-from-url', 
			'fifu_get_menu_html', 
			plugins_url() . '/featured-image-from-url/admin/images/favicon.png'
	); 

	add_action('admin_init', 'fifu_get_menu_settings');
}

function fifu_get_menu_html() {
	$image_button = plugins_url() . '/featured-image-from-url/admin/images/onoff.jpg';

	$enable_woocommerce = get_option('fifu_woocommerce');
	$enable_content = get_option('fifu_content');
	$enable_hope = get_option('fifu_hope');

	$array_cpt = array();
	for ($x = 0; $x <= 4; $x++)
		$array_cpt[$x] = get_option('fifu_cpt' . $x);

	$show_woocommerce_button = $show_content_button = "display:block";
	$output = shell_exec('uname -s');
	if ($output == "") {
		$compatible = "Unfortunatelly, the script and your server system are not compatible. " .
			"But we still can do the WooCommerce integration manually. In this case, send an email to <a href='mailto:marceljmachado@gmail.com'>marceljmachado@gmail.com</a>.";
		$show_woocommerce_button = "display:none";
	} else {
		if (strpos($output, "Linux") !== false)
			$compatible = "You server is using $output system. Great! The script may work =)";
		else
			$compatible = "You server is using $output system. The script may work. <p/>" .
				"Please, send an email to <a href='mailto:marceljmachado@gmail.com'>marceljmachado@gmail.com</a> " .
				"informing your server system and let me know if it worked for you.";
	}

	include 'html/menu.html';

	fifu_update_menu_options();

	fifu_script_woocommerce();

	if (get_option('fifu_hope') == 'toggleon')
		fifu_enable_nonstandard_compatibility();
	else
		fifu_disable_nonstandard_compatibility();
}

function fifu_get_menu_settings() {
	fifu_get_setting('fifu_woocommerce');
	fifu_get_setting('fifu_content');
	fifu_get_setting('fifu_hope');

	for ($x = 0; $x <= 4; $x++)
		fifu_get_setting('fifu_cpt' . $x);
}

function fifu_get_setting($type) {
	register_setting('settings-group', $type);

	if (!get_option($type)) {
		if (strpos($type, "cpt") !== false)
			update_option($type, '');
		else
			update_option($type, 'toggleoff');
	}
}

function fifu_update_menu_options() {
	fifu_update_option('fifu_input_woocommerce', 'fifu_woocommerce');
	fifu_update_option('fifu_input_content', 'fifu_content');
	fifu_update_option('fifu_input_hope', 'fifu_hope');

	for ($x = 0; $x <= 4; $x++)
		fifu_update_option('fifu_input_cpt' . $x, 'fifu_cpt' . $x);
}

function fifu_update_option($input, $type) {
	if (isset($_POST[$input])) {
		if ($_POST[$input] == 'on')
			update_option($type, 'toggleon');
		else if ($_POST[$input] == 'off')
			update_option($type, 'toggleoff');
		else
			update_option($type, wp_strip_all_tags($_POST[$input]));
	}
}

function fifu_script_woocommerce() {
	if (get_option('fifu_woocommerce') == 'toggleon') {
		$command1 = "echo " . get_template_directory() . " > ../wp-content/plugins/featured-image-from-url/scripts/tmp.txt";
		$command2 = "sh ../wp-content/plugins/featured-image-from-url/scripts/enableWoocommerce.sh";
	} else {
		$command1 = "sh ../wp-content/plugins/featured-image-from-url/scripts/disableWoocommerce.sh";
		$command2 = "rm ../wp-content/plugins/featured-image-from-url/scripts/tmp.txt";
	}
	shell_exec($command1);
	shell_exec($command2);
}

function fifu_enable_nonstandard_compatibility() {
	if (get_option('fifu_attachment_id'))
		return;

	global $wpdb;
	$old_attach_id = get_option('fifu_attachment_id');

	// create attachment 
	$filename = 'Featured Image From URL';
	$parent_post_id = null;
	$filetype = wp_check_filetype('fifu.png', null);
	$attachment = array(
		'guid' => basename($filename),
		'post_mime_type' => $filetype['type'],
		'post_title' => '',
		'post_excerpt' => '',
		'post_content' => 'Please don\'t remove that. It\'s just a symbolic file that keeps the field filled. ' .
			'Some themes depend on having an attached file to work. But you are free to use any image you want instead of this file.',
		'post_status' => 'inherit'
	);
	$attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_data = wp_generate_attachment_metadata($attach_id, $filename);
	wp_update_attachment_metadata($attach_id, $attach_data);
	update_option('fifu_attachment_id', $attach_id);

	// insert _thumbnail_id
	$table = $wpdb->prefix . 'postmeta';
	$query = "
		SELECT DISTINCT post_id
		FROM " . $table . " a
		WHERE a.post_id in (
			SELECT post_id 
			FROM " . $table . " b 
			WHERE b.meta_key = 'fifu_image_url' 
			AND b.meta_value IS NOT NULL 
			AND b.meta_value <> ''
		)
		AND NOT EXISTS (
			SELECT 1 
			FROM " . $table . " c 
			WHERE a.post_id = c.post_id 
			AND c.meta_key = '_thumbnail_id'
		)";
	$result = $wpdb->get_results($query);
	foreach ($result as $i) {
		$data = array('post_id' => $i->post_id, 'meta_key' => '_thumbnail_id', 'meta_value' => $attach_id);
		$wpdb->insert($table, $data);
	}

	// update _thumbnail_id
	$data = array('meta_value' => $attach_id);
	$where = array('meta_key' => '_thumbnail_id', 'meta_value' => $old_attach_id);
	$wpdb->update($table, $data, $where, null, null);

	// update _thumbnail_id
	$query = "
		SELECT post_id 
		FROM " . $table . " a
		WHERE a.meta_key = 'fifu_image_url' 
		AND a.meta_value IS NOT NULL 
		AND a.meta_value <> ''";
	$result= $wpdb->get_results($query);
	foreach ($result as $i) {
		$data = array('meta_value' => $attach_id);
		$where = array('post_id' => $i->post_id, 'meta_key' => '_thumbnail_id', 'meta_value' => -1);
		$wpdb->update($table, $data, $where, null, null);
	}
}

function fifu_disable_nonstandard_compatibility() {
	global $wpdb;
	$table = $wpdb->prefix . 'postmeta';
	$where = array('meta_key' => '_thumbnail_id', 'meta_value' => get_option('fifu_attachment_id'));
	$wpdb->delete($table, $where);

	wp_delete_attachment(get_option('fifu_attachment_id'));
	delete_option('fifu_attachment_id');
}
