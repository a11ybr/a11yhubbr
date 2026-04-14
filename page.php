<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('a11yhubbr_prepare_page_content')) {
    function a11yhubbr_prepare_page_content($content) {
        $toc = array();
        $seen_ids = array();

        $content = preg_replace_callback(
            '/<h([2-4])([^>]*)>(.*?)<\/h\1>/is',
            static function ($matches) use (&$toc, &$seen_ids) {
                $level = (int) $matches[1];
                $attributes = (string) $matches[2];
                $inner_html = (string) $matches[3];
                $label = trim(wp_strip_all_tags($inner_html));

                if ($label === '') {
                    return $matches[0];
                }

                $id = '';
                if (preg_match('/\sid=(["\'])(.*?)\1/i', $attributes, $id_match)) {
                    $id = sanitize_title($id_match[2]);
                }

                if ($id === '') {
                    $id = sanitize_title($label);
                }

                if ($id === '') {
                    $id = 'secao';
                }

                $base_id = $id;
                $suffix = 2;
                while (isset($seen_ids[$id])) {
                    $id = $base_id . '-' . $suffix;
                    $suffix++;
                }
                $seen_ids[$id] = true;

                if (stripos($attributes, ' id=') !== false) {
                    $attributes = preg_replace('/\sid=(["\'])(.*?)\1/i', '', $attributes);
                }

                $toc[] = array(
                    'id' => $id,
                    'label' => $label,
                    'level' => $level,
                );

                return sprintf('<h%d%s id="%s">%s</h%d>', $level, $attributes, esc_attr($id), $inner_html, $level);
            },
            (string) $content
        );

        return array($content, $toc);
    }
}

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-site-main a11yhubbr-default-page">
  <?php while (have_posts()): the_post(); ?>
    <?php
    $post_id = get_the_ID();
    $summary = trim(wp_strip_all_tags((string) get_the_excerpt($post_id)));
    if ($summary === '') {
        $summary = wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $post_id)), 28);
    }

    $breadcrumbs = array(
        array('label' => 'Pagina inicial', 'url' => home_url('/')),
    );

    $ancestors = array_reverse(get_post_ancestors($post_id));
    foreach ($ancestors as $ancestor_id) {
        $breadcrumbs[] = array(
            'label' => get_the_title($ancestor_id),
            'url' => get_permalink($ancestor_id),
        );
    }
    $breadcrumbs[] = array('label' => get_the_title());

    $raw_content = apply_filters('the_content', (string) get_the_content());
    list($rendered_content, $toc_items) = a11yhubbr_prepare_page_content($raw_content);

    $reading_words = str_word_count(wp_strip_all_tags($rendered_content));
    $reading_minutes = max(1, (int) ceil($reading_words / 220));

    $child_pages = get_pages(array(
        'child_of' => $post_id,
        'parent' => $post_id,
        'sort_column' => 'menu_order,post_title',
        'post_status' => 'publish',
    ));

    $related_pages = array();
    $related_title = 'Nesta secao';
    if (!empty($child_pages)) {
        $related_pages = $child_pages;
    } else {
        $parent_id = (int) wp_get_post_parent_id($post_id);
        if ($parent_id > 0) {
            $siblings = get_pages(array(
                'child_of' => $parent_id,
                'parent' => $parent_id,
                'sort_column' => 'menu_order,post_title',
                'post_status' => 'publish',
                'exclude' => array($post_id),
            ));

            if (!empty($siblings)) {
                $related_pages = $siblings;
                $related_title = 'Paginas relacionadas';
            }
        }
    }

    a11yhubbr_render_page_header(array(
        'breadcrumbs' => $breadcrumbs,
        'icon' => 'fa-regular fa-file-lines',
        'title' => get_the_title(),
        'summary' => $summary,
        'use_page_context' => false,
    ));
    ?>

    <section class="a11yhubbr-section">
      <div class="a11yhubbr-container a11yhubbr-page-layout">
        <article class="a11yhubbr-card a11yhubbr-rich-content a11yhubbr-page-article">
          <div class="a11yhubbr-page-meta" aria-label="Metadados da pagina">
            <span class="a11yhubbr-page-meta-item">
              <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
              <strong>Atualizada</strong>
              <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>"><?php echo esc_html(get_the_modified_date('d/m/Y')); ?></time>
            </span>
            <span class="a11yhubbr-page-meta-item">
              <i class="fa-regular fa-clock" aria-hidden="true"></i>
              <strong>Leitura</strong>
              <?php echo esc_html($reading_minutes . ' min'); ?>
            </span>
          </div>

          <div class="a11yhubbr-page-content">
            <?php echo wp_kses_post($rendered_content); ?>
          </div>
        </article>

        <?php if (!empty($toc_items) || !empty($related_pages)): ?>
          <aside class="a11yhubbr-page-aside">
            <?php if (!empty($toc_items)): ?>
              <section class="a11yhubbr-side-card a11yhubbr-page-aside-card" aria-labelledby="page-toc-title">
                <h2 id="page-toc-title">Nesta pagina</h2>
                <p>Navegue pelos principais blocos de conteudo desta pagina.</p>
                <nav aria-label="Secoes da pagina">
                  <ul>
                    <?php foreach ($toc_items as $item): ?>
                      <li>
                        <a class="is-level-<?php echo esc_attr((string) $item['level']); ?>" href="#<?php echo esc_attr($item['id']); ?>">
                          <?php echo esc_html($item['label']); ?>
                        </a>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </nav>
              </section>
            <?php endif; ?>

            <?php if (!empty($related_pages)): ?>
              <section class="a11yhubbr-side-card a11yhubbr-page-aside-card" aria-labelledby="page-related-title">
                <h2 id="page-related-title"><?php echo esc_html($related_title); ?></h2>
                <p>Links para continuar a navegacao dentro do mesmo contexto.</p>
                <ul>
                  <?php foreach ($related_pages as $related_page): ?>
                    <?php
                    $related_excerpt = trim(wp_strip_all_tags((string) get_the_excerpt($related_page->ID)));
                    if ($related_excerpt === '') {
                        $related_excerpt = wp_trim_words(wp_strip_all_tags((string) $related_page->post_content), 14);
                    }
                    ?>
                    <li class="a11yhubbr-page-related-item">
                      <a href="<?php echo esc_url(get_permalink($related_page->ID)); ?>"><?php echo esc_html(get_the_title($related_page->ID)); ?></a>
                      <?php if ($related_excerpt !== ''): ?>
                        <span><?php echo esc_html($related_excerpt); ?></span>
                      <?php endif; ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </section>
            <?php endif; ?>
          </aside>
        <?php endif; ?>
      </div>
    </section>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
