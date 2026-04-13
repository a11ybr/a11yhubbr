<?php
/*
Template Name: Criar conta
*/
if (!defined('ABSPATH')) {
    exit;
}

$redirect_target = function_exists('a11yhubbr_get_requested_redirect_target')
    ? a11yhubbr_get_requested_redirect_target('redirect_to', home_url('/submeter'))
    : home_url('/submeter');
$register_status = isset($_GET['a11yhubbr_register_status']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_register_status'])) : '';
$register_error = isset($_GET['a11yhubbr_register_error']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_register_error'])) : '';
$is_logged_in = is_user_logged_in();
$login_url = function_exists('a11yhubbr_get_submission_login_url')
    ? a11yhubbr_get_submission_login_url($redirect_target)
    : wp_login_url($redirect_target);
$registration_enabled = (bool) get_option('users_can_register');

$error_messages = array(
    'invalid_nonce' => 'Não foi possível validar o formulário. Tente novamente.',
    'closed' => 'O cadastro público está desativado no momento.',
    'empty_fields' => 'Preencha todos os campos obrigatórios.',
    'password_mismatch' => 'As senhas não conferem.',
    'invalid_username' => 'Escolha um nome de usuário válido, sem caracteres inválidos.',
    'username_exists' => 'Esse nome de usuário já está em uso.',
    'invalid_email' => 'Informe um e-mail válido.',
    'email_exists' => 'Já existe uma conta com esse e-mail.',
    'create_failed' => 'Não foi possível criar a conta agora. Tente novamente em instantes.',
);

$error_message = ($register_status === 'error' && isset($error_messages[$register_error]))
    ? $error_messages[$register_error]
    : '';

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-submit-page a11yhubbr-auth-page a11yhubbr-registration-page">
  <?php
  a11yhubbr_render_page_header(array(
      'breadcrumbs' => array(
          array('label' => 'Pagina inicial', 'url' => home_url('/')),
          array('label' => 'Submeter', 'url' => home_url('/submeter')),
          array('label' => 'Criar conta'),
      ),
      'icon' => 'fa-regular fa-user',
  ));
  ?>

  <section class="a11yhubbr-submit-section">
    <div class="a11yhubbr-container">
      <?php if ($error_message !== '') : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-error" role="alert"><?php echo esc_html($error_message); ?></div>
      <?php endif; ?>

      <?php if ($register_status === 'success') : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-success" role="status">Conta criada com sucesso. Você já pode continuar no fluxo de submissão.</div>
      <?php endif; ?>

      <div class="a11yhubbr-submit-grid">
        <div class="a11yhubbr-submit-main">
          <section class="a11yhubbr-about-section a11yhubbr-auth-shell">
            <?php if ($is_logged_in) : ?>
              <article class="a11yhubbr-about-vision-card a11yhubbr-auth-hero-card">
                <p class="a11yhubbr-home-kicker">Conta ativa</p>
                <h2>Sua conta já está pronta para contribuir</h2>
                <p>Você já está autenticado. Agora pode seguir para enviar conteúdos, eventos e perfis sem sair do layout do site.</p>
                <div class="a11yhubbr-form-actions">
                  <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($redirect_target); ?>">Continuar</a>
                  <a class="a11yhubbr-btn" href="<?php echo esc_url(home_url('/submeter')); ?>">Ver opções de envio</a>
                </div>
              </article>
            <?php elseif (!$registration_enabled) : ?>
              <article class="a11yhubbr-about-vision-card a11yhubbr-auth-hero-card">
                <p class="a11yhubbr-home-kicker">Cadastro indisponível</p>
                <h2>O cadastro público está desativado</h2>
                <p>Se você já tem conta, entre para continuar. Se não tiver, o cadastro precisa ser habilitado em Configurações &gt; Geral antes de liberar novos usuários.</p>
                <div class="a11yhubbr-form-actions">
                  <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($login_url); ?>">Entrar</a>
                  <a class="a11yhubbr-btn" href="<?php echo esc_url(home_url('/submeter')); ?>">Voltar para submeter</a>
                </div>
              </article>
            <?php else : ?>
              <article class="a11yhubbr-about-vision-card a11yhubbr-auth-hero-card">
                <p class="a11yhubbr-home-kicker">Criar conta</p>
                <h2>Participe da A11YBR sem sair do site</h2>
                <p>Cadastre sua conta para enviar contribuições, acompanhar o andamento das revisões e voltar ao fluxo de submissão já autenticado.</p>
              </article>

              <form method="post" class="a11yhubbr-form-grid a11yhubbr-submit-form" id="register-form">
                <p class="a11yhubbr-required-legend"><span class="a11yhubbr-required-mark" aria-hidden="true">*</span> Campos obrigatórios</p>
                <?php wp_nonce_field('a11yhubbr_register_user', 'a11yhubbr_register_nonce'); ?>
                <input type="hidden" name="redirect_to" value="<?php echo esc_attr(rawurlencode($redirect_target)); ?>">

                <section class="a11yhubbr-card a11yhubbr-form-section">
                  <h2>Dados da conta</h2>

                  <div class="a11yhubbr-field-inline">
                    <label for="register-display-name">Nome para exibição</label>
                    <div class="a11yhubbr-field-control">
                      <input id="register-display-name" type="text" name="display_name" autocomplete="name">
                      <p class="a11yhubbr-help">Opcional. Se vazio, usaremos o nome de usuário.</p>
                    </div>
                  </div>

                  <div class="a11yhubbr-field-inline">
                    <label for="register-user-login">Nome de usuário <span aria-hidden="true">*</span></label>
                    <div class="a11yhubbr-field-control">
                      <input id="register-user-login" type="text" name="user_login" required aria-required="true" autocomplete="username">
                      <p class="a11yhubbr-help">Use letras, números, ponto, hífen ou underline.</p>
                    </div>
                  </div>

                  <div class="a11yhubbr-field-inline">
                    <label for="register-user-email">E-mail <span aria-hidden="true">*</span></label>
                    <div class="a11yhubbr-field-control">
                      <input id="register-user-email" type="email" name="user_email" required aria-required="true" autocomplete="email">
                    </div>
                  </div>

                  <div class="a11yhubbr-field-inline">
                    <label for="register-user-pass">Senha <span aria-hidden="true">*</span></label>
                    <div class="a11yhubbr-field-control">
                      <input id="register-user-pass" type="password" name="user_pass" required aria-required="true" autocomplete="new-password">
                    </div>
                  </div>

                  <div class="a11yhubbr-field-inline">
                    <label for="register-user-pass-confirm">Confirmar senha <span aria-hidden="true">*</span></label>
                    <div class="a11yhubbr-field-control">
                      <input id="register-user-pass-confirm" type="password" name="user_pass_confirm" required aria-required="true" autocomplete="new-password">
                    </div>
                  </div>

                  <div class="a11yhubbr-form-actions">
                    <button class="a11yhubbr-btn a11yhubbr-btn-primary" type="submit" name="a11yhubbr_register_submit" value="1">Criar conta</button>
                    <a class="a11yhubbr-btn" href="<?php echo esc_url($login_url); ?>">Já tenho conta</a>
                  </div>
                </section>
              </form>
            <?php endif; ?>
          </section>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Orientações sobre cadastro">
          <section class="a11yhubbr-side-card a11yhubbr-side-card-primary">
            <h2>Por que criar conta?</h2>
            <ul>
              <li>Associar cada envio a uma pessoa responsável.</li>
              <li>Acompanhar revisões e futuras atualizações.</li>
              <li>Voltar ao fluxo de contribuição sem depender do painel.</li>
            </ul>
          </section>

          <section class="a11yhubbr-side-card">
            <h2>Já participou antes?</h2>
            <p>Se você já tem conta, entre e continue direto do ponto em que parou.</p>
            <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($login_url); ?>">Entrar</a>
          </section>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
