<?php
/**
 * Catalago de Literatura — Child Theme Bootstrap
 *
 * Este arquivo carrega os módulos do tema a partir de inc/.
 * Toda a lógica reside nos arquivos específicos de cada responsabilidade.
 *
 * Estrutura:
 *   inc/enqueue.php              — Registro de scripts e estilos
 *   inc/filters/                 — Filtros e hooks do WordPress
 *   inc/shortcodes/              — Shortcodes do tema
 *   inc/blocks/                  — Blocos Gutenberg customizados
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$modules = [
    'inc/enqueue.php',

    // Filtros
    'inc/filters/term-links.php',
    'inc/filters/thumbnails.php',
    'inc/filters/block-visibility.php',
    'inc/filters/obra-ordering.php',

    // Shortcodes
    'inc/shortcodes/banner.php',
    'inc/shortcodes/awards.php',
    'inc/shortcodes/materials.php',
    'inc/shortcodes/related-items.php',
    'inc/shortcodes/issuu.php',

    // Blocos
    'inc/blocks/related-obras.php',
    'inc/blocks/icon.php',
];

foreach ( $modules as $module ) {
    require_once get_stylesheet_directory() . '/' . $module;
}
