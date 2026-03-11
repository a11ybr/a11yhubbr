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
            <p class="a11yhubbr-required-legend"><span class="a11yhubbr-required-mark" aria-hidden="true">*</span> Campos obrigatórios</p>
            <?php wp_nonce_field('a11yhubbr_event', 'a11yhubbr_nonce'); ?>
            <input type="hidden" name="a11yhubbr_redirect" value="<?php echo esc_url(get_permalink()); ?>">
            <input type="hidden" name="a11yhubbr_ts" value="<?php echo esc_attr((string) time()); ?>">
            <label class="a11yhubbr-honeypot" aria-hidden="true">Empresa
              <input type="text" name="a11yhubbr_company" tabindex="-1" autocomplete="off">
            </label>

            <section class="a11yhubbr-card a11yhubbr-form-section" id="sec-evento-principal" data-collapsible-section>
              <h2>Informações principais</h2>

              <div class="a11yhubbr-field-inline">
                <label for="event-modality">Modalidade *</label>
                <div class="a11yhubbr-field-control">
                  <select id="event-modality" name="modality" required>
                    <option value="">Selecione</option>
                    <option value="presencial">Presencial</option>
                    <option value="online">Online</option>
                    <option value="hibrido">Híbrido</option>
                  </select>
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="event-type">Tipo de evento *</label>
                <div class="a11yhubbr-field-control">
                  <select id="event-type" name="event_type" required>
                    <option value="">Selecione</option>
                    <option>Workshop</option>
                    <option>Conferencia</option>
                    <option>Meetup</option>
                    <option>Webinar</option>
                    <option>Hackathon</option>
                    <option>Palestra</option>
                  </select>
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="event-title">Título do evento *</label>
                <div class="a11yhubbr-field-control">
                  <input id="event-title" type="text" name="title" required>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section" id="sec-evento-datas" data-collapsible-section>
              <h2>Detalhes da submissão</h2>
              <fieldset class="a11yhubbr-fieldset a11yhubbr-form-fieldset">
                <legend>Datas e horários do evento *</legend>
                <div id="event-slots" class="a11yhubbr-slots-list">
                  <div class="a11yhubbr-slot">
                    <label for="slot-start-1">Início *
                    <input id="slot-start-1" type="datetime-local" name="slot_start[]" required></label>
                    <label for="slot-end-1">Fim *
                    <input id="slot-end-1" type="datetime-local" name="slot_end[]" required></label>
                    <button type="button" class="a11yhubbr-slot-remove" aria-label="Remover esta data" title="Remover esta data" hidden>&#128465;</button>
                  </div>
                </div>
                <div class="a11yhubbr-slot-actions">
                  <button type="button" class="a11yhubbr-link-btn a11yhubbr-btn" id="add-slot">+ Adicionar item</button>
                </div>
                <p class="a11yhubbr-help">Para eventos com múltiplas datas, adicione todas as datas e horários.</p>
              </fieldset>

              <div class="a11yhubbr-event-location-switch" data-event-location-group>
                <div class="a11yhubbr-event-location-field" data-event-modality="presencial,hibrido" hidden>
                  <div class="a11yhubbr-field-inline">
                    <label for="event-cep">CEP do evento *</label>
                    <div class="a11yhubbr-field-control">
                      <input id="event-cep" type="text" name="event_cep" inputmode="numeric" pattern="\\d{5}-?\\d{3}" placeholder="00000-000" required>
                      <p class="a11yhubbr-help">Para eventos presenciais e híbridos, informe o CEP do local.</p>
                    </div>
                  </div>
                </div>

                <div class="a11yhubbr-event-location-field" data-event-modality="online,hibrido" hidden>
                  <div class="a11yhubbr-field-inline">
                    <label for="event-online-location">Plataforma / local online *</label>
                    <div class="a11yhubbr-field-control">
                      <input id="event-online-location" type="text" name="event_online_location" placeholder="Ex.: Zoom, Google Meet, YouTube, URL" required>
                      <p class="a11yhubbr-help">Para eventos online e híbridos, informe a plataforma ou link de transmissão.</p>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section" id="sec-evento-detalhes" data-collapsible-section>
              <h2>Informações complementares</h2>
              <div class="a11yhubbr-field-inline">
                <label for="event-organizer">Organizador *</label>
                <div class="a11yhubbr-field-control">
                  <input id="event-organizer" type="text" name="organizer" required>
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="event-link">Link do evento *</label>
                <div class="a11yhubbr-field-control">
                  <input id="event-link" type="url" name="link" required>
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="event-tags">Tags (separadas por vírgulas)</label>
                <div class="a11yhubbr-field-control">
                  <input id="event-tags" type="text" name="tags" placeholder="workshop, acessibilidade, online">
                  <p class="a11yhubbr-help">As tags ajudam a indexar o evento por tema.</p>
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="event-description">Descrição *</label>
                <div class="a11yhubbr-field-control">
                  <textarea id="event-description" name="description" rows="5" required></textarea>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-form-section-contact" id="sec-evento-contato" data-collapsible-section>
              <h2>Autor da submissão</h2>
              <div class="a11yhubbr-contact-grid">
                <div class="a11yhubbr-field-inline">
                  <label for="event-author">Nome *</label>
                  <div class="a11yhubbr-field-control">
                    <input id="event-author" type="text" name="author" required>
                  </div>
                </div>
                <div class="a11yhubbr-field-inline">
                  <label for="event-email">Email *</label>
                  <div class="a11yhubbr-field-control">
                    <input id="event-email" type="email" name="email" required>
                  </div>
                </div>
              </div>
              <p class="a11yhubbr-help">O email será privado e utilizado apenas para que a organização da <strong>A11YBR</strong> possa entrar em contato com a pessoa que realizou a submissão.</p>
              <?php if (function_exists('a11yhubbr_render_human_check_field')) { a11yhubbr_render_human_check_field(); } ?>
            </section>

            <div class="a11yhubbr-form-actions">
              <button class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-form-submit" type="submit" name="a11yhubbr_event_submit" value="1">Enviar para revisão</button>
            </div>
          </form>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Informações complementares">
          <section class="a11yhubbr-side-card a11yhubbr-submit-outline">
            <h2>Navegação do cadastro</h2>
            <nav aria-label="Etapas da submissão de evento">
              <a href="#sec-evento-principal">Informações principais</a>
              <a href="#sec-evento-datas">Detalhes da submissão</a>
              <a href="#sec-evento-detalhes">Informações complementares</a>
              <a href="#sec-evento-contato">Autor da submissão</a>
            </nav>
          </section>

          <section class="a11yhubbr-side-card">
            <h2>Diretrizes para eventos</h2>
            <ul>
              <li>Evento com foco em acessibilidade digital.</li>
              <li>Informe modalidade e local/plataforma.</li>
              <li>Inclua datas e horários completos.</li>
              <li>Adicione link oficial do evento.</li>
            </ul>
          </section>

          <section class="a11yhubbr-side-card a11yhubbr-side-card-primary">
            <h2>Processo de revisão</h2>
            <ol>
              <li>Submissão recebida</li>
              <li>Análise editorial</li>
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
