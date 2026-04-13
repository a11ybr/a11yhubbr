<?php

if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_register_submission_cpts() {
    register_post_type('a11y_conteudo', array(
        'labels' => array(
            'name' => __('Conteudos (Submissoes)', 'a11yhubbr'),
            'singular_name' => __('Conteudo (Submissao)', 'a11yhubbr'),
            'menu_name' => __('Conteudos', 'a11yhubbr'),
            'add_new_item' => __('Adicionar conteudo', 'a11yhubbr'),
            'edit_item' => __('Editar conteudo', 'a11yhubbr'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_in_admin_bar' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'author'),
        'taxonomies' => array('category', 'post_tag'),
        'has_archive' => false,
        'rewrite' => array('slug' => 'conteudo', 'with_front' => false),
        'menu_position' => 20,
        'menu_icon' => 'dashicons-media-document',
    ));

    register_post_type('a11y_evento', array(
        'labels' => array(
            'name' => __('Eventos (Submissoes)', 'a11yhubbr'),
            'singular_name' => __('Evento (Submissao)', 'a11yhubbr'),
            'menu_name' => __('Eventos', 'a11yhubbr'),
            'add_new_item' => __('Adicionar evento', 'a11yhubbr'),
            'edit_item' => __('Editar evento', 'a11yhubbr'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'show_in_admin_bar' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'custom-fields'),
        'taxonomies' => array('post_tag'),
        'has_archive' => false,
        'rewrite' => array('slug' => 'evento', 'with_front' => false),
        'menu_position' => 21,
        'menu_icon' => 'dashicons-calendar-alt',
    ));

    register_post_type('a11y_perfil', array(
        'labels' => array(
            'name' => __('Perfis (Submissoes)', 'a11yhubbr'),
            'singular_name' => __('Perfil (Submissao)', 'a11yhubbr'),
            'menu_name' => __('Perfis', 'a11yhubbr'),
            'add_new_item' => __('Adicionar perfil', 'a11yhubbr'),
            'edit_item' => __('Editar perfil', 'a11yhubbr'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'show_in_admin_bar' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'custom-fields', 'thumbnail'),
        'taxonomies' => array('category', 'post_tag'),
        'has_archive' => false,
        'rewrite' => array('slug' => 'perfil', 'with_front' => false),
        'menu_position' => 22,
        'menu_icon' => 'dashicons-id',
    ));
}
add_action('init', 'a11yhubbr_register_submission_cpts');


function a11yhubbr_flush_rewrite_rules_once() {
    if (get_option('a11yhubbr_rewrite_flushed_v2') === '1') {
        return;
    }
    flush_rewrite_rules(false);
    update_option('a11yhubbr_rewrite_flushed_v2', '1', false);
}
add_action('init', 'a11yhubbr_flush_rewrite_rules_once', 99);


function a11yhubbr_get_submission_config() {
    return array(
        'content' => array(
            'button' => 'a11yhubbr_content_submit',
            'nonce_action' => 'a11yhubbr_content',
            'label' => 'Submeter conteudo',
        ),
        'event' => array(
            'button' => 'a11yhubbr_event_submit',
            'nonce_action' => 'a11yhubbr_event',
            'label' => 'Submeter eventos',
        ),
        'profile' => array(
            'button' => 'a11yhubbr_profile_submit',
            'nonce_action' => 'a11yhubbr_profile',
            'label' => 'Submeter perfil',
        ),
    );
}


function a11yhubbr_get_submission_login_url($redirect = '') {
    $target = $redirect !== '' ? $redirect : home_url('/submeter');
    $login_url = function_exists('a11yhubbr_get_login_page_url')
        ? a11yhubbr_get_login_page_url()
        : home_url('/entrar');

    return add_query_arg('redirect_to', rawurlencode($target), $login_url);
}


function a11yhubbr_get_submission_registration_url($redirect = '') {
    if (!(bool) get_option('users_can_register')) {
        return '';
    }

    $target = $redirect !== '' ? $redirect : home_url('/submeter');
    $registration_url = function_exists('a11yhubbr_get_registration_page_url')
        ? a11yhubbr_get_registration_page_url()
        : home_url('/cadastro');

    return add_query_arg('redirect_to', rawurlencode($target), $registration_url);
}


function a11yhubbr_get_submission_user_context() {
    if (!is_user_logged_in()) {
        return array(
            'user_id' => 0,
            'author' => '',
            'email' => '',
        );
    }

    $user = wp_get_current_user();
    $author = $user->display_name !== '' ? $user->display_name : $user->user_login;

    return array(
        'user_id' => (int) $user->ID,
        'author' => sanitize_text_field($author),
        'email' => sanitize_email($user->user_email),
    );
}


function a11yhubbr_get_requested_redirect_target($param = 'redirect_to', $fallback = '') {
    $safe_fallback = $fallback !== '' ? $fallback : home_url('/submeter');
    $raw_value = isset($_REQUEST[$param]) ? wp_unslash($_REQUEST[$param]) : '';
    $value = is_string($raw_value) ? rawurldecode($raw_value) : '';
    $value = $value !== '' ? esc_url_raw($value) : '';

    if ($value !== '') {
        return wp_validate_redirect($value, $safe_fallback);
    }

    return $safe_fallback;
}


function a11yhubbr_get_registration_error_code($code) {
    $allowed = array(
        'invalid_nonce',
        'closed',
        'logged_in',
        'empty_fields',
        'password_mismatch',
        'invalid_username',
        'username_exists',
        'invalid_email',
        'email_exists',
        'create_failed',
    );

    $normalized = sanitize_key((string) $code);
    return in_array($normalized, $allowed, true) ? $normalized : 'create_failed';
}


function a11yhubbr_get_registration_error_redirect($error_code, $redirect_target = '') {
    $base_url = function_exists('a11yhubbr_get_registration_page_url')
        ? a11yhubbr_get_registration_page_url()
        : home_url('/cadastro');

    return add_query_arg(array(
        'a11yhubbr_register_status' => 'error',
        'a11yhubbr_register_error' => a11yhubbr_get_registration_error_code($error_code),
        'redirect_to' => rawurlencode($redirect_target !== '' ? $redirect_target : home_url('/submeter')),
    ), $base_url);
}


function a11yhubbr_get_login_error_code($code) {
    $allowed = array(
        'invalid_nonce',
        'empty_fields',
        'invalid_login',
    );

    $normalized = sanitize_key((string) $code);
    return in_array($normalized, $allowed, true) ? $normalized : 'invalid_login';
}


function a11yhubbr_get_login_error_redirect($error_code, $redirect_target = '') {
    $base_url = function_exists('a11yhubbr_get_login_page_url')
        ? a11yhubbr_get_login_page_url()
        : home_url('/entrar');

    return add_query_arg(array(
        'a11yhubbr_login_status' => 'error',
        'a11yhubbr_login_error' => a11yhubbr_get_login_error_code($error_code),
        'redirect_to' => rawurlencode($redirect_target !== '' ? $redirect_target : home_url('/submeter')),
    ), $base_url);
}


function a11yhubbr_handle_login_form() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['a11yhubbr_login_submit'])) {
        return;
    }

    $redirect_target = a11yhubbr_get_requested_redirect_target('redirect_to', home_url('/submeter'));

    if (is_user_logged_in()) {
        wp_safe_redirect($redirect_target);
        exit;
    }

    $nonce = isset($_POST['a11yhubbr_login_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['a11yhubbr_login_nonce']))
        : '';

    if ($nonce === '' || !wp_verify_nonce($nonce, 'a11yhubbr_login_user')) {
        wp_safe_redirect(a11yhubbr_get_login_error_redirect('invalid_nonce', $redirect_target));
        exit;
    }

    $username = isset($_POST['log'])
        ? sanitize_text_field(wp_unslash($_POST['log']))
        : '';
    $password = isset($_POST['pwd'])
        ? (string) wp_unslash($_POST['pwd'])
        : '';
    $remember = !empty($_POST['rememberme']);

    if ($username === '' || $password === '') {
        wp_safe_redirect(a11yhubbr_get_login_error_redirect('empty_fields', $redirect_target));
        exit;
    }

    $signon = wp_signon(array(
        'user_login' => $username,
        'user_password' => $password,
        'remember' => $remember,
    ), is_ssl());

    if (is_wp_error($signon)) {
        wp_safe_redirect(a11yhubbr_get_login_error_redirect('invalid_login', $redirect_target));
        exit;
    }

    wp_safe_redirect($redirect_target);
    exit;
}
add_action('init', 'a11yhubbr_handle_login_form');


function a11yhubbr_handle_registration_form() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['a11yhubbr_register_submit'])) {
        return;
    }

    $redirect_target = a11yhubbr_get_requested_redirect_target('redirect_to', home_url('/submeter'));

    if (!(bool) get_option('users_can_register')) {
        wp_safe_redirect(a11yhubbr_get_registration_error_redirect('closed', $redirect_target));
        exit;
    }

    if (is_user_logged_in()) {
        wp_safe_redirect($redirect_target);
        exit;
    }

    $nonce = isset($_POST['a11yhubbr_register_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['a11yhubbr_register_nonce']))
        : '';

    if ($nonce === '' || !wp_verify_nonce($nonce, 'a11yhubbr_register_user')) {
        wp_safe_redirect(a11yhubbr_get_registration_error_redirect('invalid_nonce', $redirect_target));
        exit;
    }

    $username = isset($_POST['user_login'])
        ? sanitize_user(wp_unslash($_POST['user_login']), true)
        : '';
    $email = isset($_POST['user_email'])
        ? sanitize_email(wp_unslash($_POST['user_email']))
        : '';
    $display_name = isset($_POST['display_name'])
        ? sanitize_text_field(wp_unslash($_POST['display_name']))
        : '';
    $password = isset($_POST['user_pass'])
        ? (string) wp_unslash($_POST['user_pass'])
        : '';
    $password_confirm = isset($_POST['user_pass_confirm'])
        ? (string) wp_unslash($_POST['user_pass_confirm'])
        : '';

    if ($username === '' || $email === '' || $password === '' || $password_confirm === '') {
        wp_safe_redirect(a11yhubbr_get_registration_error_redirect('empty_fields', $redirect_target));
        exit;
    }

    if ($password !== $password_confirm) {
        wp_safe_redirect(a11yhubbr_get_registration_error_redirect('password_mismatch', $redirect_target));
        exit;
    }

    if (!validate_username($username)) {
        wp_safe_redirect(a11yhubbr_get_registration_error_redirect('invalid_username', $redirect_target));
        exit;
    }

    if (username_exists($username)) {
        wp_safe_redirect(a11yhubbr_get_registration_error_redirect('username_exists', $redirect_target));
        exit;
    }

    if (!is_email($email)) {
        wp_safe_redirect(a11yhubbr_get_registration_error_redirect('invalid_email', $redirect_target));
        exit;
    }

    if (email_exists($email)) {
        wp_safe_redirect(a11yhubbr_get_registration_error_redirect('email_exists', $redirect_target));
        exit;
    }

    $user_id = wp_insert_user(array(
        'user_login' => $username,
        'user_email' => $email,
        'user_pass' => $password,
        'display_name' => $display_name !== '' ? $display_name : $username,
        'role' => get_option('default_role', 'subscriber'),
    ));

    if (is_wp_error($user_id) || (int) $user_id <= 0) {
        wp_safe_redirect(a11yhubbr_get_registration_error_redirect('create_failed', $redirect_target));
        exit;
    }

    $user = get_user_by('id', (int) $user_id);
    if ($user instanceof WP_User) {
        wp_clear_auth_cookie();
        wp_set_current_user((int) $user_id);
        wp_set_auth_cookie((int) $user_id, true);
        do_action('wp_login', $user->user_login, $user);
    }

    $success_redirect = add_query_arg('a11yhubbr_register_status', 'success', $redirect_target);
    wp_safe_redirect($success_redirect);
    exit;
}
add_action('init', 'a11yhubbr_handle_registration_form');


function a11yhubbr_get_profile_owner_user_id($post) {
    $post = get_post($post);
    if (!$post instanceof WP_Post || $post->post_type !== 'a11y_perfil') {
        return 0;
    }

    return (int) get_post_meta($post->ID, '_a11yhubbr_owner_user_id', true);
}


function a11yhubbr_can_manage_submission_post($post, $user_id = 0) {
    $post = get_post($post);
    if (!$post instanceof WP_Post) {
        return false;
    }

    $user_id = $user_id > 0 ? (int) $user_id : get_current_user_id();
    if ($user_id <= 0) {
        return false;
    }

    if (user_can($user_id, 'edit_post', $post->ID)) {
        return true;
    }

    if ((int) $post->post_author === $user_id) {
        return true;
    }

    if ($post->post_type === 'a11y_perfil' && a11yhubbr_get_profile_owner_user_id($post) === $user_id) {
        return true;
    }

    return false;
}


function a11yhubbr_get_content_context_config() {
    return array(
        'year_enabled_types' => array('artigos', 'multimidia', 'sites-sistemas', 'cursos-materiais'),
        'depth_enabled_types' => array('artigos', 'cursos-materiais', 'ferramentas', 'multimidia', 'sites-sistemas'),
        'choices' => array(
            'depth' => array('introdutorio', 'intermediario', 'avancado'),
            'article_kind' => array('academico', 'ativismo', 'estudo-caso', 'opinativo', 'tecnico', 'outro'),
            'book_modality' => array('online', 'presencial', 'hibrido', 'nao-se-aplica'),
            'book_price' => array('gratuito', 'pago'),
            'tool_type' => array('auditoria-automatica', 'testes-manuais', 'contraste', 'design-system', 'plugin', 'outros'),
            'tool_model' => array('open-source', 'freemium', 'pago'),
            'media_channel_type' => array('audio', 'video'),
            'media_format' => array('entrevista', 'mesa-redonda', 'solo', 'tecnico', 'storytelling', 'outro'),
            'media_platform' => array('spotify', 'apple', 'site', 'youtube', 'deezer', 'amazon-music', 'outro'),
            'media_frequency' => array('semanal', 'quinzenal', 'mensal', 'pontual'),
            'site_business_model' => array('saas', 'e-commerce-marketplace', 'open-source', 'governamental'),
            'site_stage' => array('mvp', 'em-crescimento', 'estavel', 'legado'),
            'site_access_model' => array('aberto', 'login-obrigatorio'),
        ),
    );
}


function a11yhubbr_sanitize_choice($value, $allowed) {
    $normalized = sanitize_title((string) $value);
    if (!is_array($allowed)) {
        return '';
    }
    return in_array($normalized, $allowed, true) ? $normalized : '';
}


function a11yhubbr_detect_form_type() {
    $config = a11yhubbr_get_submission_config();

    foreach ($config as $type => $item) {
        if (isset($_POST[$item['button']])) {
            return $type;
        }
    }

    return null;
}


function a11yhubbr_sanitize_submission_data($type) {
    $raw = wp_unslash($_POST);
    $user_context = a11yhubbr_get_submission_user_context();
    $source_post_id = isset($raw['source_post']) ? absint($raw['source_post']) : 0;

    if ($type === 'content') {
        $context_config = a11yhubbr_get_content_context_config();
        $choices = isset($context_config['choices']) && is_array($context_config['choices']) ? $context_config['choices'] : array();
        $type_slug = a11yhubbr_get_content_type_slug_from_input($raw['type'] ?? '');
        $year_value = isset($raw['year_publication']) ? absint($raw['year_publication']) : 0;
        return array(
            'type' => $type_slug,
            'title' => sanitize_text_field($raw['title'] ?? ''),
            'description' => sanitize_textarea_field($raw['description'] ?? ''),
            'author' => $user_context['author'],
            'organization' => sanitize_text_field($raw['organization'] ?? ''),
            'link' => esc_url_raw($raw['link'] ?? ''),
            'tags' => a11yhubbr_parse_tags_from_input($raw['tags'] ?? ''),
            'email' => $user_context['email'],
            'user_id' => $user_context['user_id'],
            'source_post_id' => $source_post_id,
            'year_publication' => $year_value > 0 ? $year_value : '',
            'depth' => a11yhubbr_sanitize_choice($raw['depth'] ?? '', $choices['depth'] ?? array()),
            'article_authors' => sanitize_text_field($raw['article_authors'] ?? ''),
            'article_kind' => a11yhubbr_sanitize_choice($raw['article_kind'] ?? '', $choices['article_kind'] ?? array()),
            'book_modality' => a11yhubbr_sanitize_choice($raw['book_modality'] ?? '', $choices['book_modality'] ?? array()),
            'book_price' => a11yhubbr_sanitize_choice($raw['book_price'] ?? '', $choices['book_price'] ?? array()),
            'tool_type' => a11yhubbr_sanitize_choice($raw['tool_type'] ?? '', $choices['tool_type'] ?? array()),
            'tool_model' => a11yhubbr_sanitize_choice($raw['tool_model'] ?? '', $choices['tool_model'] ?? array()),
            'media_theme' => sanitize_text_field($raw['media_theme'] ?? ''),
            'media_channel_type' => a11yhubbr_sanitize_choice($raw['media_channel_type'] ?? '', $choices['media_channel_type'] ?? array()),
            'media_format' => a11yhubbr_sanitize_choice($raw['media_format'] ?? '', $choices['media_format'] ?? array()),
            'media_platform' => a11yhubbr_sanitize_choice($raw['media_platform'] ?? '', $choices['media_platform'] ?? array()),
            'media_frequency' => a11yhubbr_sanitize_choice($raw['media_frequency'] ?? '', $choices['media_frequency'] ?? array()),
            'site_business_model' => a11yhubbr_sanitize_choice($raw['site_business_model'] ?? '', $choices['site_business_model'] ?? array()),
            'site_stage' => a11yhubbr_sanitize_choice($raw['site_stage'] ?? '', $choices['site_stage'] ?? array()),
            'site_access_model' => a11yhubbr_sanitize_choice($raw['site_access_model'] ?? '', $choices['site_access_model'] ?? array()),
        );
    }

    if ($type === 'event') {
        $modality_raw = sanitize_text_field($raw['modality'] ?? '');
        $modality_slug = sanitize_title($modality_raw);
        if ($modality_slug === 'hibrido' || $modality_slug === 'h-brido') {
            $modality_slug = 'hibrido';
        }
        if (!in_array($modality_slug, array('presencial', 'online', 'hibrido'), true)) {
            $modality_slug = '';
        }

        $cep_raw = preg_replace('/\D+/', '', (string) ($raw['event_cep'] ?? ''));
        $event_cep = '';
        if (is_string($cep_raw) && strlen($cep_raw) === 8) {
            $event_cep = substr($cep_raw, 0, 5) . '-' . substr($cep_raw, 5, 3);
        }

        $event_online_location = sanitize_text_field($raw['event_online_location'] ?? '');
        $location_value = '';
        if ($modality_slug === 'online') {
            $location_value = $event_online_location;
        } elseif ($event_cep !== '') {
            $location_value = 'CEP ' . $event_cep;
        }

        $starts = isset($raw['slot_start']) && is_array($raw['slot_start']) ? $raw['slot_start'] : array();
        $ends   = isset($raw['slot_end']) && is_array($raw['slot_end']) ? $raw['slot_end'] : array();
        $slots  = array();

        $count = max(count($starts), count($ends));
        for ($i = 0; $i < $count; $i++) {
            $start = sanitize_text_field($starts[$i] ?? '');
            $end   = sanitize_text_field($ends[$i] ?? '');
            if ($start !== '' || $end !== '') {
                $slots[] = array('start' => $start, 'end' => $end);
            }
        }

        return array(
            'modality' => $modality_slug,
            'event_type' => sanitize_text_field($raw['event_type'] ?? ''),
            'title' => sanitize_text_field($raw['title'] ?? ''),
            'location' => $location_value,
            'event_cep' => $event_cep,
            'event_online_location' => $event_online_location,
            'description' => sanitize_textarea_field($raw['description'] ?? ''),
            'organizer' => sanitize_text_field($raw['organizer'] ?? ''),
            'link' => esc_url_raw($raw['link'] ?? ''),
            'tags' => a11yhubbr_parse_tags_from_input($raw['tags'] ?? ''),
            'author' => $user_context['author'],
            'email' => $user_context['email'],
            'user_id' => $user_context['user_id'],
            'source_post_id' => $source_post_id,
            'slots' => $slots,
        );
    }

    return array(
        'profile_type' => sanitize_text_field($raw['profile_type'] ?? ''),
        'name' => sanitize_text_field($raw['name'] ?? ''),
        'location' => sanitize_text_field($raw['location'] ?? ''),
        'description' => sanitize_textarea_field($raw['description'] ?? ''),
        'role' => sanitize_text_field($raw['role'] ?? ''),
        'website' => esc_url_raw($raw['website'] ?? ''),
        'tags' => a11yhubbr_parse_tags_from_input($raw['tags'] ?? ''),
        'author' => $user_context['author'],
        'social_links' => (static function () use ($raw) {
            $networks = isset($raw['social_network']) && is_array($raw['social_network']) ? $raw['social_network'] : array();
            $urls = isset($raw['social_url']) && is_array($raw['social_url']) ? $raw['social_url'] : array();
            $items = array();

            $count = max(count($networks), count($urls));
            for ($i = 0; $i < $count; $i++) {
                $network = sanitize_key($networks[$i] ?? '');
                $url = esc_url_raw($urls[$i] ?? '');
                if ($url === '') {
                    continue;
                }

                $items[] = array(
                    'network' => $network !== '' ? $network : 'website',
                    'url' => $url,
                );
            }

            return $items;
        })(),
        'email' => $user_context['email'],
        'user_id' => $user_context['user_id'],
        'source_post_id' => $source_post_id,
        'profile_image_name' => isset($_FILES['profile_image']['name'])
            ? sanitize_file_name(wp_unslash($_FILES['profile_image']['name']))
            : '',
    );
}


function a11yhubbr_get_submission_source_post($type) {
    $source_post_id = isset($_GET['source_post']) ? absint(wp_unslash($_GET['source_post'])) : 0;
    if ($source_post_id <= 0) {
        return null;
    }

    $post = get_post($source_post_id);
    if (!$post instanceof WP_Post) {
        return null;
    }

    $allowed_map = array(
        'content' => 'a11y_conteudo',
        'event' => 'a11y_evento',
        'profile' => 'a11y_perfil',
    );

    $expected_post_type = $allowed_map[$type] ?? '';
    if ($expected_post_type === '' || $post->post_type !== $expected_post_type) {
        return null;
    }

    if (!in_array($post->post_status, array('publish', 'pending', 'draft', 'private'), true)) {
        return null;
    }

    if (!a11yhubbr_can_manage_submission_post($post)) {
        return null;
    }

    return $post;
}


function a11yhubbr_get_prefill_query_url($base_url, $source_post_id) {
    if ($source_post_id <= 0) {
        return $base_url;
    }

    return add_query_arg('source_post', $source_post_id, $base_url);
}


function a11yhubbr_get_content_submission_prefill($post) {
    if (!$post instanceof WP_Post) {
        return array();
    }

    $categories = get_the_terms($post->ID, 'category');
    $type_slug = '';
    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $term) {
            $candidate = a11yhubbr_get_content_type_slug_from_input((string) $term->slug);
            if ($candidate !== '') {
                $type_slug = $candidate;
                break;
            }
        }
    }

    if ($type_slug === '') {
        $type_slug = a11yhubbr_get_content_type_slug_from_input((string) get_post_meta($post->ID, '_a11yhubbr_content_type', true));
    }

    $tags = wp_get_post_terms($post->ID, 'post_tag', array('fields' => 'names'));
    if (!is_array($tags)) {
        $tags = array();
    }

    return array(
        'source_post' => (int) $post->ID,
        'type' => $type_slug,
        'title' => (string) get_the_title($post),
        'description' => (string) $post->post_content,
        'organization' => (string) get_post_meta($post->ID, '_a11yhubbr_submitter_org', true),
        'link' => (string) get_post_meta($post->ID, '_a11yhubbr_source_link', true),
        'tags' => implode(', ', $tags),
        'year_publication' => (string) get_post_meta($post->ID, '_a11yhubbr_content_year_publication', true),
        'depth' => (string) get_post_meta($post->ID, '_a11yhubbr_content_depth', true),
        'article_authors' => (string) get_post_meta($post->ID, '_a11yhubbr_content_article_authors', true),
        'article_kind' => (string) get_post_meta($post->ID, '_a11yhubbr_content_article_kind', true),
        'book_modality' => (string) get_post_meta($post->ID, '_a11yhubbr_content_book_modality', true),
        'book_price' => (string) get_post_meta($post->ID, '_a11yhubbr_content_book_price', true),
        'tool_type' => (string) get_post_meta($post->ID, '_a11yhubbr_content_tool_type', true),
        'tool_model' => (string) get_post_meta($post->ID, '_a11yhubbr_content_tool_model', true),
        'media_theme' => (string) get_post_meta($post->ID, '_a11yhubbr_content_media_theme', true),
        'media_channel_type' => (string) get_post_meta($post->ID, '_a11yhubbr_content_media_channel_type', true),
        'media_format' => (string) get_post_meta($post->ID, '_a11yhubbr_content_media_format', true),
        'media_platform' => (string) get_post_meta($post->ID, '_a11yhubbr_content_media_platform', true),
        'media_frequency' => (string) get_post_meta($post->ID, '_a11yhubbr_content_media_frequency', true),
        'site_business_model' => (string) get_post_meta($post->ID, '_a11yhubbr_content_site_business_model', true),
        'site_stage' => (string) get_post_meta($post->ID, '_a11yhubbr_content_site_stage', true),
        'site_access_model' => (string) get_post_meta($post->ID, '_a11yhubbr_content_site_access_model', true),
    );
}


