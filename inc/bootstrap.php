<?php
if (!defined('ABSPATH')) {
    exit;
}

$bootstrap_files = array(
    '/inc/core/setup.php',
    '/inc/core/routing.php',
    '/inc/core/content.php',
    '/inc/core/migrations.php',
    '/inc/core/header-context.php',
    '/inc/core/security.php',
    '/inc/core/submissions.php',
    '/inc/core/admin-columns.php',
    '/inc/core/template-routing.php',
    '/inc/modules/login-branding.php',
);

foreach ($bootstrap_files as $file) {
    $path = get_template_directory() . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}
