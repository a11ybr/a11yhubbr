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
    return a11yhubbr_get_page_url_by_template('pages/page-submeter-conteudo.php', '/submeter/submeter-conteudo');
}

function a11yhubbr_get_submit_event_url() {
    return a11yhubbr_get_page_url_by_template('pages/page-submeter-eventos.php', '/submeter/submeter-eventos');
}

function a11yhubbr_get_submit_profile_url() {
    return a11yhubbr_get_page_url_by_template('pages/page-submeter-perfil.php', '/submeter/submeter-perfil');
}

function a11yhubbr_get_accessibility_page_url() {
    return a11yhubbr_get_page_url_by_template('pages/page-acessibilidade.php', '/acessibilidade');
}

function a11yhubbr_get_terms_page_url() {
    return a11yhubbr_get_page_url_by_template('pages/page-termos-de-uso.php', '/termos-de-uso');
}

function a11yhubbr_get_privacy_page_url() {
    return a11yhubbr_get_page_url_by_template('pages/page-politica-de-privacidade.php', '/politica-de-privacidade');
}

function a11yhubbr_get_search_page_url() {
    return a11yhubbr_get_page_url_by_template('pages/page-busca.php', '/busca');
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
    $legacy_aliases = array(
        'cursos-e-materiais' => 'cursos-materiais',
        'curso-e-material' => 'cursos-materiais',
    );

    if (isset($legacy_aliases[$normalized])) {
        return $legacy_aliases[$normalized];
    }

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

function a11yhubbr_get_social_icon_class($url, $network = '') {
    return function_exists('a11yhubbr_resolve_social_icon_class')
        ? a11yhubbr_resolve_social_icon_class($url, $network)
        : 'fa-solid fa-globe';
}

function a11yhubbr_get_social_network_key($url, $network = '') {
    return function_exists('a11yhubbr_resolve_social_network_key')
        ? a11yhubbr_resolve_social_network_key($url, $network)
        : 'website';
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

function a11yhubbr_migrate_legacy_network_posts_to_profiles() {
    if (!is_admin()) {
        return;
    }

    if (get_option('a11yhubbr_network_posts_to_profiles_done_v1') === '1') {
        return;
    }

    $source_posts = get_posts(array(
        'post_type' => 'post',
        'post_status' => array('publish', 'pending', 'draft'),
        'posts_per_page' => -1,
        'fields' => 'ids',
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => array('redes', 'comunidades'),
            ),
        ),
    ));

    if (empty($source_posts)) {
        update_option('a11yhubbr_network_posts_to_profiles_done_v1', '1', false);
        return;
    }

    foreach ($source_posts as $source_id) {
        $source_id = (int) $source_id;
        if ($source_id <= 0) {
            continue;
        }

        $already_migrated = (int) get_post_meta($source_id, '_a11yhubbr_migrated_profile_id', true);
        if ($already_migrated > 0) {
            continue;
        }

        $source = get_post($source_id);
        if (!$source instanceof WP_Post) {
            continue;
        }

        $status = in_array($source->post_status, array('publish', 'pending', 'draft'), true)
            ? $source->post_status
            : 'pending';

        $new_id = wp_insert_post(array(
            'post_type' => 'a11y_perfil',
            'post_status' => $status,
            'post_title' => get_the_title($source_id),
            'post_excerpt' => (string) $source->post_excerpt,
            'post_content' => (string) $source->post_content,
        ), true);

        if (is_wp_error($new_id)) {
            continue;
        }

        $source_link = (string) get_post_meta($source_id, '_a11yhubbr_source_link', true);
        $source_email = (string) get_post_meta($source_id, '_a11yhubbr_contact_email', true);
        $source_org = (string) get_post_meta($source_id, '_a11yhubbr_submitter_org', true);

        update_post_meta($new_id, '_a11yhubbr_profile_type', 'Comunidade');
        update_post_meta($new_id, '_a11yhubbr_profile_role', $source_org !== '' ? $source_org : 'Comunidade');
        update_post_meta($new_id, '_a11yhubbr_profile_location', '');
        update_post_meta($new_id, '_a11yhubbr_profile_website', $source_link);
        update_post_meta($new_id, '_a11yhubbr_contact_email', $source_email);
        update_post_meta($new_id, '_a11yhubbr_migrated_from_post', $source_id);
        update_post_meta($source_id, '_a11yhubbr_migrated_profile_id', (int) $new_id);

        $tags = wp_get_post_terms($source_id, 'post_tag', array('fields' => 'names'));
        if (!is_wp_error($tags) && !empty($tags)) {
            wp_set_post_terms($new_id, $tags, 'post_tag', false);
        }

        $thumb_id = get_post_thumbnail_id($source_id);
        if ($thumb_id) {
            set_post_thumbnail($new_id, (int) $thumb_id);
        }
    }

    update_option('a11yhubbr_network_posts_to_profiles_done_v1', '1', false);
}
add_action('admin_init', 'a11yhubbr_migrate_legacy_network_posts_to_profiles');

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

