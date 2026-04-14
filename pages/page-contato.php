<?php
/*
Template Name: Contato
*/
if (!defined('ABSPATH')) {
  exit;
}

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-site-main a11yhubbr-contact-page">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Página inicial', 'url' => home_url('/')),
      array('label' => 'Contato'),
    ),
    'icon' => 'fa-regular fa-envelope',
    'title' => 'Contato',
    'summary' => 'Tem alguma sugestão, encontrou uma informação desatualizada ou quer contribuir com a plataforma? Este é o canal direto para falar com a equipe do A11YBR.',
    'use_page_context' => false,
  ));
  ?>
  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container a11yhubbr-single-layout">
      <article class="a11yhubbr-card a11yhubbr-rich-content a11yhubbr-contact-article">
        <h2>Fale com a equipe</h2>
        <p>Você pode usar o formulário para:</p>
        <ul>
          <li>reportar informações incorretas ou links quebrados;</li>
          <li>sugerir melhorias ou novos conteúdos para a plataforma;</li>
          <li>enviar críticas construtivas ou feedback sobre o projeto;</li>
          <li>tirar dúvidas sobre conteúdos publicados;</li>
          <li>compartilhar ideias ou iniciativas relacionadas à acessibilidade digital;</li>
        </ul>
        <p>Se sua mensagem estiver relacionada a algum conteúdo específico da plataforma, inclua o link da página e
          descreva o contexto da sugestão.</p>
        <p>Todas as mensagens são analisadas pelo time da comunidade.</p>

        <hr>

        <p>Antes de enviar uma mensagem, veja também:</p>

        <div class="a11yhubbr-contact-shortcuts" aria-label="Atalhos relacionados">
          <a class="a11yhubbr-contact-shortcut-card" href="<?php echo esc_url(home_url('/diretrizes-da-comunidade')); ?>">
            <span class="a11yhubbr-contact-shortcut-icon" aria-hidden="true"><i class="fa-solid fa-book-open"></i></span>
            <span class="a11yhubbr-contact-shortcut-content">
              <strong>Diretrizes da plataforma:</strong>
              <span>Leia as regras e combinados editoriais da comunidade.</span>
            </span>
          </a>
          <a class="a11yhubbr-contact-shortcut-card" href="<?php echo esc_url(home_url('/submeter')); ?>#como-funciona">
            <span class="a11yhubbr-contact-shortcut-icon" aria-hidden="true"><i class="fa-solid fa-paper-plane"></i></span>
            <span class="a11yhubbr-contact-shortcut-content">
              <strong>Como submeter conteúdo:</strong>
              <span>Entenda o fluxo de envio antes de entrar em contato.</span>
            </span>
          </a>
          <a class="a11yhubbr-contact-shortcut-card" href="<?php echo esc_url(home_url('/sobre')); ?>#faq">
            <span class="a11yhubbr-contact-shortcut-icon" aria-hidden="true"><i class="fa-regular fa-circle-question"></i></span>
            <span class="a11yhubbr-contact-shortcut-content">
              <strong>Perguntas frequentes:</strong>
              <span>Consulte respostas rápidas para dúvidas mais comuns.</span>
            </span>
          </a>
        </div>


        <p>Campos marcados com asterisco são obrigatórios.</p>

        <div id="contact-feedback" class="a11yhubbr-contact-feedback" role="status" aria-live="polite" aria-atomic="true" hidden></div>

        <form id="hub-contact-form" class="a11yhubbr-form-grid a11yhubbr-submit-form" method="post" novalidate>
          <?php wp_nonce_field('hub_contact_submit', 'hub_contact_nonce'); ?>

          <!-- Honeypot anti-spam: deve ficar vazio -->
          <div class="a11yhubbr-sr-only" aria-hidden="true">
            <label for="contact-website">Deixe este campo em branco</label>
            <input id="contact-website" type="text" name="website" tabindex="-1" autocomplete="off">
          </div>

          <div class="a11yhubbr-field">
            <label for="contact-tipo">Tipo de mensagem <span aria-hidden="true">*</span></label>
            <select id="contact-tipo" name="tipo_mensagem" required aria-required="true">
              <option value="">Selecione</option>
              <option value="reportar-erro-em-conteudo">Reportar erro em conteúdo</option>
              <option value="sugerir-melhoria-ou-atualizacao">Sugerir melhoria ou atualização</option>
              <option value="enviar-feedback-ou-critica">Enviar feedback ou crítica</option>
              <option value="tirar-duvida-sobre-a-plataforma">Tirar dúvida sobre a plataforma</option>
              <option value="compartilhar-iniciativa-ou-recurso">Compartilhar iniciativa ou recurso</option>
              <option value="outro-assunto">Outro assunto</option>
            </select>
          </div>
          <div class="a11yhubbr-field">
            <label for="contact-mensagem">Mensagem <span aria-hidden="true">*</span></label>
            <textarea id="contact-mensagem" name="mensagem" rows="6" required aria-required="true"></textarea>
          </div>
          <div class="a11yhubbr-field">
            <label for="contact-link">Link da página relacionada (opcional)</label>
            <input id="contact-link" type="url" name="link" placeholder="https://...">
          </div>
          <div class="a11yhubbr-field">
            <label for="contact-nome">Seu nome <span aria-hidden="true">*</span></label>
            <input id="contact-nome" type="text" name="nome" required aria-required="true" autocomplete="name">
          </div>
          <div class="a11yhubbr-field">
            <label for="contact-email">Seu e-mail <span aria-hidden="true">*</span></label>
            <input id="contact-email" type="email" name="email" required aria-required="true" autocomplete="email">
          </div>
          <div class="a11yhubbr-form-actions">
            <button id="contact-submit" type="submit" class="a11yhubbr-btn a11yhubbr-btn-primary">
              <span class="contact-btn-label">Enviar mensagem</span>
              <span class="contact-btn-sending a11yhubbr-sr-only" aria-hidden="true">Enviando…</span>
            </button>
          </div>
        </form>

        <p class="text-muted">
        <small>O prazo médio de resposta é de até 7 dias úteis.</small></p>
      </article>

      <aside class="a11yhubbr-side-card a11yhubbr-single-side">
        <h2>Outros canais</h2>
        <dl>
          <div>
            <dt>E-mail</dt>
            <dd><a href="mailto:a11yhubbr@gmail.com">a11yhubbr@gmail.com</a></dd>
          </div>
          <div>
            <dt>Rede</dt>
            <dd><a href="<?php echo esc_url(home_url('/rede')); ?>">Ver perfis da comunidade</a></dd>
          </div>
          <div>
            <dt>Diretrizes</dt>
            <dd><a href="<?php echo esc_url(home_url('/diretrizes-da-comunidade')); ?>">Consultar diretrizes da
                plataforma</a></dd>
          </div>
        </dl>
      </aside>
    </div>
  </section>
</main>
<?php get_footer(); ?>
