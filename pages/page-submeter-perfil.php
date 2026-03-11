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

get_header();
?>
<main class="a11yhubbr-submit-page">
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
                <label for="profile-type">Tipo de perfil *</label>
                <div class="a11yhubbr-field-control">
                  <select id="profile-type" name="profile_type" required>
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
                <label for="profile-name">Nome ou nome da organização *</label>
                <div class="a11yhubbr-field-control">
                  <input id="profile-name" type="text" name="name" required>
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="profile-location">Localização (cidade, estado) *</label>
                <div class="a11yhubbr-field-control">
                  <input id="profile-location" type="text" name="location" required>
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="profile-description">Bio profissional ou descrição institucional *</label>
                <div class="a11yhubbr-field-control">
                  <textarea id="profile-description" name="description" rows="5" required></textarea>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section" id="sec-perfil-areas" data-collapsible-section>
              <h2>Detalhes da submissão</h2>

              <div class="a11yhubbr-field-inline">
                <label for="profile-role">Cargo / especialidade *</label>
                <div class="a11yhubbr-field-control">
                  <input id="profile-role" type="text" name="role" required>
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

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-form-section-contact" id="sec-perfil-contato" data-collapsible-section>
              <h2>Autor da submissão</h2>
              <div class="a11yhubbr-contact-grid">
                <div class="a11yhubbr-field-inline">
                  <label for="profile-author">Nome *</label>
                  <div class="a11yhubbr-field-control">
                    <input id="profile-author" type="text" name="author" required>
                  </div>
                </div>
                <div class="a11yhubbr-field-inline">
                  <label for="profile-email">Email *</label>
                  <div class="a11yhubbr-field-control">
                    <input id="profile-email" type="email" name="email" required>
                  </div>
                </div>
              </div>
              <p class="a11yhubbr-help">O email será privado e utilizado apenas para que a organização da <strong>A11YBR</strong> possa entrar em contato com a pessoa que realizou a submissão.</p>
              <?php if (function_exists('a11yhubbr_render_human_check_field')) { a11yhubbr_render_human_check_field(); } ?>
            </section>

            <div class="a11yhubbr-form-actions">
              <button class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-form-submit" type="submit" name="a11yhubbr_profile_submit" value="1">Enviar para revisão</button>
            </div>
          </form>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Informações complementares">
          <section class="a11yhubbr-side-card a11yhubbr-submit-outline">
            <h2>Navegação do cadastro</h2>
            <nav aria-label="Etapas da submissão de perfil">
              <a href="#sec-perfil-detalhes">Informações principais</a>
              <a href="#sec-perfil-areas">Detalhes da submissão</a>
              <a href="#sec-perfil-redes">Informações complementares</a>
              <a href="#sec-perfil-contato">Autor da submissão</a>
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
