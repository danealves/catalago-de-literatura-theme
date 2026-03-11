<?php
/**
 * Theme functions and definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue styles
 */
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'twentytwentyfive-style',
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'twentytwentyfive-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        ['twentytwentyfive-style'],
        wp_get_theme()->get('Version')
    );
});

/**
 * Translation Fixes
 */
add_filter('gettext', function ($translated, $text, $domain) {
    if ($domain === 'filter-everything' && $text === 'Reset all') {
        return 'Redefinir'; // Corrigido erro de digitação "Redifinir"
    }
    return $translated;
}, 10, 3);

/**
 * Class wrapper para o Shortcode do Banner
 * Evita poluição do escopo global e colisão de nomes de função
 */
class AutoBannerShortcode {

    public function __construct() {
        add_shortcode('banner_obra_automatico', [$this, 'render']);
    }

    public function render() {
        if ( ! is_singular() ) { return ''; }

        $post_id = get_the_ID();
        
        // Data Retrieval
        $banner_data = $this->get_banner_data($post_id);
        
        // CSS Generation
        $style_css = $this->build_styles($banner_data);

        // Separator Icon
        $separator = '<span class="sep"><svg width="6" height="10" viewBox="0 0 6 10" fill="none" stroke="currentColor"><path d="M1 9L5 5L1 1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';

        ob_start();
        ?>
        <section class="banner-fullwidth-section" style="<?php echo $style_css; ?>">
            <div class="banner-content-constrained">
                <h1 class="hero-title"><?php echo get_the_title(); ?></h1>

                <nav class="hero-breadcrumbs" aria-label="Breadcrumb">
                    <?php
                    // 1. Home
                    printf('<a href="%s">Home</a>', esc_url(home_url('/')));
                    echo $separator;

                    // 2. Archive do Post Type (Obras)
                    $post_type = get_post_type();
                    if ( $archive_url = get_post_type_archive_link($post_type) ) {
                        $pt_obj = get_post_type_object($post_type);
                        printf('<a href="%s">%s</a>', esc_url($archive_url), esc_html($pt_obj->labels->name));
                    }

                    // 3. Termo Atual (Dinâmico)
                    // Buscamos a taxonomia associada a este post type (ex: 'ano-escolar' ou 'category')
                    $taxonomies = get_object_taxonomies($post_type);
                    if ( !empty($taxonomies) ) {
                        $terms = get_the_terms(get_the_ID(), $taxonomies[0]);
                        if ( $terms && !is_wp_error($terms) ) {
                            $term = current($terms);
                            echo $separator;
                            printf('<a href="%s">%s</a>', esc_url(get_term_link($term)), esc_html($term->name));
                        }
                    }

                    // 4. Título do Post
                    echo $separator;
                    printf('<span class="current">%s</span>', get_the_title());
                    ?>
                </nav>

            </div>
        </section>
        <?php
        return trim(ob_get_clean());
    }

    private function get_banner_data($post_id) {
        $data = [
            'color'     => '',
            'image_url' => '',
            'term_name' => 'Obras',
            'term_link' => ''
        ];

        // 1. Taxonomia (Fallback e Cor)
        $terms = get_the_terms( $post_id, 'ano_escolar' );
        $term_img_fallback = '';

        if ( $terms && ! is_wp_error( $terms ) ) {
            $term = $terms[0];
            $data['term_name'] = $term->name;
            $data['term_link'] = get_term_link( $term );
            
            // ACF da Taxonomia
            $term_id_prefix = 'ano_escolar_' . $term->term_id;
            $data['color'] = get_field('cor_da_categoria', $term_id_prefix);
            $term_img_fallback = get_field('imagem_de_fundo', $term_id_prefix);
        }

        // 2. Imagem do Post (Prioridade)
        $post_img = get_field('banner_hero_image', $post_id);
        
        // Resolve a URL final
        $final_image = !empty($post_img) ? $post_img : $term_img_fallback;
        $data['image_url'] = $this->resolve_image_url($final_image);

        return $data;
    }

