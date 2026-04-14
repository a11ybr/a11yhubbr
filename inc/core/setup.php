<?php

if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_is_frontend_runtime_request() {
    if (is_admin()) {
        return false;
    }

    if (defined('REST_REQUEST') && REST_REQUEST) {
        return false;
    }

    if (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
        return false;
    }

    return true;
}

function a11yhubbr_enqueue_assets() {
    $theme = wp_get_theme();
    $css_path = get_template_directory() . '/style.css';
    $site_js_path = get_template_directory() . '/assets/js/site.js';
    $submissions_js_path = get_template_directory() . '/assets/js/submissions.js';
    $contact_js_path = get_template_directory() . '/assets/js/contact.js';
    $css_ver = file_exists($css_path) ? (string) filemtime($css_path) : $theme->get('Version');
    $site_js_ver = file_exists($site_js_path) ? (string) filemtime($site_js_path) : $theme->get('Version');
    $submissions_js_ver = file_exists($submissions_js_path) ? (string) filemtime($submissions_js_path) : $theme->get('Version');
    $contact_js_ver = file_exists($contact_js_path) ? (string) filemtime($contact_js_path) : $theme->get('Version');

    wp_enqueue_style(
        'a11yhubbr-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'a11yhubbr-style',
        get_stylesheet_uri(),
        array('a11yhubbr-fonts'),
        $css_ver
    );

    // FontAwesome auto-hospedado — apenas solid, regular e brands (sem v4-shims, sem CDN externo)
    $fa_base = get_template_directory_uri() . '/assets/vendor/fontawesome/css/';
    $fa_ver  = '6.5.2';
    wp_enqueue_style('a11yhubbr-fa-base',    $fa_base . 'fontawesome.min.css', array(), $fa_ver);
    wp_enqueue_style('a11yhubbr-fa-solid',   $fa_base . 'solid.min.css',   array('a11yhubbr-fa-base'), $fa_ver);
    wp_enqueue_style('a11yhubbr-fa-regular', $fa_base . 'regular.min.css', array('a11yhubbr-fa-base'), $fa_ver);
    wp_enqueue_style('a11yhubbr-fa-brands',  $fa_base . 'brands.min.css',  array('a11yhubbr-fa-base'), $fa_ver);

    wp_style_add_data('a11yhubbr-style', 'rtl', 'replace');

    wp_enqueue_script(
        'a11yhubbr-site',
        get_template_directory_uri() . '/assets/js/site.js',
        array(),
        $site_js_ver,
        true
    );
    wp_script_add_data('a11yhubbr-site', 'strategy', 'defer');

    if (
        is_page_template('pages/page-submeter.php') ||
        is_page_template('pages/page-submeter-conteudo.php') ||
        is_page_template('pages/page-submeter-eventos.php') ||
        is_page_template('pages/page-submeter-perfil.php')
    ) {
        wp_enqueue_script(
            'a11yhubbr-submissions',
            get_template_directory_uri() . '/assets/js/submissions.js',
            array('a11yhubbr-site'),
            $submissions_js_ver,
            true
        );
        wp_script_add_data('a11yhubbr-submissions', 'strategy', 'defer');
    }

    // Formulário de contato: carrega JS e dados AJAX apenas na página de contato
    if (is_page_template('pages/page-contato.php')) {
        wp_enqueue_script(
            'a11yhubbr-contact',
            get_template_directory_uri() . '/assets/js/contact.js',
            array('a11yhubbr-site'),
            $contact_js_ver,
            true
        );
        wp_script_add_data('a11yhubbr-contact', 'strategy', 'defer');
        wp_localize_script('a11yhubbr-contact', 'hubContactData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
        ));
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


function a11yhubbr_optimize_frontend_head() {
    if (!a11yhubbr_is_frontend_runtime_request()) {
        return;
    }

    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
}
add_action('init', 'a11yhubbr_optimize_frontend_head');


function a11yhubbr_disable_emoji_tinymce($plugins) {
    if (!is_array($plugins)) {
        return array();
    }

    return array_diff($plugins, array('wpemoji'));
}
add_filter('tiny_mce_plugins', 'a11yhubbr_disable_emoji_tinymce');


function a11yhubbr_optimize_frontend_styles() {
    if (!a11yhubbr_is_frontend_runtime_request()) {
        return;
    }

    wp_dequeue_style('sib-front-css');
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('classic-theme-styles');
}
add_action('wp_enqueue_scripts', 'a11yhubbr_optimize_frontend_styles', 100);
add_action('wp_print_styles', 'a11yhubbr_optimize_frontend_styles', 100);


function a11yhubbr_disable_block_frontend_overhead() {
    if (!a11yhubbr_is_frontend_runtime_request()) {
        return;
    }

    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
    remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
}
add_action('wp_loaded', 'a11yhubbr_disable_block_frontend_overhead');


function a11yhubbr_optimize_frontend_scripts() {
    if (!a11yhubbr_is_frontend_runtime_request()) {
        return;
    }

    wp_dequeue_script('sib-front-js');
    wp_dequeue_script('jquery');
    wp_dequeue_script('jquery-core');
    wp_dequeue_script('jquery-migrate');
}
add_action('wp_enqueue_scripts', 'a11yhubbr_optimize_frontend_scripts', 100);
add_action('wp_print_scripts', 'a11yhubbr_optimize_frontend_scripts', 100);


function a11yhubbr_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
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
