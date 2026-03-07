<?php
/*
Template Name: Submeter Eventos
*/
if (!defined('ABSPATH')) {
    exit;
}

$status = isset($_GET['a11yhubbr_status']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_status'])) : '';
$form = isset($_GET['a11yhubbr_form']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_form'])) : '';
$submitted = ($status === 'success' && $form === 'event');
$has_error = ($status === 'error' && $form === 'event');

get_header();
?>
<main class="a11yhubbr-submit-page">
  <?php
  a11yhubbr_render_page_header(array(
      'breadcrumbs' => array(
          array('label' => 'Página inicial', 'url' => home_url('/')),
          array('label' => 'Submeter', 'url' => home_url('/submeter')),
          array('label' => 'Submeter eventos'),
      ),
      'icon' => 'fa-regular fa-calendar',
  ));
  ?>

  <section class="a11yhubbr-submit-section">
    <div class="a11yhubbr-container">
      <?php if ($submitted) : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-success" role="status">Evento enviado para revisão com sucesso.</div>
      <?php endif; ?>
      <?php if ($has_error) : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-error" role="alert">Não foi possível enviar agora. Tente novamente em instantes.</div>
      <?php endif; ?>

      <div class="a11yhubbr-submit-grid">
        <div class="a11yhubbr-submit-main">
          <form method="post" class="a11yhubbr-form-grid a11yhubbr-submit-form" id="event-form">
            <?php wp_nonce_field('a11yhubbr_event', 'a11yhubbr_nonce'); ?>
            <input type="hidden" name="a11yhubbr_redirect" value="<?php echo esc_url(get_permalink()); ?>">
            <input type="hidden" name="a11yhubbr_ts" value="<?php echo esc_attr((string) time()); ?>">
            <label class="a11yhubbr-honeypot" aria-hidden="true">Empresa
              <input type="text" name="a11yhubbr_company" tabindex="-1" autocomplete="off">
            </label>

            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Informações principais do evento</h2>
              <label>Modalidade *
                <select name="modality" required>
                  <option value="">Selecione</option>
                  <option>Presencial</option><option>Online</option><option>Hébrido</option>
                </select>
              </label>
              <label>Tipo de evento *
                <select name="event_type" required>
                  <option value="">Selecione</option>
                  <option>Workshop</option><option>Conferéncia</option><option>Meetup</option>
                  <option>Webinar</option><option>Hackathon</option><option>Curso</option><option>Palestra</option>
                </select>
              </label>
              <label>Título do evento *
                <input type="text" name="title" required>
              </label>

              <fieldset class="a11yhubbr-fieldset a11yhubbr-form-fieldset">
                <legend>Datas e horérios do evento *</legend>
                <div id="event-slots" class="a11yhubbr-slots-list">
                  <div class="a11yhubbr-slot">
                    <label>Início *<input type="datetime-local" name="slot_start[]" required></label>
                    <label>Fim *<input type="datetime-local" name="slot_end[]" required></label>
                    <button type="button" class="a11yhubbr-slot-remove" aria-label="Remover esta data" title="Remover esta data" hidden>&#128465;</button>
                  </div>
                </div>
                <div class="a11yhubbr-slot-actions">
                  <button type="button" class="a11yhubbr-link-btn" id="add-slot">+ Adicionar outra data</button>
                </div>
                <p class="a11yhubbr-help">Para eventos com múltiplas datas, adicione todas as datas e horérios.</p>
              </fieldset>

              <label>Localização (cidade/estado) ou ferramenta de transmissão *
                <input type="text" name="location" required>
              </label>
              <p class="a11yhubbr-help">Para eventos presenciais/hábridos: cidade e estado. Para eventos online: plataforma utilizada.</p>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Detalhes do evento</h2>
              <label>Descrição *
                <textarea name="description" rows="5" required></textarea>
              </label>
              <label>Organizador *
                <input type="text" name="organizer" required>
              </label>
              <label>Link do evento *
                <input type="url" name="link" required>
              </label>
              <label>Tags (separadas por vérgulas)
                <input type="text" name="tags" placeholder="workshop, acessibilidade, online">
              </label>
              <p class="a11yhubbr-help">As tags ajudam a indexar o evento por tema.</p>
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
              <button class="a11yhubbr-btn a11yhubbr-form-submit" type="submit" name="a11yhubbr_event_submit" value="1">Enviar para revisão</button>
            </div>
          </form>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Informações complementares">
          <section class="a11yhubbr-side-card">
            <h2>Diretrizes para eventos</h2>
            <ul>
              <li>Evento com foco em acessibilidade digital.</li>
              <li>Informe modalidade e local/plataforma.</li>
              <li>Inclua datas e horérios completos.</li>
              <li>Adicione link oficial do evento.</li>
            </ul>
          </section>

          <section class="a11yhubbr-side-card a11yhubbr-side-card-primary">
            <h2>Processo de revisão</h2>
            <ol>
              <li>Submissão recebida</li>
              <li>Anélise editorial</li>
              <li>Feedback por email</li>
              <li>Publicação após aprovação</li>
            </ol>
          </section>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>