    private function resolve_image_url($image) {
        if (empty($image)) return '';
        if (is_array($image)) return $image['sizes']['full'] ?? $image['url'];
        if (is_numeric($image)) return wp_get_attachment_image_url($image, 'full');
        return $image;
    }

    private function build_styles($data) {
        $layers = [];
        $css = '';

        // Layer 1: Gradiente
        if ( ! empty($data['color']) ) {
            $rgb = $this->hex2rgb($data['color']);
            if ($rgb) {
                $layers[] = "linear-gradient(to right, rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, 0.8) 0%, rgba(255, 255, 255, 0) 100%)";
            }
            $css .= 'background-color: ' . esc_attr($data['color']) . '; ';
        }

        // Layer 2: Imagem
        if ( ! empty($data['image_url']) ) {
            $layers[] = "url(" . esc_url($data['image_url']) . ")";
        }

        if ( ! empty($layers) ) {
            $css .= 'background-image: ' . implode(', ', $layers) . ';';
            $css .= 'background-size: cover; background-position: center;';
        }

        return esc_attr($css);
    }

    // Função utilitária movida para dentro da classe (Método Privado)
    private function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex,0,1), 2));
            $g = hexdec(str_repeat(substr($hex,1,1), 2));
            $b = hexdec(str_repeat(substr($hex,2,1), 2));
        } elseif (strlen($hex) == 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        } else {
            return false;
        }
        return [$r, $g, $b];
    }
}

// Inicializa o shortcode
new AutoBannerShortcode();



/**
 * Engenharia de URL Abstrata: Converte qualquer link de taxonomia em filtro de busca.
 * De: /taxonomia/termo/
 * Para: /post-type-archive/?taxonomia=termo
 */
add_filter('term_link', function( $url, $term, $taxonomy ) {
    // Obtém o objeto da taxonomia para descobrir a quais Post Types ela está ligada
    $tax_obj = get_taxonomy($taxonomy);
    if ( ! $tax_obj || empty($tax_obj->object_type) ) {
        return $url;
    }

    // Pega o primeiro Post Type associado (ex: 'obra', 'produto', etc.)
    $associated_post_type = $tax_obj->object_type[0];

    // Busca o link oficial do Archive desse Post Type
    $archive_link = get_post_type_archive_link($associated_post_type);

    if ( $archive_link ) {
        // Reconstrói a URL mantendo a abstração total
        // Resultado: https://site.com/post-type/?taxonomia=slug-do-termo
        return add_query_arg($taxonomy, $term->slug, $archive_link);
    }

    return $url;
}, 10, 3);


/**
 * Define uma imagem de destaque padrão (fallback) caso o post não tenha uma.
 */
function definir_imagem_fallback_global($html, $post_id, $post_thumbnail_id, $size, $attr) {
    // Se já existe uma imagem definida, retorna ela normalmente
    if (!empty($html)) {
        return $html;
    }

    // URL da sua imagem de fallback (altere o caminho conforme necessário)
    $url_fallback = get_stylesheet_directory_uri() . '/assets/images/default-thumbnail.png';
    
    // Opcional: Pegar o título do post para o atributo 'alt'
    $post_title = get_the_title($post_id);

    // Monta o HTML da imagem padrão
    $html = '<img src="' . esc_url($url_fallback) . '" alt="' . esc_attr($post_title) . '" class="attachment-' . $size . ' size-' . $size . ' wp-post-image-fallback" />';

    return $html;
}

add_filter('post_thumbnail_html', 'definir_imagem_fallback_global', 10, 5);




function limpar_cache_banner_ao_salvar( $post_id ) {
    delete_transient( 'banner_obra_v1_' . $post_id );
}
add_action( 'save_post', 'limpar_cache_banner_ao_salvar' );