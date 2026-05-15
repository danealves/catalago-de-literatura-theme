<?php
/**
 * Block: cl/icon — Server-side render template
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block inner content (empty for this block).
 * @var WP_Block $block      Block instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$source    = $attributes['source'] ?? 'dashicon';
$dashicon  = $attributes['dashicon'] ?? 'star-filled';
$color     = $attributes['iconColor'] ?? '#000000';
$media_url = $attributes['mediaUrl'] ?? '';
$media_alt = $attributes['mediaAlt'] ?? '';
$size      = (int) ( $attributes['size'] ?? 48 );

if ( $source === 'dashicon' ) {
    // Ensure Dashicons CSS is loaded on the frontend
    wp_enqueue_style( 'dashicons' );

    $icon_html = sprintf(
        '<span class="dashicons dashicons-%s" style="font-size:%dpx;width:%dpx;height:%dpx;line-height:%dpx;color:%s;" aria-hidden="true"></span>',
        esc_attr( $dashicon ),
        $size,
        $size,
        $size,
        $size,
        esc_attr( $color )
    );
} elseif ( $source === 'media' && $media_url ) {
    $is_svg   = str_ends_with( strtolower( $media_url ), '.svg' );
    $has_color = ! empty( $color );

    if ( $is_svg && $has_color ) {
        // SVG with color applied → CSS mask-image technique
        $icon_html = sprintf(
            '<span class="cl-icon-block__svg-mask" style="display:inline-block;width:%1$dpx;height:%1$dpx;background-color:%2$s;-webkit-mask-image:url(%3$s);mask-image:url(%3$s);-webkit-mask-size:contain;mask-size:contain;-webkit-mask-repeat:no-repeat;mask-repeat:no-repeat;-webkit-mask-position:center;mask-position:center;" aria-hidden="true"></span>',
            $size,
            esc_attr( $color ),
            esc_url( $media_url )
        );
    } else {
        // Raster image or SVG without color → regular img
        $icon_html = sprintf(
            '<img src="%s" alt="%s" width="%d" height="%d" style="object-fit:contain;" loading="lazy" />',
            esc_url( $media_url ),
            esc_attr( $media_alt ),
            $size,
            $size
        );
    }
} else {
    // Nothing to render
    return;
}

$wrapper_attributes = get_block_wrapper_attributes( [
    'class' => 'cl-icon-block',
] );

printf( '<div %s>%s</div>', $wrapper_attributes, $icon_html );
