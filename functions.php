<?php

if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_enqueue_assets() {
    $theme = wp_get_theme();
    $css_path = get_template_directory() . '/assets/css/theme.css';
    $js_path = get_template_directory() . '/assets/js/forms.js';
    $css_ver = file_exists($css_path) ? (string) filemtime($css_path) : $theme->get('Version');
    $js_ver = file_exists($js_path) ? (string) filemtime($js_path) : $theme->get('Version');

    wp_enqueue_style(
        'a11yhubbr-style',
        get_stylesheet_uri(),
        array(),
        $theme->get('Version')
    );

    wp_enqueue_style(
        'a11yhubbr-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        array(),
        '6.5.2'
    );

    wp_enqueue_style(
        'a11yhubbr-custom',
        get_template_directory_uri() . '/assets/css/theme.css',
        array('a11yhubbr-style', 'a11yhubbr-fontawesome'),
        $css_ver
    );

    wp_enqueue_script(
        'a11yhubbr-forms',
        get_template_directory_uri() . '/assets/js/forms.js',
        array(),
        $js_ver,
        true
    );
}
add_action('wp_enqueue_scripts', 'a11yhubbr_enqueue_assets');

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

function a11yhubbr_get_page_url_by_template($template, $fallback_path = '/') {
    static $cache = array();
    $template = (string) $template;
    $fallback = home_url($fallback_path);

    if ($template === '') {
        return $fallback;
    }

    if (isset($cache[$template])) {
        return $cache[$template];
    }

    $pages = get_posts(array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'numberposts' => 1,
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
        'fields' => 'ids',
    ));

    if (!empty($pages)) {
        $url = get_permalink((int) $pages[0]);
        if (is_string($url) && $url !== '') {
            $cache[$template] = $url;
            return $cache[$template];
        }
    }

    $cache[$template] = $fallback;
    return $cache[$template];
}

function a11yhubbr_get_submit_content_url() {
    return a11yhubbr_get_page_url_by_template('page-submeter-conteudo.php', '/submeter/submeter-conteudo');
}

function a11yhubbr_get_submit_event_url() {
    return a11yhubbr_get_page_url_by_template('page-submeter-eventos.php', '/submeter/submeter-eventos');
}

function a11yhubbr_get_submit_profile_url() {
    return a11yhubbr_get_page_url_by_template('page-submeter-perfil.php', '/submeter/submeter-perfil');
}

function a11yhubbr_get_accessibility_page_url() {
    return a11yhubbr_get_page_url_by_template('page-acessibilidade.php', '/acessibilidade');
}

function a11yhubbr_get_terms_page_url() {
    return a11yhubbr_get_page_url_by_template('page-termos-de-uso.php', '/termos-de-uso');
}

function a11yhubbr_get_privacy_page_url() {
    return a11yhubbr_get_page_url_by_template('page-politica-de-privacidade.php', '/politica-de-privacidade');
}

function a11yhubbr_get_search_page_url() {
    return a11yhubbr_get_page_url_by_template('page-busca.php', '/busca');
}

