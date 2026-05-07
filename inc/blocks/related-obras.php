<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Bloco customizado: Obras Relacionadas
 *
 * Reutiliza o template part "modelo-loop-consulta" do Site Editor.
 * Qualquer mudança feita no editor reflete automaticamente no front-end.
 */
add_action( 'init', function () {
    register_block_type( 'meu-plugin/related-obras', [
        'render_callback' => '_cl_render_related_obras',
    ] );
} );

function _cl_render_related_obras( $attributes, $content, $block ) {
    if ( ! is_singular( 'obra' ) ) {
        return '';
    }

    $post_id = get_the_ID();

    $terms = wp_get_post_terms( $post_id, 'segmento_escolar', [ 'fields' => 'ids' ] );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return '';
    }

    global $_cl_related_obras_ctx;
    $_cl_related_obras_ctx = [
        'exclude'  => $post_id,
        'term_ids' => $terms,
    ];

    add_filter( 'query_loop_block_query_vars', '_cl_related_obras_filter_query', 10, 3 );

    $template_part = get_block_template(
        'catalago-de-literatura//modelo-loop-consulta',
        'wp_template_part'
    );

    if ( ! $template_part || empty( $template_part->content ) ) {
        remove_filter( 'query_loop_block_query_vars', '_cl_related_obras_filter_query', 10 );
        $_cl_related_obras_ctx = null;
        return '<!-- template part modelo-loop-consulta não encontrado -->';
    }

    $output = do_blocks( $template_part->content );

    remove_filter( 'query_loop_block_query_vars', '_cl_related_obras_filter_query', 10 );
    $_cl_related_obras_ctx = null;

    return $output;
}

function _cl_related_obras_filter_query( $query, $block, $page ) {
    global $_cl_related_obras_ctx;

    if ( empty( $_cl_related_obras_ctx ) ) {
        return $query;
    }

    $query['tax_query'] = [
        [
            'taxonomy' => 'segmento_escolar',
            'field'    => 'term_id',
            'terms'    => $_cl_related_obras_ctx['term_ids'],
        ],
    ];

    $query['post__not_in'] = [ $_cl_related_obras_ctx['exclude'] ];

    return $query;
}
