<?php
/*
Template Name: Submeter Conteúdo
*/
if (!defined('ABSPATH')) {
    exit;
}

$status = isset($_GET['a11yhubbr_status']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_status'])) : '';
$form = isset($_GET['a11yhubbr_form']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_form'])) : '';
$submitted = ($status === 'success' && $form === 'content');
$has_error = ($status === 'error' && $form === 'content');
$content_types = function_exists('a11yhubbr_get_content_type_map') ? a11yhubbr_get_content_type_map() : array();

get_header();
?>
<main class="a11yhubbr-submit-page">
  <?php
  a11yhubbr_render_page_header(array(
      'breadcrumbs' => array(
          array('label' => 'Página inicial', 'url' => home_url('/')),
          array('label' => 'Submeter', 'url' => home_url('/submeter')),
          array('label' => 'Submeter conteúdo'),
      ),
      'icon' => 'fa-regular fa-file-lines',
  ));
  ?>

  <section class="a11yhubbr-submit-section">
    <div class="a11yhubbr-container">
      <?php if ($submitted) : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-success" role="status">Conteúdo enviado para revisão com sucesso.</div>
      <?php endif; ?>
      <?php if ($has_error) : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-error" role="alert">Não foi possível enviar agora. Tente novamente em instantes.</div>
      <?php endif; ?>

      <div class="a11yhubbr-submit-grid">
        <div class="a11yhubbr-submit-main">
          <form method="post" class="a11yhubbr-form-grid a11yhubbr-submit-form">
            <?php wp_nonce_field('a11yhubbr_content', 'a11yhubbr_nonce'); ?>
            <input type="hidden" name="a11yhubbr_redirect" value="<?php echo esc_url(get_permalink()); ?>">
            <input type="hidden" name="a11yhubbr_ts" value="<?php echo esc_attr((string) time()); ?>">
            <label class="a11yhubbr-honeypot" aria-hidden="true">Empresa
              <input type="text" name="a11yhubbr_company" tabindex="-1" autocomplete="off">
            </label>

            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Informações principais do conteúdo</h2>
              <label>Tipo de conteúdo *
                <select name="type" required>
                  <option value="">Selecione</option>
                  <?php foreach ($content_types as $slug => $type) : ?>
                    <option value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($type['label']); ?></option>
                  <?php endforeach; ?>
                </select>
              </label>
              <label>Título do conteúdo *
                <input type="text" name="title" required>
              </label>
              <label>Descrição *
                <textarea name="description" rows="5" required></textarea>
              </label>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Detalhes da informação</h2>
              <label>Autor *
                <input type="text" name="author" required>
              </label>
              <label>Organização
                <input type="text" name="organization">
              </label>
              <label>Link do conteúdo *
                <input type="url" name="link" required>
              </label>
              <label>Tags (separadas por vérgulas)
                <input type="text" name="tags" placeholder="acessibilidade, wcag, ux">
              </label>
              <p class="a11yhubbr-help">Use palavras-chave curtas para facilitar a busca e organização.</p>
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
              <button class="a11yhubbr-btn a11yhubbr-form-submit" type="submit" name="a11yhubbr_content_submit" value="1">Enviar para revisão</button>
            </div>
          </form>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Informações complementares">
          <section class="a11yhubbr-side-card">
            <h2>Diretrizes para conteúdo</h2>
            <ul>
              <li>Foque em acessibilidade digital e inclusão.</li>
              <li>Use título e descrição claros e objetivos.</li>
              <li>Inclua link vélido para referéncia.</li>
              <li>Priorize informações úteis para a comunidade.</li>
            </ul>
          </section>

          <section class="a11yhubbr-side-card a11yhubbr-side-card-primary">
            <h2>Processo de revisão</h2>
            <ol>
              <li>Recebimento da submissão</li>
              <li>Anélise editorial</li>
              <li>Contato, se necessário</li>
              <li>Publicação após aprovação</li>
            </ol>
          </section>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>






