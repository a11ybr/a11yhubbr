<?php
/*
Template Name: Submeter Perfil
*/
if (!defined('ABSPATH')) {
    exit;
}

$status = isset($_GET['a11yhubbr_status']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_status'])) : '';
$form = isset($_GET['a11yhubbr_form']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_form'])) : '';
$submitted = ($status === 'success' && $form === 'profile');
$has_error = ($status === 'error' && $form === 'profile');
$is_logged_in = is_user_logged_in();
$login_url = function_exists('a11yhubbr_get_submission_login_url') ? a11yhubbr_get_submission_login_url(get_permalink()) : wp_login_url(get_permalink());
$registration_url = function_exists('a11yhubbr_get_submission_registration_url') ? a11yhubbr_get_submission_registration_url(get_permalink()) : '';
$current_user = $is_logged_in ? wp_get_current_user() : null;

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-submit-page">
  <?php
  a11yhubbr_render_page_header(array(
      'breadcrumbs' => array(
          array('label' => 'Página inicial', 'url' => home_url('/')),
          array('label' => 'Submeter', 'url' => home_url('/submeter')),
          array('label' => 'Submeter perfil'),
      ),
      'icon' => 'fa-regular fa-id-card',
  ));
  ?>

  <section class="a11yhubbr-submit-section">
    <div class="a11yhubbr-container">
      <?php if ($submitted) : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-success" role="status">Perfil enviado para validação com sucesso.</div>
      <?php endif; ?>
      <?php if ($has_error) : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-error" role="alert">Não foi possível enviar agora. Tente novamente em instantes.</div>
      <?php endif; ?>

      <div class="a11yhubbr-submit-grid">
        <div class="a11yhubbr-submit-main">
          <?php if (!$is_logged_in) : ?>
            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Entre para submeter perfis</h2>
              <p>Agora a submissão de perfis exige uma conta no WordPress. Entre para continuar e vincular a submissão ao seu usuário.</p>
              <div class="a11yhubbr-form-actions">
                <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($login_url); ?>">Entrar</a>
                <?php if ($registration_url !== '') : ?>
                  <a class="a11yhubbr-btn" href="<?php echo esc_url($registration_url); ?>">Criar conta</a>
                <?php endif; ?>
              </div>
            </section>
          <?php else : ?>
          <form method="post" enctype="multipart/form-data" class="a11yhubbr-form-grid a11yhubbr-submit-form" id="profile-form">
            <p class="a11yhubbr-required-legend"><span class="a11yhubbr-required-mark" aria-hidden="true">*</span> Campos obrigatórios</p>
            <?php wp_nonce_field('a11yhubbr_profile', 'a11yhubbr_nonce'); ?>
            <input type="hidden" name="a11yhubbr_redirect" value="<?php echo esc_url(get_permalink()); ?>">
            <input type="hidden" name="a11yhubbr_ts" value="<?php echo esc_attr((string) time()); ?>">
            <label class="a11yhubbr-honeypot" aria-hidden="true" for="profile-company">Empresa
              <input id="profile-company" type="text" name="a11yhubbr_company" tabindex="-1" autocomplete="off">
            </label>

            <section class="a11yhubbr-card a11yhubbr-form-section" id="sec-perfil-detalhes" data-collapsible-section>
              <h2>Informações principais</h2>

              <div class="a11yhubbr-field-inline">
                <label for="profile-type">Tipo de perfil <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <select id="profile-type" name="profile_type" required aria-required="true">
                    <option value="">Selecione</option>
                    <option>Profissional de IT</option>
                    <option>Empresa ou ONG</option>
                    <option>Intérprete de Libras</option>
                    <option>Audiodescritor</option>
                    <option>Tradutor de Braille</option>
                    <option>Comunidade</option>
                  </select>
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="profile-name">Nome ou nome da organização <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <input id="profile-name" type="text" name="name" required aria-required="true">
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="profile-location">Localização (cidade, estado) <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <input id="profile-location" type="text" name="location" required aria-required="true">
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="profile-description">Bio profissional ou descrição institucional <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <textarea id="profile-description" name="description" rows="5" required aria-required="true"></textarea>
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="profile-role">Cargo / especialidade <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <input id="profile-role" type="text" name="role" required aria-required="true">
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="profile-website">Website</label>
                <div class="a11yhubbr-field-control">
                  <input id="profile-website" type="url" name="website">
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="profile-tags">Tags (separadas por vírgulas)</label>
                <div class="a11yhubbr-field-control">
                  <input id="profile-tags" type="text" name="tags" placeholder="design inclusivo, libras, consultoria">
                  <p class="a11yhubbr-help">Use tags para destacar especialidades e temas de atuação.</p>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section" id="sec-perfil-redes" data-collapsible-section>
              <h2>Informações complementares</h2>
              <fieldset class="a11yhubbr-fieldset a11yhubbr-form-fieldset">
                <legend>Links de redes sociais</legend>
                <div id="profile-social-links" class="a11yhubbr-slots-list">
                  <div class="a11yhubbr-slot">
                    <label for="social-network-1">Rede social
                      <select id="social-network-1" name="social_network[]">
                        <option value="">Selecione</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="github">GitHub</option>
                        <option value="instagram">Instagram</option>
                        <option value="x">X/Twitter</option>
                        <option value="medium">Medium</option>
                        <option value="youtube">YouTube</option>
                        <option value="threads">Threads</option>
                        <option value="bluesky">Bluesky</option>
                        <option value="telegram">Telegram</option>
                        <option value="facebook">Facebook</option>
                        <option value="website">Outro website</option>
                      </select>
                    </label>
                    <label for="social-url-1">URL
                      <input id="social-url-1" type="url" name="social_url[]" placeholder="https://...">
                    </label>
                    <button type="button" class="a11yhubbr-slot-remove a11yhubbr-social-slot-remove" aria-label="Remover link social" title="Remover link social" hidden>&#128465;</button>
                  </div>
                </div>
                <div class="a11yhubbr-slot-actions">
                  <button type="button" class="a11yhubbr-link-btn a11yhubbr-btn" id="add-social-link">+ Adicionar item</button>
                </div>
              </fieldset>

              <div class="a11yhubbr-field-inline">
                <label for="profile-image">Imagem do perfil</label>
                <div class="a11yhubbr-field-control">
                  <input id="profile-image" type="file" name="profile_image" accept="image/jpeg,image/png,image/webp">
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-submit-account" aria-label="Conta responsável pela submissão">
              <h2>Conta responsável pela submissão</h2>
              <p class="a11yhubbr-submit-account-summary">
                <strong>Nome:</strong> <?php echo esc_html($current_user ? $current_user->display_name : ''); ?>
                <br>
                <strong>Email:</strong> <?php echo esc_html($current_user ? $current_user->user_email : ''); ?>
              </p>
              <p class="a11yhubbr-help">A submissão será vinculada à conta logada e esses dados serão usados pela equipe da <strong>A11YBR</strong> em caso de contato.</p>
              <?php if (function_exists('a11yhubbr_render_human_check_field')) { a11yhubbr_render_human_check_field(); } ?>
            </section>

            <div class="a11yhubbr-form-actions">
              <button class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-form-submit" type="submit" name="a11yhubbr_profile_submit" value="1">Enviar para revisão</button>
            </div>
          </form>
          <?php endif; ?>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Informações complementares">
          <section class="a11yhubbr-side-card a11yhubbr-submit-outline">
            <h2>Navegação do cadastro</h2>
            <nav aria-label="Etapas da submissão de perfil">
              <a href="#sec-perfil-detalhes">Informações principais</a>
              <a href="#sec-perfil-redes">Informações complementares</a>
            </nav>
          </section>

          <section class="a11yhubbr-side-card">
            <h2>Diretrizes para perfil</h2>
            <ul>
              <li>Informe atuação real em acessibilidade.</li>
              <li>Use dados verificáveis e atualizados.</li>
              <li>Inclua links profissionais válidos.</li>
              <li>Descrição clara e objetiva do perfil.</li>
            </ul>
          </section>

          <section class="a11yhubbr-side-card a11yhubbr-side-card-primary">
            <h2>Processo de validação</h2>
            <ol>
              <li>Envio do perfil</li>
              <li>Verificação das informações</li>
              <li>Contato se necessário</li>
              <li>Publicação no diretório</li>
            </ol>
          </section>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
