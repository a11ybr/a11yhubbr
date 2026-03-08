<?php

if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_enqueue_assets() {
    $theme = wp_get_theme();
    $css_path = get_template_directory() . '/style.css';
    $js_path = get_template_directory() . '/assets/js/forms.js';
    $css_ver = file_exists($css_path) ? (string) filemtime($css_path) : $theme->get('Version');
    $js_ver = file_exists($js_path) ? (string) filemtime($js_path) : $theme->get('Version');

    wp_enqueue_style(
        'a11yhubbr-style',
        get_stylesheet_uri(),
        array(),
        $css_ver
    );

    wp_enqueue_style(
        'a11yhubbr-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        array(),
        '6.5.2'
    );

    wp_style_add_data('a11yhubbr-style', 'rtl', 'replace');

    if (
        is_front_page() ||
        is_singular() ||
        is_page_template('pages/page-submeter.php') ||
        is_page_template('pages/page-submeter-conteudo.php') ||
        is_page_template('pages/page-submeter-eventos.php') ||
        is_page_template('pages/page-submeter-perfil.php') ||
        is_page_template('pages/page-busca.php') ||
        is_page_template('pages/page-contato.php')
    ) {
        wp_enqueue_script(
            'a11yhubbr-forms',
            get_template_directory_uri() . '/assets/js/forms.js',
            array(),
            $js_ver,
            true
        );
        wp_script_add_data('a11yhubbr-forms', 'strategy', 'defer');
    }
}
add_action('wp_enqueue_scripts', 'a11yhubbr_enqueue_assets');


function a11yhubbr_resource_hints($hints, $relation_type) {
    if ('preconnect' !== $relation_type) {
        return $hints;
    }

    $hints[] = 'https://fonts.googleapis.com';
    $hints[] = array(
        'href' => 'https://fonts.gstatic.com',
        'crossorigin' => 'anonymous',
    );
    $hints[] = 'https://cdnjs.cloudflare.com';
    return $hints;
}
add_filter('wp_resource_hints', 'a11yhubbr_resource_hints', 10, 2);


function a11yhubbr_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    register_nav_menus(array(
        'primary' => __('Menu Primario', 'a11yhubbr'),
        'footer_platform'  => __('Rodape - Plataforma', 'a11yhubbr'),
        'footer_community' => __('Rodape - Comunidade', 'a11yhubbr'),
        'footer_legal' => __('Rodape - Legal', 'a11yhubbr'),
    ));
}
add_action('after_setup_theme', 'a11yhubbr_theme_setup');


function a11yhubbr_enable_page_excerpt_support() {
    add_post_type_support('page', 'excerpt');
}
add_action('init', 'a11yhubbr_enable_page_excerpt_support');