function a11yhubbr_get_event_submission_prefill($post) {
    if (!$post instanceof WP_Post) {
        return array();
    }

    $tags = wp_get_post_terms($post->ID, 'post_tag', array('fields' => 'names'));
    if (!is_array($tags)) {
        $tags = array();
    }

    $slots = json_decode((string) get_post_meta($post->ID, '_a11yhubbr_event_slots', true), true);
    if (!is_array($slots) || empty($slots)) {
        $slots = array(array('start' => '', 'end' => ''));
    }

    return array(
        'source_post' => (int) $post->ID,
        'modality' => (string) get_post_meta($post->ID, '_a11yhubbr_event_modality', true),
        'event_type' => (string) get_post_meta($post->ID, '_a11yhubbr_event_type', true),
        'title' => (string) get_the_title($post),
        'event_cep' => (string) get_post_meta($post->ID, '_a11yhubbr_event_postal_code', true),
        'event_online_location' => (string) get_post_meta($post->ID, '_a11yhubbr_event_online_location', true),
        'organizer' => (string) get_post_meta($post->ID, '_a11yhubbr_event_organizer', true),
        'link' => (string) get_post_meta($post->ID, '_a11yhubbr_event_link', true),
        'tags' => implode(', ', $tags),
        'description' => (string) $post->post_content,
        'slots' => $slots,
    );
}