function a11yhubbr_is_submit_path_active() {
    $request_path = parse_url(isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/', PHP_URL_PATH);
    $request_path = is_string($request_path) ? trim($request_path, '/') : '';
    return ($request_path === 'submeter' || strpos($request_path, 'submeter/') === 0);
}

function a11yhubbr_nav_menu_classes($classes, $item, $args) {
    if (!isset($args->theme_location) || $args->theme_location !== 'primary') {
        return $classes;
    }

    if (!a11yhubbr_is_submit_path_active()) {
        return $classes;
    }

    $url_path = parse_url((string) $item->url, PHP_URL_PATH);
    $url_path = is_string($url_path) ? trim($url_path, '/') : '';

    if ($url_path === 'submeter') {
        $classes[] = 'current-menu-item';
        $classes[] = 'current_page_item';
        $classes[] = 'current-menu-ancestor';
    }

    return array_values(array_unique($classes));
}
add_filter('nav_menu_css_class', 'a11yhubbr_nav_menu_classes', 10, 3);

function a11yhubbr_filter_primary_menu_items($items, $args) {
    if (!isset($args->theme_location) || $args->theme_location !== 'primary' || !is_array($items)) {
        return $items;
    }

    $filtered = array();
    foreach ($items as $item) {
        $url_path = parse_url((string) $item->url, PHP_URL_PATH);
        $url_path = is_string($url_path) ? trim($url_path, '/') : '';
        if ($url_path === 'submeter') {
            continue;
        }
        $filtered[] = $item;
    }

    return $filtered;
}
add_filter('wp_nav_menu_objects', 'a11yhubbr_filter_primary_menu_items', 10, 2);

function a11yhubbr_get_content_type_map() {
    return array(
        'artigos' => array('label' => 'Artigos', 'icon' => 'fa-regular fa-file-lines'),
        'comunidades' => array('label' => 'Comunidades', 'icon' => 'fa-solid fa-users'),
        'cursos-materiais' => array('label' => 'Livros e Materiais', 'icon' => 'fa-solid fa-book-open'),
        'eventos' => array('label' => 'Eventos', 'icon' => 'fa-regular fa-calendar'),
        'ferramentas' => array('label' => 'Ferramentas', 'icon' => 'fa-solid fa-wrench'),
        'multimidia' => array('label' => 'Multimidia', 'icon' => 'fa-solid fa-headphones'),
        'sites-sistemas' => array('label' => 'Sites e Sistemas', 'icon' => 'fa-solid fa-desktop'),
    );
}

function a11yhubbr_get_content_type_by_slug($slug) {
    $types = a11yhubbr_get_content_type_map();
    $normalized = sanitize_title((string) $slug);
    return $types[$normalized] ?? null;
}

function a11yhubbr_get_content_type_slug_from_input($value) {
    $normalized = sanitize_title((string) $value);
    $types = a11yhubbr_get_content_type_map();

    if (isset($types[$normalized])) {
        return $normalized;
    }

    foreach ($types as $slug => $type) {
        if (sanitize_title($type['label']) === $normalized) {
            return $slug;
        }
    }

    return '';
}

function a11yhubbr_find_posts_by_term($post_type, $term, $meta_keys = array(), $post_status = 'publish') {
    $normalized_term = sanitize_text_field((string) $term);
    if ($normalized_term === '') {
        return array();
    }

    $max_posts = (int) apply_filters('a11yhubbr_search_max_posts', 300);
    if ($max_posts < 20) {
        $max_posts = 20;
    }

    $base_args = array(
        'post_type' => $post_type,
        'post_status' => $post_status,
        'posts_per_page' => $max_posts,
        'fields' => 'ids',
    );

    $text_ids = get_posts(array_merge($base_args, array(
        's' => $normalized_term,
    )));

    $meta_ids = array();
    if (!empty($meta_keys)) {
        $meta_query = array('relation' => 'OR');
        foreach ($meta_keys as $meta_key) {
            $meta_query[] = array(
                'key' => sanitize_key((string) $meta_key),
                'value' => $normalized_term,
                'compare' => 'LIKE',
            );
        }

        $meta_ids = get_posts(array_merge($base_args, array(
            'meta_query' => $meta_query,
        )));
    }

    return array_values(array_unique(array_map('intval', array_merge($text_ids, $meta_ids))));
}

function a11yhubbr_parse_tags_from_input($raw_tags) {
    $parts = array_map('trim', explode(',', (string) $raw_tags));
    $parts = array_filter($parts, static function ($value) {
        return $value !== '';
    });
    $parts = array_map('sanitize_text_field', $parts);
    return array_values(array_unique($parts));
}

function a11yhubbr_migrate_legacy_content_type_meta_to_category() {
    if (!is_admin()) {
        return;
    }

    if (get_option('a11yhubbr_content_type_migration_done') === '1') {
        return;
    }

    $post_ids = get_posts(array(
        'post_type' => 'post',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => '_a11yhubbr_content_type',
    ));

    if (empty($post_ids)) {
        update_option('a11yhubbr_content_type_migration_done', '1', false);
        return;
    }

    foreach ($post_ids as $post_id) {
        $legacy_type = get_post_meta($post_id, '_a11yhubbr_content_type', true);
        $slug = a11yhubbr_get_content_type_slug_from_input($legacy_type);
        if ($slug === '') {
            continue;
        }

        $existing = wp_get_post_terms($post_id, 'category', array('fields' => 'slugs'));
        if (is_wp_error($existing)) {
            continue;
        }
        if (in_array($slug, $existing, true)) {
            continue;
        }

        $term = get_term_by('slug', $slug, 'category');
        if (!$term || is_wp_error($term)) {
            continue;
        }

        wp_set_post_terms($post_id, array((int) $term->term_id), 'category', true);
    }

    update_option('a11yhubbr_content_type_migration_done', '1', false);
}
add_action('admin_init', 'a11yhubbr_migrate_legacy_content_type_meta_to_category');

function a11yhubbr_rename_books_category_once() {
    if (get_option('a11yhubbr_books_category_renamed_v1') === '1') {
        return;
    }

    $term = get_term_by('slug', 'cursos-materiais', 'category');
    if ($term && !is_wp_error($term)) {
        wp_update_term((int) $term->term_id, 'category', array(
            'name' => 'Livros e Materiais',
            'description' => 'Livros, guias e materiais de referencia sobre acessibilidade digital.',
        ));
    }

    update_option('a11yhubbr_books_category_renamed_v1', '1', false);
}
add_action('init', 'a11yhubbr_rename_books_category_once');

function a11yhubbr_render_page_header($args = array()) {
    $defaults = array(
        'breadcrumbs' => array(),
        'icon' => 'fa-regular fa-file-lines',
        'title' => '',
        'summary' => '',
        'use_page_context' => true,
    );

    $data = wp_parse_args($args, $defaults);
    $icon_class = preg_replace('/[^a-z0-9\-\s]/i', '', (string) $data['icon']);
    $title = (string) $data['title'];
    $summary = (string) $data['summary'];
    $use_page_context = !empty($data['use_page_context']);
    $breadcrumbs = is_array($data['breadcrumbs']) ? $data['breadcrumbs'] : array();

    if ($use_page_context) {
        $queried_id = get_queried_object_id();
        if (!empty($queried_id)) {
            $page_title = get_the_title($queried_id);
            if (is_string($page_title) && $page_title !== '') {
                $title = $page_title;
            }

            $page_summary = get_the_excerpt($queried_id);
            if (is_string($page_summary)) {
                $page_summary = trim(wp_strip_all_tags($page_summary));
                if ($page_summary !== '') {
                    $summary = $page_summary;
                }
            }
        }
    }

    echo '<section class="a11yhubbr-page-header a11yhubbr-home-v2-hero">';
    echo '<div class="a11yhubbr-container">';

    if (!empty($breadcrumbs)) {
        echo '<nav class="a11yhubbr-page-breadcrumb" aria-label="Breadcrumb">';

        foreach ($breadcrumbs as $index => $item) {
            $label = isset($item['label']) ? (string) $item['label'] : '';
            $url = isset($item['url']) ? (string) $item['url'] : '';
            $is_last = ($index === count($breadcrumbs) - 1);

            if ($index > 0) {
                echo '<span aria-hidden="true">&rsaquo;</span>';
            }

            if (!$is_last && !empty($url)) {
                echo '<a href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
            } else {
                echo '<span aria-current="page">' . esc_html($label) . '</span>';
            }
        }

        echo '</nav>';
    }

    echo '<h1 class="a11yhubbr-page-header-title">';
    echo '<span class="a11yhubbr-page-header-icon" aria-hidden="true"><i class="' . esc_attr($icon_class) . '"></i></span>';
    echo esc_html($title);
    echo '</h1>';

    if (!empty($summary)) {
        echo '<p class="a11yhubbr-page-header-summary">' . esc_html($summary) . '</p>';
    }

    echo '</div>';
    echo '</section>';
}

function a11yhubbr_sync_pages_title_excerpt_once() {
    if (get_option('a11yhubbr_pages_title_excerpt_synced_v4') === '1') {
        return;
    }

    $map = array(
        'page-conteudos.php' => array(
            'title' => 'Conteudos',
            'excerpt' => 'Explore recursos organizados por tipo para facilitar sua busca por conhecimento em acessibilidade.',
        ),
        'page-rede.php' => array(
            'title' => 'Rede',
            'excerpt' => 'Profissionais e organizações que atuam com acessibilidade digital.',
        ),
        'page-eventos.php' => array(
            'title' => 'Eventos',
            'excerpt' => 'Conferencias, workshops, meetups e webinars sobre acessibilidade digital.',
        ),
        'page-submeter.php' => array(
            'title' => 'Submeter',
            'excerpt' => 'Envie conteudos, eventos e perfis para fortalecer a comunidade.',
        ),
        'page-submeter-conteudo.php' => array(
            'title' => 'Submeter conteudo',
            'excerpt' => 'Compartilhe artigos, ferramentas, livros e outros recursos sobre acessibilidade.',
        ),
        'page-submeter-eventos.php' => array(
            'title' => 'Submeter evento',
            'excerpt' => 'Divulgue workshops, conferencias, meetups e webinars sobre acessibilidade.',
        ),
        'page-submeter-perfil.php' => array(
            'title' => 'Submeter perfil',
            'excerpt' => 'Cadastre profissionais e organizacoes da comunidade de acessibilidade.',
        ),
        'page-sobre.php' => array(
            'title' => 'Sobre a A11YBR',
            'excerpt' => 'O que somos, por que existimos e como funciona a plataforma.',
        ),
        'page-diretrizes.php' => array(
            'title' => 'Diretrizes da comunidade',
            'excerpt' => 'Critérios de publicação, padrões de qualidade e regras de convivência da plataforma.',
        ),
        'page-contato.php' => array(
            'title' => 'Contato',
            'excerpt' => 'Canal para sugerir alterações, reportar informações desatualizadas e tirar dévidas.',
        ),
    );

    foreach ($map as $template => $payload) {
        $pages = get_posts(array(
            'post_type' => 'page',
            'post_status' => array('publish', 'draft', 'pending', 'private'),
            'numberposts' => -1,
            'meta_key' => '_wp_page_template',
            'meta_value' => $template,
            'fields' => 'ids',
        ));

        if (empty($pages)) {
            continue;
        }

        foreach ($pages as $page_id) {
            wp_update_post(array(
                'ID' => (int) $page_id,
                'post_title' => $payload['title'],
                'post_excerpt' => $payload['excerpt'],
            ));
        }
    }

    update_option('a11yhubbr_pages_title_excerpt_synced_v4', '1', false);
}
add_action('init', 'a11yhubbr_sync_pages_title_excerpt_once');

function a11yhubbr_ensure_diretrizes_page_once() {
    if (get_option('a11yhubbr_diretrizes_page_created_v1') === '1') {
        return;
    }

    $page = get_page_by_path('diretrizes-da-comunidade');
    if (!($page instanceof WP_Post)) {
        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Diretrizes da comunidade',
            'post_name' => 'diretrizes-da-comunidade',
            'post_excerpt' => 'Critérios de publicação, padrões de qualidade e regras de convivência da plataforma.',
        ));
        if (!is_wp_error($page_id) && $page_id > 0) {
            update_post_meta((int) $page_id, '_wp_page_template', 'page-diretrizes.php');
        }
    } else {
        update_post_meta((int) $page->ID, '_wp_page_template', 'page-diretrizes.php');
    }

    update_option('a11yhubbr_diretrizes_page_created_v1', '1', false);
}
add_action('init', 'a11yhubbr_ensure_diretrizes_page_once');

