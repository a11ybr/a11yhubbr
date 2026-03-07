<?php get_header(); ?>
<main class="a11yhubbr-site-main">
  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container a11yhubbr-content-single">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article class="a11yhubbr-card a11yhubbr-rich-content">
          <h1><?php the_title(); ?></h1>
          <div><?php the_content(); ?></div>
        </article>
      <?php endwhile; else : ?>
        <article class="a11yhubbr-card">
          <h1>Página não encontrada</h1>
          <p>Não há conteúdo para exibir.</p>
        </article>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>




