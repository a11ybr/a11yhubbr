<?php

if (!defined('ABSPATH')) {
    exit;
}

function a11yhubbr_get_admin_content_editor_option_labels() {
    return array(
        'depth' => array(
            'introdutorio' => 'Introdutório',
            'intermediario' => 'Intermediário',
            'avancado' => 'Avançado',
        ),
        'article_kind' => array(
            'academico' => 'Acadêmico',
            'ativismo' => 'Ativismo',
            'estudo-caso' => 'Estudo de caso',
            'opinativo' => 'Opinativo',
            'tecnico' => 'Técnico',
            'outro' => 'Outro',
        ),
        'book_modality' => array(
            'online' => 'Online',
            'presencial' => 'Presencial',
            'hibrido' => 'Híbrido',
            'nao-se-aplica' => 'Não se aplica',
        ),
        'book_price' => array(
            'gratuito' => 'Gratuito',
            'pago' => 'Pago',
        ),
        'tool_type' => array(
            'auditoria-automatica' => 'Auditoria automática',
            'testes-manuais' => 'Testes manuais',
            'contraste' => 'Contraste',
            'design-system' => 'Design System',
            'plugin' => 'Plugin',
            'outros' => 'Outros',
        ),
        'tool_model' => array(
            'open-source' => 'Open source',
            'freemium' => 'Freemium',
            'pago' => 'Pago',
        ),
        'media_channel_type' => array(
            'audio' => 'Áudio',
            'video' => 'Vídeo',
        ),
        'media_format' => array(
            'entrevista' => 'Entrevista',
            'mesa-redonda' => 'Mesa-redonda',
            'solo' => 'Solo',
            'tecnico' => 'Técnico',
            'storytelling' => 'Storytelling',
            'outro' => 'Outro',
        ),
        'media_platform' => array(
            'spotify' => 'Spotify',
            'apple' => 'Apple',
            'site' => 'Site',
            'youtube' => 'YouTube / YouTube Music',
            'deezer' => 'Deezer',
            'amazon-music' => 'Amazon Music',
            'outro' => 'Outro',
        ),
        'media_frequency' => array(
            'semanal' => 'Semanal',
            'quinzenal' => 'Quinzenal',
            'mensal' => 'Mensal',
            'pontual' => 'Pontual',
        ),
        'site_business_model' => array(
            'saas' => 'SaaS',
            'e-commerce-marketplace' => 'E-commerce / Marketplace',
            'open-source' => 'Open source',
            'governamental' => 'Governamental',
        ),
        'site_stage' => array(
            'mvp' => 'MVP',
            'em-crescimento' => 'Em crescimento',
            'estavel' => 'Estável',
            'legado' => 'Legado',
        ),
        'site_access_model' => array(
            'aberto' => 'Aberto',
            'login-obrigatorio' => 'Login obrigatório',
        ),
    );
}

function a11yhubbr_render_admin_content_editor_select($name, $id, $options, $selected, $placeholder = 'Selecione') {
    ?>
    <select name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>">
      <option value=""><?php echo esc_html($placeholder); ?></option>
      <?php foreach ($options as $value => $label): ?>
        <option value="<?php echo esc_attr($value); ?>" <?php selected((string) $selected, (string) $value); ?>><?php echo esc_html($label); ?></option>
      <?php endforeach; ?>
    </select>
    <?php
}

