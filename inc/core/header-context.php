<?php

if (!defined('ABSPATH')) {
    exit;
}

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
        $post_type === 'a11y_conteudo' ||
        $template === 'pages/page-conteudos.php' ||
        $template === 'pages/page-busca.php' ||
        $template === 'pages/page-submeter-conteudo.php' ||
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