function a11yhubbr_get_header_context() {
    $queried_id = (int) get_queried_object_id();
    $template = $queried_id > 0 ? (string) get_page_template_slug($queried_id) : '';
    $post_type = $queried_id > 0 ? (string) get_post_type($queried_id) : '';

    $lineage_slugs = array();
    if ($queried_id > 0 && get_post_type($queried_id) === 'page') {
        $ancestor_ids = get_post_ancestors($queried_id);
        $ancestor_ids[] = $queried_id;
        foreach ($ancestor_ids as $ancestor_id) {
            $slug = (string) get_post_field('post_name', (int) $ancestor_id);
            if ($slug !== '') {
                $lineage_slugs[] = sanitize_title($slug);
            }
        }
    }

    if (
        $post_type === 'a11y_evento' ||
        is_post_type_archive('a11y_evento') ||
        $template === 'pages/page-eventos.php' ||
        $template === 'pages/page-submeter-eventos.php' ||
        in_array('eventos', $lineage_slugs, true)
    ) {
        return 'eventos';
    }

    if (
        $post_type === 'a11y_perfil' ||
        is_post_type_archive('a11y_perfil') ||
        $template === 'pages/page-rede.php' ||
        $template === 'pages/page-submeter-perfil.php' ||
        in_array('rede', $lineage_slugs, true) ||
        in_array('comunidade', $lineage_slugs, true)
    ) {
        return 'rede';
    }

    if (
        $post_type === 'post' ||
        $template === 'pages/page-conteudos.php' ||
        $template === 'pages/page-busca.php' ||
        $template === 'pages/page-submeter-conteudo.php' ||
        is_home() ||
        is_category() ||
        is_tag() ||
        is_search() ||
        in_array('conteudos', $lineage_slugs, true)
    ) {
        return 'conteudos';
    }

    return 'default';
}

function a11yhubbr_add_context_body_class($classes) {
    $context = a11yhubbr_get_header_context();
    if (!is_array($classes)) {
        $classes = array();
    }

    if (is_string($context) && $context !== '') {
        $classes[] = 'a11yhubbr-context-' . sanitize_html_class($context);
    }

    return array_values(array_unique($classes));
}
add_filter('body_class', 'a11yhubbr_add_context_body_class');

