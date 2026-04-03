<?php
if (!defined('ABSPATH')) {
    exit;
}

$types = function_exists('a11yhubbr_get_content_type_map') ? a11yhubbr_get_content_type_map() : array();

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-site-main a11yhubbr-single-page a11yhubbr-single-content-page">
  <?php while (have_posts()): the_post(); ?>
    <?php
    $post_id = get_the_ID();
    $category_terms = get_the_terms($post_id, 'category');
    $type_label = 'Conteudo';
    $type_icon = 'fa-regular fa-file-lines';
    $primary_type_slug = '';
    $primary_category_id = 0;

    if (!empty($category_terms) && !is_wp_error($category_terms)) {
        foreach ($types as $slug => $type) {
            foreach ($category_terms as $term) {
                if ($term->slug === $slug) {
                    $type_label = $term->name;
                    $type_icon = isset($type['icon']) ? (string) $type['icon'] : 'fa-regular fa-file-lines';
                    $primary_type_slug = (string) $term->slug;
                    $primary_category_id = (int) $term->term_id;
                    break 2;
                }
            }
        }

        if ($primary_category_id <= 0) {
            $primary_category_id = (int) $category_terms[0]->term_id;
        }
    }

    $source_link = (string) get_post_meta($post_id, '_a11yhubbr_source_link', true);
    $submitter_name = (string) get_post_meta($post_id, '_a11yhubbr_submitter_name', true);
    $submitter_org = (string) get_post_meta($post_id, '_a11yhubbr_submitter_org', true);
    $year_publication = (string) get_post_meta($post_id, '_a11yhubbr_content_year_publication', true);
    $depth = (string) get_post_meta($post_id, '_a11yhubbr_content_depth', true);
    $article_authors = (string) get_post_meta($post_id, '_a11yhubbr_content_article_authors', true);
    $article_kind = (string) get_post_meta($post_id, '_a11yhubbr_content_article_kind', true);
    $book_modality = (string) get_post_meta($post_id, '_a11yhubbr_content_book_modality', true);
    $book_price = (string) get_post_meta($post_id, '_a11yhubbr_content_book_price', true);
    $tool_type = (string) get_post_meta($post_id, '_a11yhubbr_content_tool_type', true);
    $tool_model = (string) get_post_meta($post_id, '_a11yhubbr_content_tool_model', true);
    $media_theme = (string) get_post_meta($post_id, '_a11yhubbr_content_media_theme', true);
    $media_channel_type = (string) get_post_meta($post_id, '_a11yhubbr_content_media_channel_type', true);
    $media_format = (string) get_post_meta($post_id, '_a11yhubbr_content_media_format', true);
    $media_platform = (string) get_post_meta($post_id, '_a11yhubbr_content_media_platform', true);
    $media_frequency = (string) get_post_meta($post_id, '_a11yhubbr_content_media_frequency', true);
    $site_business_model = (string) get_post_meta($post_id, '_a11yhubbr_content_site_business_model', true);
    $site_stage = (string) get_post_meta($post_id, '_a11yhubbr_content_site_stage', true);
    $site_access_model = (string) get_post_meta($post_id, '_a11yhubbr_content_site_access_model', true);

    $content_option_labels = array(
        'depth' => array(
            'introdutorio' => 'Introdutorio',
            'intermediario' => 'Intermediario',
            'avancado' => 'Avancado',
        ),
        'article_kind' => array(
            'academico' => 'Academico',
            'ativismo' => 'Ativismo',
            'estudo-caso' => 'Estudo de caso',
            'opinativo' => 'Opinativo',
            'tecnico' => 'Tecnico',
            'outro' => 'Outro',
        ),
        'book_modality' => array(
            'online' => 'Online',
            'presencial' => 'Presencial',
            'hibrido' => 'Hibrido',
            'nao-se-aplica' => 'Nao se aplica',
        ),
        'book_price' => array(
            'gratuito' => 'Gratuito',
            'pago' => 'Pago',
        ),
        'tool_type' => array(
            'auditoria-automatica' => 'Auditoria automatica',
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
            'audio' => 'Audio',
            'video' => 'Video',
        ),
        'media_format' => array(
            'entrevista' => 'Entrevista',
            'mesa-redonda' => 'Mesa-redonda',
            'solo' => 'Solo',
            'tecnico' => 'Tecnico',
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
            'estavel' => 'Estavel',
            'legado' => 'Legado',
        ),
        'site_access_model' => array(
            'aberto' => 'Aberto',
            'login-obrigatorio' => 'Login obrigatorio',
        ),
    );

    $resolve_content_option = static function ($group, $value) use ($content_option_labels) {
        $value = sanitize_title((string) $value);
        if ($value === '') {
            return '';
        }
        $group_map = $content_option_labels[$group] ?? array();
        return $group_map[$value] ?? '';
    };
    $tags = get_the_terms($post_id, 'post_tag');
    $permalink = get_permalink($post_id);
    $tag_ids = wp_get_post_terms($post_id, 'post_tag', array('fields' => 'ids'));

    $raw_content = (string) get_post_field('post_content', $post_id);
    $legacy_markers = array(
        'Tipo de conteudo:',
        'Autor indicado:',
        'Organizacao:',
        'Link de referencia:',
        'Tags:',
    );

    $filtered_lines = array();
    foreach (preg_split('/\R/u', $raw_content) as $line) {
        $trimmed = ltrim((string) $line);
        $is_legacy = false;

        foreach ($legacy_markers as $marker) {
            if (stripos($trimmed, $marker) === 0) {
                $is_legacy = true;
                break;
            }
        }

        if ($is_legacy) {
            break;
        }

        $filtered_lines[] = $line;
    }

    $content_to_render = trim(implode("\n", $filtered_lines));
    if ($content_to_render === '') {
        $content_to_render = $raw_content;
    }
    $content_to_render = apply_filters('the_content', $content_to_render);

    $related_query = null;
    if (!empty($tag_ids)) {
        $related_query = new WP_Query(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => true,
            'tag__in' => array_map('intval', $tag_ids),
        ));
    }

    if (!($related_query instanceof WP_Query) || !$related_query->have_posts()) {
        $category_args = array();
        if ($primary_category_id > 0) {
            $category_args['category__in'] = array($primary_category_id);
        }

        $related_query = new WP_Query(array_merge(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => true,
        ), $category_args));
    }

    a11yhubbr_render_page_header(array(
        'breadcrumbs' => array(
            array('label' => 'Pagina inicial', 'url' => home_url('/')),
            array('label' => 'Conteudos', 'url' => home_url('/conteudos')),
            array('label' => get_the_title()),
        ),
        'icon' => '',
        'title' => get_the_title(),
        'summary' => '',
        'use_page_context' => false,
        'context' => 'conteudos',
    ));
    ?>

    <section class="a11yhubbr-section">
      <div class="a11yhubbr-container a11yhubbr-single-layout">
        <article class="a11yhubbr-card a11yhubbr-rich-content">
          <div class="a11yhubbr-single-meta-head">
            <span class="a11yhubbr-content-item-badge a11yhubbr-content-item-badge--conteudos"><i class="<?php echo esc_attr($type_icon); ?>" aria-hidden="true"></i><?php echo esc_html($type_label); ?></span>
          </div>

          <div class="a11yhubbr-single-content-body"><?php echo wp_kses_post($content_to_render); ?></div>

          <?php if (!empty($tags) && !is_wp_error($tags)): ?>
            <h3>Tags</h3>
            <div class="a11yhubbr-single-tags">
              <?php foreach ($tags as $tag): ?>
                <a href="<?php echo esc_url(add_query_arg(array('busca' => $tag->name, 'tipo' => 'conteudos'), home_url('/busca/'))); ?>"><?php echo esc_html($tag->name); ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php get_template_part('inc/components/single-side-engagement', null, array(
            'share_url' => $permalink,
            'share_title' => get_the_title(),
            'layout' => 'inline',
            'show_suggest' => false,
          )); ?>

          <?php if ($submitter_name !== '' || get_the_date() !== ''): ?>
            <div class="a11yhubbr-single-muted-meta" aria-label="Metadados da submissão">
              <?php if ($submitter_name !== ''): ?><span>Submetido por <?php echo esc_html($submitter_name); ?></span><?php endif; ?>
              <span>Enviado em <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time></span>
            </div>
          <?php endif; ?>
        </article>

        <aside class="a11yhubbr-single-aside-stack">
          <?php if ($source_link !== ''): ?>
            <div class="a11yhubbr-side-card a11yhubbr-single-primary-action">
              <h2>Acessar conteúdo</h2>
              <p>Abra a referência original em uma nova aba.</p>
              <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($source_link); ?>" target="_blank" rel="noopener noreferrer">Abrir conteúdo <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
            </div>
          <?php endif; ?>

          <div class="a11yhubbr-side-card a11yhubbr-single-side a11yhubbr-single-side-meta">
            <h2>Ficha técnica</h2>
            <dl>
              <div><dt>Tipo</dt><dd><?php echo esc_html($type_label); ?></dd></div>
              <?php if ($submitter_org !== ''): ?><div><dt>Organizacao</dt><dd><?php echo esc_html($submitter_org); ?></dd></div><?php endif; ?>
              <?php if ($year_publication !== ''): ?><div><dt>Ano</dt><dd><?php echo esc_html($year_publication); ?></dd></div><?php endif; ?>
              <?php $depth_label = $resolve_content_option('depth', $depth); ?>
              <?php if ($depth_label !== ''): ?><div><dt>Nivel</dt><dd><?php echo esc_html($depth_label); ?></dd></div><?php endif; ?>

              <?php if ($primary_type_slug === 'artigos'): ?>
                <?php if ($article_authors !== ''): ?><div><dt>Autorias</dt><dd><?php echo esc_html($article_authors); ?></dd></div><?php endif; ?>
                <?php $article_kind_label = $resolve_content_option('article_kind', $article_kind); ?>
                <?php if ($article_kind_label !== ''): ?><div><dt>Tipo de artigo</dt><dd><?php echo esc_html($article_kind_label); ?></dd></div><?php endif; ?>
              <?php endif; ?>

              <?php if ($primary_type_slug === 'cursos-materiais'): ?>
                <?php $book_modality_label = $resolve_content_option('book_modality', $book_modality); ?>
                <?php if ($book_modality_label !== ''): ?><div><dt>Modalidade</dt><dd><?php echo esc_html($book_modality_label); ?></dd></div><?php endif; ?>
                <?php $book_price_label = $resolve_content_option('book_price', $book_price); ?>
                <?php if ($book_price_label !== ''): ?><div><dt>Preco</dt><dd><?php echo esc_html($book_price_label); ?></dd></div><?php endif; ?>
              <?php endif; ?>

              <?php if ($primary_type_slug === 'ferramentas'): ?>
                <?php $tool_type_label = $resolve_content_option('tool_type', $tool_type); ?>
                <?php if ($tool_type_label !== ''): ?><div><dt>Tipo de ferramenta</dt><dd><?php echo esc_html($tool_type_label); ?></dd></div><?php endif; ?>
                <?php $tool_model_label = $resolve_content_option('tool_model', $tool_model); ?>
                <?php if ($tool_model_label !== ''): ?><div><dt>Modelo</dt><dd><?php echo esc_html($tool_model_label); ?></dd></div><?php endif; ?>
              <?php endif; ?>

              <?php if ($primary_type_slug === 'multimidia'): ?>
                <?php if ($media_theme !== ''): ?><div><dt>Tema principal</dt><dd><?php echo esc_html($media_theme); ?></dd></div><?php endif; ?>
                <?php $media_channel_type_label = $resolve_content_option('media_channel_type', $media_channel_type); ?>
                <?php if ($media_channel_type_label !== ''): ?><div><dt>Midia</dt><dd><?php echo esc_html($media_channel_type_label); ?></dd></div><?php endif; ?>
                <?php $media_format_label = $resolve_content_option('media_format', $media_format); ?>
                <?php if ($media_format_label !== ''): ?><div><dt>Formato</dt><dd><?php echo esc_html($media_format_label); ?></dd></div><?php endif; ?>
                <?php $media_platform_label = $resolve_content_option('media_platform', $media_platform); ?>
                <?php if ($media_platform_label !== ''): ?><div><dt>Plataforma</dt><dd><?php echo esc_html($media_platform_label); ?></dd></div><?php endif; ?>
                <?php $media_frequency_label = $resolve_content_option('media_frequency', $media_frequency); ?>
                <?php if ($media_frequency_label !== ''): ?><div><dt>Frequencia</dt><dd><?php echo esc_html($media_frequency_label); ?></dd></div><?php endif; ?>
              <?php endif; ?>

              <?php if ($primary_type_slug === 'sites-sistemas'): ?>
                <?php $site_business_model_label = $resolve_content_option('site_business_model', $site_business_model); ?>
                <?php if ($site_business_model_label !== ''): ?><div><dt>Modelo de negocio</dt><dd><?php echo esc_html($site_business_model_label); ?></dd></div><?php endif; ?>
                <?php $site_stage_label = $resolve_content_option('site_stage', $site_stage); ?>
                <?php if ($site_stage_label !== ''): ?><div><dt>Estagio</dt><dd><?php echo esc_html($site_stage_label); ?></dd></div><?php endif; ?>
                <?php $site_access_model_label = $resolve_content_option('site_access_model', $site_access_model); ?>
                <?php if ($site_access_model_label !== ''): ?><div><dt>Modelo de acesso</dt><dd><?php echo esc_html($site_access_model_label); ?></dd></div><?php endif; ?>
              <?php endif; ?>
            </dl>
          </div>

          <?php get_template_part('inc/components/single-side-engagement', null, array(
            'contact_url' => home_url('/contato'),
            'suggest_url' => add_query_arg('source_post', $post_id, function_exists('a11yhubbr_get_submit_content_url') ? a11yhubbr_get_submit_content_url() : home_url('/submeter/submeter-conteudo')),
            'suggest_label' => 'Sugerir alteracao',
            'show_share' => false,
          )); ?>
        </aside>
      </div>
    </section>

    <?php if ($related_query instanceof WP_Query && $related_query->have_posts()): ?>
      <section class="a11yhubbr-section a11yhubbr-single-related">
        <div class="a11yhubbr-container">
          <h2 class="a11yhubbr-content-heading">Conteúdos relacionados</h2>
          <div class="a11yhubbr-content-results-grid">
            <?php while ($related_query->have_posts()): $related_query->the_post(); ?>
              <?php
              $related_author = (string) get_post_meta(get_the_ID(), '_a11yhubbr_submitter_name', true);
              if ($related_author === '') {
                  $related_author = get_the_author();
              }

              $related_excerpt = get_the_excerpt();
              if ($related_excerpt === '') {
                  $related_excerpt = wp_trim_words(wp_strip_all_tags(get_post_field('post_content', get_the_ID())), 22);
              }

              $related_terms = get_the_terms(get_the_ID(), 'category');
              $related_label = 'Conteudo';
              $related_badge_icon = 'fa-regular fa-file-lines';
              if (!empty($related_terms) && !is_wp_error($related_terms)) {
                  $related_label = $related_terms[0]->name;
                  foreach ($related_terms as $related_term) {
                      $related_slug = (string) $related_term->slug;
                      if (isset($types[$related_slug]['icon'])) {
                          $related_badge_icon = (string) $types[$related_slug]['icon'];
                          break;
                      }
                  }
              }
              $related_tags = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
              if (!is_array($related_tags)) {
                  $related_tags = array();
              }
              ?>
              <?php get_template_part('inc/components/content-card', null, array(
                'label' => $related_label,
                'badge_icon' => $related_badge_icon,
                'date_iso' => get_the_date('c'),
                'date_text' => get_the_date('d/m/Y'),
                'title' => get_the_title(),
                'title_url' => get_permalink(),
                'excerpt' => $related_excerpt,
                'author' => $related_author,
                'tags' => $related_tags,
                'action_url' => get_permalink(),
                'action_label' => 'Acessar',
              )); ?>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
