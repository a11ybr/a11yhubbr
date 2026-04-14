<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('a11yhubbr_should_render_global_page_cta')) {
    function a11yhubbr_should_render_global_page_cta() {
        if (is_admin()) {
            return false;
        }

        if (function_exists('a11yhubbr_is_submit_path_active') && a11yhubbr_is_submit_path_active()) {
            return false;
        }

        if (is_page()) {
            $queried_id = (int) get_queried_object_id();
            if ($queried_id > 0) {
                $ancestor_ids = get_post_ancestors($queried_id);
                foreach ($ancestor_ids as $ancestor_id) {
                    $slug = (string) get_post_field('post_name', (int) $ancestor_id);
                    if (sanitize_title($slug) === 'submeter') {
                        return false;
                    }
                }
            }
        }

        if (
            is_front_page() ||
            is_home() ||
            is_page() ||
            is_singular(array('a11y_conteudo', 'a11y_evento', 'a11y_perfil', 'post')) ||
            is_post_type_archive(array('a11y_conteudo', 'a11y_evento', 'a11y_perfil')) ||
            is_category() ||
            is_tag() ||
            is_search()
        ) {
            return true;
        }

        return false;
    }
}

if (!function_exists('a11yhubbr_get_global_page_cta_args')) {
    function a11yhubbr_get_global_page_cta_args() {
        $context = function_exists('a11yhubbr_get_header_context') ? a11yhubbr_get_header_context() : 'default';
        $queried_id = (int) get_queried_object_id();
        $page_slug = '';

        if ($queried_id > 0) {
            $page_slug = sanitize_title((string) get_post_field('post_name', $queried_id));
        }

        $args = array(
            'title' => 'Se fizer sentido para a pauta, vale registrar',
            'description' => 'A contribuicao mais util para a plataforma e aquela que ajuda outra pessoa a encontrar contexto, referencia ou contato relevante sobre acessibilidade digital.',
            'primary' => array(
                'label' => 'Ir para submissao',
                'url' => home_url('/submeter'),
            ),
            'secondary' => array(
                'label' => 'Ler diretrizes',
                'url' => home_url('/diretrizes-da-comunidade'),
            ),
        );

        if ($page_slug === 'diretrizes-da-comunidade') {
            $args['secondary'] = array(
                'label' => '',
                'url' => '',
            );
        } elseif ($context === 'conteudos') {
            $args['primary'] = array(
                'label' => 'Submeter conteudo',
                'url' => function_exists('a11yhubbr_get_submit_content_url') ? a11yhubbr_get_submit_content_url() : home_url('/submeter/submeter-conteudo'),
            );
        } elseif ($context === 'eventos') {
            $args['primary'] = array(
                'label' => 'Submeter evento',
                'url' => function_exists('a11yhubbr_get_submit_event_url') ? a11yhubbr_get_submit_event_url() : home_url('/submeter/submeter-eventos'),
            );
        } elseif ($context === 'rede') {
            $args['primary'] = array(
                'label' => 'Submeter perfil',
                'url' => function_exists('a11yhubbr_get_submit_profile_url') ? a11yhubbr_get_submit_profile_url() : home_url('/submeter/submeter-perfil'),
            );
        }

        return $args;
    }
}

if (!function_exists('a11yhubbr_render_global_page_cta')) {
    function a11yhubbr_render_global_page_cta() {
        if (!a11yhubbr_should_render_global_page_cta()) {
            return;
        }

        get_template_part('inc/components/cta-box', null, a11yhubbr_get_global_page_cta_args());
    }
}