function a11yhubbr_enqueue_admin_content_editor_assets($hook_suffix) {
    if (!in_array($hook_suffix, array('post.php', 'post-new.php'), true)) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'a11y_conteudo') {
        return;
    }

    $submissions_js_path = get_template_directory() . '/assets/js/submissions.js';
    $submissions_js_ver = file_exists($submissions_js_path) ? (string) filemtime($submissions_js_path) : wp_get_theme()->get('Version');

    wp_enqueue_script(
        'a11yhubbr-submissions-admin',
        get_template_directory_uri() . '/assets/js/submissions.js',
        array(),
        $submissions_js_ver,
        true
    );

    wp_register_style('a11yhubbr-admin-content-editor', false, array(), wp_get_theme()->get('Version'));
    wp_enqueue_style('a11yhubbr-admin-content-editor');
    wp_add_inline_style('a11yhubbr-admin-content-editor', '
      .a11yhubbr-admin-content-editor{display:grid;gap:16px;padding-top:6px}
      .a11yhubbr-admin-content-editor-note{margin:0;padding:14px 16px;border:1px solid #d8dfec;border-radius:12px;background:#f8fafc;color:#50627f}
      .a11yhubbr-admin-content-editor .a11yhubbr-form-section{margin:0;border:1px solid #d8dfec;border-radius:12px;background:#fff;overflow:hidden}
      .a11yhubbr-admin-content-editor .a11yhubbr-section-toggle{all:unset;box-sizing:border-box;display:flex;justify-content:space-between;align-items:center;width:100%;padding:14px 16px;font-weight:600;cursor:pointer}
      .a11yhubbr-admin-content-editor .a11yhubbr-section-toggle:hover{background:#f8fafc}
      .a11yhubbr-admin-content-editor .a11yhubbr-form-section-body{display:grid;gap:14px;padding:0 16px 16px}
      .a11yhubbr-admin-content-editor .a11yhubbr-form-section.is-collapsed .a11yhubbr-form-section-body{display:none}
      .a11yhubbr-admin-content-editor .a11yhubbr-field-inline{display:grid;gap:6px}
      .a11yhubbr-admin-content-editor .a11yhubbr-field-control{display:grid;gap:6px}
      .a11yhubbr-admin-content-editor label{font-weight:600}
      .a11yhubbr-admin-content-editor input[type=text],
      .a11yhubbr-admin-content-editor input[type=url],
      .a11yhubbr-admin-content-editor input[type=number],
      .a11yhubbr-admin-content-editor select,
      .a11yhubbr-admin-content-editor textarea{width:100%;max-width:none}
      .a11yhubbr-admin-content-editor .a11yhubbr-help{margin:0;color:#6b778c;font-size:12px}
      .a11yhubbr-admin-content-editor .a11yhubbr-content-conditional[hidden]{display:none!important}
      .a11yhubbr-admin-content-editor .a11yhubbr-required-mark{color:#c0392b}
    ');
}
add_action('admin_enqueue_scripts', 'a11yhubbr_enqueue_admin_content_editor_assets');

function a11yhubbr_customize_admin_content_editor_screen() {
    remove_meta_box('categorydiv', 'a11y_conteudo', 'side');
    remove_meta_box('tagsdiv-post_tag', 'a11y_conteudo', 'side');
    remove_meta_box('postcustom', 'a11y_conteudo', 'normal');

    add_meta_box(
        'a11yhubbr_content_submission_editor',
        'Detalhes da submissão',
        'a11yhubbr_render_admin_content_editor_metabox',
        'a11y_conteudo',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_a11y_conteudo', 'a11yhubbr_customize_admin_content_editor_screen');

function a11yhubbr_render_admin_content_editor_metabox($post) {
    $content_types = function_exists('a11yhubbr_get_content_type_map') ? a11yhubbr_get_content_type_map() : array();
    $content_types = array_diff_key($content_types, array(
        'eventos' => true,
        'comunidades' => true,
        'redes' => true,
    ));
    $config = function_exists('a11yhubbr_get_content_context_config') ? a11yhubbr_get_content_context_config() : array();
    $option_labels = a11yhubbr_get_admin_content_editor_option_labels();

    $categories = get_the_terms($post->ID, 'category');
    $selected_type = '';
    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $term) {
            $slug = a11yhubbr_get_content_type_slug_from_input($term->slug);
            if ($slug !== '') {
                $selected_type = $slug;
                break;
            }
        }
    }
    if ($selected_type === '') {
        $selected_type = a11yhubbr_get_content_type_slug_from_input((string) get_post_meta($post->ID, '_a11yhubbr_content_type', true));
    }

    $tags = wp_get_post_terms($post->ID, 'post_tag', array('fields' => 'names'));
    if (!is_array($tags)) {
        $tags = array();
    }

    wp_nonce_field('a11yhubbr_admin_content_editor', 'a11yhubbr_admin_content_editor_nonce');
    ?>
    <div id="content-form" class="a11yhubbr-admin-content-editor a11yhubbr-submit-form">
      <p class="a11yhubbr-admin-content-editor-note">
        Use os campos nativos do WordPress para <strong>título</strong> e <strong>descrição</strong>. Os detalhes abaixo seguem a mesma organização da página de submissão.
      </p>

      <section class="a11yhubbr-form-section" id="sec-conteudo-principal" data-collapsible-section>
        <h2>Informações principais</h2>
        <div class="a11yhubbr-field-inline">
          <label for="content-type-select">Tipo de conteúdo <span class="a11yhubbr-required-mark" aria-hidden="true">*</span></label>
          <div class="a11yhubbr-field-control">
            <select name="a11yhubbr_content_type_slug" id="content-type-select" required>
              <option value="">Selecione</option>
              <?php foreach ($content_types as $slug => $type): ?>
                <option value="<?php echo esc_attr($slug); ?>" <?php selected($selected_type, $slug); ?>><?php echo esc_html($type['label']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </section>

      <section class="a11yhubbr-form-section" id="sec-conteudo-detalhes" data-collapsible-section>
        <h2>Detalhes da submissão</h2>
        <div class="a11yhubbr-field-inline">
          <label for="content-organization">Organização</label>
          <div class="a11yhubbr-field-control">
            <input id="content-organization" type="text" name="a11yhubbr_content_organization" value="<?php echo esc_attr((string) get_post_meta($post->ID, '_a11yhubbr_submitter_org', true)); ?>">
          </div>
        </div>

        <div class="a11yhubbr-field-inline">
          <label for="content-link">Link do conteúdo <span class="a11yhubbr-required-mark" aria-hidden="true">*</span></label>
          <div class="a11yhubbr-field-control">
            <input id="content-link" type="url" name="a11yhubbr_content_link" value="<?php echo esc_attr((string) get_post_meta($post->ID, '_a11yhubbr_source_link', true)); ?>" required>
          </div>
        </div>

        <div class="a11yhubbr-field-inline">
          <label for="content-tags">Tags</label>
          <div class="a11yhubbr-field-control">
            <input id="content-tags" type="text" name="a11yhubbr_content_tags" value="<?php echo esc_attr(implode(', ', $tags)); ?>" placeholder="acessibilidade, wcag, ux">
            <p class="a11yhubbr-help">Use vírgulas para separar as tags. Esse campo substitui a box padrão de tags.</p>
          </div>
        </div>

        <div class="a11yhubbr-content-conditional" data-content-types="<?php echo esc_attr(implode(',', $config['year_enabled_types'] ?? array())); ?>" hidden>
          <div class="a11yhubbr-field-inline">
            <label for="content-year-publication">Ano de publicação/atualização</label>
            <div class="a11yhubbr-field-control">
              <input id="content-year-publication" type="number" name="a11yhubbr_content_year_publication" min="1900" max="2100" step="1" value="<?php echo esc_attr((string) get_post_meta($post->ID, '_a11yhubbr_content_year_publication', true)); ?>">
            </div>
          </div>
        </div>

        <div class="a11yhubbr-content-conditional" data-content-types="<?php echo esc_attr(implode(',', $config['depth_enabled_types'] ?? array())); ?>" hidden>
          <div class="a11yhubbr-field-inline">
            <label for="content-depth">Nível de profundidade</label>
            <div class="a11yhubbr-field-control">
              <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_depth', 'content-depth', $option_labels['depth'], (string) get_post_meta($post->ID, '_a11yhubbr_content_depth', true)); ?>
            </div>
          </div>
        </div>
      </section>

      <section class="a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-artigos" data-content-types="artigos" data-collapsible-section hidden>
        <h2>Informações adiconais do artigo</h2>
        <div class="a11yhubbr-field-inline">
          <label for="content-article-authors">Nomes das pessoas autoras</label>
          <div class="a11yhubbr-field-control">
            <input id="content-article-authors" type="text" name="a11yhubbr_content_article_authors" value="<?php echo esc_attr((string) get_post_meta($post->ID, '_a11yhubbr_content_article_authors', true)); ?>">
          </div>
        </div>
        <div class="a11yhubbr-field-inline">
          <label for="content-article-kind">Tipo de artigo</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_article_kind', 'content-article-kind', $option_labels['article_kind'], (string) get_post_meta($post->ID, '_a11yhubbr_content_article_kind', true)); ?>
          </div>
        </div>
      </section>

      <section class="a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-livros" data-content-types="cursos-materiais" data-collapsible-section hidden>
        <h2>Informações adiconais do livro ou material</h2>
        <div class="a11yhubbr-field-inline">
          <label for="content-book-modality">Modalidade</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_book_modality', 'content-book-modality', $option_labels['book_modality'], (string) get_post_meta($post->ID, '_a11yhubbr_content_book_modality', true)); ?>
          </div>
        </div>
        <div class="a11yhubbr-field-inline">
          <label for="content-book-price">Preço</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_book_price', 'content-book-price', $option_labels['book_price'], (string) get_post_meta($post->ID, '_a11yhubbr_content_book_price', true)); ?>
          </div>
        </div>
      </section>

      <section class="a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-ferramentas" data-content-types="ferramentas" data-collapsible-section hidden>
        <h2>Informações adiconais da ferramenta</h2>
        <div class="a11yhubbr-field-inline">
          <label for="content-tool-type">Tipo</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_tool_type', 'content-tool-type', $option_labels['tool_type'], (string) get_post_meta($post->ID, '_a11yhubbr_content_tool_type', true)); ?>
          </div>
        </div>
        <div class="a11yhubbr-field-inline">
          <label for="content-tool-model">Modelo</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_tool_model', 'content-tool-model', $option_labels['tool_model'], (string) get_post_meta($post->ID, '_a11yhubbr_content_tool_model', true)); ?>
          </div>
        </div>
      </section>

      <section class="a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-multimidia" data-content-types="multimidia" data-collapsible-section hidden>
        <h2>Informações adiconais da multimídia</h2>
        <div class="a11yhubbr-field-inline">
          <label for="content-media-theme">Tema principal</label>
          <div class="a11yhubbr-field-control">
            <input id="content-media-theme" type="text" name="a11yhubbr_content_media_theme" value="<?php echo esc_attr((string) get_post_meta($post->ID, '_a11yhubbr_content_media_theme', true)); ?>">
          </div>
        </div>
        <div class="a11yhubbr-field-inline">
          <label for="content-media-channel-type">Mídia</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_media_channel_type', 'content-media-channel-type', $option_labels['media_channel_type'], (string) get_post_meta($post->ID, '_a11yhubbr_content_media_channel_type', true)); ?>
          </div>
        </div>
        <div class="a11yhubbr-field-inline">
          <label for="content-media-format">Formato</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_media_format', 'content-media-format', $option_labels['media_format'], (string) get_post_meta($post->ID, '_a11yhubbr_content_media_format', true)); ?>
          </div>
        </div>
        <div class="a11yhubbr-field-inline">
          <label for="content-media-platform">Plataforma</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_media_platform', 'content-media-platform', $option_labels['media_platform'], (string) get_post_meta($post->ID, '_a11yhubbr_content_media_platform', true)); ?>
          </div>
        </div>
        <div class="a11yhubbr-field-inline">
          <label for="content-media-frequency">Frequência</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_media_frequency', 'content-media-frequency', $option_labels['media_frequency'], (string) get_post_meta($post->ID, '_a11yhubbr_content_media_frequency', true)); ?>
          </div>
        </div>
      </section>

      <section class="a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-sites" data-content-types="sites-sistemas" data-collapsible-section hidden>
        <h2>Informações adiconais do site ou sistema</h2>
        <div class="a11yhubbr-field-inline">
          <label for="content-site-business-model">Modelo de negócio</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_site_business_model', 'content-site-business-model', $option_labels['site_business_model'], (string) get_post_meta($post->ID, '_a11yhubbr_content_site_business_model', true)); ?>
          </div>
        </div>
        <div class="a11yhubbr-field-inline">
          <label for="content-site-stage">Estágio do produto</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_site_stage', 'content-site-stage', $option_labels['site_stage'], (string) get_post_meta($post->ID, '_a11yhubbr_content_site_stage', true)); ?>
          </div>
        </div>
        <div class="a11yhubbr-field-inline">
          <label for="content-site-access-model">Modelo de acesso</label>
          <div class="a11yhubbr-field-control">
            <?php a11yhubbr_render_admin_content_editor_select('a11yhubbr_content_site_access_model', 'content-site-access-model', $option_labels['site_access_model'], (string) get_post_meta($post->ID, '_a11yhubbr_content_site_access_model', true)); ?>
          </div>
        </div>
      </section>
    </div>
    <?php
}

function a11yhubbr_save_admin_content_editor_metabox($post_id) {
    if (!isset($_POST['a11yhubbr_admin_content_editor_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['a11yhubbr_admin_content_editor_nonce'])), 'a11yhubbr_admin_content_editor')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $type_slug = a11yhubbr_get_content_type_slug_from_input($_POST['a11yhubbr_content_type_slug'] ?? '');
    if ($type_slug !== '') {
        $term = get_term_by('slug', $type_slug, 'category');
        if ($term && !is_wp_error($term)) {
            wp_set_post_terms($post_id, array((int) $term->term_id), 'category', false);
            $type_data = a11yhubbr_get_content_type_by_slug($type_slug);
            update_post_meta($post_id, '_a11yhubbr_content_type', (string) ($type_data['label'] ?? ''));
        }
    }

    $tags = a11yhubbr_parse_tags_from_input($_POST['a11yhubbr_content_tags'] ?? '');
    wp_set_post_terms($post_id, $tags, 'post_tag', false);

    $context_config = a11yhubbr_get_content_context_config();
    $choices = isset($context_config['choices']) && is_array($context_config['choices']) ? $context_config['choices'] : array();
    $year_value = isset($_POST['a11yhubbr_content_year_publication']) ? absint($_POST['a11yhubbr_content_year_publication']) : 0;
    $current_year = (int) gmdate('Y');
    $year = ($year_value >= 1900 && $year_value <= ($current_year + 1)) ? (string) $year_value : '';

    update_post_meta($post_id, '_a11yhubbr_submitter_org', sanitize_text_field(wp_unslash($_POST['a11yhubbr_content_organization'] ?? '')));
    update_post_meta($post_id, '_a11yhubbr_source_link', esc_url_raw(wp_unslash($_POST['a11yhubbr_content_link'] ?? '')));
    update_post_meta($post_id, '_a11yhubbr_content_year_publication', $year);
    update_post_meta($post_id, '_a11yhubbr_content_depth', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_depth'] ?? '', $choices['depth'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_article_authors', sanitize_text_field(wp_unslash($_POST['a11yhubbr_content_article_authors'] ?? '')));
    update_post_meta($post_id, '_a11yhubbr_content_article_kind', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_article_kind'] ?? '', $choices['article_kind'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_book_modality', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_book_modality'] ?? '', $choices['book_modality'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_book_price', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_book_price'] ?? '', $choices['book_price'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_tool_type', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_tool_type'] ?? '', $choices['tool_type'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_tool_model', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_tool_model'] ?? '', $choices['tool_model'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_media_theme', sanitize_text_field(wp_unslash($_POST['a11yhubbr_content_media_theme'] ?? '')));
    update_post_meta($post_id, '_a11yhubbr_content_media_channel_type', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_media_channel_type'] ?? '', $choices['media_channel_type'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_media_format', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_media_format'] ?? '', $choices['media_format'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_media_platform', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_media_platform'] ?? '', $choices['media_platform'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_media_frequency', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_media_frequency'] ?? '', $choices['media_frequency'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_site_business_model', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_site_business_model'] ?? '', $choices['site_business_model'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_site_stage', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_site_stage'] ?? '', $choices['site_stage'] ?? array()));
    update_post_meta($post_id, '_a11yhubbr_content_site_access_model', a11yhubbr_sanitize_choice($_POST['a11yhubbr_content_site_access_model'] ?? '', $choices['site_access_model'] ?? array()));
}
add_action('save_post_a11y_conteudo', 'a11yhubbr_save_admin_content_editor_metabox');
