<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'title' => '',
    'description' => '',
    'primary' => array('label' => '', 'url' => ''),
    'secondary' => array('label' => '', 'url' => ''),
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;

if (!function_exists('a11yhubbr_should_render_global_page_cta')) {
    function a11yhubbr_should_render_global_page_cta() {
        if (is_admin()) {
            return false;
        }

        if (function_exists('a11yhubbr_is_submit_path_active') && a11yhubbr_is_submit_path_active()) {
            return false;
        }

        if (is_page()) {
            $queried_id = (int) get_queried_object_id();
            if ($queried_id > 0) {
                $ancestor_ids = get_post_ancestors($queried_id);
                foreach ($ancestor_ids as $ancestor_id) {
                    $slug = (string) get_post_field('post_name', (int) $ancestor_id);
                    if (sanitize_title($slug) === 'submeter') {
                        return false;
                    }
                }
            }
        }

        if (
            is_front_page() ||
            is_home() ||
            is_page() ||
            is_singular(array('a11y_conteudo', 'a11y_evento', 'a11y_perfil', 'post')) ||
            is_post_type_archive(array('a11y_conteudo', 'a11y_evento', 'a11y_perfil')) ||
            is_category() ||
            is_tag() ||
            is_search()
        ) {
            return true;
        }

        return false;
    }
}

if (!function_exists('a11yhubbr_get_global_page_cta_args')) {
    function a11yhubbr_get_global_page_cta_args() {
        $context = function_exists('a11yhubbr_get_header_context') ? a11yhubbr_get_header_context() : 'default';
        $queried_id = (int) get_queried_object_id();
        $page_slug = '';

        if ($queried_id > 0) {
            $page_slug = sanitize_title((string) get_post_field('post_name', $queried_id));
        }

        $args = array(
            'title' => 'Se fizer sentido para a pauta, vale registrar',
            'description' => 'A contribuição mais útil para a plataforma é aquela que ajuda outra pessoa a encontrar contexto, referência ou contato relevante sobre acessibilidade digital.',
            'primary' => array(
                'label' => 'Ir para submissão',
                'url' => home_url('/submeter'),
            ),
            'secondary' => array(
                'label' => 'Ler diretrizes',
                'url' => home_url('/diretrizes-da-comunidade'),
            ),
        );

        if ($page_slug === 'diretrizes-da-comunidade') {
            $args['secondary'] = array(
                'label' => '',
                'url' => '',
            );
        } elseif ($context === 'conteudos') {
            $args['primary'] = array(
                'label' => 'Submeter conteúdo',
                'url' => function_exists('a11yhubbr_get_submit_content_url') ? a11yhubbr_get_submit_content_url() : home_url('/submeter/submeter-conteudo'),
            );
            $args['secondary'] = array(
                'label' => 'Ler diretrizes',
                'url' => home_url('/diretrizes-da-comunidade'),
            );
        } elseif ($context === 'eventos') {
            $args['primary'] = array(
                'label' => 'Submeter evento',
                'url' => function_exists('a11yhubbr_get_submit_event_url') ? a11yhubbr_get_submit_event_url() : home_url('/submeter/submeter-eventos'),
            );
            $args['secondary'] = array(
                'label' => 'Ler diretrizes',
                'url' => home_url('/diretrizes-da-comunidade'),
            );
        } elseif ($context === 'rede') {
            $args['primary'] = array(
                'label' => 'Submeter perfil',
                'url' => function_exists('a11yhubbr_get_submit_profile_url') ? a11yhubbr_get_submit_profile_url() : home_url('/submeter/submeter-perfil'),
            );
            $args['secondary'] = array(
                'label' => 'Ler diretrizes',
                'url' => home_url('/diretrizes-da-comunidade'),
            );
        }

        return $args;
    }
}

if (!function_exists('a11yhubbr_render_global_page_cta')) {
    function a11yhubbr_render_global_page_cta() {
        if (!a11yhubbr_should_render_global_page_cta()) {
            return;
        }

        get_template_part('inc/components/cta-box', null, a11yhubbr_get_global_page_cta_args());
    }
}

$has_cta_content = (
    $args['title'] !== '' ||
    $args['description'] !== '' ||
    (!empty($args['primary']['label']) && !empty($args['primary']['url'])) ||
    (!empty($args['secondary']['label']) && !empty($args['secondary']['url']))
);

if (!$has_cta_content) {
    return;
}
?>
<div class="a11yhubbr-submit-cta-box a11yhubbr-section-cta">
  <?php if ($args['title'] !== ''): ?>
    <h2><?php echo esc_html($args['title']); ?></h2>
  <?php endif; ?>

  <?php if ($args['description'] !== ''): ?>
    <p><?php echo esc_html($args['description']); ?></p>
  <?php endif; ?>

  <div class="a11yhubbr-actions">
    <?php if (!empty($args['secondary']['label']) && !empty($args['secondary']['url'])): ?>
      <a class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light a11yhubbr-btn-context-secondary" href="<?php echo esc_url($args['secondary']['url']); ?>"><?php echo esc_html($args['secondary']['label']); ?></a>
    <?php endif; ?>
    <?php if (!empty($args['primary']['label']) && !empty($args['primary']['url'])): ?>
      <a class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-btn-context" href="<?php echo esc_url($args['primary']['url']); ?>"><?php echo esc_html($args['primary']['label']); ?></a>
    <?php endif; ?>
  </div>
</div>
