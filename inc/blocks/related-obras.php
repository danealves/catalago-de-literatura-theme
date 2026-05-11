<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Bloco customizado: Obras Relacionadas
 *
 * Reutiliza o template part "modelo-loop-consulta" do Site Editor.
 * Exibe as obras manuais do ACF e, como fallback, usa o 'segmento_escolar'.
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
    global $_cl_related_obras_ctx;

    // 1. Tenta buscar os itens manuais definidos no ACF primeiro
    $posts_acf = get_field( 'item_relacionado', $post_id );

    if ( ! empty( $posts_acf ) ) {
        
        // Cenário A: Existem posts selecionados no ACF
        $ids_relacionados = wp_list_pluck( $posts_acf, 'ID' );
        
        $_cl_related_obras_ctx = [
            'type' => 'acf',
            'ids'  => $ids_relacionados,
        ];

    } else {
        
        // Cenário B: ACF está vazio. Fazemos o fallback pelo Segmento Escolar
        $terms = wp_get_post_terms( $post_id, 'segmento_escolar', [ 'fields' => 'ids' ] );

        // Se a obra atual também não tiver segmento escolar, não exibimos nada
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return '';
        }

        $_cl_related_obras_ctx = [
            'type'     => 'taxonomy',
            'exclude'  => $post_id,
            'term_ids' => $terms,
        ];
    }

    // Aplica o filtro antes de processar o bloco
    add_filter( 'query_loop_block_query_vars', '_cl_related_obras_filter_query', 10, 3 );

    $template_part = get_block_template(
        'catalago-de-literatura//modelo-loop-consulta',
        'wp_template_part'
    );

    if ( ! $template_part || empty( $template_part->content ) ) {
        remove_filter( 'query_loop_block_query_vars', '_cl_related_obras_filter_query', 10 );
        $_cl_related_obras_ctx = null;
        return '';
    }

    $output = do_blocks( $template_part->content );

    // Limpa o filtro e a global após o uso para não afetar o resto da página
    remove_filter( 'query_loop_block_query_vars', '_cl_related_obras_filter_query', 10 );
    $_cl_related_obras_ctx = null;

    return $output;
}

function _cl_related_obras_filter_query( $query, $block, $page ) {
    global $_cl_related_obras_ctx;

    if ( empty( $_cl_related_obras_ctx ) ) {
        return $query;
    }

    if ( $_cl_related_obras_ctx['type'] === 'acf' ) {
        
        // Aplica as regras do ACF
        $query['post__in'] = $_cl_related_obras_ctx['ids'];
        $query['orderby']  = 'post__in'; // Mantém a ordem definida manualmente pelo editor no ACF
        unset( $query['tax_query'] ); // Remove qualquer taxonomia padrão do bloco para não causar conflito

    } elseif ( $_cl_related_obras_ctx['type'] === 'taxonomy' ) {
        
        // Aplica as regras do Fallback (Segmento Escolar)
        $query['tax_query'] = [
            [
                'taxonomy' => 'segmento_escolar',
                'field'    => 'term_id',
                'terms'    => $_cl_related_obras_ctx['term_ids'],
            ],
        ];

        // Não mostra a própria obra na listagem de recomendadas
        $query['post__not_in'] = [ $_cl_related_obras_ctx['exclude'] ];
        
        // Ordena por Destaque no topo (menu_order) e depois pela Data, como configurado antes
        $query['orderby'] = array(
            'menu_order' => 'DESC',
            'date'       => 'DESC'
        );
    }

    return $query;
}