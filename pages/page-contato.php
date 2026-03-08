<?php
/*
Template Name: Contato
*/
if (!defined('ABSPATH')) {
  exit;
}

get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-contact-page">
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
      <article>
        <h2>Fale com a equipe</h2>
        <p>Você pode usar o formulário para:</p>
        <ul>
          <li>reportar informações incorretas ou links quebrados</li>
          <li>sugerir melhorias ou novos conteúdos para a plataforma</li>
          <li>enviar críticas construtivas ou feedback sobre o projeto</li>
          <li>tirar dúvidas sobre conteúdos publicados</li>
          <li>compartilhar ideias ou iniciativas relacionadas à acessibilidade digital</li>
        </ul>
        <p>Se sua mensagem estiver relacionada a algum conteúdo específico da plataforma, inclua o link da página e
          descreva o contexto da sugestão.</p>
        <p>Todas as mensagens são analisadas pela equipe editorial.</p>

        <hr>

        <p>Antes de enviar uma mensagem, veja também:</p>

        <ul>
          <li><a href="http://a11ybr.local/diretrizes-da-comunidade">Diretrizes da plataforma</a></li>
          <li><a href="http://a11ybr.local/submeter#como-funciona">Como submeter conteúdo</a></li>
          <li><a href="http://a11ybr.local/sobre#faq">Perguntas frequentes</a></li>
        </ul>

        <form class="a11yhubbr-form-grid a11yhubbr-submit-form" method="post" action="mailto:a11yhubbr@gmail.com"
          enctype="text/plain">
          <label>Tipo de mensagem
            <select name="tipo_mensagem" required>
              <option value="">Selecione</option>
              <option value="reportar-erro-em-conteudo">Reportar erro em conteúdo</option>
              <option value="sugerir-melhoria-ou-atualizacao">Sugerir melhoria ou atualização</option>
              <option value="enviar-feedback-ou-critica">Enviar feedback ou crítica</option>
              <option value="tirar-duvida-sobre-a-plataforma">Tirar dúvida sobre a plataforma</option>
              <option value="compartilhar-iniciativa-ou-recurso">Compartilhar iniciativa ou recurso</option>
              <option value="outro-assunto">Outro assunto</option>
            </select>
          </label>
          <label>Mensagem
            <textarea name="mensagem" rows="6" required></textarea>
          </label>
          <label>Link da página relacionada (opcional)
            <input type="url" name="link" placeholder="https://...">
          </label>
          <label>Seu nome
            <input type="text" name="nome" required>
          </label>
          <label>Seu e-mail
            <input type="email" name="email" required>
          </label>
          <div class="a11yhubbr-form-actions">
            <button type="submit" class="a11yhubbr-btn a11yhubbr-btn-primary">Enviar mensagem</button>
          </div>
        </form>

        <p class="text-muted">As mensagens são analisadas pela equipe editorial. O prazo médio de resposta é de até
          alguns dias úteis.</p>
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