function a11yhubbr_get_profile_submission_prefill($post) {
    if (!$post instanceof WP_Post) {
        return array();
    }

    $tags = wp_get_post_terms($post->ID, 'post_tag', array('fields' => 'names'));
    if (!is_array($tags)) {
        $tags = array();
    }

    $social_links = json_decode((string) get_post_meta($post->ID, '_a11yhubbr_profile_social_links', true), true);
    if (!is_array($social_links) || empty($social_links)) {
        $social_links = array(array('network' => '', 'url' => ''));
    }

    return array(
        'source_post' => (int) $post->ID,
        'profile_type' => (string) get_post_meta($post->ID, '_a11yhubbr_profile_type', true),
        'name' => (string) get_the_title($post),
        'location' => (string) get_post_meta($post->ID, '_a11yhubbr_profile_location', true),
        'description' => (string) $post->post_content,
        'role' => (string) get_post_meta($post->ID, '_a11yhubbr_profile_role', true),
        'website' => (string) get_post_meta($post->ID, '_a11yhubbr_profile_website', true),
        'tags' => implode(', ', $tags),
        'social_links' => $social_links,
    );
}


function a11yhubbr_get_submission_prefill_payload_for_current_request() {
    if (is_page_template('pages/page-submeter-conteudo.php')) {
        $source_post = a11yhubbr_get_submission_source_post('content');
        if ($source_post instanceof WP_Post) {
            return array(
                'type' => 'content',
                'title' => get_the_title($source_post),
                'data' => a11yhubbr_get_content_submission_prefill($source_post),
            );
        }
    }

    if (is_page_template('pages/page-submeter-eventos.php')) {
        $source_post = a11yhubbr_get_submission_source_post('event');
        if ($source_post instanceof WP_Post) {
            return array(
                'type' => 'event',
                'title' => get_the_title($source_post),
                'data' => a11yhubbr_get_event_submission_prefill($source_post),
            );
        }
    }

    if (is_page_template('pages/page-submeter-perfil.php')) {
        $source_post = a11yhubbr_get_submission_source_post('profile');
        if ($source_post instanceof WP_Post) {
            return array(
                'type' => 'profile',
                'title' => get_the_title($source_post),
                'data' => a11yhubbr_get_profile_submission_prefill($source_post),
            );
        }
    }

    return null;
}


