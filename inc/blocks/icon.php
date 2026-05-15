<?php
/**
 * Block: cl/icon — Registration
 *
 * Registers the icon block from its compiled build directory.
 * The block.json in build/blocks/icon/ handles all metadata,
 * scripts, styles, and render template references.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', function () {
    $block_dir = get_stylesheet_directory() . '/build/blocks/icon';

    if ( ! file_exists( $block_dir . '/block.json' ) ) {
        return;
    }

    register_block_type( $block_dir );
} );
