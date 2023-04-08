<?php

add_action('init', 'create_faq_posttype');
function create_faq_posttype() {
    $args = [
        'labels'       => array(
            'name'          => __( 'FAQs' ),
            'singular_name' => __( 'FAQ' )
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array( 'slug' => 'faqs' ),
        'show_in_rest' => true,
        'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
        'taxonomies'   => array(
            'categories',
            'post_tag'
        ),];


    register_post_type('faq', $args);
}