function a11yhubbr_enqueue_submission_prefill_script() {
    if (is_admin()) {
        return;
    }

    $payload = a11yhubbr_get_submission_prefill_payload_for_current_request();
    if (!is_array($payload) || empty($payload['data']) || !wp_script_is('a11yhubbr-submissions', 'enqueued')) {
        return;
    }

    $json = wp_json_encode($payload);
    if (!is_string($json) || $json === '') {
        return;
    }

    $script = <<<JS
(function () {
  var payload = {$json};
  if (!payload || !payload.data) {
    return;
  }

  function setValue(id, value) {
    var field = document.getElementById(id);
    if (!field || value === undefined || value === null) {
      return;
    }
    field.value = String(value);
    field.dispatchEvent(new Event('input', { bubbles: true }));
    field.dispatchEvent(new Event('change', { bubbles: true }));
  }

  function ensureHiddenField(form, name, value) {
    if (!form) {
      return;
    }
    var field = form.querySelector('input[name="' + name + '"]');
    if (!field) {
      field = document.createElement('input');
      field.type = 'hidden';
      field.name = name;
      form.appendChild(field);
    }
    field.value = String(value || '');
  }

  function insertNotice(form, title) {
    if (!form || !title) {
      return;
    }
    var container = form.closest('.a11yhubbr-container');
    if (!container || container.querySelector('.a11yhubbr-prefill-toast')) {
      return;
    }
    var notice = document.createElement('div');
    notice.className = 'a11yhubbr-toast a11yhubbr-prefill-toast';
    notice.setAttribute('role', 'status');
    notice.innerHTML = 'Voce esta sugerindo uma edicao para: <strong>' + title + '</strong>.';
    var submitGrid = container.querySelector('.a11yhubbr-submit-grid');
    if (submitGrid) {
      container.insertBefore(notice, submitGrid);
    } else {
      container.prepend(notice);
    }
  }

  function updateSubmitLabel(form) {
    var button = form ? form.querySelector('.a11yhubbr-form-submit') : null;
    if (button) {
      button.textContent = 'Enviar sugestao de edicao';
    }
  }

  function prefillContent(form, data) {
    setValue('content-type-select', data.type || '');
    setValue('content-title', data.title || '');
    setValue('content-description', data.description || '');
    setValue('content-organization', data.organization || '');
    setValue('content-link', data.link || '');
    setValue('content-tags', data.tags || '');
    setValue('content-year-publication', data.year_publication || '');
    setValue('content-depth', data.depth || '');
    setValue('content-article-authors', data.article_authors || '');
    setValue('content-article-kind', data.article_kind || '');
    setValue('content-book-modality', data.book_modality || '');
    setValue('content-book-price', data.book_price || '');
    setValue('content-tool-type', data.tool_type || '');
    setValue('content-tool-model', data.tool_model || '');
    setValue('content-media-theme', data.media_theme || '');
    setValue('content-media-channel-type', data.media_channel_type || '');
    setValue('content-media-format', data.media_format || '');
    setValue('content-media-platform', data.media_platform || '');
    setValue('content-media-frequency', data.media_frequency || '');
    setValue('content-site-business-model', data.site_business_model || '');
    setValue('content-site-stage', data.site_stage || '');
    setValue('content-site-access-model', data.site_access_model || '');
  }

  function prefillEvent(form, data) {
    setValue('event-modality', data.modality || '');
    setValue('event-type', data.event_type || '');
    setValue('event-title', data.title || '');
    setValue('event-cep', data.event_cep || '');
    setValue('event-online-location', data.event_online_location || '');
    setValue('event-organizer', data.organizer || '');
    setValue('event-link', data.link || '');
    setValue('event-tags', data.tags || '');
    setValue('event-description', data.description || '');

    var rows = document.getElementById('event-slots');
    var addButton = document.getElementById('add-slot');
    if (rows && addButton && Array.isArray(data.slots) && data.slots.length) {
      var existing = rows.querySelectorAll('.a11yhubbr-slot');
      for (var i = existing.length; i < data.slots.length; i++) {
        addButton.click();
      }
      rows.querySelectorAll('.a11yhubbr-slot').forEach(function (row, index) {
        var slot = data.slots[index] || {};
        var start = row.querySelector('input[name="slot_start[]"]');
        var end = row.querySelector('input[name="slot_end[]"]');
        if (start) start.value = slot.start || '';
        if (end) end.value = slot.end || '';
      });
    }
  }

  function prefillProfile(form, data) {
    setValue('profile-type', data.profile_type || '');
    setValue('profile-name', data.name || '');
    setValue('profile-location', data.location || '');
    setValue('profile-description', data.description || '');
    setValue('profile-role', data.role || '');
    setValue('profile-website', data.website || '');
    setValue('profile-tags', data.tags || '');

    var rows = document.getElementById('profile-social-links');
    var addButton = document.getElementById('add-social-link');
    if (rows && addButton && Array.isArray(data.social_links) && data.social_links.length) {
      var existing = rows.querySelectorAll('.a11yhubbr-slot');
      for (var i = existing.length; i < data.social_links.length; i++) {
        addButton.click();
      }
      rows.querySelectorAll('.a11yhubbr-slot').forEach(function (row, index) {
        var item = data.social_links[index] || {};
        var network = row.querySelector('select[name="social_network[]"]');
        var url = row.querySelector('input[name="social_url[]"]');
        if (network) network.value = item.network || '';
        if (url) url.value = item.url || '';
      });
    }
  }

  window.addEventListener('load', function () {
    var formIdMap = {
      content: 'content-form',
      event: 'event-form',
      profile: 'profile-form'
    };
    var form = document.getElementById(formIdMap[payload.type] || '');
    if (!form) {
      return;
    }

    ensureHiddenField(form, 'source_post', payload.data.source_post || 0);
    ensureHiddenField(form, 'a11yhubbr_redirect', window.location.href);
    insertNotice(form, payload.title || '');
    updateSubmitLabel(form);

    if (payload.type === 'content') {
      prefillContent(form, payload.data);
    } else if (payload.type === 'event') {
      prefillEvent(form, payload.data);
    } else if (payload.type === 'profile') {
      prefillProfile(form, payload.data);
    }

    if (typeof form.a11yhubbrRefreshAccordionState === 'function') {
      form.a11yhubbrRefreshAccordionState();
    }
  });
})();
JS;

    wp_add_inline_script('a11yhubbr-submissions', $script, 'after');
}
add_action('wp_enqueue_scripts', 'a11yhubbr_enqueue_submission_prefill_script', 20);


