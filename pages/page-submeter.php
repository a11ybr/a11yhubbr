<?php
/*
Template Name: Submeter
*/
if (!defined('ABSPATH')) {
  exit;
}

$help_cards = array(
  array('icon' => 'fa-regular fa-lightbulb', 'title' => 'Cadastrar iniciativas', 'text' => 'Conhece uma iniciativa de acessibilidade que ainda não está no diretório? Ajude a documentar e ampliar sua visibilidade.'),
  array('icon' => 'fa-regular fa-comment-dots', 'title' => 'Sugerir melhorias', 'text' => 'Encontrou informação desatualizada? Envie sugestões para mantermos o conteúdo útil e atualizado.'),
  array('icon' => 'fa-solid fa-share-nodes', 'title' => 'Divulgar a plataforma', 'text' => 'Siga e compartilhe a A11YBR nas suas redes para conectar mais pessoas e iniciativas de inclusão digital. Nossos perfis são @a11yhubbr.'),
  array('icon' => 'fa-solid fa-bug', 'title' => 'Reportar problemas', 'text' => 'Encontrou um bug ou problema de acessibilidade na plataforma? Sua ajuda é fundamental para melhorarmos continuamente.'),
);

get_header();
?>
<main class="a11yhubbr-site-main">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Página inicial', 'url' => home_url('/')),
      array('label' => 'Submeter'),
    ),
    'icon' => 'fa-solid fa-arrow-up-from-bracket',
  ));
  ?>

  <section class="a11yhubbr-section a11yhubbr-submit-hub-section">
    <div class="a11yhubbr-container">
      <div class="a11yhubbr-submit-hub-grid">
        <article class="a11yhubbr-submit-hub-card">
          <span class="a11yhubbr-submit-hub-icon" aria-hidden="true"><i class="fa-regular fa-file-lines"></i></span>
          <h2>Submeter conteúdo</h2>
          <p>Envie artigos, ferramentas, livros e materiais ou outros recursos sobre acessibilidade digital.</p>
          <a class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-submit-hub-btn" href="<?php echo esc_url(home_url('/submeter/submeter-conteudo')); ?>">Criar conteúdo</a>
        </article>

        <article class="a11yhubbr-submit-hub-card">
          <span class="a11yhubbr-submit-hub-icon" aria-hidden="true"><i class="fa-regular fa-calendar"></i></span>
          <h2>Submeter evento</h2>
          <p>Divulgue workshops, conferências, meetups e outros eventos sobre acessibilidade.</p>
          <a class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-submit-hub-btn" href="<?php echo esc_url(home_url('/submeter/submeter-eventos')); ?>">Criar evento</a>
        </article>

        <article class="a11yhubbr-submit-hub-card">
          <span class="a11yhubbr-submit-hub-icon" aria-hidden="true"><i class="fa-regular fa-id-card"></i></span>
          <h2>Submeter perfil</h2>
          <p>Cadastre profissionais, empresas, ONGs e comunidades para fortalecer conexões na rede.</p>
          <a class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-submit-hub-btn" href="<?php echo esc_url(home_url('/submeter/submeter-perfil')); ?>">Criar perfil</a>
        </article>
      </div>
    </div>
  </section>

  <?php get_template_part('inc/sections/como-funciona'); ?>

  <section class="a11yhubbr-section a11yhubbr-section-soft">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-section-title">Como você pode ajudar</h2>
      <div class="a11yhubbr-cards-grid a11yhubbr-submit-extra-grid">
        <?php foreach ($help_cards as $card): ?>
          <?php get_template_part('inc/components/feature-card', null, array(
            'icon' => $card['icon'],
            'title' => $card['title'],
            'text' => $card['text'],
          )); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container">
      <?php get_template_part('inc/components/cta-box', null, array(
        'title' => 'Pronto para começar?',
        'description' => 'A maneira mais simples de contribuir é cadastrando uma iniciativa que você conhece. Leva apenas alguns minutos e ajuda a dar visibilidade a trabalhos importantes.',
        'primary' => array(
          'label' => 'Submeter',
          'url' => home_url('/submeter/submeter-conteudo'),
        ),
        'secondary' => array(
          'label' => 'Ler diretrizes',
          'url' => home_url('/diretrizes-da-comunidade'),
        ),
      )); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
