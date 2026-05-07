<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'twentytwentyfive-style',
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'twentytwentyfive-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [ 'twentytwentyfive-style' ],
        wp_get_theme()->get( 'Version' )
    );

    $scss_path = get_stylesheet_directory() . '/assets/css/style.css';
    if ( file_exists( $scss_path ) ) {
        wp_enqueue_style(
            'catalago-scss',
            get_stylesheet_directory_uri() . '/assets/css/style.css',
            [],
            filemtime( $scss_path )
        );
    }

    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        null
    );

    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        null,
        true
    );

    wp_enqueue_script(
        'catalago-slider',
        get_stylesheet_directory_uri() . '/assets/js/cl-slider.js',
        [ 'swiper-js' ],
        filemtime( get_stylesheet_directory() . '/assets/js/cl-slider.js' ),
        true
    );

    wp_enqueue_script(
        'catalago-form-contact',
        get_stylesheet_directory_uri() . '/assets/js/form-contact.js',
        [],
        filemtime( get_stylesheet_directory() . '/assets/js/form-contact.js' ),
        true
    );
} );
