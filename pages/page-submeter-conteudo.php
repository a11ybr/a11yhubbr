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
$is_logged_in = is_user_logged_in();
$login_url = function_exists('a11yhubbr_get_submission_login_url') ? a11yhubbr_get_submission_login_url(get_permalink()) : wp_login_url(get_permalink());
$registration_url = function_exists('a11yhubbr_get_submission_registration_url') ? a11yhubbr_get_submission_registration_url(get_permalink()) : '';
$current_user = $is_logged_in ? wp_get_current_user() : null;
$content_types = function_exists('a11yhubbr_get_content_type_map') ? a11yhubbr_get_content_type_map() : array();
$content_types = array_diff_key($content_types, array(
  'eventos' => true,
  'comunidades' => true,
  'redes' => true,
));

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-submit-page">
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
          <?php if (!$is_logged_in) : ?>
            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Entre para submeter conteúdo</h2>
              <p>Agora a submissão de conteúdo exige uma conta no WordPress. Entre para continuar e vincular a submissão ao seu usuário.</p>
              <div class="a11yhubbr-form-actions">
                <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($login_url); ?>">Entrar</a>
                <?php if ($registration_url !== '') : ?>
                  <a class="a11yhubbr-btn" href="<?php echo esc_url($registration_url); ?>">Criar conta</a>
                <?php endif; ?>
              </div>
            </section>
          <?php else : ?>
          <form method="post" class="a11yhubbr-form-grid a11yhubbr-submit-form" id="content-form">
            <p class="a11yhubbr-required-legend"><span class="a11yhubbr-required-mark" aria-hidden="true">*</span> Campos obrigatórios</p>
            <?php wp_nonce_field('a11yhubbr_content', 'a11yhubbr_nonce'); ?>
            <input type="hidden" name="a11yhubbr_redirect" value="<?php echo esc_url(get_permalink()); ?>">
            <input type="hidden" name="a11yhubbr_ts" value="<?php echo esc_attr((string) time()); ?>">
            <label class="a11yhubbr-honeypot" aria-hidden="true">Empresa
              <input type="text" name="a11yhubbr_company" tabindex="-1" autocomplete="off">
            </label>

            <section class="a11yhubbr-card a11yhubbr-form-section" id="sec-conteudo-principal" data-collapsible-section>
              <h2>Informações principais</h2>
              <div class="a11yhubbr-field-inline">
                <label for="content-type-select">Tipo de conteúdo <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <select name="type" id="content-type-select" required aria-required="true">
                    <option value="">Selecione</option>
                    <?php foreach ($content_types as $slug => $type) : ?>
                      <option value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($type['label']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-title">Título do conteúdo <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <input id="content-title" type="text" name="title" required aria-required="true">
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-description">Descrição <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <textarea id="content-description" name="description" rows="5" required aria-required="true"></textarea>
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-organization">Organização do conteúdo</label>
                <div class="a11yhubbr-field-control">
                  <input id="content-organization" type="text" name="organization">
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-link">Link do conteúdo <span aria-hidden="true">*</span></label>
                <div class="a11yhubbr-field-control">
                  <input id="content-link" type="url" name="link" required aria-required="true">
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-tags">Tags (separadas por vírgulas)</label>
                <div class="a11yhubbr-field-control">
                  <input id="content-tags" type="text" name="tags" placeholder="acessibilidade, wcag, ux">
                  <p class="a11yhubbr-help">Use palavras-chave curtas para facilitar a busca e organização.</p>
                </div>
              </div>

              <div class="a11yhubbr-content-conditional" data-content-types="artigos,multimidia,sites-sistemas,cursos-materiais" hidden>
                <div class="a11yhubbr-field-inline">
                  <label for="content-year-publication">Ano de publicação/atualização</label>
                  <div class="a11yhubbr-field-control">
                    <input id="content-year-publication" type="number" name="year_publication" min="1900" max="2100" step="1">
                  </div>
                </div>
              </div>

              <div class="a11yhubbr-content-conditional" data-content-types="artigos,cursos-materiais,ferramentas,multimidia,sites-sistemas" hidden>
                <div class="a11yhubbr-field-inline">
                  <label for="content-depth">Nível de profundidade</label>
                  <div class="a11yhubbr-field-control">
                    <select id="content-depth" name="depth">
                      <option value="">Selecione</option>
                      <option value="introdutorio">Introdutório</option>
                      <option value="intermediario">Intermediário</option>
                      <option value="avancado">Avançado</option>
                    </select>
                  </div>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-artigos" data-content-types="artigos" data-collapsible-section hidden>
              <h2>Informações adiconais do artigo</h2>
              <div class="a11yhubbr-field-inline">
                <label for="content-article-authors">Nomes das pessoas autoras</label>
                <div class="a11yhubbr-field-control">
                  <input id="content-article-authors" type="text" name="article_authors" placeholder="Ex.: Maria Silva, João Souza">
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-article-kind">Tipo de artigo</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-article-kind" name="article_kind">
                    <option value="">Selecione</option>
                    <option value="academico">Acadêmico</option>
                    <option value="ativismo">Ativismo</option>
                    <option value="estudo-caso">Estudo de caso</option>
                    <option value="opinativo">Opinativo</option>
                    <option value="tecnico">Técnico</option>
                    <option value="outro">Outro</option>
                  </select>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-livros" data-content-types="cursos-materiais" data-collapsible-section hidden>
        <h2>Informações adiconais do livro ou material</h2>
              <div class="a11yhubbr-field-inline">
                <label for="content-book-modality">Modalidade</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-book-modality" name="book_modality">
                    <option value="">Selecione</option>
                    <option value="online">Online</option>
                    <option value="presencial">Presencial</option>
                    <option value="hibrido">Híbrido</option>
                    <option value="nao-se-aplica">Não se aplica</option>
                  </select>
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-book-price">Preço</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-book-price" name="book_price">
                    <option value="">Selecione</option>
                    <option value="gratuito">Gratuito</option>
                    <option value="pago">Pago</option>
                  </select>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-ferramentas" data-content-types="ferramentas" data-collapsible-section hidden>
        <h2>Informações adiconais da ferramenta</h2>
              <div class="a11yhubbr-field-inline">
                <label for="content-tool-type">Tipo</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-tool-type" name="tool_type">
                    <option value="">Selecione</option>
                    <option value="auditoria-automatica">Auditoria automática</option>
                    <option value="testes-manuais">Testes manuais</option>
                    <option value="contraste">Contraste</option>
                    <option value="design-system">Design System</option>
                    <option value="plugin">Plugin</option>
                    <option value="outros">Outros</option>
                  </select>
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-tool-model">Modelo</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-tool-model" name="tool_model">
                    <option value="">Selecione</option>
                    <option value="open-source">Open source</option>
                    <option value="freemium">Freemium</option>
                    <option value="pago">Pago</option>
                  </select>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-multimidia" data-content-types="multimidia" data-collapsible-section hidden>
              <h2>Informações adiconais da multimídia</h2>
              <div class="a11yhubbr-field-inline">
                <label for="content-media-theme">Tema principal</label>
                <div class="a11yhubbr-field-control">
                  <input id="content-media-theme" type="text" name="media_theme">
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-media-channel-type">Mídia</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-media-channel-type" name="media_channel_type">
                    <option value="">Selecione</option>
                    <option value="audio">Áudio</option>
                    <option value="video">Vídeo</option>
                  </select>
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-media-format">Formato</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-media-format" name="media_format">
                    <option value="">Selecione</option>
                    <option value="entrevista">Entrevista</option>
                    <option value="mesa-redonda">Mesa-redonda</option>
                    <option value="solo">Solo</option>
                    <option value="tecnico">Técnico</option>
                    <option value="storytelling">Storytelling</option>
                    <option value="outro">Outro</option>
                  </select>
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-media-platform">Plataforma</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-media-platform" name="media_platform">
                    <option value="">Selecione</option>
                    <option value="spotify">Spotify</option>
                    <option value="apple">Apple</option>
                    <option value="site">Site</option>
                    <option value="youtube">YouTube / YouTube Music</option>
                    <option value="deezer">Deezer</option>
                    <option value="amazon-music">Amazon Music</option>
                    <option value="outro">Outro</option>
                  </select>
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-media-frequency">Frequência</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-media-frequency" name="media_frequency">
                    <option value="">Selecione</option>
                    <option value="semanal">Semanal</option>
                    <option value="quinzenal">Quinzenal</option>
                    <option value="mensal">Mensal</option>
                    <option value="pontual">Pontual</option>
                  </select>
                </div>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" id="sec-conteudo-sites" data-content-types="sites-sistemas" data-collapsible-section hidden>
              <h2>Informações adiconais do site ou sistema</h2>
              <div class="a11yhubbr-field-inline">
                <label for="content-site-business-model">Modelo de negócio</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-site-business-model" name="site_business_model">
                    <option value="">Selecione</option>
                    <option value="saas">SaaS</option>
                    <option value="e-commerce-marketplace">E-commerce / Marketplace</option>
                    <option value="open-source">Open source</option>
                    <option value="governamental">Governamental</option>
                  </select>
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-site-stage">Estágio do produto</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-site-stage" name="site_stage">
                    <option value="">Selecione</option>
                    <option value="mvp">MVP</option>
                    <option value="em-crescimento">Em crescimento</option>
                    <option value="estavel">Estável</option>
                    <option value="legado">Legado</option>
                  </select>
                </div>
              </div>
              <div class="a11yhubbr-field-inline">
                <label for="content-site-access-model">Modelo de acesso</label>
                <div class="a11yhubbr-field-control">
                  <select id="content-site-access-model" name="site_access_model">
                    <option value="">Selecione</option>
                    <option value="aberto">Aberto</option>
                    <option value="login-obrigatorio">Login obrigatório</option>
                  </select>
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
              <button class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-form-submit" type="submit" name="a11yhubbr_content_submit" value="1">Enviar para revisão</button>
            </div>
          </form>
          <?php endif; ?>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Informações complementares">
          <section class="a11yhubbr-side-card a11yhubbr-submit-outline">
            <h2>Navegação do cadastro</h2>
            <nav aria-label="Etapas da submissão de conteúdo">
              <a href="#sec-conteudo-principal">Informações principais</a>
              <a href="#sec-conteudo-artigos">Informações adiconais do artigo</a>
              <a href="#sec-conteudo-livros">Informações adiconais do livros ou materiais</a>
              <a href="#sec-conteudo-ferramentas">Informações adiconais da ferramenta</a>
              <a href="#sec-conteudo-multimidia">Informações adiconais da multimídia</a>
              <a href="#sec-conteudo-sites">Informações adiconais do site ou sistema</a>
            </nav>
          </section>

          <section class="a11yhubbr-side-card">
            <h2>Diretrizes para conteúdo</h2>
            <ul>
              <li>Foque em acessibilidade digital e inclusão.</li>
              <li>Use título e descrição claros e objetivos.</li>
              <li>Inclua link válido para referência.</li>
              <li>Priorize informações úteis para a comunidade.</li>
            </ul>
          </section>

          <section class="a11yhubbr-side-card a11yhubbr-side-card-primary">
            <h2>Processo de revisão</h2>
            <ol>
              <li>Recebimento da submissão</li>
              <li>Análise editorial</li>
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
