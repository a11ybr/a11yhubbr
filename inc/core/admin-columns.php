<?php

if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_columns_post($columns) {
    $columns['a11y_content_type'] = __('Tipo', 'a11yhubbr');
    $columns['a11y_contact_email'] = __('Email de contato', 'a11yhubbr');
    $columns['a11y_source_link'] = __('Link', 'a11yhubbr');
    return $columns;
}
add_filter('manage_post_posts_columns', 'a11yhubbr_columns_post');
add_filter('manage_a11y_conteudo_posts_columns', 'a11yhubbr_columns_post');


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
add_action('manage_a11y_conteudo_posts_custom_column', 'a11yhubbr_columns_post_content', 10, 2);


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
