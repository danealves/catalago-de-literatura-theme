<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Converte links de taxonomia em filtros de busca no archive.
 * De: /taxonomia/termo/
 * Para: /post-type-archive/?taxonomia=slug-do-termo
 */
add_filter( 'term_link', function ( $url, $term, $taxonomy ) {
    $tax_obj = get_taxonomy( $taxonomy );

    if ( ! $tax_obj || empty( $tax_obj->object_type ) ) {
        return $url;
    }

    $associated_post_type = $tax_obj->object_type[0];
    $archive_link         = get_post_type_archive_link( $associated_post_type );

    if ( $archive_link ) {
        return add_query_arg( $taxonomy, $term->slug, $archive_link );
    }

    return $url;
}, 10, 3 );
