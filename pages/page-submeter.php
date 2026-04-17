<?php
/*
Template Name: Submeter
*/
if (!defined('ABSPATH')) {
  exit;
}

$is_logged_in = is_user_logged_in();
$login_url = function_exists('a11yhubbr_get_submission_login_url') ? a11yhubbr_get_submission_login_url(get_permalink()) : wp_login_url(get_permalink());
$registration_url = function_exists('a11yhubbr_get_submission_registration_url') ? a11yhubbr_get_submission_registration_url(get_permalink()) : '';

$help_cards = array(
  array('icon' => 'fa-regular fa-lightbulb', 'title' => 'Cadastrar iniciativas', 'text' => 'Conhece uma iniciativa de acessibilidade que ainda nao esta no diretorio? Ajude a documentar e ampliar sua visibilidade.'),
  array('icon' => 'fa-regular fa-comment-dots', 'title' => 'Sugerir melhorias', 'text' => 'Encontrou informacao desatualizada? Envie sugestoes para mantermos o conteudo util e atualizado.'),
  array('icon' => 'fa-solid fa-share-nodes', 'title' => 'Divulgar a plataforma', 'text' => 'Siga e compartilhe a A11YBR nas suas redes para conectar mais pessoas e iniciativas de inclusao digital. Nossos perfis sao @a11yhubbr.'),
  array('icon' => 'fa-solid fa-bug', 'title' => 'Reportar problemas', 'text' => 'Encontrou um bug ou problema de acessibilidade na plataforma? Sua ajuda e fundamental para melhorarmos continuamente.'),
);

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-site-main">
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
      <?php if ($is_logged_in && function_exists('a11yhubbr_get_my_submissions_url')) : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-success" role="status">
          Sua conta ja pode enviar itens e acompanhar o andamento em
          <a href="<?php echo esc_url(a11yhubbr_get_my_submissions_url()); ?>">Minhas submissoes</a>.
        </div>
      <?php elseif (!$is_logged_in) : ?>
        <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-submit-auth-card">
          <h2>Crie uma conta para enviar contribuicoes</h2>
          <p>Para submeter conteudos, eventos e perfis, primeiro entre ou crie uma conta. Assim cada envio fica vinculado a pessoa responsavel e pode ser acompanhado depois.</p>
          <div class="a11yhubbr-form-actions">
            <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($login_url); ?>">Entrar</a>
            <?php if ($registration_url !== '') : ?>
              <a class="a11yhubbr-btn" href="<?php echo esc_url($registration_url); ?>">Criar conta</a>
            <?php endif; ?>
          </div>
        </section>
      <?php endif; ?>

      <div class="a11yhubbr-submit-hub-grid">
        <article class="a11yhubbr-submit-hub-card">
          <span class="a11yhubbr-submit-hub-icon" aria-hidden="true"><i class="fa-regular fa-file-lines"></i></span>
          <h2>Submeter conteudo</h2>
          <p>Envie artigos, ferramentas, livros e materiais ou outros recursos sobre acessibilidade digital.</p>
          <a class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-submit-hub-btn" href="<?php echo esc_url(home_url('/submeter/submeter-conteudo')); ?>">Criar conteudo</a>
        </article>

        <article class="a11yhubbr-submit-hub-card">
          <span class="a11yhubbr-submit-hub-icon" aria-hidden="true"><i class="fa-regular fa-calendar"></i></span>
          <h2>Submeter evento</h2>
          <p>Divulgue workshops, conferencias, meetups e outros eventos sobre acessibilidade.</p>
          <a class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-submit-hub-btn" href="<?php echo esc_url(home_url('/submeter/submeter-eventos')); ?>">Criar evento</a>
        </article>

        <article class="a11yhubbr-submit-hub-card">
          <span class="a11yhubbr-submit-hub-icon" aria-hidden="true"><i class="fa-regular fa-id-card"></i></span>
          <h2>Submeter perfil</h2>
          <p>Cadastre profissionais, empresas, ONGs e comunidades para fortalecer conexoes na rede.</p>
          <a class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-submit-hub-btn" href="<?php echo esc_url(home_url('/submeter/submeter-perfil')); ?>">Criar perfil</a>
        </article>
      </div>
    </div>
  </section>

  <?php get_template_part('inc/sections/como-funciona'); ?>

  <section class="a11yhubbr-section a11yhubbr-section-soft">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-section-title">Como voce pode ajudar</h2>
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
        'title' => 'Pronto para comecar?',
        'description' => 'A maneira mais simples de contribuir e cadastrando uma iniciativa que voce conhece. Leva apenas alguns minutos e ajuda a dar visibilidade a trabalhos importantes.',
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
