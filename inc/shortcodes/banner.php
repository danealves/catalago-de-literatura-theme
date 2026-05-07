<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AutoBannerShortcode {

    public function __construct() {
        add_shortcode( 'banner_obra_automatico', [ $this, 'render' ] );
        add_action( 'save_post', [ $this, 'clear_cache' ] );
    }

    public function render() {
        if ( ! is_singular() ) {
            return '';
        }

        $post_id = get_the_ID();
        $banner  = $this->get_banner_data( $post_id );
        $style   = $this->build_styles( $banner );

        $sep = '<span class="sep"><svg width="6" height="10" viewBox="0 0 6 10" fill="none" stroke="currentColor"><path d="M1 9L5 5L1 1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';

        ob_start(); ?>
        <section class="banner-fullwidth-section" style="<?php echo $style; ?>">
            <div class="banner-content-constrained">
                <h1 class="hero-title"><?php echo esc_html( get_the_title() ); ?></h1>

                <nav class="hero-breadcrumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                    <?php echo $sep; ?>

                    <?php
                    $post_type = get_post_type();
                    $pt_obj    = get_post_type_object( $post_type );

                    $archive_url = get_post_type_archive_link( $post_type );
                    if ( $archive_url ) {
                        printf( '<a href="%s">%s</a>', esc_url( $archive_url ), esc_html( $pt_obj->labels->name ) );
                        echo $sep;
                    }

                    if ( ! empty( $banner['term_name'] ) ) {
                        printf( '<a href="%s">%s</a>', esc_url( $banner['term_link'] ), esc_html( $banner['term_name'] ) );
                        echo $sep;
                    }
                    ?>
                    <span class="current"><?php echo esc_html( get_the_title() ); ?></span>
                </nav>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    public function clear_cache( $post_id ) {
        delete_transient( 'banner_obra_v1_' . $post_id );
    }

    private function get_banner_data( $post_id ) {
        $data = [
            'color'     => '',
            'image_url' => '',
            'term_name' => '',
            'term_link' => '',
        ];

        $term  = null;
        $terms = get_the_terms( $post_id, 'segmento_escolar' );

        if ( $terms && ! is_wp_error( $terms ) ) {
            $term = $terms[0];
        } else {
            // Fallback via ACF se o WP não sincronizou a taxonomia
            $acf_term = get_field( 'segmento_escolar', $post_id );

            if ( $acf_term instanceof WP_Term ) {
                $term = $acf_term;
            } elseif ( is_numeric( $acf_term ) ) {
                $term = get_term( $acf_term, 'segmento_escolar' );
            } elseif ( is_array( $acf_term ) && isset( $acf_term[0] ) ) {
                $term = is_numeric( $acf_term[0] )
                    ? get_term( $acf_term[0], 'segmento_escolar' )
                    : $acf_term[0];
            }
        }

        $fallback_img = '';

        if ( $term && ! is_wp_error( $term ) ) {
            $data['term_name'] = $term->name;
            $data['term_link'] = get_term_link( $term );

            $term_prefix          = 'segmento_escolar_' . $term->term_id;
            $data['color']        = get_field( 'cor_da_categoria', $term_prefix );
            $fallback_img         = get_field( 'imagem_de_fundo', $term_prefix );
        }

        $post_img    = get_field( 'banner_hero_image', $post_id );
        $final_image = ! empty( $post_img ) ? $post_img : $fallback_img;

        $data['image_url'] = $this->resolve_image_url( $final_image );

        return $data;
    }

    private function resolve_image_url( $image ) {
        if ( empty( $image ) ) {
            return '';
        }
        if ( is_array( $image ) ) {
            return $image['url'];
        }
        if ( is_numeric( $image ) ) {
            return wp_get_attachment_image_url( $image, 'full' );
        }
        return $image;
    }

    private function build_styles( $data ) {
        $layers = [];
        $css    = '';

        if ( ! empty( $data['color'] ) ) {
            $rgb = $this->hex2rgb( $data['color'] );
            if ( $rgb ) {
                $layers[] = "linear-gradient(to right, rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, 0.85) 0%, rgba(0,0,0,0.2) 100%)";
            }
            $css .= 'background-color: ' . esc_attr( $data['color'] ) . '; ';
        } else {
            $css .= 'background-color: #333; ';
        }

        if ( ! empty( $data['image_url'] ) ) {
            $layers[] = 'url(' . esc_url( $data['image_url'] ) . ')';
        }

        if ( ! empty( $layers ) ) {
            $css .= 'background-image: ' . implode( ', ', $layers ) . ';';
            $css .= 'background-size: cover; background-position: center;';
        }

        return esc_attr( $css );
    }

    private function hex2rgb( $hex ) {
        $hex = str_replace( '#', '', $hex );
        if ( strlen( $hex ) === 3 ) {
            $r = hexdec( str_repeat( substr( $hex, 0, 1 ), 2 ) );
            $g = hexdec( str_repeat( substr( $hex, 1, 1 ), 2 ) );
            $b = hexdec( str_repeat( substr( $hex, 2, 1 ), 2 ) );
        } elseif ( strlen( $hex ) === 6 ) {
            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
        } else {
            return false;
        }
        return [ $r, $g, $b ];
    }
}

new AutoBannerShortcode();
