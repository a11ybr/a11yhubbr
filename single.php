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
    $category_terms = get_the_terms(get_the_ID(), 'category');
    $type_label = 'Conteúdo';
    if (!empty($category_terms) && !is_wp_error($category_terms)) {
        foreach ($types as $slug => $type) {
            foreach ($category_terms as $term) {
                if ($term->slug === $slug) {
                    $type_label = $term->name;
                    break 2;
                }
            }
        }
    }

    $source_link = (string) get_post_meta(get_the_ID(), '_a11yhubbr_source_link', true);
    $submitter_name = (string) get_post_meta(get_the_ID(), '_a11yhubbr_submitter_name', true);
    $submitter_org = (string) get_post_meta(get_the_ID(), '_a11yhubbr_submitter_org', true);
    $tags = get_the_terms(get_the_ID(), 'post_tag');
    $permalink = get_permalink();

    a11yhubbr_render_page_header(array(
        'breadcrumbs' => array(
            array('label' => 'Página inicial', 'url' => home_url('/')),
            array('label' => 'Conteúdos', 'url' => home_url('/conteudos')),
            array('label' => get_the_title()),
        ),
        'icon' => 'fa-regular fa-file-lines',
        'title' => get_the_title(),
        'summary' => get_the_excerpt() !== '' ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content(null, false, get_the_ID())), 28),
        'use_page_context' => false,
    ));
    ?>

    <section class="a11yhubbr-section">
      <div class="a11yhubbr-container a11yhubbr-single-layout">
        <article class="a11yhubbr-card a11yhubbr-rich-content">
          <div class="a11yhubbr-single-meta-head">
            <span class="a11yhubbr-content-item-badge"><?php echo esc_html($type_label); ?></span>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time>
          </div>

          <?php the_content(); ?>

          <?php if (!empty($tags) && !is_wp_error($tags)): ?>
            <h3>Tags</h3>
            <div class="a11yhubbr-single-tags">
              <?php foreach ($tags as $tag): ?>
                <span><?php echo esc_html($tag->name); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </article>

        <aside class="a11yhubbr-single-aside-stack">
          <div class="a11yhubbr-side-card a11yhubbr-single-side">
            <h2>Detalhes da submissão</h2>
            <dl>
              <div><dt>Tipo</dt><dd><?php echo esc_html($type_label); ?></dd></div>
              <div><dt>Data</dt><dd><?php echo esc_html(get_the_date('d/m/Y')); ?></dd></div>
              <?php if ($submitter_name !== ''): ?><div><dt>Autor</dt><dd><?php echo esc_html($submitter_name); ?></dd></div><?php endif; ?>
              <?php if ($submitter_org !== ''): ?><div><dt>Organização</dt><dd><?php echo esc_html($submitter_org); ?></dd></div><?php endif; ?>
              <?php if ($source_link !== ''): ?><div><dt>Link de referéncia</dt><dd><a href="<?php echo esc_url($source_link); ?>" target="_blank" rel="noopener noreferrer">Abrir fonte <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a></dd></div><?php endif; ?>
            </dl>
          </div>

          <?php get_template_part('inc/components/single-side-engagement', null, array(
            'share_url' => $permalink,
            'share_title' => get_the_title(),
            'contact_url' => home_url('/contato'),
            'suggest_label' => 'Sugerir alteração',
          )); ?>
        </aside>
      </div>
    </section>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