function a11yhubbr_ensure_contato_page_once() {
    if (get_option('a11yhubbr_contato_page_created_v1') === '1') {
        return;
    }

    $page = get_page_by_path('contato');
    if (!($page instanceof WP_Post)) {
        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Contato',
            'post_name' => 'contato',
            'post_excerpt' => 'Canal para sugerir alterações, reportar informações desatualizadas e tirar dévidas.',
        ));
        if (!is_wp_error($page_id) && $page_id > 0) {
            update_post_meta((int) $page_id, '_wp_page_template', 'page-contato.php');
        }
    } else {
        update_post_meta((int) $page->ID, '_wp_page_template', 'page-contato.php');
    }

    update_option('a11yhubbr_contato_page_created_v1', '1', false);
}
add_action('init', 'a11yhubbr_ensure_contato_page_once');

function a11yhubbr_rename_community_to_rede_once() {
    if (get_option('a11yhubbr_rename_rede_done_v4') === '1') {
        return;
    }

    $pages = get_posts(array(
        'post_type' => 'page',
        'post_status' => array('publish', 'draft', 'pending', 'private'),
        'numberposts' => -1,
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-rede.php',
    ));
    $legacy_pages = get_posts(array(
        'post_type' => 'page',
        'post_status' => array('publish', 'draft', 'pending', 'private'),
        'numberposts' => -1,
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-comunidade.php',
    ));
    if (!empty($legacy_pages)) {
        $pages = array_merge($pages, $legacy_pages);
    }

    $target_page_ids = array();
    $page_by_path = get_page_by_path('comunidade');
    if ($page_by_path instanceof WP_Post) {
        $pages[] = $page_by_path;
    }

    $pages_by_title = get_posts(array(
        'post_type' => 'page',
        'post_status' => array('publish', 'draft', 'pending', 'private'),
        'numberposts' => -1,
        'title' => 'Comunidade',
    ));
    if (!empty($pages_by_title)) {
        $pages = array_merge($pages, $pages_by_title);
    }

    $unique_pages = array();
    foreach ($pages as $page) {
        if (!($page instanceof WP_Post)) {
            continue;
        }
        $unique_pages[(int) $page->ID] = $page;
    }

    foreach ($unique_pages as $page) {
        $target_page_ids[] = (int) $page->ID;
        wp_update_post(array(
            'ID' => (int) $page->ID,
            'post_title' => 'Rede',
            'post_name' => 'rede',
            'post_excerpt' => 'Profissionais e organizações que atuam com acessibilidade digital.',
        ));
        update_post_meta((int) $page->ID, '_wp_page_template', 'page-rede.php');
    }

    $page_rede = get_page_by_path('rede');
    if ($page_rede instanceof WP_Post) {
        $target_page_ids[] = (int) $page_rede->ID;
        wp_update_post(array(
            'ID' => (int) $page_rede->ID,
            'post_title' => 'Rede',
            'post_excerpt' => 'Profissionais e organizações que atuam com acessibilidade digital.',
        ));
        update_post_meta((int) $page_rede->ID, '_wp_page_template', 'page-rede.php');
    }

    $menu_ids = wp_get_nav_menus(array('fields' => 'ids'));
    if (!empty($menu_ids)) {
        foreach ($menu_ids as $menu_id) {
            $items = wp_get_nav_menu_items((int) $menu_id, array('post_status' => 'any'));
            if (empty($items)) {
                continue;
            }

            foreach ($items as $item) {
                $should_update = false;
                if ((string) $item->type === 'post_type' && (string) $item->object === 'page' && in_array((int) $item->object_id, $target_page_ids, true)) {
                    $should_update = true;
                } elseif ((string) $item->type === 'custom' && strpos((string) $item->url, '/comunidade') !== false) {
                    $should_update = true;
                }

                if (!$should_update) {
                    continue;
                }

                wp_update_nav_menu_item((int) $menu_id, (int) $item->ID, array(
                    'menu-item-title' => 'Rede',
                    'menu-item-url' => home_url('/rede'),
                    'menu-item-status' => 'publish',
                ));
            }
        }
    }

    update_option('a11yhubbr_rename_rede_done_v4', '1', false);
}
add_action('init', 'a11yhubbr_rename_community_to_rede_once');

