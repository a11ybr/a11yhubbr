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
          <form method="post" enctype="multipart/form-data" class="a11yhubbr-form-grid a11yhubbr-submit-form">
            <?php wp_nonce_field('a11yhubbr_profile', 'a11yhubbr_nonce'); ?>
            <input type="hidden" name="a11yhubbr_redirect" value="<?php echo esc_url(get_permalink()); ?>">
            <input type="hidden" name="a11yhubbr_ts" value="<?php echo esc_attr((string) time()); ?>">
            <label class="a11yhubbr-honeypot" aria-hidden="true">Empresa
              <input type="text" name="a11yhubbr_company" tabindex="-1" autocomplete="off">
            </label>

            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Detalhes do perfil</h2>
              <label>Tipo de perfil *
                <select name="profile_type" required>
                  <option value="">Selecione</option>
                  <option>Profissional de tecnologia</option>
                  <option>Empresa ou ONG</option>
                  <option>Intérprete de Libras</option>
                  <option>Audiodescritor</option>
                  <option>Tradutor de Braille</option>
                </select>
              </label>
              <label>Nome ou nome da organização *
                <input type="text" name="name" required>
              </label>
              <label>Localização (cidade, estado) *
                <input type="text" name="location" required>
              </label>
              <label>Bio profissional ou descrição institucional *
                <textarea name="description" rows="5" required></textarea>
              </label>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Especializações ou áreas de atuação</h2>
              <label>Cargo / Especialidade *
                <input type="text" name="role" required>
              </label>
              <label>Website
                <input type="url" name="website">
              </label>
              <label>Tags (separadas por vérgulas)
                <input type="text" name="tags" placeholder="design inclusivo, libras, consultoria">
              </label>
              <p class="a11yhubbr-help">Use tags para destacar especialidades e temas de atuação.</p>
              <fieldset class="a11yhubbr-fieldset a11yhubbr-form-fieldset">
                <legend>Links de redes sociais</legend>
                <div id="profile-social-links" class="a11yhubbr-slots-list a11yhubbr-social-slots-list">
                  <div class="a11yhubbr-slot a11yhubbr-social-slot">
                    <label>Rede social
                      <select name="social_network[]">
                        <option value="">Selecione</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="github">GitHub</option>
                        <option value="instagram">Instagram</option>
                        <option value="x">X/Twitter</option>
                        <option value="facebook">Facebook</option>
                        <option value="website">Outro website</option>
                      </select>
                    </label>
                    <label>URL
                      <input type="url" name="social_url[]" placeholder="https://...">
                    </label>
                    <button type="button" class="a11yhubbr-slot-remove a11yhubbr-social-slot-remove" aria-label="Remover link social" title="Remover link social" hidden>&#128465;</button>
                  </div>
                </div>
                <div class="a11yhubbr-slot-actions">
                  <button type="button" class="a11yhubbr-link-btn" id="add-social-link">+ Adicionar rede social</button>
                </div>
              </fieldset>
              <label>Foto de perfil
                <input type="file" name="profile_image" accept="image/jpeg,image/png,image/webp">
              </label>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-form-section-contact">
              <h2>Email de contato</h2>
              <label>Email *
                <input type="email" name="email" required>
              </label>
              <p class="a11yhubbr-help">O email será privado e utilizado apenas para que a organização da <strong>A11YBR</strong> possa entrar em contato com a pessoa que realizou a submissão.</p>
              <?php if (function_exists('a11yhubbr_render_human_check_field')) { a11yhubbr_render_human_check_field(); } ?>
            </section>

            <div class="a11yhubbr-form-actions">
              <button class="a11yhubbr-btn a11yhubbr-form-submit" type="submit" name="a11yhubbr_profile_submit" value="1">Enviar perfil</button>
            </div>
          </form>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Informações complementares">
          <section class="a11yhubbr-side-card">
            <h2>Diretrizes para perfis</h2>
            <ul>
              <li>Informe atuação real em acessibilidade.</li>
              <li>Use dados verificáveis e atualizados.</li>
              <li>Inclua links profissionais vélidos.</li>
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






