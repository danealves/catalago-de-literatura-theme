<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'ftd_premios', function ( $atts ) {
    if ( ! is_singular() ) {
        return '';
    }

    global $post;

    $atts = shortcode_atts( [
        'taxonomy' => 'premio',
        'limit'    => 2,
    ], $atts );

    $terms = get_the_terms( $post->ID, $atts['taxonomy'] );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return '';
    }

    usort( $terms, fn( $a, $b ) => strcmp( $a->name, $b->name ) );
    $terms = array_slice( $terms, 0, (int) $atts['limit'] );

    $svg_red    = 'https://novoliteratura.kinsta.cloud/wp-content/uploads/2026/04/Selo-Literatura-Infantil-Catedra-Unesco-2025.svg';
    $svg_orange = 'https://novoliteratura.kinsta.cloud/wp-content/uploads/2026/04/Selo-Literatura-Infantil-Catedra-Unesco-2025-1.svg';

    ob_start();

    echo '<div class="ftd-premios">';

    foreach ( $terms as $index => $term ) {
        $is_primary = $index === 0;
        $class      = $is_primary ? 'ftd-premio ftd-premio--primary' : 'ftd-premio ftd-premio--secondary';
        $icon       = $is_primary ? $svg_red : $svg_orange;

        printf(
            '<div class="%s">
                <span class="ftd-premio__icon"><img src="%s" alt=""></span>
                <span class="ftd-premio__text">%s</span>
            </div>',
            esc_attr( $class ),
            esc_url( $icon ),
            esc_html( $term->name )
        );
    }

    echo '</div>';

    return ob_get_clean();
} );