function a11yhubbr_validate_content_submission_data($data) {
    if (!is_array($data)) {
        return false;
    }

    if (empty($data['type']) || !a11yhubbr_get_content_type_by_slug($data['type'])) {
        return false;
    }

    if (empty($data['title']) || empty($data['description']) || empty($data['author']) || empty($data['link']) || empty($data['email'])) {
        return false;
    }

    if (!filter_var($data['link'], FILTER_VALIDATE_URL) || !is_email($data['email'])) {
        return false;
    }

    $type_slug = (string) $data['type'];
    $context_config = a11yhubbr_get_content_context_config();
    $year_enabled = $context_config['year_enabled_types'] ?? array();
    $depth_enabled = $context_config['depth_enabled_types'] ?? array();

    if (!in_array($type_slug, $year_enabled, true)) {
        $data['year_publication'] = '';
    } elseif (!empty($data['year_publication'])) {
        $current = (int) gmdate('Y');
        $year = (int) $data['year_publication'];
        if ($year < 1900 || $year > ($current + 1)) {
            return false;
        }
    }

    if (!in_array($type_slug, $depth_enabled, true)) {
        $data['depth'] = '';
    }

    return true;
}


function a11yhubbr_validate_event_submission_data($data) {
    if (!is_array($data)) {
        return false;
    }

    if (
        empty($data['modality']) ||
        empty($data['event_type']) ||
        empty($data['title']) ||
        empty($data['description']) ||
        empty($data['organizer']) ||
        empty($data['link']) ||
        empty($data['author']) ||
        empty($data['email'])
    ) {
        return false;
    }

    if (!in_array($data['modality'], array('presencial', 'online', 'hibrido'), true)) {
        return false;
    }

    if (!filter_var($data['link'], FILTER_VALIDATE_URL) || !is_email($data['email'])) {
        return false;
    }

    if (!isset($data['slots']) || !is_array($data['slots']) || empty($data['slots'])) {
        return false;
    }

    if ($data['modality'] === 'online') {
        if (empty($data['event_online_location'])) {
            return false;
        }
    } elseif ($data['modality'] === 'presencial') {
        if (empty($data['event_cep'])) {
            return false;
        }
    } elseif ($data['modality'] === 'hibrido') {
        if (empty($data['event_cep']) || empty($data['event_online_location'])) {
            return false;
        }
    }

    return true;
}


