<?php
/*
Template Name: Acessibilidade
*/
if (!defined('ABSPATH')) {
  exit;
}

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-site-main a11yhubbr-legal-page">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Página inicial', 'url' => home_url('/')),
      array('label' => 'Acessibilidade'),
    ),
    'icon' => 'fa-solid fa-universal-access',
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container a11yhubbr-legal-stack">
      <article>
        <h2>Status de Conformidade</h2>
        <p>A <strong>A11YBR</strong> busca estar em conformidade com as <strong>Diretrizes de Acessibilidade para
            Conteudo Web (WCAG) 2.1</strong> nivel AA. Essas diretrizes explicam como tornar o conteudo web mais
          acessivel para pessoas com deficiencia. A conformidade nos ajuda a tornar o site mais acessivel para pessoas
          com deficiencias visuais, auditivas, motoras e cognitivas.</p>
      </article>


      <section class="a11yhubbr-resource-section ">
        <h2>Recursos de Acessibilidade</h2>
        <div class="a11yhubbr-about-flow-grid">
          <article class="a11yhubbr-about-flow-card">
            <span class="a11yhubbr-about-flow-icon " aria-hidden="true"><i
                class="fa-solid fa-keyboard"></i></span>
            <h3>Navegacao por teclado</h3>
            <p>Todo o site pode ser navegado usando apenas o teclado. Use <kbd>Tab</kbd> para avancar, <kbd>Shift +
                Tab</kbd> para voltar e <kbd>Enter</kbd> para ativar links e botoes.</p>
          </article>
          <article class="a11yhubbr-about-flow-card">
            <span class="a11yhubbr-about-flow-icon " aria-hidden="true"><i class="fa-regular fa-eye"></i></span>
            <h3>Leitores de tela</h3>
            <p>O site e compativel com leitores de tela como NVDA, JAWS e VoiceOver. Sempre que possivel, usamos
              textos
              alternativos descritivos.</p>
          </article>
          <article class="a11yhubbr-about-flow-card">
            <span class="a11yhubbr-about-flow-icon " aria-hidden="true"><i
                class="fa-regular fa-window-restore"></i></span>
            <h3>Design responsivo</h3>
            <p>O layout se adapta a diferentes tamanhos de tela e suporta zoom de ate 200% sem perda de funcionalidade
              ou
              conteudo.</p>
          </article>
          <article class="a11yhubbr-about-flow-card">
            <span class="a11yhubbr-about-flow-icon " aria-hidden="true"><i
                class="fa-solid fa-circle-half-stroke"></i></span>
            <h3>Contraste e cores</h3>
            <p>Utilizamos contraste de cor adequado e nao dependemos apenas de cor para transmitir informacao.</p>
          </article>
        </div>
      </section>



      <div class="a11yhubbr-cards-grid">
        <article>
          <h2>Especificacoes Tecnicas</h2>
          <p>A acessibilidade deste site depende das seguintes tecnologias:</p>
          <ul>
            <li>HTML semantico</li>
            <li>WAI-ARIA para componentes interativos</li>
            <li>CSS para apresentacao visual</li>
            <li>JavaScript para interatividade aprimorada</li>
          </ul>
        </article>

        <article>
          <h2>Limitacoes Conhecidas</h2>
          <ul>
            <li>Conteudo de terceiros: links externos podem levar a sites que nao seguem as mesmas praticas de
              acessibilidade.</li>
            <li>Conteudo gerado por usuarios: descricoes de iniciativas cadastradas podem nao seguir todas as diretrizes
              de acessibilidade.</li>
          </ul>
        </article>
      </div>
      <article>
        <h2>Feedback de Acessibilidade</h2>
        <p>Estamos sempre trabalhando para melhorar a acessibilidade do site. Se voce encontrar barreiras ou tiver
          sugestoes, entre em contato.</p>
        <p><strong>Email:</strong> <a
            href="mailto:a11yhubbr@gmail.com">a11yhubbr@gmail.com</a><br>
          <strong>Prazo de resposta:</strong> ate 5 dias uteis. Esta declaracao foi atualizada em janeiro de 2025.</p>
      </article>
    </div>
  </section>
</main>
<?php get_footer(); ?>