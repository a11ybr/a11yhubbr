<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="a11yhubbr-site-main">
  <?php
  a11yhubbr_render_page_header(array(
      'breadcrumbs' => array(
          array('label' => 'Página inicial', 'url' => home_url('/')),
          array('label' => get_the_title()),
      ),
      'icon' => 'fa-regular fa-file-lines',
  ));
  ?>

  <section class="a11yhubbr-container a11yhubbr-section">
    <div class="a11yhubbr-content-single">
      <?php while (have_posts()): the_post(); ?>
        <article class="a11yhubbr-rich-content a11yhubbr-card " >
          <p class="a11yhubbr-updated-at">
            Última atualização: <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>"><?php echo esc_html(get_the_modified_date('F \d\e Y')); ?></time>
          </p>
          <?php the_content(); ?>
        </article>
      <?php endwhile; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>



