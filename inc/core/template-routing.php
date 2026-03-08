<?php

if (!defined('ABSPATH')) {
    exit;
}

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
