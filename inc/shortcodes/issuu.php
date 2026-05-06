<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'degustacao_issuu', function () {
    $url = get_field( 'degustacao' );

    if ( ! $url ) {
        return '';
    }

    $parts = explode( '/', trim( parse_url( $url, PHP_URL_PATH ), '/' ) );

    if ( count( $parts ) < 3 ) {
        return '';
    }

    $embed_url = "https://e.issuu.com/embed.html?d={$parts[2]}&u={$parts[0]}";

    return '<div style="position:relative;width:100%;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;box-shadow: 9px 8px 0px 0px #5bc8c8;">
        <iframe src="' . esc_url( $embed_url ) . '" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;" allowfullscreen allow="fullscreen" loading="lazy"></iframe>
    </div>';
} );