function a11yhubbr_security_settings_register() {
    register_setting('a11yhubbr_security', 'a11yhubbr_turnstile_enabled', array(
        'type' => 'string',
        'sanitize_callback' => static function ($value) {
            return $value === '1' ? '1' : '0';
        },
        'default' => '0',
    ));

    register_setting('a11yhubbr_security', 'a11yhubbr_turnstile_site_key', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ));

    register_setting('a11yhubbr_security', 'a11yhubbr_turnstile_secret_key', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ));
}
add_action('admin_init', 'a11yhubbr_security_settings_register');

function a11yhubbr_security_menu() {
    add_options_page(
        'A11YBR Seguranca',
        'A11YBR Seguranca',
        'manage_options',
        'a11yhubbr-security',
        'a11yhubbr_render_security_page'
    );
}
add_action('admin_menu', 'a11yhubbr_security_menu');

function a11yhubbr_get_turnstile_site_key() {
    if (defined('A11YHUBBR_TURNSTILE_SITE_KEY') && A11YHUBBR_TURNSTILE_SITE_KEY) {
        return (string) A11YHUBBR_TURNSTILE_SITE_KEY;
    }
    return (string) get_option('a11yhubbr_turnstile_site_key', '');
}

function a11yhubbr_get_turnstile_secret_key() {
    if (defined('A11YHUBBR_TURNSTILE_SECRET_KEY') && A11YHUBBR_TURNSTILE_SECRET_KEY) {
        return (string) A11YHUBBR_TURNSTILE_SECRET_KEY;
    }
    return (string) get_option('a11yhubbr_turnstile_secret_key', '');
}

function a11yhubbr_is_turnstile_enabled() {
    $enabled = (string) get_option('a11yhubbr_turnstile_enabled', '0') === '1';
    return $enabled && a11yhubbr_get_turnstile_site_key() !== '' && a11yhubbr_get_turnstile_secret_key() !== '';
}

function a11yhubbr_render_security_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
      <h1>A11YBR Seguranca</h1>
      <p>Ative o Cloudflare Turnstile para reduzir spam automatizado nos formularios de submissao.</p>
      <form method="post" action="options.php">
        <?php settings_fields('a11yhubbr_security'); ?>
        <table class="form-table" role="presentation">
          <tr>
            <th scope="row">Ativar Turnstile</th>
            <td>
              <label>
                <input type="checkbox" name="a11yhubbr_turnstile_enabled" value="1" <?php checked(get_option('a11yhubbr_turnstile_enabled', '0'), '1'); ?>>
                Exigir verificacao humana nos formularios de submissao
              </label>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="a11yhubbr_turnstile_site_key">Site key</label></th>
            <td><input type="text" class="regular-text" id="a11yhubbr_turnstile_site_key" name="a11yhubbr_turnstile_site_key" value="<?php echo esc_attr((string) get_option('a11yhubbr_turnstile_site_key', '')); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="a11yhubbr_turnstile_secret_key">Secret key</label></th>
            <td><input type="password" class="regular-text" id="a11yhubbr_turnstile_secret_key" name="a11yhubbr_turnstile_secret_key" value="<?php echo esc_attr((string) get_option('a11yhubbr_turnstile_secret_key', '')); ?>"></td>
          </tr>
        </table>
        <?php submit_button('Salvar configuracoes'); ?>
      </form>
    </div>
    <?php
}

function a11yhubbr_render_human_check_field() {
    if (!a11yhubbr_is_turnstile_enabled()) {
        return;
    }

    $site_key = a11yhubbr_get_turnstile_site_key();
    if ($site_key === '') {
        return;
    }
    ?>
    <div class="a11yhubbr-human-check">
      <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
      <div class="cf-turnstile" data-sitekey="<?php echo esc_attr($site_key); ?>"></div>
      <p class="a11yhubbr-help">Confirmacao de seguranca para evitar envios automatizados.</p>
    </div>
    <?php
}

function a11yhubbr_turnstile_is_valid() {
    if (!a11yhubbr_is_turnstile_enabled()) {
        return true;
    }

    $token = isset($_POST['cf-turnstile-response']) ? sanitize_text_field(wp_unslash($_POST['cf-turnstile-response'])) : '';
    if ($token === '') {
        return false;
    }

    $secret = a11yhubbr_get_turnstile_secret_key();
    if ($secret === '') {
        return false;
    }

    $body = array(
        'secret' => $secret,
        'response' => $token,
    );
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        $body['remoteip'] = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
    }

    $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
        'timeout' => 8,
        'body' => $body,
    ));

    if (is_wp_error($response)) {
        return false;
    }

    $payload = json_decode((string) wp_remote_retrieve_body($response), true);
    return is_array($payload) && !empty($payload['success']);
}

