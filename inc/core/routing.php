<?php

if (!defined('ABSPATH')) {
    exit;
}

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