function a11yhubbr_validate_profile_submission_data($data) {
    if (!is_array($data)) {
        return false;
    }

    if (
        empty($data['profile_type']) ||
        empty($data['name']) ||
        empty($data['location']) ||
        empty($data['description']) ||
        empty($data['role']) ||
        empty($data['author']) ||
        empty($data['email'])
    ) {
        return false;
    }

    if (!is_email($data['email'])) {
        return false;
    }

    if ($data['website'] !== '' && !filter_var($data['website'], FILTER_VALIDATE_URL)) {
        return false;
    }

    return true;
}


function a11yhubbr_create_pending_content_post($data) {
    $title = $data['title'] !== '' ? $data['title'] : 'Submissao sem titulo';
    $type_slug = a11yhubbr_get_content_type_slug_from_input($data['type'] ?? '');
    if ($type_slug === '') {
        $type_slug = 'artigos';
    }
    $type_data = a11yhubbr_get_content_type_by_slug($type_slug);
    $type_label = $type_data['label'] ?? 'Artigos';

    $postarr = array(
        'post_type'    => 'a11y_conteudo',
        'post_status'  => 'pending',
        'post_title'   => $title,
        'post_excerpt' => wp_trim_words(wp_strip_all_tags($data['description']), 28),
        'post_content' => (string) $data['description'],
        'post_author'  => isset($data['user_id']) ? (int) $data['user_id'] : 0,
    );

    $post_id = wp_insert_post($postarr, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    $term = get_term_by('slug', $type_slug, 'category');
    if ($term && !is_wp_error($term)) {
        wp_set_post_terms($post_id, array((int) $term->term_id), 'category', false);
    }
    if (!empty($data['tags'])) {
        wp_set_post_terms($post_id, $data['tags'], 'post_tag', false);
    }

    // Compatibilidade temporÃ¡ria com dados legados que liam meta.
    update_post_meta($post_id, '_a11yhubbr_content_type', $type_label);
    update_post_meta($post_id, '_a11yhubbr_submitter_name', $data['author']);
    update_post_meta($post_id, '_a11yhubbr_submitter_org', $data['organization']);
    update_post_meta($post_id, '_a11yhubbr_source_link', $data['link']);
    update_post_meta($post_id, '_a11yhubbr_contact_email', $data['email']);
    update_post_meta($post_id, '_a11yhubbr_content_year_publication', $data['year_publication']);
    update_post_meta($post_id, '_a11yhubbr_content_depth', $data['depth']);
    update_post_meta($post_id, '_a11yhubbr_content_article_authors', $data['article_authors']);
    update_post_meta($post_id, '_a11yhubbr_content_article_kind', $data['article_kind']);
    update_post_meta($post_id, '_a11yhubbr_content_book_modality', $data['book_modality']);
    update_post_meta($post_id, '_a11yhubbr_content_book_price', $data['book_price']);
    update_post_meta($post_id, '_a11yhubbr_content_tool_type', $data['tool_type']);
    update_post_meta($post_id, '_a11yhubbr_content_tool_model', $data['tool_model']);
    update_post_meta($post_id, '_a11yhubbr_content_media_theme', $data['media_theme']);
    update_post_meta($post_id, '_a11yhubbr_content_media_channel_type', $data['media_channel_type']);
    update_post_meta($post_id, '_a11yhubbr_content_media_format', $data['media_format']);
    update_post_meta($post_id, '_a11yhubbr_content_media_platform', $data['media_platform']);
    update_post_meta($post_id, '_a11yhubbr_content_media_frequency', $data['media_frequency']);
    update_post_meta($post_id, '_a11yhubbr_content_site_business_model', $data['site_business_model']);
    update_post_meta($post_id, '_a11yhubbr_content_site_stage', $data['site_stage']);
    update_post_meta($post_id, '_a11yhubbr_content_site_access_model', $data['site_access_model']);
    if (!empty($data['source_post_id'])) {
        update_post_meta($post_id, '_a11yhubbr_edit_source_post', (int) $data['source_post_id']);
        update_post_meta($post_id, '_a11yhubbr_submission_mode', 'update');
    }

    return $post_id;
}


function a11yhubbr_create_pending_event_post($data) {
    $title = $data['title'] !== '' ? $data['title'] : 'Evento sem titulo';

    $postarr = array(
        'post_type'    => 'a11y_evento',
        'post_status'  => 'pending',
        'post_title'   => $title,
        'post_excerpt' => wp_trim_words(wp_strip_all_tags($data['description']), 28),
        'post_content' => (string) $data['description'],
        'post_author'  => isset($data['user_id']) ? (int) $data['user_id'] : 0,
    );

    $post_id = wp_insert_post($postarr, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    update_post_meta($post_id, '_a11yhubbr_event_modality', $data['modality']);
    update_post_meta($post_id, '_a11yhubbr_event_type', $data['event_type']);
    update_post_meta($post_id, '_a11yhubbr_event_location', $data['location']);
    update_post_meta($post_id, '_a11yhubbr_event_postal_code', $data['event_cep']);
    update_post_meta($post_id, '_a11yhubbr_event_online_location', $data['event_online_location']);
    update_post_meta($post_id, '_a11yhubbr_event_organizer', $data['organizer']);
    update_post_meta($post_id, '_a11yhubbr_event_link', $data['link']);
    update_post_meta($post_id, '_a11yhubbr_submitter_name', $data['author']);
    update_post_meta($post_id, '_a11yhubbr_contact_email', $data['email']);
    update_post_meta($post_id, '_a11yhubbr_event_slots', wp_json_encode($data['slots']));
    if (!empty($data['tags'])) {
        wp_set_post_terms($post_id, $data['tags'], 'post_tag', false);
    }
    if (!empty($data['source_post_id'])) {
        update_post_meta($post_id, '_a11yhubbr_edit_source_post', (int) $data['source_post_id']);
        update_post_meta($post_id, '_a11yhubbr_submission_mode', 'update');
    }

    return $post_id;
}


function a11yhubbr_create_pending_profile_post($data) {
    $title = $data['name'] !== '' ? $data['name'] : 'Perfil sem nome';

    $postarr = array(
        'post_type'    => 'a11y_perfil',
        'post_status'  => 'pending',
        'post_title'   => $title,
        'post_excerpt' => wp_trim_words(wp_strip_all_tags($data['description']), 28),
        'post_content' => (string) $data['description'],
        'post_author'  => isset($data['user_id']) ? (int) $data['user_id'] : 0,
    );

    $post_id = wp_insert_post($postarr, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    $owner_user_id = isset($data['user_id']) ? (int) $data['user_id'] : 0;
    if (!empty($data['source_post_id'])) {
        $source_owner_user_id = a11yhubbr_get_profile_owner_user_id((int) $data['source_post_id']);
        if ($source_owner_user_id > 0) {
            $owner_user_id = $source_owner_user_id;
        }
    }

    update_post_meta($post_id, '_a11yhubbr_profile_type', $data['profile_type']);
    update_post_meta($post_id, '_a11yhubbr_profile_location', $data['location']);
    update_post_meta($post_id, '_a11yhubbr_profile_role', $data['role']);
    update_post_meta($post_id, '_a11yhubbr_profile_website', $data['website']);
    update_post_meta($post_id, '_a11yhubbr_profile_social_links', wp_json_encode($data['social_links']));
    update_post_meta($post_id, '_a11yhubbr_profile_image_name', $data['profile_image_name']);
    update_post_meta($post_id, '_a11yhubbr_submitter_name', $data['author']);
    update_post_meta($post_id, '_a11yhubbr_contact_email', $data['email']);
    if ($owner_user_id > 0) {
        update_post_meta($post_id, '_a11yhubbr_owner_user_id', $owner_user_id);
    }
    if (!empty($data['tags'])) {
        wp_set_post_terms($post_id, $data['tags'], 'post_tag', false);
    }
    if (!empty($data['source_post_id'])) {
        update_post_meta($post_id, '_a11yhubbr_edit_source_post', (int) $data['source_post_id']);
        update_post_meta($post_id, '_a11yhubbr_submission_mode', 'update');
    }

    if (!empty($_FILES['profile_image']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $attachment_id = media_handle_upload('profile_image', $post_id);
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($post_id, (int) $attachment_id);
        }
    }

    return $post_id;
}


function a11yhubbr_build_email_message($type, $data) {
    if ($type === 'content') {
        $type_slug = a11yhubbr_get_content_type_slug_from_input($data['type'] ?? '');
        $type_data = a11yhubbr_get_content_type_by_slug($type_slug);
        $type_label = $type_data['label'] ?? 'Artigos';
        return implode("\n", array(
            'Nova submissao de conteudo',
            '--------------------------',
            'Tipo: ' . $type_label . ' (' . $type_slug . ')',
            'Titulo: ' . $data['title'],
            'Descricao: ' . $data['description'],
            'Autor da submissÃ£o: ' . $data['author'],
            'OrganizaÃ§Ã£o: ' . $data['organization'],
            'Link: ' . $data['link'],
            'Ano de publicaÃ§Ã£o/atualizaÃ§Ã£o: ' . ($data['year_publication'] !== '' ? $data['year_publication'] : '-'),
            'NÃ­vel de profundidade: ' . ($data['depth'] !== '' ? $data['depth'] : '-'),
            'Autorias (artigos): ' . ($data['article_authors'] !== '' ? $data['article_authors'] : '-'),
            'Tipo de artigo: ' . ($data['article_kind'] !== '' ? $data['article_kind'] : '-'),
            'Modalidade (livros e materiais): ' . ($data['book_modality'] !== '' ? $data['book_modality'] : '-'),
            'PreÃ§o (livros e materiais): ' . ($data['book_price'] !== '' ? $data['book_price'] : '-'),
            'Tipo de ferramenta: ' . ($data['tool_type'] !== '' ? $data['tool_type'] : '-'),
            'Modelo de ferramenta: ' . ($data['tool_model'] !== '' ? $data['tool_model'] : '-'),
            'Tema principal (multimÃ­dia): ' . ($data['media_theme'] !== '' ? $data['media_theme'] : '-'),
            'MÃ­dia: ' . ($data['media_channel_type'] !== '' ? $data['media_channel_type'] : '-'),
            'Formato: ' . ($data['media_format'] !== '' ? $data['media_format'] : '-'),
            'Plataforma: ' . ($data['media_platform'] !== '' ? $data['media_platform'] : '-'),
            'FrequÃªncia: ' . ($data['media_frequency'] !== '' ? $data['media_frequency'] : '-'),
            'Modelo de negÃ³cio (site/sistema): ' . ($data['site_business_model'] !== '' ? $data['site_business_model'] : '-'),
            'EstÃ¡gio do produto: ' . ($data['site_stage'] !== '' ? $data['site_stage'] : '-'),
            'Modelo de acesso: ' . ($data['site_access_model'] !== '' ? $data['site_access_model'] : '-'),
            'Tags: ' . implode(', ', $data['tags']),
            'Email de contato: ' . $data['email'],
        ));
    }

    if ($type === 'event') {
        $modality_label = array(
            'presencial' => 'Presencial',
            'online' => 'Online',
            'hibrido' => 'Hibrido',
        );
        $lines = array(
            'Nova submissao de evento',
            '------------------------',
            'Modalidade: ' . ($modality_label[$data['modality']] ?? $data['modality']),
            'Tipo de evento: ' . $data['event_type'],
            'TÃ­tulo: ' . $data['title'],
            'LocalizaÃ§Ã£o: ' . $data['location'],
            'CEP: ' . ($data['event_cep'] !== '' ? $data['event_cep'] : '-'),
            'Local online/plataforma: ' . ($data['event_online_location'] !== '' ? $data['event_online_location'] : '-'),
            'DescriÃ§Ã£o: ' . $data['description'],
            'Organizador: ' . $data['organizer'],
            'Link: ' . $data['link'],
            'Tags: ' . implode(', ', $data['tags']),
            'Autor da submissÃ£o: ' . $data['author'],
            'Email de contato: ' . $data['email'],
            '',
            'Datas e horÃ¡rios:',
        );

        foreach ($data['slots'] as $index => $slot) {
            $lines[] = sprintf('%d) InÃ­cio: %s | Fim: %s', $index + 1, $slot['start'], $slot['end']);
        }

        return implode("\n", $lines);
    }

    return implode("\n", array(
        'Nova submissao de perfil',
        '------------------------',
        'Tipo de perfil: ' . $data['profile_type'],
        'Nome/OrganizaÃ§Ã£o: ' . $data['name'],
        'LocalizaÃ§Ã£o: ' . $data['location'],
        'DescriÃ§Ã£o: ' . $data['description'],
        'Cargo/Especialidade: ' . $data['role'],
        'Website: ' . $data['website'],
        'Tags: ' . implode(', ', $data['tags']),
        'Redes sociais: ' . (is_array($data['social_links'])
            ? implode(' | ', array_map(static function ($item) {
                return sanitize_text_field($item['network'] ?? 'website') . ': ' . esc_url_raw($item['url'] ?? '');
            }, $data['social_links']))
            : ''),
        'Arquivo de foto: ' . $data['profile_image_name'],
        'Autor da submissÃ£o: ' . $data['author'],
        'Email de contato: ' . $data['email'],
    ));
}


function a11yhubbr_send_submission_email($type, $label, $data) {
    $subject = sprintf('[a11yhubbr] Nova submissao: %s', $label);
    $message = a11yhubbr_build_email_message($type, $data);

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    if (!empty($data['email']) && is_email($data['email'])) {
        $headers[] = 'Reply-To: ' . $data['email'];
    }

    return wp_mail(get_option('admin_email'), $subject, $message, $headers);
}


function a11yhubbr_get_submission_fingerprint($type) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : 'unknown';
    return md5($type . '|' . $ip . '|' . $agent);
}


function a11yhubbr_is_submission_rate_limited($type) {
    $key = 'a11yhubbr_rl_' . a11yhubbr_get_submission_fingerprint($type);
    $attempts = (int) get_transient($key);
    return $attempts >= 10;
}


function a11yhubbr_track_submission_attempt($type) {
    $key = 'a11yhubbr_rl_' . a11yhubbr_get_submission_fingerprint($type);
    $attempts = (int) get_transient($key);
    set_transient($key, $attempts + 1, 15 * MINUTE_IN_SECONDS);
}


function a11yhubbr_is_submission_spam_request() {
    $honeypot = isset($_POST['a11yhubbr_company']) ? trim((string) wp_unslash($_POST['a11yhubbr_company'])) : '';
    if ($honeypot !== '') {
        return true;
    }

    $ts = isset($_POST['a11yhubbr_ts']) ? absint($_POST['a11yhubbr_ts']) : 0;
    if ($ts > 0) {
        $elapsed = time() - $ts;
        if ($elapsed < 3 || $elapsed > DAY_IN_SECONDS) {
            return true;
        }
    }

    if (!a11yhubbr_turnstile_is_valid()) {
        return true;
    }

    return false;
}


function a11yhubbr_get_redirect_target() {
    $posted = isset($_POST['a11yhubbr_redirect'])
        ? esc_url_raw(wp_unslash($_POST['a11yhubbr_redirect']))
        : '';

    if (!empty($posted)) {
        return wp_validate_redirect($posted, home_url('/'));
    }

    $referer = wp_get_referer();
    if ($referer) {
        return wp_validate_redirect($referer, home_url('/'));
    }

    return home_url('/');
}

function a11yhubbr_handle_form_submissions() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $type = a11yhubbr_detect_form_type();
    if (!$type) {
        return;
    }

    $config = a11yhubbr_get_submission_config();
    $item = $config[$type] ?? null;
    if (!$item) {
        return;
    }

    if (!is_user_logged_in()) {
        wp_safe_redirect(a11yhubbr_get_submission_login_url(a11yhubbr_get_redirect_target()));
        exit;
    }

    $nonce = isset($_POST['a11yhubbr_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['a11yhubbr_nonce']))
        : '';

    if (!$nonce || !wp_verify_nonce($nonce, $item['nonce_action'])) {
        $redirect_url = add_query_arg(
            array('a11yhubbr_form' => $type, 'a11yhubbr_status' => 'error'),
            a11yhubbr_get_redirect_target()
        );
        wp_safe_redirect($redirect_url);
        exit;
    }

    if (a11yhubbr_is_submission_spam_request() || a11yhubbr_is_submission_rate_limited($type)) {
        $redirect_url = add_query_arg(
            array('a11yhubbr_form' => $type, 'a11yhubbr_status' => 'error'),
            a11yhubbr_get_redirect_target()
        );
        wp_safe_redirect($redirect_url);
        exit;
    }

    a11yhubbr_track_submission_attempt($type);

    $data = a11yhubbr_sanitize_submission_data($type);
    $created = false;

    if ($type === 'content') {
        if (!a11yhubbr_validate_content_submission_data($data)) {
            $result = new WP_Error('invalid_content_data', 'Dados de conteÃºdo invÃ¡lidos');
            $created = false;
        } else {
        $result = a11yhubbr_create_pending_content_post($data);
        $created = !is_wp_error($result);
        }
    } elseif ($type === 'event') {
        if (!a11yhubbr_validate_event_submission_data($data)) {
            $result = new WP_Error('invalid_event_data', 'Dados de evento invalidos');
            $created = false;
        } else {
            $result = a11yhubbr_create_pending_event_post($data);
            $created = !is_wp_error($result);
        }
    } else {
        if (!a11yhubbr_validate_profile_submission_data($data)) {
            $result = new WP_Error('invalid_profile_data', 'Dados de perfil invÃ¡lidos');
            $created = false;
        } else {
            $result = a11yhubbr_create_pending_profile_post($data);
            $created = !is_wp_error($result);
        }
    }

    if ($created) {
        a11yhubbr_send_submission_email($type, $item['label'], $data);
    }

    $redirect_url = add_query_arg(
        array(
            'a11yhubbr_form' => $type,
            'a11yhubbr_status' => $created ? 'success' : 'error',
        ),
        a11yhubbr_get_redirect_target()
    );

    wp_safe_redirect($redirect_url);
    exit;
}
add_action('init', 'a11yhubbr_handle_form_submissions');

/* Admin list columns for submissions */
