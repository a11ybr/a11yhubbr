<?php
/*
Template Name: Contato
*/
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-contact-page">
  <?php
  a11yhubbr_render_page_header(array(
      'breadcrumbs' => array(
          array('label' => 'Página inicial', 'url' => home_url('/')),
          array('label' => 'Contato'),
      ),
      'icon' => 'fa-regular fa-envelope',
      'title' => 'Contato',
      'summary' => 'Use este canal para sugerir alterações, reportar informações desatualizadas e enviar dévidas.',
      'use_page_context' => false,
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container a11yhubbr-single-layout">
      <article class="a11yhubbr-card">
        <h2>Fale com a equipe</h2>
        <p>Encontrou informação incorreta em um conteúdo, evento ou perfil? Envie os detalhes para revisão editorial.</p>
        <p>Inclua o link da página e descreva a alteração sugerida.</p>

        <form class="a11yhubbr-form-grid a11yhubbr-submit-form" method="post" action="mailto:a11yhubbr@gmail.com" enctype="text/plain">
          <label>Seu nome
            <input type="text" name="nome" required>
          </label>
          <label>Seu e-mail
            <input type="email" name="email" required>
          </label>
          <label>Link da página
            <input type="url" name="link" placeholder="https://..." required>
          </label>
          <label>Mensagem
            <textarea name="mensagem" rows="6" required></textarea>
          </label>
          <div class="a11yhubbr-form-actions">
            <button type="submit" class="a11yhubbr-btn a11yhubbr-btn-primary">Enviar mensagem</button>
          </div>
        </form>
      </article>

      <aside class="a11yhubbr-side-card a11yhubbr-single-side">
        <h2>Outros canais</h2>
        <dl>
          <div>
            <dt>E-mail</dt>
            <dd><a href="mailto:a11yhubbr@gmail.com">a11yhubbr@gmail.com</a></dd>
          </div>
          <div>
            <dt>Rede</dt>
            <dd><a href="<?php echo esc_url(home_url('/rede')); ?>">Ver perfis da comunidade</a></dd>
          </div>
          <div>
            <dt>Diretrizes</dt>
            <dd><a href="<?php echo esc_url(home_url('/diretrizes-da-comunidade')); ?>">Consultar diretrizes da plataforma</a></dd>
          </div>
        </dl>
      </aside>
    </div>
  </section>
</main>
<?php get_footer(); ?>
