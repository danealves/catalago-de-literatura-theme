<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Query Loop block (Gutenberg)
add_filter( 'query_loop_block_query_vars', function ( $query, $block, $page ) {
    if ( isset( $query['post_type'] ) && $query['post_type'] === 'obra' ) {
        $query['orderby'] = array(
            'menu_order' => 'DESC',
            'date'       => 'DESC',
        );
        unset( $query['meta_key'], $query['meta_query'] );
    }
    return $query;
}, 10, 3 );

/**
 * Intercepta o salvamento do ACF e atualiza a coluna nativa menu_order.
 * Destaques recebem peso 10, obras normais recebem peso 0.
 * Versão Otimizada com limpeza de cache.
 */
add_action( 'acf/save_post', 'sincronizar_destaque_acf_menu_order', 20 );
function sincronizar_destaque_acf_menu_order( $post_id ) {

    // Evita rodar em opções ou revisões
    if ( get_post_type( $post_id ) !== 'obra' ) return;

    $is_destaque = get_field( 'is_destaque', $post_id );
    $menu_order  = $is_destaque ? 10 : 0;

    // Pega o post atual para comparar
    $post_atual = get_post( $post_id );

    // Se o valor já estiver correto no banco, não faz nada (poupa processamento)
    if ( (int) $post_atual->menu_order === $menu_order ) {
        return;
    }

    // 1. DESENGATA a nossa função para evitar um loop infinito quando usarmos wp_update_post
    remove_action( 'acf/save_post', 'sincronizar_destaque_acf_menu_order', 20 );

    // 2. Atualiza o post nativamente (Isso força o WordPress a limpar todos os caches daquele post)
    wp_update_post( array(
        'ID'         => $post_id,
        'menu_order' => $menu_order
    ) );

    // 3. ENGATA a função novamente para os próximos salvamentos
    add_action( 'acf/save_post', 'sincronizar_destaque_acf_menu_order', 20 );
}

// Archive de obras (main query)
add_action( 'pre_get_posts', function ( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( $query->is_post_type_archive( 'obra' ) || $query->is_tax() && $query->get( 'post_type' ) === 'obra' ) {
        $query->set( 'orderby', array(
            'menu_order' => 'DESC',
            'date'       => 'DESC',
        ) );
    }
} );
