<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'post_thumbnail_html', function ( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    if ( ! empty( $html ) ) {
        return $html;
    }

    $url  = get_stylesheet_directory_uri() . '/assets/images/default-thumbnail.png';
    $alt  = esc_attr( get_the_title( $post_id ) );
    $size = esc_attr( $size );

    return '<img src="' . esc_url( $url ) . '" alt="' . $alt . '" class="attachment-' . $size . ' size-' . $size . ' wp-post-image-fallback" />';
}, 10, 5 );
