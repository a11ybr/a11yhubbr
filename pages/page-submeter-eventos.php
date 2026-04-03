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
          <?php if (!$is_logged_in) : ?>
            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Entre para submeter eventos</h2>
              <p>Agora a submissão de eventos exige uma conta no WordPress. Entre para continuar e vincular a submissão ao seu usuário.</p>
              <div class="a11yhubbr-form-actions">
                <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($login_url); ?>">Entrar</a>
                <?php if ($registration_url !== '') : ?>
                  <a class="a11yhubbr-btn" href="<?php echo esc_url($registration_url); ?>">Criar conta</a>
                <?php endif; ?>
              </div>
            </section>
          <?php else : ?>
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

              <div class="a11yhubbr-field-inline a11yhubbr-field-inline-choice">
                <label id="event-modality-label">Modalidade <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <div class="a11yhubbr-choice-group" role="radiogroup" aria-labelledby="event-modality-label">
                    <label class="a11yhubbr-radio-card" for="event-modality-presencial">
                      <input id="event-modality-presencial" type="radio" name="modality_choice" value="presencial">
                      <span>Presencial</span>
                    </label>
                    <label class="a11yhubbr-radio-card" for="event-modality-online">
                      <input id="event-modality-online" type="radio" name="modality_choice" value="online">
                      <span>Online</span>
                    </label>
                    <label class="a11yhubbr-radio-card" for="event-modality-hibrido">
                      <input id="event-modality-hibrido" type="radio" name="modality_choice" value="hibrido">
                      <span>Híbrido</span>
                    </label>
                  </div>
                  <input id="event-modality" type="hidden" name="modality" required aria-required="true">
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="event-type">Tipo de evento <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <select id="event-type" name="event_type" required aria-required="true">
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
                <label for="event-title">Título do evento <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <input id="event-title" type="text" name="title" required aria-required="true">
                </div>
              </div>

              <fieldset class="a11yhubbr-fieldset a11yhubbr-form-fieldset">
                <legend>Datas e horários do evento *</legend>
                <div id="event-slots" class="a11yhubbr-slots-list">
                  <div class="a11yhubbr-slot">
                    <label for="slot-start-1">Início <span aria-hidden="true">*</span>
                    <input id="slot-start-1" type="datetime-local" name="slot_start[]" required aria-required="true"></label>
                    <label for="slot-end-1">Fim <span aria-hidden="true">*</span>
                    <input id="slot-end-1" type="datetime-local" name="slot_end[]" required aria-required="true"></label>
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
                    <label for="event-cep">CEP do evento <span aria-hidden="true">*</span></label>
                    <div class="a11yhubbr-field-control">
                      <input id="event-cep" type="text" name="event_cep" inputmode="numeric" pattern="\\d{5}-?\\d{3}" placeholder="00000-000" required aria-required="true">
                      <p class="a11yhubbr-help">Para eventos presenciais e híbridos, informe o CEP do local.</p>
                    </div>
                  </div>
                </div>

                <div class="a11yhubbr-event-location-field" data-event-modality="online,hibrido" hidden>
                  <div class="a11yhubbr-field-inline">
                    <label for="event-online-location">Plataforma / local online <span aria-hidden="true">*</span></label>
                    <div class="a11yhubbr-field-control">
                      <input id="event-online-location" type="text" name="event_online_location" placeholder="Ex.: Zoom, Google Meet, YouTube, URL" required aria-required="true">
                      <p class="a11yhubbr-help">Para eventos online e híbridos, informe a plataforma ou link de transmissão.</p>
                    </div>
                  </div>
                </div>
              </div>

              <h2>Informações complementares</h2>

              <div class="a11yhubbr-field-inline">
                <label for="event-organizer">Organizador <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <input id="event-organizer" type="text" name="organizer" required aria-required="true">
                </div>
              </div>

              <div class="a11yhubbr-field-inline">
                <label for="event-link">Link do evento <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <input id="event-link" type="url" name="link" required aria-required="true">
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
                <label for="event-description">Descrição <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <textarea id="event-description" name="description" rows="5" required aria-required="true"></textarea>
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
              <button class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-form-submit" type="submit" name="a11yhubbr_event_submit" value="1">Enviar para revisão</button>
            </div>
          </form>
          <?php endif; ?>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Informações complementares">
          <section class="a11yhubbr-side-card a11yhubbr-submit-outline">
            <h2>Navegação do cadastro</h2>
            <nav aria-label="Etapas da submissão de evento">
              <a href="#sec-evento-principal">Informações principais</a>
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
