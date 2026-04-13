<?php

if (!defined('ABSPATH')) {
    exit;
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
        'pages/page-entrar.php' => array(
            'title' => 'Entrar',
            'excerpt' => 'Acesse sua conta para submeter e acompanhar contribuições.',
        ),
        'pages/page-cadastro.php' => array(
            'title' => 'Criar conta',
            'excerpt' => 'Cadastre-se para enviar conteúdos, eventos e perfis pela plataforma.',
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
        'pages/page-minhas-submissoes.php' => array(
            'title' => 'Minhas submissões',
            'excerpt' => 'Acompanhe conteúdos, eventos e perfis enviados pela sua conta.',
        ),
        'pages/page-sobre.php' => array(
            'title' => 'Sobre a A11YBR',
            'excerpt' => 'O que somos, por que existimos e como funciona a plataforma.',
        ),
        'pages/page-projeto.php' => array(
            'title' => 'Projeto',
            'excerpt' => 'Missao, visao, motivacao da comunidade e criterios de submissao da A11YBR.',
        ),
        'pages/page-diretrizes.php' => array(
            'title' => 'Diretrizes da comunidade',
            'excerpt' => 'Critérios de publicação, padrões de qualidade e regras de convivência da plataforma.',
        ),
        'pages/page-contato.php' => array(
            'title' => 'Contato',
            'excerpt' => 'Canal para sugerir alterações, reportar informações desatualizadas e tirar dúvidas.',
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


function a11yhubbr_ensure_projeto_page_once() {
    if (get_option('a11yhubbr_projeto_page_created_v1') === '1') {
        return;
    }

    $page = get_page_by_path('projeto');
    if (!($page instanceof WP_Post)) {
        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Projeto',
            'post_name' => 'projeto',
            'post_excerpt' => 'Missao, visao, motivacao da comunidade e criterios de submissao da A11YBR.',
        ));
        if (!is_wp_error($page_id) && $page_id > 0) {
            update_post_meta((int) $page_id, '_wp_page_template', 'pages/page-projeto.php');
        }
    } else {
        update_post_meta((int) $page->ID, '_wp_page_template', 'pages/page-projeto.php');
    }

    update_option('a11yhubbr_projeto_page_created_v1', '1', false);
}
add_action('init', 'a11yhubbr_ensure_projeto_page_once');


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
            'post_excerpt' => 'Canal para sugerir alterações, reportar informações desatualizadas e tirar dúvidas.',
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


function a11yhubbr_ensure_my_submissions_page_once() {
    if (get_option('a11yhubbr_my_submissions_page_created_v1') === '1') {
        return;
    }

    $page = get_page_by_path('minhas-submissoes');
    if (!($page instanceof WP_Post)) {
        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Minhas submissões',
            'post_name' => 'minhas-submissoes',
            'post_excerpt' => 'Acompanhe conteúdos, eventos e perfis enviados pela sua conta.',
        ));
        if (!is_wp_error($page_id) && $page_id > 0) {
            update_post_meta((int) $page_id, '_wp_page_template', 'pages/page-minhas-submissoes.php');
        }
    } else {
        update_post_meta((int) $page->ID, '_wp_page_template', 'pages/page-minhas-submissoes.php');
    }

    update_option('a11yhubbr_my_submissions_page_created_v1', '1', false);
}
add_action('init', 'a11yhubbr_ensure_my_submissions_page_once');


function a11yhubbr_ensure_login_page_once() {
    if (get_option('a11yhubbr_login_page_created_v1') === '1') {
        return;
    }

    $page = get_page_by_path('entrar');
    if (!($page instanceof WP_Post)) {
        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Entrar',
            'post_name' => 'entrar',
            'post_excerpt' => 'Acesse sua conta para submeter e acompanhar contribuições.',
        ));
        if (!is_wp_error($page_id) && $page_id > 0) {
            update_post_meta((int) $page_id, '_wp_page_template', 'pages/page-entrar.php');
        }
    } else {
        update_post_meta((int) $page->ID, '_wp_page_template', 'pages/page-entrar.php');
    }

    update_option('a11yhubbr_login_page_created_v1', '1', false);
}
add_action('init', 'a11yhubbr_ensure_login_page_once');


function a11yhubbr_ensure_registration_page_once() {
    if (get_option('a11yhubbr_registration_page_created_v1') === '1') {
        return;
    }

    $page = get_page_by_path('cadastro');
    if (!($page instanceof WP_Post)) {
        $page_id = wp_insert_post(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => 'Criar conta',
            'post_name' => 'cadastro',
            'post_excerpt' => 'Cadastre-se para enviar conteúdos, eventos e perfis pela plataforma.',
        ));
        if (!is_wp_error($page_id) && $page_id > 0) {
            update_post_meta((int) $page_id, '_wp_page_template', 'pages/page-cadastro.php');
        }
    } else {
        update_post_meta((int) $page->ID, '_wp_page_template', 'pages/page-cadastro.php');
    }

    update_option('a11yhubbr_registration_page_created_v1', '1', false);
}
add_action('init', 'a11yhubbr_ensure_registration_page_once');


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
        'page-entrar.php' => 'pages/page-entrar.php',
        'page-contato.php' => 'pages/page-contato.php',
        'page-conteudos.php' => 'pages/page-conteudos.php',
        'page-cadastro.php' => 'pages/page-cadastro.php',
        'page-diretrizes.php' => 'pages/page-diretrizes.php',
        'page-eventos.php' => 'pages/page-eventos.php',
        'page-politica-de-privacidade.php' => 'pages/page-politica-de-privacidade.php',
        'page-projeto.php' => 'pages/page-projeto.php',
        'page-rede.php' => 'pages/page-rede.php',
        'page-sobre.php' => 'pages/page-sobre.php',
        'page-submeter-conteudo.php' => 'pages/page-submeter-conteudo.php',
        'page-submeter-eventos.php' => 'pages/page-submeter-eventos.php',
        'page-submeter-perfil.php' => 'pages/page-submeter-perfil.php',
        'page-submeter.php' => 'pages/page-submeter.php',
        'page-minhas-submissoes.php' => 'pages/page-minhas-submissoes.php',
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


function a11yhubbr_migrate_posts_to_content_cpt_once() {
    if (!is_admin()) {
        return;
    }

    if (get_option('a11yhubbr_posts_to_content_cpt_done_v1') === '1') {
        return;
    }

    $content_types = function_exists('a11yhubbr_get_content_type_map') ? a11yhubbr_get_content_type_map() : array();
    $content_slugs = array_keys($content_types);
    $content_slugs = array_values(array_diff($content_slugs, array('eventos', 'redes', 'comunidades')));

    if (empty($content_slugs)) {
        update_option('a11yhubbr_posts_to_content_cpt_done_v1', '1', false);
        return;
    }

    $post_ids = get_posts(array(
        'post_type' => 'post',
        'post_status' => array('publish', 'pending', 'draft', 'private'),
        'posts_per_page' => -1,
        'fields' => 'ids',
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $content_slugs,
                'operator' => 'IN',
            ),
        ),
    ));

    if (!empty($post_ids)) {
        foreach ($post_ids as $post_id) {
            $post_id = (int) $post_id;
            if ($post_id <= 0) {
                continue;
            }

            wp_update_post(array(
                'ID' => $post_id,
                'post_type' => 'a11y_conteudo',
            ));
        }
    }

    update_option('a11yhubbr_posts_to_content_cpt_done_v1', '1', false);
}
add_action('admin_init', 'a11yhubbr_migrate_posts_to_content_cpt_once');