function a11yhubbr_render_page_header($args = array()) {
    $defaults = array(
        'breadcrumbs' => array(),
        'icon' => 'fa-regular fa-file-lines',
        'title' => '',
        'summary' => '',
        'use_page_context' => true,
        'context' => '',
    );

    $data = wp_parse_args($args, $defaults);
    $icon_class = preg_replace('/[^a-z0-9\-\s]/i', '', (string) $data['icon']);
    $title = (string) $data['title'];
    $summary = (string) $data['summary'];
    $use_page_context = !empty($data['use_page_context']);
    $breadcrumbs = is_array($data['breadcrumbs']) ? $data['breadcrumbs'] : array();
    $context = sanitize_key((string) $data['context']);
    if ($context === '' && $use_page_context) {
        $context = a11yhubbr_get_header_context();
    }

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

    $section_classes = array('a11yhubbr-page-header', 'a11yhubbr-home-hero');
    if ($context !== '' && $context !== 'default') {
        $section_classes[] = 'a11yhubbr-page-header--' . sanitize_html_class($context);
    }

    echo '<header class="' . esc_attr(implode(' ', $section_classes)) . '">';
    echo '<div class="a11yhubbr-container">';

    if (!empty($breadcrumbs)) {
        echo '<nav class="a11yhubbr-page-breadcrumb" aria-label="Breadcrumb">';

        foreach ($breadcrumbs as $index => $item) {
            $label = isset($item['label']) ? (string) $item['label'] : '';
            $url = isset($item['url']) ? (string) $item['url'] : '';
            $is_last = ($index === count($breadcrumbs) - 1);

            if ($index > 0) {
                echo '<span class="a11yhubbr-page-breadcrumb-separator" aria-hidden="true">&rsaquo;</span>';
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
    if ($icon_class !== '') {
        echo '<span class="a11yhubbr-page-header-icon" aria-hidden="true"><i class="' . esc_attr($icon_class) . '"></i></span>';
    }
    echo esc_html($title);
    echo '</h1>';

    if (!empty($summary)) {
        echo '<p class="a11yhubbr-page-header-summary">' . esc_html($summary) . '</p>';
    }

    echo '</div>';
    echo '</header>';
}

function a11yhubbr_sync_pages_title_excerpt_once() {
    if (get_option('a11yhubbr_pages_title_excerpt_synced_v4') === '1') {
        return;
    }

    $map = array(
        'pages/page-conteudos.php' => array(
            'title' => 'Conteúdos',
            'excerpt' => 'Explore recursos organizados por tipo para facilitar sua busca por conhecimento em acessibilidade.',
        ),
        'pages/page-rede.php' => array(
            'title' => 'Rede',
            'excerpt' => 'Profissionais e organizações que atuam com acessibilidade digital.',
        ),
        'pages/page-eventos.php' => array(
            'title' => 'Eventos',
            'excerpt' => 'Conferências, workshops, meetups e webinars sobre acessibilidade digital.',
        ),
        'pages/page-submeter.php' => array(
            'title' => 'Submeter',
            'excerpt' => 'Envie conteúdos, eventos e perfis para fortalecer a comunidade.',
        ),
        'pages/page-submeter-conteudo.php' => array(
            'title' => 'Submeter conteudo',
            'excerpt' => 'Compartilhe artigos, ferramentas, livros e outros recursos sobre acessibilidade.',
        ),
        'pages/page-submeter-eventos.php' => array(
            'title' => 'Submeter evento',
            'excerpt' => 'Divulgue workshops, conferências, meetups e webinars sobre acessibilidade.',
        ),
        'pages/page-submeter-perfil.php' => array(
            'title' => 'Submeter perfil',
            'excerpt' => 'Cadastre profissionais e organizacoes da comunidade de acessibilidade.',
        ),
        'pages/page-sobre.php' => array(
            'title' => 'Sobre a A11YBR',
            'excerpt' => 'O que somos, por que existimos e como funciona a plataforma.',
        ),
        'pages/page-diretrizes.php' => array(
            'title' => 'Diretrizes da comunidade',
            'excerpt' => 'Critérios de publicação, padrões de qualidade e regras de convivência da plataforma.',
        ),
        'pages/page-contato.php' => array(
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
            update_post_meta((int) $page_id, '_wp_page_template', 'pages/page-diretrizes.php');
        }
    } else {
        update_post_meta((int) $page->ID, '_wp_page_template', 'pages/page-diretrizes.php');
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
            update_post_meta((int) $page_id, '_wp_page_template', 'pages/page-contato.php');
        }
    } else {
        update_post_meta((int) $page->ID, '_wp_page_template', 'pages/page-contato.php');
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
        'meta_value' => 'pages/page-rede.php',
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
        update_post_meta((int) $page->ID, '_wp_page_template', 'pages/page-rede.php');
    }

    $page_rede = get_page_by_path('rede');
    if ($page_rede instanceof WP_Post) {
        $target_page_ids[] = (int) $page_rede->ID;
        wp_update_post(array(
            'ID' => (int) $page_rede->ID,
            'post_title' => 'Rede',
            'post_excerpt' => 'Profissionais e organizações que atuam com acessibilidade digital.',
        ));
        update_post_meta((int) $page_rede->ID, '_wp_page_template', 'pages/page-rede.php');
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

function a11yhubbr_get_content_context_config() {
    return array(
        'year_enabled_types' => array('artigos', 'multimidia', 'sites-sistemas', 'cursos-materiais'),
        'depth_enabled_types' => array('artigos', 'cursos-materiais', 'ferramentas', 'multimidia', 'sites-sistemas'),
        'choices' => array(
            'depth' => array('introdutorio', 'intermediario', 'avancado'),
            'article_kind' => array('academico', 'ativismo', 'estudo-caso', 'opinativo', 'tecnico', 'outro'),
            'book_modality' => array('online', 'presencial', 'hibrido', 'nao-se-aplica'),
            'book_price' => array('gratuito', 'pago'),
            'tool_type' => array('auditoria-automatica', 'testes-manuais', 'contraste', 'design-system', 'plugin', 'outros'),
            'tool_model' => array('open-source', 'freemium', 'pago'),
            'media_channel_type' => array('audio', 'video'),
            'media_format' => array('entrevista', 'mesa-redonda', 'solo', 'tecnico', 'storytelling', 'outro'),
            'media_platform' => array('spotify', 'apple', 'site', 'youtube', 'deezer', 'amazon-music', 'outro'),
            'media_frequency' => array('semanal', 'quinzenal', 'mensal', 'pontual'),
            'site_business_model' => array('saas', 'e-commerce-marketplace', 'open-source', 'governamental'),
            'site_stage' => array('mvp', 'em-crescimento', 'estavel', 'legado'),
            'site_access_model' => array('aberto', 'login-obrigatorio'),
        ),
    );
}

function a11yhubbr_sanitize_choice($value, $allowed) {
    $normalized = sanitize_title((string) $value);
    if (!is_array($allowed)) {
        return '';
    }
    return in_array($normalized, $allowed, true) ? $normalized : '';
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
        $context_config = a11yhubbr_get_content_context_config();
        $choices = isset($context_config['choices']) && is_array($context_config['choices']) ? $context_config['choices'] : array();
        $type_slug = a11yhubbr_get_content_type_slug_from_input($raw['type'] ?? '');
        $year_value = isset($raw['year_publication']) ? absint($raw['year_publication']) : 0;
        return array(
            'type' => $type_slug,
            'title' => sanitize_text_field($raw['title'] ?? ''),
            'description' => sanitize_textarea_field($raw['description'] ?? ''),
            'author' => sanitize_text_field($raw['author'] ?? ''),
            'organization' => sanitize_text_field($raw['organization'] ?? ''),
            'link' => esc_url_raw($raw['link'] ?? ''),
            'tags' => a11yhubbr_parse_tags_from_input($raw['tags'] ?? ''),
            'email' => sanitize_email($raw['email'] ?? ''),
            'year_publication' => $year_value > 0 ? $year_value : '',
            'depth' => a11yhubbr_sanitize_choice($raw['depth'] ?? '', $choices['depth'] ?? array()),
            'article_authors' => sanitize_text_field($raw['article_authors'] ?? ''),
            'article_kind' => a11yhubbr_sanitize_choice($raw['article_kind'] ?? '', $choices['article_kind'] ?? array()),
            'book_modality' => a11yhubbr_sanitize_choice($raw['book_modality'] ?? '', $choices['book_modality'] ?? array()),
            'book_price' => a11yhubbr_sanitize_choice($raw['book_price'] ?? '', $choices['book_price'] ?? array()),
            'tool_type' => a11yhubbr_sanitize_choice($raw['tool_type'] ?? '', $choices['tool_type'] ?? array()),
            'tool_model' => a11yhubbr_sanitize_choice($raw['tool_model'] ?? '', $choices['tool_model'] ?? array()),
            'media_theme' => sanitize_text_field($raw['media_theme'] ?? ''),
            'media_channel_type' => a11yhubbr_sanitize_choice($raw['media_channel_type'] ?? '', $choices['media_channel_type'] ?? array()),
            'media_format' => a11yhubbr_sanitize_choice($raw['media_format'] ?? '', $choices['media_format'] ?? array()),
            'media_platform' => a11yhubbr_sanitize_choice($raw['media_platform'] ?? '', $choices['media_platform'] ?? array()),
            'media_frequency' => a11yhubbr_sanitize_choice($raw['media_frequency'] ?? '', $choices['media_frequency'] ?? array()),
            'site_business_model' => a11yhubbr_sanitize_choice($raw['site_business_model'] ?? '', $choices['site_business_model'] ?? array()),
            'site_stage' => a11yhubbr_sanitize_choice($raw['site_stage'] ?? '', $choices['site_stage'] ?? array()),
            'site_access_model' => a11yhubbr_sanitize_choice($raw['site_access_model'] ?? '', $choices['site_access_model'] ?? array()),
        );
    }

    if ($type === 'event') {
        $modality_raw = sanitize_text_field($raw['modality'] ?? '');
        $modality_slug = sanitize_title($modality_raw);
        if ($modality_slug === 'hibrido' || $modality_slug === 'h-brido') {
            $modality_slug = 'hibrido';
        }
        if (!in_array($modality_slug, array('presencial', 'online', 'hibrido'), true)) {
            $modality_slug = '';
        }

        $cep_raw = preg_replace('/\D+/', '', (string) ($raw['event_cep'] ?? ''));
        $event_cep = '';
        if (is_string($cep_raw) && strlen($cep_raw) === 8) {
            $event_cep = substr($cep_raw, 0, 5) . '-' . substr($cep_raw, 5, 3);
        }

        $event_online_location = sanitize_text_field($raw['event_online_location'] ?? '');
        $location_value = '';
        if ($modality_slug === 'online') {
            $location_value = $event_online_location;
        } elseif ($event_cep !== '') {
            $location_value = 'CEP ' . $event_cep;
        }

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
            'modality' => $modality_slug,
            'event_type' => sanitize_text_field($raw['event_type'] ?? ''),
            'title' => sanitize_text_field($raw['title'] ?? ''),
            'location' => $location_value,
            'event_cep' => $event_cep,
            'event_online_location' => $event_online_location,
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

function a11yhubbr_validate_content_submission_data($data) {
    if (!is_array($data)) {
        return false;
    }

    if (empty($data['type']) || !a11yhubbr_get_content_type_by_slug($data['type'])) {
        return false;
    }

    if (empty($data['title']) || empty($data['description']) || empty($data['author']) || empty($data['link']) || empty($data['email'])) {
        return false;
    }

    if (!filter_var($data['link'], FILTER_VALIDATE_URL) || !is_email($data['email'])) {
        return false;
    }

    $type_slug = (string) $data['type'];
    $context_config = a11yhubbr_get_content_context_config();
    $year_enabled = $context_config['year_enabled_types'] ?? array();
    $depth_enabled = $context_config['depth_enabled_types'] ?? array();

    if (!in_array($type_slug, $year_enabled, true)) {
        $data['year_publication'] = '';
    } elseif (!empty($data['year_publication'])) {
        $current = (int) gmdate('Y');
        $year = (int) $data['year_publication'];
        if ($year < 1900 || $year > ($current + 1)) {
            return false;
        }
    }

    if (!in_array($type_slug, $depth_enabled, true)) {
        $data['depth'] = '';
    }

    return true;
}

function a11yhubbr_validate_event_submission_data($data) {
    if (!is_array($data)) {
        return false;
    }

    if (
        empty($data['modality']) ||
        empty($data['event_type']) ||
        empty($data['title']) ||
        empty($data['description']) ||
        empty($data['organizer']) ||
        empty($data['link']) ||
        empty($data['email'])
    ) {
        return false;
    }

    if (!in_array($data['modality'], array('presencial', 'online', 'hibrido'), true)) {
        return false;
    }

    if (!filter_var($data['link'], FILTER_VALIDATE_URL) || !is_email($data['email'])) {
        return false;
    }

    if (!isset($data['slots']) || !is_array($data['slots']) || empty($data['slots'])) {
        return false;
    }

    if ($data['modality'] === 'online') {
        if (empty($data['event_online_location'])) {
            return false;
        }
    } elseif ($data['modality'] === 'presencial') {
        if (empty($data['event_cep'])) {
            return false;
        }
    } elseif ($data['modality'] === 'hibrido') {
        if (empty($data['event_cep']) || empty($data['event_online_location'])) {
            return false;
        }
    }

    return true;
}

function a11yhubbr_create_pending_content_post($data) {
    $title = $data['title'] !== '' ? $data['title'] : 'Submissao sem titulo';
    $type_slug = a11yhubbr_get_content_type_slug_from_input($data['type'] ?? '');
    if ($type_slug === '') {
        $type_slug = 'artigos';
    }
    $type_data = a11yhubbr_get_content_type_by_slug($type_slug);
    $type_label = $type_data['label'] ?? 'Artigos';

    $postarr = array(
        'post_type'    => 'post',
        'post_status'  => 'pending',
        'post_title'   => $title,
        'post_excerpt' => wp_trim_words(wp_strip_all_tags($data['description']), 28),
        'post_content' => (string) $data['description'],
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
    update_post_meta($post_id, '_a11yhubbr_content_year_publication', $data['year_publication']);
    update_post_meta($post_id, '_a11yhubbr_content_depth', $data['depth']);
    update_post_meta($post_id, '_a11yhubbr_content_article_authors', $data['article_authors']);
    update_post_meta($post_id, '_a11yhubbr_content_article_kind', $data['article_kind']);
    update_post_meta($post_id, '_a11yhubbr_content_book_modality', $data['book_modality']);
    update_post_meta($post_id, '_a11yhubbr_content_book_price', $data['book_price']);
    update_post_meta($post_id, '_a11yhubbr_content_tool_type', $data['tool_type']);
    update_post_meta($post_id, '_a11yhubbr_content_tool_model', $data['tool_model']);
    update_post_meta($post_id, '_a11yhubbr_content_media_theme', $data['media_theme']);
    update_post_meta($post_id, '_a11yhubbr_content_media_channel_type', $data['media_channel_type']);
    update_post_meta($post_id, '_a11yhubbr_content_media_format', $data['media_format']);
    update_post_meta($post_id, '_a11yhubbr_content_media_platform', $data['media_platform']);
    update_post_meta($post_id, '_a11yhubbr_content_media_frequency', $data['media_frequency']);
    update_post_meta($post_id, '_a11yhubbr_content_site_business_model', $data['site_business_model']);
    update_post_meta($post_id, '_a11yhubbr_content_site_stage', $data['site_stage']);
    update_post_meta($post_id, '_a11yhubbr_content_site_access_model', $data['site_access_model']);

    return $post_id;
}

function a11yhubbr_create_pending_event_post($data) {
    $title = $data['title'] !== '' ? $data['title'] : 'Evento sem titulo';

    $postarr = array(
        'post_type'    => 'a11y_evento',
        'post_status'  => 'pending',
        'post_title'   => $title,
        'post_excerpt' => wp_trim_words(wp_strip_all_tags($data['description']), 28),
        'post_content' => (string) $data['description'],
    );

    $post_id = wp_insert_post($postarr, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    update_post_meta($post_id, '_a11yhubbr_event_modality', $data['modality']);
    update_post_meta($post_id, '_a11yhubbr_event_type', $data['event_type']);
    update_post_meta($post_id, '_a11yhubbr_event_location', $data['location']);
    update_post_meta($post_id, '_a11yhubbr_event_postal_code', $data['event_cep']);
    update_post_meta($post_id, '_a11yhubbr_event_online_location', $data['event_online_location']);
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

    $postarr = array(
        'post_type'    => 'a11y_perfil',
        'post_status'  => 'pending',
        'post_title'   => $title,
        'post_excerpt' => wp_trim_words(wp_strip_all_tags($data['description']), 28),
        'post_content' => (string) $data['description'],
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
            'Ano de publicacao/atualizacao: ' . ($data['year_publication'] !== '' ? $data['year_publication'] : '-'),
            'Nivel de profundidade: ' . ($data['depth'] !== '' ? $data['depth'] : '-'),
            'Autorias (artigos): ' . ($data['article_authors'] !== '' ? $data['article_authors'] : '-'),
            'Tipo de artigo: ' . ($data['article_kind'] !== '' ? $data['article_kind'] : '-'),
            'Modalidade (livros e materiais): ' . ($data['book_modality'] !== '' ? $data['book_modality'] : '-'),
            'Preco (livros e materiais): ' . ($data['book_price'] !== '' ? $data['book_price'] : '-'),
            'Tipo de ferramenta: ' . ($data['tool_type'] !== '' ? $data['tool_type'] : '-'),
            'Modelo de ferramenta: ' . ($data['tool_model'] !== '' ? $data['tool_model'] : '-'),
            'Tema principal (multimidia): ' . ($data['media_theme'] !== '' ? $data['media_theme'] : '-'),
            'Midia: ' . ($data['media_channel_type'] !== '' ? $data['media_channel_type'] : '-'),
            'Formato: ' . ($data['media_format'] !== '' ? $data['media_format'] : '-'),
            'Plataforma: ' . ($data['media_platform'] !== '' ? $data['media_platform'] : '-'),
            'Frequencia: ' . ($data['media_frequency'] !== '' ? $data['media_frequency'] : '-'),
            'Modelo de negocio (site/sistema): ' . ($data['site_business_model'] !== '' ? $data['site_business_model'] : '-'),
            'Estagio do produto: ' . ($data['site_stage'] !== '' ? $data['site_stage'] : '-'),
            'Modelo de acesso: ' . ($data['site_access_model'] !== '' ? $data['site_access_model'] : '-'),
            'Tags: ' . implode(', ', $data['tags']),
            'Email de contato: ' . $data['email'],
        ));
    }

    if ($type === 'event') {
        $modality_label = array(
            'presencial' => 'Presencial',
            'online' => 'Online',
            'hibrido' => 'Hibrido',
        );
        $lines = array(
            'Nova submissao de evento',
            '------------------------',
            'Modalidade: ' . ($modality_label[$data['modality']] ?? $data['modality']),
            'Tipo de evento: ' . $data['event_type'],
            'Titulo: ' . $data['title'],
            'Localizacao: ' . $data['location'],
            'CEP: ' . ($data['event_cep'] !== '' ? $data['event_cep'] : '-'),
            'Local online/plataforma: ' . ($data['event_online_location'] !== '' ? $data['event_online_location'] : '-'),
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
        if (!a11yhubbr_validate_content_submission_data($data)) {
            $result = new WP_Error('invalid_content_data', 'Dados de conteúdo inválidos');
            $created = false;
        } else {
        $result = a11yhubbr_create_pending_content_post($data);
        $created = !is_wp_error($result);
        }
    } elseif ($type === 'event') {
        if (!a11yhubbr_validate_event_submission_data($data)) {
            $result = new WP_Error('invalid_event_data', 'Dados de evento invalidos');
            $created = false;
        } else {
            $result = a11yhubbr_create_pending_event_post($data);
            $created = !is_wp_error($result);
        }
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
            'template' => 'pages/page-acessibilidade.php',
            'excerpt' => 'Compromisso de acessibilidade, status de conformidade WCAG, limitações conhecidas e canal de feedback.',
        ),
        array(
            'slug' => 'termos-de-uso',
            'title' => 'Termos de Uso',
            'template' => 'pages/page-termos-de-uso.php',
            'excerpt' => 'Regras de uso da plataforma, responsabilidades, moderação, propriedade intelectual e limitações.',
        ),
        array(
            'slug' => 'politica-de-privacidade',
            'title' => 'Política de Privacidade',
            'template' => 'pages/page-politica-de-privacidade.php',
            'excerpt' => 'Como coletamos, usamos e protegemos dados pessoais em conformidade com a LGPD.',
        ),
        array(
            'slug' => 'busca',
            'title' => 'Busca',
            'template' => 'pages/page-busca.php',
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

function a11yhubbr_migrate_page_templates_to_pages_dir_once() {
    if (get_option('a11yhubbr_templates_pages_dir_migrated_v1') === '1') {
        return;
    }

    $template_map = array(
        'page-acessibilidade.php' => 'pages/page-acessibilidade.php',
        'page-busca.php' => 'pages/page-busca.php',
        'page-contato.php' => 'pages/page-contato.php',
        'page-conteudos.php' => 'pages/page-conteudos.php',
        'page-diretrizes.php' => 'pages/page-diretrizes.php',
        'page-eventos.php' => 'pages/page-eventos.php',
        'page-politica-de-privacidade.php' => 'pages/page-politica-de-privacidade.php',
        'page-rede.php' => 'pages/page-rede.php',
        'page-sobre.php' => 'pages/page-sobre.php',
        'page-submeter-conteudo.php' => 'pages/page-submeter-conteudo.php',
        'page-submeter-eventos.php' => 'pages/page-submeter-eventos.php',
        'page-submeter-perfil.php' => 'pages/page-submeter-perfil.php',
        'page-submeter.php' => 'pages/page-submeter.php',
        'page-termos-de-uso.php' => 'pages/page-termos-de-uso.php',
    );

    foreach ($template_map as $old => $new) {
        $page_ids = get_posts(array(
            'post_type' => 'page',
            'post_status' => array('publish', 'draft', 'pending', 'private'),
            'numberposts' => -1,
            'fields' => 'ids',
            'meta_key' => '_wp_page_template',
            'meta_value' => $old,
        ));

        foreach ($page_ids as $page_id) {
            update_post_meta((int) $page_id, '_wp_page_template', $new);
        }
    }

    update_option('a11yhubbr_templates_pages_dir_migrated_v1', '1', false);
}
add_action('init', 'a11yhubbr_migrate_page_templates_to_pages_dir_once');

function a11yhubbr_page_slug_template_fallback($template) {
    if (!is_page()) {
        return $template;
    }

    $page_id = get_queried_object_id();
    if ($page_id <= 0) {
        return $template;
    }

    $assigned = get_page_template_slug($page_id);
    if (is_string($assigned) && $assigned !== '' && $assigned !== 'default') {
        return $template;
    }

    $slug = get_query_var('pagename');
    if (!is_string($slug) || $slug === '') {
        $slug = get_post_field('post_name', $page_id);
    }
    $slug = sanitize_title((string) $slug);
    if ($slug === '') {
        return $template;
    }

    $candidate_rel = 'pages/page-' . $slug . '.php';
    $candidate_abs = locate_template($candidate_rel);
    if (is_string($candidate_abs) && $candidate_abs !== '' && file_exists($candidate_abs)) {
        return $candidate_abs;
    }

    return $template;
}
add_filter('template_include', 'a11yhubbr_page_slug_template_fallback', 20);

function a11yhubbr_virtual_busca_template_fallback() {
    if (!is_404()) {
        return;
    }

    $request_path = parse_url(isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/', PHP_URL_PATH);
    $request_path = is_string($request_path) ? trim($request_path, '/') : '';

    if ($request_path !== 'busca') {
        return;
    }

    $template = locate_template('pages/page-busca.php');
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