function a11yhubbr_register_submission_cpts() {
    register_post_type('a11y_evento', array(
        'labels' => array(
            'name' => __('Eventos (Submissoes)', 'a11yhubbr'),
            'singular_name' => __('Evento (Submissao)', 'a11yhubbr'),
            'menu_name' => __('Eventos', 'a11yhubbr'),
            'add_new_item' => __('Adicionar evento', 'a11yhubbr'),
            'edit_item' => __('Editar evento', 'a11yhubbr'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'show_in_admin_bar' => false,
        'supports' => array('title', 'editor', 'custom-fields'),
        'taxonomies' => array('post_tag'),
        'has_archive' => false,
        'rewrite' => array('slug' => 'evento', 'with_front' => false),
        'menu_position' => 21,
        'menu_icon' => 'dashicons-calendar-alt',
    ));

    register_post_type('a11y_perfil', array(
        'labels' => array(
            'name' => __('Perfis (Submissoes)', 'a11yhubbr'),
            'singular_name' => __('Perfil (Submissao)', 'a11yhubbr'),
            'menu_name' => __('Perfis', 'a11yhubbr'),
            'add_new_item' => __('Adicionar perfil', 'a11yhubbr'),
            'edit_item' => __('Editar perfil', 'a11yhubbr'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'show_in_admin_bar' => false,
        'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'),
        'taxonomies' => array('category', 'post_tag'),
        'has_archive' => false,
        'rewrite' => array('slug' => 'perfil', 'with_front' => false),
        'menu_position' => 22,
        'menu_icon' => 'dashicons-id',
    ));
}
add_action('init', 'a11yhubbr_register_submission_cpts');

function a11yhubbr_flush_rewrite_rules_once() {
    if (get_option('a11yhubbr_rewrite_flushed_v1') === '1') {
        return;
    }
    flush_rewrite_rules(false);
    update_option('a11yhubbr_rewrite_flushed_v1', '1', false);
}
add_action('init', 'a11yhubbr_flush_rewrite_rules_once', 99);

function a11yhubbr_get_submission_config() {
    return array(
        'content' => array(
            'button' => 'a11yhubbr_content_submit',
            'nonce_action' => 'a11yhubbr_content',
            'label' => 'Submeter conteudo',
        ),
        'event' => array(
            'button' => 'a11yhubbr_event_submit',
            'nonce_action' => 'a11yhubbr_event',
            'label' => 'Submeter eventos',
        ),
        'profile' => array(
            'button' => 'a11yhubbr_profile_submit',
            'nonce_action' => 'a11yhubbr_profile',
            'label' => 'Submeter perfil',
        ),
    );
}

function a11yhubbr_detect_form_type() {
    $config = a11yhubbr_get_submission_config();

    foreach ($config as $type => $item) {
        if (isset($_POST[$item['button']])) {
            return $type;
        }
    }

    return null;
}

function a11yhubbr_sanitize_submission_data($type) {
    $raw = wp_unslash($_POST);

    if ($type === 'content') {
        $type_slug = a11yhubbr_get_content_type_slug_from_input($raw['type'] ?? '');
        return array(
            'type' => $type_slug,
            'title' => sanitize_text_field($raw['title'] ?? ''),
            'description' => sanitize_textarea_field($raw['description'] ?? ''),
            'author' => sanitize_text_field($raw['author'] ?? ''),
            'organization' => sanitize_text_field($raw['organization'] ?? ''),
            'link' => esc_url_raw($raw['link'] ?? ''),
            'tags' => a11yhubbr_parse_tags_from_input($raw['tags'] ?? ''),
            'email' => sanitize_email($raw['email'] ?? ''),
        );
    }

    if ($type === 'event') {
        $starts = isset($raw['slot_start']) && is_array($raw['slot_start']) ? $raw['slot_start'] : array();
        $ends   = isset($raw['slot_end']) && is_array($raw['slot_end']) ? $raw['slot_end'] : array();
        $slots  = array();

        $count = max(count($starts), count($ends));
        for ($i = 0; $i < $count; $i++) {
            $start = sanitize_text_field($starts[$i] ?? '');
            $end   = sanitize_text_field($ends[$i] ?? '');
            if ($start !== '' || $end !== '') {
                $slots[] = array('start' => $start, 'end' => $end);
            }
        }

        return array(
            'modality' => sanitize_text_field($raw['modality'] ?? ''),
            'event_type' => sanitize_text_field($raw['event_type'] ?? ''),
            'title' => sanitize_text_field($raw['title'] ?? ''),
            'location' => sanitize_text_field($raw['location'] ?? ''),
            'description' => sanitize_textarea_field($raw['description'] ?? ''),
            'organizer' => sanitize_text_field($raw['organizer'] ?? ''),
            'link' => esc_url_raw($raw['link'] ?? ''),
            'tags' => a11yhubbr_parse_tags_from_input($raw['tags'] ?? ''),
            'email' => sanitize_email($raw['email'] ?? ''),
            'slots' => $slots,
        );
    }

    return array(
        'profile_type' => sanitize_text_field($raw['profile_type'] ?? ''),
        'name' => sanitize_text_field($raw['name'] ?? ''),
        'location' => sanitize_text_field($raw['location'] ?? ''),
        'description' => sanitize_textarea_field($raw['description'] ?? ''),
        'role' => sanitize_text_field($raw['role'] ?? ''),
        'website' => esc_url_raw($raw['website'] ?? ''),
        'tags' => a11yhubbr_parse_tags_from_input($raw['tags'] ?? ''),
        'social_links' => (static function () use ($raw) {
            $networks = isset($raw['social_network']) && is_array($raw['social_network']) ? $raw['social_network'] : array();
            $urls = isset($raw['social_url']) && is_array($raw['social_url']) ? $raw['social_url'] : array();
            $items = array();

            $count = max(count($networks), count($urls));
            for ($i = 0; $i < $count; $i++) {
                $network = sanitize_key($networks[$i] ?? '');
                $url = esc_url_raw($urls[$i] ?? '');
                if ($url === '') {
                    continue;
                }

                $items[] = array(
                    'network' => $network !== '' ? $network : 'website',
                    'url' => $url,
                );
            }

            return $items;
        })(),
        'email' => sanitize_email($raw['email'] ?? ''),
        'profile_image_name' => isset($_FILES['profile_image']['name'])
            ? sanitize_file_name(wp_unslash($_FILES['profile_image']['name']))
            : '',
    );
}

function a11yhubbr_create_pending_content_post($data) {
    $title = $data['title'] !== '' ? $data['title'] : 'Submissao sem titulo';
    $type_slug = a11yhubbr_get_content_type_slug_from_input($data['type'] ?? '');
    if ($type_slug === '') {
        $type_slug = 'artigos';
    }
    $type_data = a11yhubbr_get_content_type_by_slug($type_slug);
    $type_label = $type_data['label'] ?? 'Artigos';

    $body = array();
    $body[] = $data['description'];
    $body[] = '';
    $body[] = 'Tipo de conteudo: ' . $type_label;

    if (!empty($data['author'])) {
        $body[] = '';
        $body[] = 'Autor indicado: ' . $data['author'];
    }

    if (!empty($data['organization'])) {
        $body[] = 'Organizacao: ' . $data['organization'];
    }

    if (!empty($data['link'])) {
        $body[] = 'Link de referencia: ' . $data['link'];
    }
    if (!empty($data['tags'])) {
        $body[] = 'Tags: ' . implode(', ', $data['tags']);
    }

    $postarr = array(
        'post_type'    => 'post',
        'post_status'  => 'pending',
        'post_title'   => $title,
        'post_excerpt' => wp_trim_words(wp_strip_all_tags($data['description']), 28),
        'post_content' => implode("\n", $body),
    );

    $post_id = wp_insert_post($postarr, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    $term = get_term_by('slug', $type_slug, 'category');
    if ($term && !is_wp_error($term)) {
        wp_set_post_terms($post_id, array((int) $term->term_id), 'category', false);
    }
    if (!empty($data['tags'])) {
        wp_set_post_terms($post_id, $data['tags'], 'post_tag', false);
    }

    // Compatibilidade temporéria com dados legados que liam meta.
    update_post_meta($post_id, '_a11yhubbr_content_type', $type_label);
    update_post_meta($post_id, '_a11yhubbr_submitter_name', $data['author']);
    update_post_meta($post_id, '_a11yhubbr_submitter_org', $data['organization']);
    update_post_meta($post_id, '_a11yhubbr_source_link', $data['link']);
    update_post_meta($post_id, '_a11yhubbr_contact_email', $data['email']);

    return $post_id;
}

function a11yhubbr_create_pending_event_post($data) {
    $title = $data['title'] !== '' ? $data['title'] : 'Evento sem titulo';

    $lines = array();
    $lines[] = $data['description'];
    $lines[] = '';
    $lines[] = 'Modalidade: ' . $data['modality'];
    $lines[] = 'Tipo de evento: ' . $data['event_type'];
    $lines[] = 'Localizacao: ' . $data['location'];
    $lines[] = 'Organizador: ' . $data['organizer'];
    if (!empty($data['link'])) {
        $lines[] = 'Link: ' . $data['link'];
    }
    if (!empty($data['slots'])) {
        $lines[] = '';
        $lines[] = 'Datas e horarios:';
        foreach ($data['slots'] as $index => $slot) {
            $lines[] = sprintf('%d) Inicio: %s | Fim: %s', $index + 1, $slot['start'], $slot['end']);
        }
    }
    if (!empty($data['tags'])) {
        $lines[] = 'Tags: ' . implode(', ', $data['tags']);
    }

    $postarr = array(
        'post_type'    => 'a11y_evento',
        'post_status'  => 'pending',
        'post_title'   => $title,
        'post_excerpt' => wp_trim_words(wp_strip_all_tags($data['description']), 28),
        'post_content' => implode("\n", $lines),
    );

    $post_id = wp_insert_post($postarr, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    update_post_meta($post_id, '_a11yhubbr_event_modality', $data['modality']);
    update_post_meta($post_id, '_a11yhubbr_event_type', $data['event_type']);
    update_post_meta($post_id, '_a11yhubbr_event_location', $data['location']);
    update_post_meta($post_id, '_a11yhubbr_event_organizer', $data['organizer']);
    update_post_meta($post_id, '_a11yhubbr_event_link', $data['link']);
    update_post_meta($post_id, '_a11yhubbr_contact_email', $data['email']);
    update_post_meta($post_id, '_a11yhubbr_event_slots', wp_json_encode($data['slots']));
    if (!empty($data['tags'])) {
        wp_set_post_terms($post_id, $data['tags'], 'post_tag', false);
    }

    return $post_id;
}

function a11yhubbr_create_pending_profile_post($data) {
    $title = $data['name'] !== '' ? $data['name'] : 'Perfil sem nome';

    $lines = array();
    $lines[] = $data['description'];
    $lines[] = '';
    $lines[] = 'Tipo de perfil: ' . $data['profile_type'];
    $lines[] = 'Nome/Organizacao: ' . $data['name'];
    $lines[] = 'Localizacao: ' . $data['location'];
    $lines[] = 'Cargo/Especialidade: ' . $data['role'];
    if (!empty($data['website'])) {
        $lines[] = 'Website: ' . $data['website'];
    }
    if (!empty($data['social_links']) && is_array($data['social_links'])) {
        $lines[] = 'Redes sociais:';
        foreach ($data['social_links'] as $item) {
            $network = sanitize_text_field($item['network'] ?? 'website');
            $url = esc_url_raw($item['url'] ?? '');
            if ($url !== '') {
                $lines[] = '- ' . $network . ': ' . $url;
            }
        }
    }
    if (!empty($data['profile_image_name'])) {
        $lines[] = 'Arquivo de foto: ' . $data['profile_image_name'];
    }
    if (!empty($data['tags'])) {
        $lines[] = 'Tags: ' . implode(', ', $data['tags']);
    }

    $postarr = array(
        'post_type'    => 'a11y_perfil',
        'post_status'  => 'pending',
        'post_title'   => $title,
        'post_excerpt' => wp_trim_words(wp_strip_all_tags($data['description']), 28),
        'post_content' => implode("\n", $lines),
    );

    $post_id = wp_insert_post($postarr, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    update_post_meta($post_id, '_a11yhubbr_profile_type', $data['profile_type']);
    update_post_meta($post_id, '_a11yhubbr_profile_location', $data['location']);
    update_post_meta($post_id, '_a11yhubbr_profile_role', $data['role']);
    update_post_meta($post_id, '_a11yhubbr_profile_website', $data['website']);
    update_post_meta($post_id, '_a11yhubbr_profile_social_links', wp_json_encode($data['social_links']));
    update_post_meta($post_id, '_a11yhubbr_profile_image_name', $data['profile_image_name']);
    update_post_meta($post_id, '_a11yhubbr_contact_email', $data['email']);
    if (!empty($data['tags'])) {
        wp_set_post_terms($post_id, $data['tags'], 'post_tag', false);
    }

    if (!empty($_FILES['profile_image']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $attachment_id = media_handle_upload('profile_image', $post_id);
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($post_id, (int) $attachment_id);
        }
    }

    return $post_id;
}

function a11yhubbr_build_email_message($type, $data) {
    if ($type === 'content') {
        $type_slug = a11yhubbr_get_content_type_slug_from_input($data['type'] ?? '');
        $type_data = a11yhubbr_get_content_type_by_slug($type_slug);
        $type_label = $type_data['label'] ?? 'Artigos';
        return implode("\n", array(
            'Nova submissao de conteudo',
            '--------------------------',
            'Tipo: ' . $type_label . ' (' . $type_slug . ')',
            'Titulo: ' . $data['title'],
            'Descricao: ' . $data['description'],
            'Autor: ' . $data['author'],
            'Organizacao: ' . $data['organization'],
            'Link: ' . $data['link'],
            'Tags: ' . implode(', ', $data['tags']),
            'Email de contato: ' . $data['email'],
        ));
    }

    if ($type === 'event') {
        $lines = array(
            'Nova submissao de evento',
            '------------------------',
            'Modalidade: ' . $data['modality'],
            'Tipo de evento: ' . $data['event_type'],
            'Titulo: ' . $data['title'],
            'Localizacao: ' . $data['location'],
            'Descricao: ' . $data['description'],
            'Organizador: ' . $data['organizer'],
            'Link: ' . $data['link'],
            'Tags: ' . implode(', ', $data['tags']),
            'Email de contato: ' . $data['email'],
            '',
            'Datas e horarios:',
        );

        foreach ($data['slots'] as $index => $slot) {
            $lines[] = sprintf('%d) Inicio: %s | Fim: %s', $index + 1, $slot['start'], $slot['end']);
        }

        return implode("\n", $lines);
    }

    return implode("\n", array(
        'Nova submissao de perfil',
        '------------------------',
        'Tipo de perfil: ' . $data['profile_type'],
        'Nome/Organizacao: ' . $data['name'],
        'Localizacao: ' . $data['location'],
        'Descricao: ' . $data['description'],
        'Cargo/Especialidade: ' . $data['role'],
        'Website: ' . $data['website'],
        'Tags: ' . implode(', ', $data['tags']),
        'Redes sociais: ' . (is_array($data['social_links'])
            ? implode(' | ', array_map(static function ($item) {
                return sanitize_text_field($item['network'] ?? 'website') . ': ' . esc_url_raw($item['url'] ?? '');
            }, $data['social_links']))
            : ''),
        'Arquivo de foto: ' . $data['profile_image_name'],
        'Email de contato: ' . $data['email'],
    ));
}

function a11yhubbr_send_submission_email($type, $label, $data) {
    $subject = sprintf('[a11yhubbr] Nova submissao: %s', $label);
    $message = a11yhubbr_build_email_message($type, $data);

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    if (!empty($data['email']) && is_email($data['email'])) {
        $headers[] = 'Reply-To: ' . $data['email'];
    }

    return wp_mail(get_option('admin_email'), $subject, $message, $headers);
}

function a11yhubbr_get_submission_fingerprint($type) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : 'unknown';
    return md5($type . '|' . $ip . '|' . $agent);
}

function a11yhubbr_is_submission_rate_limited($type) {
    $key = 'a11yhubbr_rl_' . a11yhubbr_get_submission_fingerprint($type);
    $attempts = (int) get_transient($key);
    return $attempts >= 10;
}

function a11yhubbr_track_submission_attempt($type) {
    $key = 'a11yhubbr_rl_' . a11yhubbr_get_submission_fingerprint($type);
    $attempts = (int) get_transient($key);
    set_transient($key, $attempts + 1, 15 * MINUTE_IN_SECONDS);
}

function a11yhubbr_is_submission_spam_request() {
    $honeypot = isset($_POST['a11yhubbr_company']) ? trim((string) wp_unslash($_POST['a11yhubbr_company'])) : '';
    if ($honeypot !== '') {
        return true;
    }

    $ts = isset($_POST['a11yhubbr_ts']) ? absint($_POST['a11yhubbr_ts']) : 0;
    if ($ts > 0) {
        $elapsed = time() - $ts;
        if ($elapsed < 3 || $elapsed > DAY_IN_SECONDS) {
            return true;
        }
    }

    if (!a11yhubbr_turnstile_is_valid()) {
        return true;
    }

    return false;
}

function a11yhubbr_get_redirect_target() {
    $posted = isset($_POST['a11yhubbr_redirect'])
        ? esc_url_raw(wp_unslash($_POST['a11yhubbr_redirect']))
        : '';

    if (!empty($posted)) {
        return wp_validate_redirect($posted, home_url('/'));
    }

    $referer = wp_get_referer();
    if ($referer) {
        return wp_validate_redirect($referer, home_url('/'));
    }

    return home_url('/');
}
function a11yhubbr_handle_form_submissions() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $type = a11yhubbr_detect_form_type();
    if (!$type) {
        return;
    }

    $config = a11yhubbr_get_submission_config();
    $item = $config[$type] ?? null;
    if (!$item) {
        return;
    }

    $nonce = isset($_POST['a11yhubbr_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['a11yhubbr_nonce']))
        : '';

    if (!$nonce || !wp_verify_nonce($nonce, $item['nonce_action'])) {
        $redirect_url = add_query_arg(
            array('a11yhubbr_form' => $type, 'a11yhubbr_status' => 'error'),
            a11yhubbr_get_redirect_target()
        );
        wp_safe_redirect($redirect_url);
        exit;
    }

    if (a11yhubbr_is_submission_spam_request() || a11yhubbr_is_submission_rate_limited($type)) {
        $redirect_url = add_query_arg(
            array('a11yhubbr_form' => $type, 'a11yhubbr_status' => 'error'),
            a11yhubbr_get_redirect_target()
        );
        wp_safe_redirect($redirect_url);
        exit;
    }

    a11yhubbr_track_submission_attempt($type);

    $data = a11yhubbr_sanitize_submission_data($type);
    $created = false;

    if ($type === 'content') {
        $result = a11yhubbr_create_pending_content_post($data);
        $created = !is_wp_error($result);
    } elseif ($type === 'event') {
        $result = a11yhubbr_create_pending_event_post($data);
        $created = !is_wp_error($result);
    } else {
        $result = a11yhubbr_create_pending_profile_post($data);
        $created = !is_wp_error($result);
    }

    if ($created) {
        a11yhubbr_send_submission_email($type, $item['label'], $data);
    }

    $redirect_url = add_query_arg(
        array(
            'a11yhubbr_form' => $type,
            'a11yhubbr_status' => $created ? 'success' : 'error',
        ),
        a11yhubbr_get_redirect_target()
    );

    wp_safe_redirect($redirect_url);
    exit;
}
add_action('init', 'a11yhubbr_handle_form_submissions');

/* Admin list columns for submissions */
function a11yhubbr_columns_post($columns) {
    $columns['a11y_content_type'] = __('Tipo', 'a11yhubbr');
    $columns['a11y_contact_email'] = __('Email de contato', 'a11yhubbr');
    $columns['a11y_source_link'] = __('Link', 'a11yhubbr');
    return $columns;
}
add_filter('manage_post_posts_columns', 'a11yhubbr_columns_post');

function a11yhubbr_columns_post_content($column, $post_id) {
    if ($column === 'a11y_content_type') {
        $terms = get_the_terms($post_id, 'category');
        if (!empty($terms) && !is_wp_error($terms)) {
            $labels = wp_list_pluck($terms, 'name');
            echo esc_html(implode(', ', $labels));
        }
    }

    if ($column === 'a11y_contact_email') {
        $email = get_post_meta($post_id, '_a11yhubbr_contact_email', true);
        if ($email) {
            echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
        }
    }

    if ($column === 'a11y_source_link') {
        $url = get_post_meta($post_id, '_a11yhubbr_source_link', true);
        if ($url) {
            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">Abrir</a>';
        }
    }
}
add_action('manage_post_posts_custom_column', 'a11yhubbr_columns_post_content', 10, 2);

function a11yhubbr_columns_event($columns) {
    $columns['a11y_event_type'] = __('Tipo', 'a11yhubbr');
    $columns['a11y_event_organizer'] = __('Organizador', 'a11yhubbr');
    $columns['a11y_event_location'] = __('Localizacao', 'a11yhubbr');
    $columns['a11y_contact_email'] = __('Email de contato', 'a11yhubbr');
    return $columns;
}
add_filter('manage_a11y_evento_posts_columns', 'a11yhubbr_columns_event');

function a11yhubbr_columns_event_content($column, $post_id) {
    if ($column === 'a11y_event_type') {
        echo esc_html(get_post_meta($post_id, '_a11yhubbr_event_type', true));
    }

    if ($column === 'a11y_event_organizer') {
        echo esc_html(get_post_meta($post_id, '_a11yhubbr_event_organizer', true));
    }

    if ($column === 'a11y_event_location') {
        echo esc_html(get_post_meta($post_id, '_a11yhubbr_event_location', true));
    }

    if ($column === 'a11y_contact_email') {
        $email = get_post_meta($post_id, '_a11yhubbr_contact_email', true);
        if ($email) {
            echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
        }
    }
}
add_action('manage_a11y_evento_posts_custom_column', 'a11yhubbr_columns_event_content', 10, 2);

function a11yhubbr_columns_profile($columns) {
    $columns['a11y_profile_type'] = __('Tipo de perfil', 'a11yhubbr');
    $columns['a11y_profile_role'] = __('Especialidade', 'a11yhubbr');
    $columns['a11y_profile_location'] = __('Localizacao', 'a11yhubbr');
    $columns['a11y_contact_email'] = __('Email de contato', 'a11yhubbr');
    return $columns;
}
add_filter('manage_a11y_perfil_posts_columns', 'a11yhubbr_columns_profile');

function a11yhubbr_columns_profile_content($column, $post_id) {
    if ($column === 'a11y_profile_type') {
        echo esc_html(get_post_meta($post_id, '_a11yhubbr_profile_type', true));
    }

    if ($column === 'a11y_profile_role') {
        echo esc_html(get_post_meta($post_id, '_a11yhubbr_profile_role', true));
    }

    if ($column === 'a11y_profile_location') {
        echo esc_html(get_post_meta($post_id, '_a11yhubbr_profile_location', true));
    }

    if ($column === 'a11y_contact_email') {
        $email = get_post_meta($post_id, '_a11yhubbr_contact_email', true);
        if ($email) {
            echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
        }
    }
}
add_action('manage_a11y_perfil_posts_custom_column', 'a11yhubbr_columns_profile_content', 10, 2);

function a11yhubbr_seed_legal_pages_once() {
    if (!is_admin()) {
        return;
    }

    if (get_option('a11yhubbr_legal_pages_seeded_v1') === '1') {
        return;
    }

    $pages = array(
        array(
            'slug' => 'acessibilidade',
            'title' => 'Declaração de Acessibilidade',
            'template' => 'page-acessibilidade.php',
            'excerpt' => 'Compromisso de acessibilidade, status de conformidade WCAG, limitações conhecidas e canal de feedback.',
        ),
        array(
            'slug' => 'termos-de-uso',
            'title' => 'Termos de Uso',
            'template' => 'page-termos-de-uso.php',
            'excerpt' => 'Regras de uso da plataforma, responsabilidades, moderação, propriedade intelectual e limitações.',
        ),
        array(
            'slug' => 'politica-de-privacidade',
            'title' => 'Política de Privacidade',
            'template' => 'page-politica-de-privacidade.php',
            'excerpt' => 'Como coletamos, usamos e protegemos dados pessoais em conformidade com a LGPD.',
        ),
        array(
            'slug' => 'busca',
            'title' => 'Busca',
            'template' => 'page-busca.php',
            'excerpt' => 'Busque conteúdos, eventos e perfis em um único lugar.',
        ),
    );

    foreach ($pages as $item) {
        $existing = get_page_by_path($item['slug'], OBJECT, 'page');
        if ($existing instanceof WP_Post) {
            if (get_page_template_slug((int) $existing->ID) !== $item['template']) {
                update_post_meta((int) $existing->ID, '_wp_page_template', $item['template']);
            }
            continue;
        }

        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => $item['title'],
            'post_name' => $item['slug'],
            'post_excerpt' => $item['excerpt'],
            'post_content' => '',
        ), true);

        if (!is_wp_error($page_id) && (int) $page_id > 0) {
            update_post_meta((int) $page_id, '_wp_page_template', $item['template']);
        }
    }

    update_option('a11yhubbr_legal_pages_seeded_v1', '1', false);
}
add_action('admin_init', 'a11yhubbr_seed_legal_pages_once');

function a11yhubbr_virtual_busca_template_fallback() {
    if (!is_404()) {
        return;
    }

    $request_path = parse_url(isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/', PHP_URL_PATH);
    $request_path = is_string($request_path) ? trim($request_path, '/') : '';

    if ($request_path !== 'busca') {
        return;
    }

    $template = locate_template('page-busca.php');
    if (!is_string($template) || $template === '' || !file_exists($template)) {
        return;
    }

    global $wp_query;
    if ($wp_query instanceof WP_Query) {
        $wp_query->is_404 = false;
        status_header(200);
    }

    include $template;
    exit;
}
add_action('template_redirect', 'a11yhubbr_virtual_busca_template_fallback', 1);






