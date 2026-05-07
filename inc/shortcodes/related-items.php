<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'itens_relacionados', function () {
    $posts = get_field( 'item_relacionado' );

    if ( ! $posts ) {
        return '';
    }

    $html = '<ul>';
    foreach ( $posts as $post ) {
        $html .= '<li>' . esc_html( get_the_title( $post->ID ) ) . '</li>';
    }
    $html .= '</ul>';

    return $html;
} );
