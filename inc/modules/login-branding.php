<?php
if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_get_login_logo_url() {
    $custom_logo_id = (int) get_theme_mod('custom_logo');
    if ($custom_logo_id > 0) {
        $custom_logo = wp_get_attachment_image_url($custom_logo_id, 'full');
        if (is_string($custom_logo) && $custom_logo !== '') {
            return $custom_logo;
        }
    }

    return get_theme_file_uri('screenshot.png');
}

function a11yhubbr_login_branding_styles() {
    $logo_url = esc_url(a11yhubbr_get_login_logo_url());
    ?>
    <style>
      .login h1 a {
        background-image: url('<?php echo $logo_url; ?>');
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        width: 280px;
        height: 84px;
      }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'a11yhubbr_login_branding_styles');

function a11yhubbr_login_branding_url() {
    return home_url('/');
}
add_filter('login_headerurl', 'a11yhubbr_login_branding_url');

function a11yhubbr_login_branding_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'a11yhubbr_login_branding_title');
