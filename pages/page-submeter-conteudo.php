<?php
/*
Template Name: Submeter Conteudo
*/
if (!defined('ABSPATH')) {
    exit;
}

$status = isset($_GET['a11yhubbr_status']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_status'])) : '';
$form = isset($_GET['a11yhubbr_form']) ? sanitize_key(wp_unslash($_GET['a11yhubbr_form'])) : '';
$submitted = ($status === 'success' && $form === 'content');
$has_error = ($status === 'error' && $form === 'content');
$content_types = function_exists('a11yhubbr_get_content_type_map') ? a11yhubbr_get_content_type_map() : array();
$content_types = array_diff_key($content_types, array(
  'eventos' => true,
  'comunidades' => true,
  'redes' => true,
));

get_header();
?>
<main class="a11yhubbr-submit-page">
  <?php
  a11yhubbr_render_page_header(array(
      'breadcrumbs' => array(
          array('label' => 'Pagina inicial', 'url' => home_url('/')),
          array('label' => 'Submeter', 'url' => home_url('/submeter')),
          array('label' => 'Submeter conteudo'),
      ),
      'icon' => 'fa-regular fa-file-lines',
  ));
  ?>

  <section class="a11yhubbr-submit-section">
    <div class="a11yhubbr-container">
      <?php if ($submitted) : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-success" role="status">Conteudo enviado para revisao com sucesso.</div>
      <?php endif; ?>
      <?php if ($has_error) : ?>
        <div class="a11yhubbr-toast a11yhubbr-toast-error" role="alert">Nao foi possivel enviar agora. Tente novamente em instantes.</div>
      <?php endif; ?>

      <div class="a11yhubbr-submit-grid">
        <div class="a11yhubbr-submit-main">
          <form method="post" class="a11yhubbr-form-grid a11yhubbr-submit-form" id="content-form">
            <?php wp_nonce_field('a11yhubbr_content', 'a11yhubbr_nonce'); ?>
            <input type="hidden" name="a11yhubbr_redirect" value="<?php echo esc_url(get_permalink()); ?>">
            <input type="hidden" name="a11yhubbr_ts" value="<?php echo esc_attr((string) time()); ?>">
            <label class="a11yhubbr-honeypot" aria-hidden="true">Empresa
              <input type="text" name="a11yhubbr_company" tabindex="-1" autocomplete="off">
            </label>

            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Informacoes principais do conteudo</h2>
              <label>Tipo de conteudo *
                <select name="type" id="content-type-select" required>
                  <option value="">Selecione</option>
                  <?php foreach ($content_types as $slug => $type) : ?>
                    <option value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($type['label']); ?></option>
                  <?php endforeach; ?>
                </select>
              </label>
              <label>Titulo do conteudo *
                <input type="text" name="title" required>
              </label>
              <label>Descricao *
                <textarea name="description" rows="5" required></textarea>
              </label>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section">
              <h2>Detalhes da informacao</h2>
              <label>Autor *
                <input type="text" name="author" required>
              </label>
              <label>Organizacao
                <input type="text" name="organization">
              </label>
              <label>Link do conteudo *
                <input type="url" name="link" required>
              </label>
              <label>Tags (separadas por virgulas)
                <input type="text" name="tags" placeholder="acessibilidade, wcag, ux">
              </label>
              <p class="a11yhubbr-help">Use palavras-chave curtas para facilitar a busca e organizacao.</p>

              <div class="a11yhubbr-content-conditional" data-content-types="artigos,multimidia,sites-sistemas,cursos-materiais" hidden>
                <label>Ano de publicacao/atualizacao
                  <input type="number" name="year_publication" min="1900" max="2100" step="1">
                </label>
              </div>

              <div class="a11yhubbr-content-conditional" data-content-types="artigos,cursos-materiais,ferramentas,multimidia,sites-sistemas" hidden>
                <label>Nivel de profundidade
                  <select name="depth">
                    <option value="">Selecione</option>
                    <option value="introdutorio">Introdutorio</option>
                    <option value="intermediario">Intermediario</option>
                    <option value="avancado">Avancado</option>
                  </select>
                </label>
              </div>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" data-content-types="artigos" hidden>
              <h2>Campos contextuais: Artigos</h2>
              <label>Nomes das pessoas autoras
                <input type="text" name="article_authors" placeholder="Ex.: Maria Silva, Joao Souza">
              </label>
              <label>Tipo de artigo
                <select name="article_kind">
                  <option value="">Selecione</option>
                  <option value="academico">Academico</option>
                  <option value="ativismo">Ativismo</option>
                  <option value="estudo-caso">Estudo de caso</option>
                  <option value="opinativo">Opinativo</option>
                  <option value="tecnico">Tecnico</option>
                  <option value="outro">Outro</option>
                </select>
              </label>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" data-content-types="cursos-materiais" hidden>
              <h2>Campos contextuais: Livros e Materiais</h2>
              <label>Modalidade
                <select name="book_modality">
                  <option value="">Selecione</option>
                  <option value="online">Online</option>
                  <option value="presencial">Presencial</option>
                  <option value="hibrido">Hibrido</option>
                  <option value="nao-se-aplica">Nao se aplica</option>
                </select>
              </label>
              <label>Preco
                <select name="book_price">
                  <option value="">Selecione</option>
                  <option value="gratuito">Gratuito</option>
                  <option value="pago">Pago</option>
                </select>
              </label>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" data-content-types="ferramentas" hidden>
              <h2>Campos contextuais: Ferramentas</h2>
              <label>Tipo
                <select name="tool_type">
                  <option value="">Selecione</option>
                  <option value="auditoria-automatica">Auditoria automatica</option>
                  <option value="testes-manuais">Testes manuais</option>
                  <option value="contraste">Contraste</option>
                  <option value="design-system">Design System</option>
                  <option value="plugin">Plugin</option>
                  <option value="outros">Outros</option>
                </select>
              </label>
              <label>Modelo
                <select name="tool_model">
                  <option value="">Selecione</option>
                  <option value="open-source">Open source</option>
                  <option value="freemium">Freemium</option>
                  <option value="pago">Pago</option>
                </select>
              </label>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" data-content-types="multimidia" hidden>
              <h2>Campos contextuais: Multimidia</h2>
              <label>Tema principal
                <input type="text" name="media_theme">
              </label>
              <label>Midia
                <select name="media_channel_type">
                  <option value="">Selecione</option>
                  <option value="audio">Audio</option>
                  <option value="video">Video</option>
                </select>
              </label>
              <label>Formato
                <select name="media_format">
                  <option value="">Selecione</option>
                  <option value="entrevista">Entrevista</option>
                  <option value="mesa-redonda">Mesa-redonda</option>
                  <option value="solo">Solo</option>
                  <option value="tecnico">Tecnico</option>
                  <option value="storytelling">Storytelling</option>
                  <option value="outro">Outro</option>
                </select>
              </label>
              <label>Plataforma
                <select name="media_platform">
                  <option value="">Selecione</option>
                  <option value="spotify">Spotify</option>
                  <option value="apple">Apple</option>
                  <option value="site">Site</option>
                  <option value="youtube">YouTube / YouTube Music</option>
                  <option value="deezer">Deezer</option>
                  <option value="amazon-music">Amazon Music</option>
                  <option value="outro">Outro</option>
                </select>
              </label>
              <label>Frequencia
                <select name="media_frequency">
                  <option value="">Selecione</option>
                  <option value="semanal">Semanal</option>
                  <option value="quinzenal">Quinzenal</option>
                  <option value="mensal">Mensal</option>
                  <option value="pontual">Pontual</option>
                </select>
              </label>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-content-conditional" data-content-types="sites-sistemas" hidden>
              <h2>Campos contextuais: Sites e Sistemas</h2>
              <label>Modelo de negocio
                <select name="site_business_model">
                  <option value="">Selecione</option>
                  <option value="saas">SaaS</option>
                  <option value="e-commerce-marketplace">E-commerce / Marketplace</option>
                  <option value="open-source">Open source</option>
                  <option value="governamental">Governamental</option>
                </select>
              </label>
              <label>Estagio do produto
                <select name="site_stage">
                  <option value="">Selecione</option>
                  <option value="mvp">MVP</option>
                  <option value="em-crescimento">Em crescimento</option>
                  <option value="estavel">Estavel</option>
                  <option value="legado">Legado</option>
                </select>
              </label>
              <label>Modelo de acesso
                <select name="site_access_model">
                  <option value="">Selecione</option>
                  <option value="aberto">Aberto</option>
                  <option value="login-obrigatorio">Login obrigatorio</option>
                </select>
              </label>
            </section>

            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-form-section-contact">
              <h2>Email de contato</h2>
              <label>Email *
                <input type="email" name="email" required>
              </label>
              <p class="a11yhubbr-help">O email sera privado e utilizado apenas para que a organizacao da <strong>A11YBR</strong> possa entrar em contato com a pessoa que realizou a submissao.</p>
              <?php if (function_exists('a11yhubbr_render_human_check_field')) { a11yhubbr_render_human_check_field(); } ?>
            </section>

            <div class="a11yhubbr-form-actions">
              <button class="a11yhubbr-btn a11yhubbr-btn-primary a11yhubbr-form-submit" type="submit" name="a11yhubbr_content_submit" value="1">Enviar para revisao</button>
            </div>
          </form>
        </div>

        <aside class="a11yhubbr-submit-aside" aria-label="Informacoes complementares">
          <section class="a11yhubbr-side-card">
            <h2>Diretrizes para conteudo</h2>
            <ul>
              <li>Foque em acessibilidade digital e inclusao.</li>
              <li>Use titulo e descricao claros e objetivos.</li>
              <li>Inclua link valido para referencia.</li>
              <li>Priorize informacoes uteis para a comunidade.</li>
            </ul>
          </section>

          <section class="a11yhubbr-side-card a11yhubbr-side-card-primary">
            <h2>Processo de revisao</h2>
            <ol>
              <li>Recebimento da submissao</li>
              <li>Analise editorial</li>
              <li>Contato, se necessario</li>
              <li>Publicacao apos aprovacao</li>
            </ol>
          </section>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
