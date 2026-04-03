<?php

if (!defined('ABSPATH')) {
    exit;
}

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


function a11yhubbr_resolve_social_icon_class($url, $network = '') {
    $network = sanitize_key((string) $network);
    $network_map = array(
        'linkedin' => 'fa-brands fa-linkedin-in',
        'github' => 'fa-brands fa-github',
        'gitlab' => 'fa-brands fa-gitlab',
        'instagram' => 'fa-brands fa-instagram',
        'x' => 'fa-brands fa-x-twitter',
        'twitter' => 'fa-brands fa-x-twitter',
        'facebook' => 'fa-brands fa-facebook-f',
        'threads' => 'fa-brands fa-threads',
        'bluesky' => 'fa-brands fa-bluesky',
        'telegram' => 'fa-brands fa-telegram',
        'whatsapp' => 'fa-brands fa-whatsapp',
        'youtube' => 'fa-brands fa-youtube',
        'tiktok' => 'fa-brands fa-tiktok',
        'medium' => 'fa-brands fa-medium',
        'behance' => 'fa-brands fa-behance',
        'dribbble' => 'fa-brands fa-dribbble',
        'discord' => 'fa-brands fa-discord',
        'mastodon' => 'fa-brands fa-mastodon',
        'devto' => 'fa-brands fa-dev',
    );

    if ($network !== '' && isset($network_map[$network])) {
        return $network_map[$network];
    }

    $host = wp_parse_url((string) $url, PHP_URL_HOST);
    $host = is_string($host) ? strtolower($host) : '';

    if ($host === '') {
        return 'fa-solid fa-globe';
    }

    $map = array(
        'linkedin.com' => 'fa-brands fa-linkedin-in',
        'github.com' => 'fa-brands fa-github',
        'gitlab.com' => 'fa-brands fa-gitlab',
        'instagram.com' => 'fa-brands fa-instagram',
        'twitter.com' => 'fa-brands fa-x-twitter',
        'x.com' => 'fa-brands fa-x-twitter',
        'facebook.com' => 'fa-brands fa-facebook-f',
        'threads.net' => 'fa-brands fa-threads',
        'bsky.app' => 'fa-brands fa-bluesky',
        'telegram.org' => 'fa-brands fa-telegram',
        't.me' => 'fa-brands fa-telegram',
        'wa.me' => 'fa-brands fa-whatsapp',
        'whatsapp.com' => 'fa-brands fa-whatsapp',
        'youtube.com' => 'fa-brands fa-youtube',
        'youtu.be' => 'fa-brands fa-youtube',
        'tiktok.com' => 'fa-brands fa-tiktok',
        'medium.com' => 'fa-brands fa-medium',
        'behance.net' => 'fa-brands fa-behance',
        'dribbble.com' => 'fa-brands fa-dribbble',
        'discord.com' => 'fa-brands fa-discord',
        'mastodon.social' => 'fa-brands fa-mastodon',
        'dev.to' => 'fa-brands fa-dev',
    );

    foreach ($map as $domain => $icon) {
        if (strpos($host, $domain) !== false) {
            return $icon;
        }
    }

    return 'fa-solid fa-globe';
}

function a11yhubbr_get_social_icon_class($url, $network = '') {
    return a11yhubbr_resolve_social_icon_class($url, $network);
}


function a11yhubbr_resolve_social_network_key($url, $network = '') {
    $network = sanitize_key((string) $network);
    if ($network !== '' && $network !== 'website') {
        return $network;
    }

    $host = wp_parse_url((string) $url, PHP_URL_HOST);
    $host = is_string($host) ? strtolower($host) : '';
    if ($host === '') {
        return 'website';
    }

    $domain_map = array(
        'linkedin.com' => 'linkedin',
        'github.com' => 'github',
        'gitlab.com' => 'gitlab',
        'instagram.com' => 'instagram',
        'twitter.com' => 'x',
        'x.com' => 'x',
        'facebook.com' => 'facebook',
        'threads.net' => 'threads',
        'bsky.app' => 'bluesky',
        'telegram.org' => 'telegram',
        't.me' => 'telegram',
        'wa.me' => 'whatsapp',
        'whatsapp.com' => 'whatsapp',
        'youtube.com' => 'youtube',
        'youtu.be' => 'youtube',
        'tiktok.com' => 'tiktok',
        'medium.com' => 'medium',
        'behance.net' => 'behance',
        'dribbble.com' => 'dribbble',
        'discord.com' => 'discord',
        'mastodon.social' => 'mastodon',
        'dev.to' => 'devto',
    );

    foreach ($domain_map as $domain => $key) {
        if (strpos($host, $domain) !== false) {
            return $key;
        }
    }

    return 'website';
}


function a11yhubbr_get_social_network_key($url, $network = '') {
    return a11yhubbr_resolve_social_network_key($url, $network);
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
