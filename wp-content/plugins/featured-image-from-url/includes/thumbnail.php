<?php

add_filter('wp_head', 'fifu_add_social_tags');
add_filter('wp_head', 'fifu_add_sirv_js');

function fifu_add_social_tags() {
    $post_id = get_the_ID();
    $url = get_post_meta($post_id, 'fifu_image_url', true);
    $title = get_the_title($post_id);
    $description = wp_strip_all_tags(get_post_field('post_content', $post_id));

    if ($url)
        include 'html/social.html';
}

function fifu_add_sirv_js() {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (is_plugin_active('sirv/sirv.php')) {
        include 'html/sirv.html';
    }
}

add_action('the_post', 'fifu_choose');

function fifu_choose($post) {
    $post_id = $post->ID;

    $image_url = get_post_meta($post_id, 'fifu_image_url', true);

    $featured_image = get_post_meta($post_id, '_thumbnail_id', true);

    if ($image_url) {
        if (!$featured_image)
            update_post_meta($post_id, '_thumbnail_id', -1);
    }
    else {
        if ($featured_image == -1)
            delete_post_meta($post_id, '_thumbnail_id');
    }
}

add_filter('post_thumbnail_html', 'fifu_replace', 10, 2);

function fifu_replace($html, $post_id) {
    $url = get_post_meta($post_id, 'fifu_image_url', true);
    $alt = get_post_meta($post_id, 'fifu_image_alt', true);

    if ($url)
        $html = fifu_get_html($url, $alt);

    return $html;
}

function fifu_get_html($url, $alt) {
    if (is_plugin_active('sirv/sirv.php') && strpos($url, "sirv.com") !== false)
        return sprintf('<!-- Featured Image From URL plugin --> <img class="Sirv" data-src="%s"></img>', $url);

    return sprintf('<!-- Featured Image From URL plugin --> <img src="%s" alt="%s"></img>', $url, $alt);
}

add_filter('the_content', 'fifu_add_to_content');

function fifu_add_to_content($content) {
    if (is_singular() && has_post_thumbnail() && get_option('fifu_content') == 'toggleon')
        return get_the_post_thumbnail() . $content;
    else
        return $content;
}

add_filter('wp_get_attachment_url', 'fifu_replace_attachment_url', 10, 2);

function fifu_replace_attachment_url($att_url, $att_id) {
    if (get_option('fifu_attachment_id') == $att_id) {
        if (is_admin())
            $att_url = '/wp-content/plugins/featured-image-from-url/admin/images/fifu-text.jpg';
        else {
            $url = get_post_meta(get_the_ID(), 'fifu_image_url', true);
            if ($url)
                $att_url = $url;
        }
    }
    return $att_url;
}
