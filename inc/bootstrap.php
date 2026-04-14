<?php
if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_bootstrap_include_files($files) {
    foreach ($files as $file) {
        $path = get_template_directory() . $file;
        if (file_exists($path)) {
            require_once $path;
        }
    }
}

function a11yhubbr_is_rest_request_context() {
    if (defined('REST_REQUEST') && REST_REQUEST) {
        return true;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
    if ($request_uri === '') {
        return false;
    }

    $rest_prefix = '/' . trim((string) rest_get_url_prefix(), '/') . '/';
    return strpos($request_uri, $rest_prefix) !== false;
}

function a11yhubbr_is_frontend_request_context() {
    if (a11yhubbr_is_rest_request_context()) {
        return false;
    }

    if (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
        return false;
    }

    if (defined('WP_CLI') && WP_CLI) {
        return false;
    }

    return !is_admin();
}

$shared_files = array(
    '/inc/core/setup.php',
    '/inc/modules/login-branding.php',
);

$frontend_files = array(
    '/inc/core/header-context.php',
    '/inc/core/global-page-cta.php',
    '/inc/core/seo.php',
    '/inc/core/template-routing.php',
);

a11yhubbr_bootstrap_include_files($shared_files);

if (a11yhubbr_is_frontend_request_context()) {
    a11yhubbr_bootstrap_include_files($frontend_files);
}
