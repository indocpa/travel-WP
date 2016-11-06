<?php

add_action('product_cat_edit_form_fields', 'fifu_cat_show_box');
add_action('product_cat_add_form_fields', 'fifu_cat_show_box');

function fifu_cat_show_box($term) {
	$margin = 'margin-top:10px;';
	$width = 'width:100%;';
	$height = 'height:266px;';
	$align = 'text-align:left;';

	$url = get_term_meta($term->term_id, 'fifu_image_url', true);
	$alt = get_term_meta($term->term_id, 'fifu_image_alt', true);

	if ($url)
		$show_url = $show_button = 'display:none;';
	else
		$show_alt = $show_image = $show_link = 'display:none;';

	include 'html/category.html';
	include 'html/category-advertisement.html';
}

add_action('edited_product_cat', 'fifu_cat_save_properties', 10, 2);
add_action('create_product_cat', 'fifu_cat_save_properties', 10, 2);

function fifu_cat_save_properties($term_id) {
	if (isset($_POST['fifu_input_url']))
		update_term_meta($term_id, 'fifu_image_url', esc_url($_POST['fifu_input_url']));

	if (isset($_POST['fifu_input_alt']))
		update_term_meta($term_id, 'fifu_image_alt', wp_strip_all_tags($_POST['fifu_input_alt']));
}
