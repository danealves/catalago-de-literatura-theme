<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Oculta seções condicionalmente com base em campos ACF.
 * Gera o embed do booktrailer a partir do campo ACF correspondente.
 */
add_filter( 'render_block', function ( $content, $block ) {
    $class = $block['attrs']['className'] ?? '';

    if ( str_contains( $class, 'section-booktrailer' ) ) {
        return empty( get_field( 'booktrailer' ) ) ? '' : $content;
    }

    if ( str_contains( $class, 'section-previa-livro' ) ) {
        return empty( get_field( 'degustacao' ) ) ? '' : $content;
    }

    if ( $block['blockName'] === 'core/embed' && str_contains( $class, 'usar-booktrailer' ) ) {
        $url = get_field( 'booktrailer' );

        if ( empty( $url ) ) {
            return '';
        }

        global $wp_embed;
        $embed = $wp_embed->autoembed( $url );

        if ( ! $embed ) {
            return '';
        }

        return '<figure style="border-radius:12px;overflow:hidden;box-shadow: 9px 8px 0px 0px #5bc8c8;" class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">' . $embed . '</div></figure>';
    }

    return $content;
}, 10, 2 );

/**
 * Corrige tradução do plugin filter-everything.
 */
add_filter( 'gettext', function ( $translated, $text, $domain ) {
    if ( $domain === 'filter-everything' && $text === 'Reset all' ) {
        return 'Redefinir';
    }
    return $translated;
}, 10, 3 );
