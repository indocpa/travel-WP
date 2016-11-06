<?php

add_action('add_meta_boxes', 'fifu_insert_meta_box');

function fifu_insert_meta_box() {
    $post_types = array('post', 'page', 'product', get_option('fifu_cpt0'), get_option('fifu_cpt1'), get_option('fifu_cpt2'), get_option('fifu_cpt3'), get_option('fifu_cpt4'));

    foreach ($post_types as $post_type) {
        if ($post_type == 'product') {
            add_meta_box(
                    'urlMetaBox', 'External Product Image', 'fifu_show_elements', $post_type, 'side', 'low'
            );
            if (get_option('fifu_woocommerce') == 'toggleon') {
                add_meta_box(
                        'wooCommerceGalleryMetaBox', 'External Product Gallery', 'fifu_wc_show_elements', $post_type, 'side', 'low'
                );
            }
        } else {
            if ($post_type)
                add_meta_box(
                        'imageUrlMetaBox', 'External Featured Image', 'fifu_show_elements', $post_type, 'side', 'low'
                );
        }

        if ($post_type) {
            add_meta_box(
                    'sliderImageUrlMetaBox', 'External Featured Image Slider', 'fifu_slider_show_elements', $post_type, 'side', 'low'
            );
            add_meta_box(
                    'videoUrlMetaBox', 'External Featured Video', 'fifu_show_advertisement', $post_type, 'side', 'low'
            );
        }
    }
}

function fifu_show_elements($post) {
    $margin = 'margin-top:10px;';
    $width = 'width:100%;';
    $height = 'height:266px;';
    $align = 'text-align:left;';
    $is_sirv_active = is_plugin_active('sirv/sirv.php');

    $url = get_post_meta($post->ID, 'fifu_image_url', true);
    $alt = get_post_meta($post->ID, 'fifu_image_alt', true);

    if ($url) {
        $show_url = $show_button = $show_sirv = 'display:none;';
        $show_alt = $show_image = $show_link = '';
    } else {
        $show_alt = $show_image = $show_link = 'display:none;';
        $show_url = $show_button = '';
        $show_sirv = ($is_sirv_active ? '' : 'display:none;');
    }

    include 'html/meta-box.html';
}

function fifu_show_advertisement($post) {
    $margin = 'margin-top:10px;';
    $width = 'width:100%;';
    $height = 'height:266px;';
    $align = 'text-align:left;';

    include 'html/meta-box-advertisement.html';
}

function fifu_wc_show_elements($post) {
    $margin = 'margin-top:1px;';
    $width = 'width:70%;';
    $height = 'height:266px;';
    $align = 'text-align:left;';
    $altWidth = 'width:86%;';

    for ($i = 0; $i < 10; $i++) {
        $url[$i] = get_post_meta($post->ID, 'fifu_image_url_' . $i, true);
        $alt[$i] = get_post_meta($post->ID, 'fifu_image_alt_' . $i, true);

        if ($url[$i]) {
            $show_url[$i] = $show_button[$i] = 'display:none;';
            $show_alt[$i] = $show_image[$i] = $show_link[$i] = '';
        } else {
            $show_alt[$i] = $show_image[$i] = $show_link[$i] = 'display:none;';
            $show_url[$i] = $show_button[$i] = '';
        }

        include 'html/wc-meta-box.html';
    }
}

function fifu_slider_show_elements($post) {
    $margin = 'margin-top:1px;';
    $width = 'width:70%;';
    $height = 'height:266px;';
    $align = 'text-align:left;';
    $altWidth = 'width:86%;';

    for ($i = 1; $i < 5; $i++)
        include 'html/slider-meta-box.html';
    include 'html/live-preview.html';
}

add_action('save_post', 'fifu_save_properties');

function fifu_save_properties($post_id) {
    if (isset($_POST['fifu_input_url'])) {
        update_post_meta($post_id, 'fifu_image_url', esc_url($_POST['fifu_input_url']));

        if (get_option('fifu_attachment_id') && !get_post_thumbnail_id($post_id) && esc_url($_POST['fifu_input_url']))
            set_post_thumbnail($post_id, get_option('fifu_attachment_id'));
    }

    if (isset($_POST['fifu_input_alt']))
        update_post_meta($post_id, 'fifu_image_alt', wp_strip_all_tags($_POST['fifu_input_alt']));

    if (get_post_type(get_the_ID()) == 'product') {
        $count = 10;
        for ($i = 0; $i < 10; $i++) {
            if (isset($_POST['fifu_input_url_' . $i]) && isset($_POST['fifu_input_alt_' . $i])) {
                if ($_POST['fifu_input_url_' . $i] != '' && $_POST['fifu_input_alt_' . $i] != '') {
                    update_post_meta($post_id, 'fifu_image_url_' . $i, esc_url($_POST['fifu_input_url_' . $i]));
                    update_post_meta($post_id, 'fifu_image_alt_' . $i, wp_strip_all_tags($_POST['fifu_input_alt_' . $i]));
                    if (get_option('fifu_attachment_id') && !get_post_thumbnail_id($post_id) && esc_url($_POST['fifu_input_url_' . $i]))
                        set_post_thumbnail($post_id, get_option('fifu_attachment_id'));
                } else {
                    delete_post_meta($post_id, 'fifu_image_url_' . $i);
                    delete_post_meta($post_id, 'fifu_image_alt_' . $i);
                    $count--;
                }
            }
        }
        if ($count == 0 && (isset($_POST['fifu_input_url']) && !esc_url($_POST['fifu_input_url'])) && get_post_thumbnail_id($post_id) == get_option('fifu_attachment_id'))
            delete_post_thumbnail($post_id);
    }
    else {
        if ((isset($_POST['fifu_input_url']) && !esc_url($_POST['fifu_input_url'])) && get_post_thumbnail_id($post_id) == get_option('fifu_attachment_id'))
            delete_post_thumbnail($post_id);
    }
}

