<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_shortcode( 'materiais_complementares', function () {
    $post_id = get_the_ID();

    $cards = [
        [
            'label'     => 'Material Complementar',
            'titulo'    => 'Projetos de Leitura',
            'descricao' => 'Os Projetos de Leitura despertam novos olhares em educadores e familiares empenhados em fazer da leitura um momento de aprendizado e prazer.',
            'cor'       => '#E8873A',
            'icone'     => 'https://novoliteratura.kinsta.cloud/wp-content/uploads/2026/02/BookOpen.svg',
            'url'       => get_field( 'projeto_de_leitura', $post_id ),
        ],
        [
            'label'     => 'Suplemento de Leitura',
            'titulo'    => 'Para Professores',
            'descricao' => 'A versão completa do Suplemento, apenas para professores com acesso à iônica, com respostas, dicas e orientações exclusivas a respeito das atividades do estudante.',
            'cor'       => '#2E9E6B',
            'icone'     => 'https://novoliteratura.kinsta.cloud/wp-content/uploads/2026/02/ChalkboardUser.svg',
            'url'       => get_field( 'suplemento_de_leitura_mestre', $post_id ),
        ],
        [
            'label'     => 'Suplemento de Leitura',
            'titulo'    => 'Para Estudantes',
            'descricao' => 'O Suplemento de Leitura oferece atividades que consolidam a formação literária e instigam a reflexão e o pensamento crítico de quem lê.',
            'cor'       => '#2B5BA8',
            'icone'     => 'https://novoliteratura.kinsta.cloud/wp-content/uploads/2026/02/UserGraduate-1.svg',
            'url'       => get_field( 'suplemento_de_leitura_aluno', $post_id ),
        ],
    ];

    $cards_ativos = array_values( array_filter( $cards, fn( $c ) => ! empty( trim( $c['url'] ?? '' ) ) ) );

    if ( empty( $cards_ativos ) ) {
        return '';
    }

    $total = count( $cards_ativos );

    $svg_externo = '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>';

    $html  = '<section class="mc-secao">';
    $html .= '<div class="mc-header">';
    $html .= '<span class="mc-estrelas">✦ ✦</span>';
    $html .= '<h2 class="mc-titulo">Materiais complementares</h2>';
    $html .= '</div>';
    $html .= '<div class="mc-grid mc-grid--' . $total . '">';

    foreach ( $cards_ativos as $card ) {
        $url       = esc_url( $card['url'] );
        $cor       = esc_attr( $card['cor'] );
        $label     = esc_html( $card['label'] );
        $titulo    = esc_html( $card['titulo'] );
        $descricao = esc_html( $card['descricao'] );

        $html .= '<div class="mc-card">';
        $html .= '<img width="32" height="24" src="' . esc_url( $card['icone'] ) . '" alt="">';
        $html .= '<p class="mc-card__label" style="color:' . $cor . '">' . $label . '</p>';
        $html .= '<h3 class="mc-card__titulo">' . $titulo . '</h3>';
        $html .= '<p class="mc-card__descricao">' . $descricao . '</p>';
        $html .= '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" class="mc-card__btn" style="background-color:' . $cor . '">';
        $html .= 'Acessar material ' . $svg_externo;
        $html .= '</a>';
        $html .= '</div>';
    }

    $html .= '</div></section>';

    return $html;
} );
