<?php

add_action('admin_init', 'fifu_column');

function fifu_column() {
	add_filter('manage_posts_columns', 'fifu_column_head');
	add_filter('manage_pages_columns', 'fifu_column_head');
	add_filter('manage_edit-product_cat_columns', 'fifu_column_head');
	add_action('manage_posts_custom_column', 'fifu_column_content', 10, 2);
	add_action('manage_pages_custom_column', 'fifu_column_content', 10, 2);
	add_action('manage_product_cat_custom_column', 'fifu_cat_column_content', 10, 3);
}

function fifu_column_head($default) {
	$default['featured_image'] = 'Featured Image';
	return $default;
}

function fifu_cat_column_content($internal_image, $column, $term_id) {
	if ($column == 'featured_image') {
		$url = get_term_meta($term_id, 'fifu_image_url', true);
		if ($url != '')
			echo sprintf('<img src="%s" width="100"/>', $url);
	} else
		echo $internal_image;
}

function fifu_column_content($column, $post_id) {
	if ($column == 'featured_image') {
		$url = get_post_meta($post_id, 'fifu_image_url', true);
		if ($url == '')
			$url = wp_get_attachment_url(get_post_thumbnail_id());
		echo sprintf('<img src="%s" width="100"/>', $url);
	}
}
