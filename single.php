<?php
if (!defined('ABSPATH')) {
    exit;
}

$types = function_exists('a11yhubbr_get_content_type_map') ? a11yhubbr_get_content_type_map() : array();

get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-single-page a11yhubbr-single-content-page">
  <?php while (have_posts()): the_post(); ?>
    <?php
    $post_id = get_the_ID();
    $category_terms = get_the_terms($post_id, 'category');
    $type_label = 'Conteudo';
    $type_icon = 'fa-regular fa-file-lines';
    $primary_category_id = 0;

    if (!empty($category_terms) && !is_wp_error($category_terms)) {
        foreach ($types as $slug => $type) {
            foreach ($category_terms as $term) {
                if ($term->slug === $slug) {
                    $type_label = $term->name;
                    $type_icon = isset($type['icon']) ? (string) $type['icon'] : 'fa-regular fa-file-lines';
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
            <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($source_link); ?>" target="_blank" rel="noopener noreferrer">Acessar conteudo <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
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
        </article>

        <aside class="a11yhubbr-single-aside-stack">
          <div class="a11yhubbr-side-card a11yhubbr-single-side">
            <h2>Detalhes da submissao</h2>
            <dl>
              <div><dt>Tipo</dt><dd><?php echo esc_html($type_label); ?></dd></div>
              <div><dt>Data</dt><dd><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time></dd></div>
              <?php if ($submitter_name !== ''): ?><div><dt>Autor</dt><dd><?php echo esc_html($submitter_name); ?></dd></div><?php endif; ?>
              <?php if ($submitter_org !== ''): ?><div><dt>Organizacao</dt><dd><?php echo esc_html($submitter_org); ?></dd></div><?php endif; ?>
             
            </dl>
          </div>

          <?php get_template_part('inc/components/single-side-engagement', null, array(
            'share_url' => $permalink,
            'share_title' => get_the_title(),
            'contact_url' => home_url('/contato'),
            'suggest_label' => 'Sugerir alteracao',
          )); ?>
        </aside>
      </div>
    </section>

    <?php if ($related_query instanceof WP_Query && $related_query->have_posts()): ?>
      <section class="a11yhubbr-section a11yhubbr-single-related">
        <div class="a11yhubbr-container">
          <h2 class="a11yhubbr-content-heading">Conteudos relacionados</h2>
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
