<?php
/*
Template Name: Entrar
*/
if (!defined('ABSPATH')) {
    exit;
}

$redirect_target = function_exists('a11yhubbr_get_requested_redirect_target')
    ? a11yhubbr_get_requested_redirect_target('redirect_to', home_url('/submeter'))
    : home_url('/submeter');
$login_status = isset($_GET['a11yhubbr_login_status']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_login_status'])) : '';
$login_error = isset($_GET['a11yhubbr_login_error']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_login_error'])) : '';
$is_logged_in = is_user_logged_in();
$registration_url = function_exists('a11yhubbr_get_submission_registration_url')
    ? a11yhubbr_get_submission_registration_url($redirect_target)
    : '';
$lost_password_url = wp_lostpassword_url($redirect_target);

$error_messages = array(
    'invalid_nonce' => 'Não foi possível validar o formulário. Tente novamente.',
    'empty_fields' => 'Informe usuário ou e-mail e a senha.',
    'invalid_login' => 'Não foi possível entrar com essas credenciais.',
);

$error_message = ($login_status === 'error' && isset($error_messages[$login_error]))
    ? $error_messages[$login_error]
    : '';

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-submit-page a11yhubbr-auth-page a11yhubbr-login-page">
  <?php
  a11yhubbr_render_page_header(array(
      'breadcrumbs' => array(
          array('label' => 'Pagina inicial', 'url' => home_url('/')),
          array('label' => 'Submeter', 'url' => home_url('/submeter')),
          array('label' => 'Entrar'),
      ),
      'icon' => 'fa-solid fa-right-to-bracket',
  ));
  ?>

  <section class="a11yhubbr-submit-section">
    <div class="a11yhubbr-container">
      <?php if ($error_message !== '') : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-error" role="alert"><?php echo esc_html($error_message); ?></div>
      <?php endif; ?>

      <div class="a11yhubbr-submit-grid">
        <div class="a11yhubbr-submit-main">
          <section class="a11yhubbr-about-section a11yhubbr-auth-shell">
            <?php if ($is_logged_in) : ?>
              <article class="a11yhubbr-about-vision-card a11yhubbr-auth-hero-card">
                <p class="a11yhubbr-home-kicker">Sessão ativa</p>
                <h2>Você já entrou</h2>
                <p>Sua sessão já está aberta. Continue para a página que originou o acesso ou volte para a área de submissão.</p>
                <div class="a11yhubbr-form-actions">
                  <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($redirect_target); ?>">Continuar</a>
                  <a class="a11yhubbr-btn" href="<?php echo esc_url(home_url('/submeter')); ?>">Ir para submeter</a>
                </div>
              </article>
            <?php else : ?>
              <article class="a11yhubbr-about-vision-card a11yhubbr-auth-hero-card">
                <p class="a11yhubbr-home-kicker">Entrar</p>
                <h2>Acesse sua conta e continue contribuindo</h2>
                <p>Entre para enviar novos itens, revisar suas submissões anteriores e manter tudo dentro do fluxo público do site.</p>
              </article>

              <form method="post" class="a11yhubbr-form-grid a11yhubbr-submit-form" id="login-form">
                <?php wp_nonce_field('a11yhubbr_login_user', 'a11yhubbr_login_nonce'); ?>
                <input type="hidden" name="redirect_to" value="<?php echo esc_attr(rawurlencode($redirect_target)); ?>">

                <section class="a11yhubbr-card a11yhubbr-form-section">
                  <h2>Dados de acesso</h2>

                  <div class="a11yhubbr-field-inline">
                    <label for="login-user">Usuário ou e-mail</label>
                    <div class="a11yhubbr-field-control">
                      <input id="login-user" type="text" name="log" required aria-required="true" autocomplete="username">
                    </div>
                  </div>

                  <div class="a11yhubbr-field-inline">
                    <label for="login-pass">Senha</label>
                    <div class="a11yhubbr-field-control">
                      <input id="login-pass" type="password" name="pwd" required aria-required="true" autocomplete="current-password">
                    </div>
                  </div>

                  <label class="a11yhubbr-auth-checkbox" for="login-remember">
                    <input id="login-remember" type="checkbox" name="rememberme" value="forever">
                    <span>Manter sessão ativa neste navegador</span>
                  </label>

                  <div class="a11yhubbr-form-actions">
                    <button class="a11yhubbr-btn a11yhubbr-btn-primary" type="submit" name="a11yhubbr_login_submit" value="1">Entrar</button>
                    <?php if ($registration_url !== '') : ?>
                    <a class="a11yhubbr-btn" href="<?php echo esc_url($registration_url); ?>">Criar conta</a>
                    <?php endif; ?>
                  </div>

                  <p class="a11yhubbr-auth-support-link">
                    <a href="<?php echo esc_url($lost_password_url); ?>">Esqueci minha senha</a>
                  </p>
                </section>
              </form>
            <?php endif; ?>
          </section>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Orientações sobre entrada">
          <section class="a11yhubbr-side-card a11yhubbr-side-card-primary">
            <h2>O que você ganha ao entrar</h2>
            <ul>
              <li>Acesso direto aos formulários de contribuição.</li>
              <li>Continuidade entre cadastro, envio e acompanhamento.</li>
              <li>Menos dependência das telas padrão do WordPress.</li>
            </ul>
          </section>

          <?php if ($registration_url !== '') : ?>
            <section class="a11yhubbr-side-card">
              <h2>Ainda não tem conta?</h2>
              <p>Crie sua conta em poucos minutos e volte automaticamente para o fluxo que iniciou.</p>
              <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($registration_url); ?>">Criar conta</a>
            </section>
          <?php endif; ?>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
