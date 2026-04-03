<?php
/**
 * LGPD — Lei Geral de Proteção de Dados
 * Exportação e apagamento de dados pessoais do usuário
 * Requisito: Lei 13.709/2018 (LGPD) + WordPress Privacy API
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Registra exportador de dados pessoais do hub
 * Acessível em: wp-admin > Ferramentas > Exportar dados pessoais
 */
add_filter('wp_privacy_personal_data_exporters', function ($exporters) {
    $exporters['a11yhubbr-submissoes'] = array(
        'exporter_friendly_name' => 'Submissões A11YBR',
        'callback'               => 'a11yhubbr_exportar_submissoes_usuario',
    );
    $exporters['a11yhubbr-perfil'] = array(
        'exporter_friendly_name' => 'Perfil A11YBR',
        'callback'               => 'a11yhubbr_exportar_perfil_usuario',
    );
    return $exporters;
});


/**
 * Exporta submissões (conteúdos, eventos) do usuário
 */
function a11yhubbr_exportar_submissoes_usuario($email, $page = 1) {
    $user = get_user_by('email', $email);
    if (!$user) {
        return array('data' => array(), 'done' => true);
    }

    $post_types = array('a11y_conteudo', 'a11y_evento');
    $data       = array();

    foreach ($post_types as $post_type) {
        $posts = get_posts(array(
            'post_type'      => $post_type,
            'author'         => $user->ID,
            'posts_per_page' => -1,
            'post_status'    => array('publish', 'pending', 'draft'),
            'fields'         => 'ids',
        ));

        foreach ($posts as $post_id) {
            $data[] = array(
                'group_id'    => $post_type,
                'group_label' => $post_type === 'a11y_conteudo' ? 'Conteúdo submetido' : 'Evento submetido',
                'item_id'     => $post_type . '-' . $post_id,
                'data'        => array(
                    array('name' => 'Título',   'value' => get_the_title($post_id)),
                    array('name' => 'Status',   'value' => get_post_status($post_id)),
                    array('name' => 'Data',     'value' => get_the_date('d/m/Y', $post_id)),
                    array('name' => 'Link',     'value' => esc_url((string) get_post_meta($post_id, '_a11yhubbr_link', true))),
                ),
            );
        }
    }

    return array('data' => $data, 'done' => true);
}


/**
 * Exporta dados de perfil público do usuário
 */
function a11yhubbr_exportar_perfil_usuario($email, $page = 1) {
    $user = get_user_by('email', $email);
    if (!$user) {
        return array('data' => array(), 'done' => true);
    }

    // Buscar CPT a11y_perfil vinculado ao usuário
    $perfis = get_posts(array(
        'post_type'      => 'a11y_perfil',
        'author'         => $user->ID,
        'posts_per_page' => 1,
        'post_status'    => array('publish', 'pending', 'draft'),
        'fields'         => 'ids',
    ));

    $data = array();

    foreach ($perfis as $post_id) {
        $social_links = get_post_meta($post_id, '_a11yhubbr_social_links', true);
        $social_str   = is_array($social_links) ? implode(', ', array_map('esc_url', $social_links)) : '';

        $data[] = array(
            'group_id'    => 'a11yhubbr-perfil',
            'group_label' => 'Perfil público A11YBR',
            'item_id'     => 'perfil-' . $post_id,
            'data'        => array(
                array('name' => 'Nome público',   'value' => get_the_title($post_id)),
                array('name' => 'Bio',            'value' => wp_strip_all_tags((string) get_post_meta($post_id, '_a11yhubbr_bio', true))),
                array('name' => 'Cargo/Função',   'value' => sanitize_text_field((string) get_post_meta($post_id, '_a11yhubbr_role', true))),
                array('name' => 'Links sociais',  'value' => $social_str),
            ),
        );
    }

    return array('data' => $data, 'done' => true);
}


/**
 * Registra apagador de dados pessoais do hub
 * Acessível em: wp-admin > Ferramentas > Apagar dados pessoais
 */
add_filter('wp_privacy_personal_data_erasers', function ($erasers) {
    $erasers['a11yhubbr-dados'] = array(
        'eraser_friendly_name' => 'Dados A11YBR',
        'callback'             => 'a11yhubbr_apagar_dados_usuario',
    );
    return $erasers;
});


/**
 * Apaga dados pessoais: anonimiza posts (não exclui) para preservar integridade
 */
function a11yhubbr_apagar_dados_usuario($email, $page = 1) {
    $user = get_user_by('email', $email);
    if (!$user) {
        return array('items_removed' => 0, 'items_retained' => 0, 'messages' => array(), 'done' => true);
    }

    $removed  = 0;
    $retained = 0;

    // Anonimizar perfil público
    $perfis = get_posts(array(
        'post_type'      => 'a11y_perfil',
        'author'         => $user->ID,
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ));

    foreach ($perfis as $post_id) {
        delete_post_meta($post_id, '_a11yhubbr_bio');
        delete_post_meta($post_id, '_a11yhubbr_role');
        delete_post_meta($post_id, '_a11yhubbr_social_links');
        wp_update_post(array('ID' => $post_id, 'post_status' => 'trash'));
        $removed++;
    }

    return array(
        'items_removed'  => $removed,
        'items_retained' => $retained,
        'messages'       => array(),
        'done'           => true,
    );
}
