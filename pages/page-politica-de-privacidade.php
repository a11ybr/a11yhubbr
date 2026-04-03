<?php
/*
Template Name: Politica de Privacidade
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
      array('label' => 'Pagina inicial', 'url' => home_url('/')),
      array('label' => 'Politica de Privacidade'),
    ),
    'icon' => 'fa-solid fa-shield-halved',
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container a11yhubbr-legal-stack">
      <article class="a11yhubbr-card">
        <p><strong>Ultima atualizacao:</strong> Janeiro de 2025</p>
      </article>

      <article class="a11yhubbr-card">
        <h2>1. Introducao</h2>
        <p>A A11YBR esta comprometida com a protecao da sua privacidade. Esta politica explica como coletamos, usamos e protegemos informacoes pessoais em conformidade com a LGPD (Lei n 13.709/2018).</p>
      </article>

      <article class="a11yhubbr-card">
        <h2>2. Dados que Coletamos</h2>
        <h3>2.1 Dados fornecidos por voce</h3>
        <ul>
          <li>Cadastro de iniciativas: nome, email e informacoes da iniciativa</li>
          <li>Contato: nome, email e conteudo das mensagens enviadas</li>
          <li>Contribuicoes: sugestoes, correcoes e feedback enviados</li>
        </ul>
        <h3>2.2 Dados coletados automaticamente</h3>
        <ul>
          <li>Dados de navegacao: paginas visitadas e tempo de permanencia</li>
          <li>Dados tecnicos: navegador, sistema operacional e endereco IP</li>
          <li>Cookies: identificadores para melhorar a experiencia de uso</li>
        </ul>
      </article>

      <article class="a11yhubbr-card">
        <h2>3. Como Usamos seus Dados</h2>
        <ul>
          <li>Processar e publicar iniciativas cadastradas</li>
          <li>Responder contatos e solicitacoes</li>
          <li>Melhorar a plataforma e a experiencia de uso</li>
          <li>Gerar estatisticas anonimas</li>
          <li>Enviar comunicacoes relevantes, com consentimento</li>
          <li>Prevenir fraudes e proteger a seguranca da plataforma</li>
        </ul>
      </article>

      <article class="a11yhubbr-card">
        <h2>4. Base Legal para Tratamento</h2>
        <ul>
          <li>Consentimento</li>
          <li>Legitimo interesse</li>
          <li>Execucao de contrato</li>
        </ul>
      </article>

      <article class="a11yhubbr-card">
        <h2>5. Compartilhamento de Dados</h2>
        <p>Nao vendemos dados pessoais. Podemos compartilhar informacoes apenas com prestadores essenciais, por obrigacao legal, para protecao de direitos legais ou com consentimento explicito.</p>
        <p>Informacoes publicas das iniciativas (nome, descricao e links) sao exibidas publicamente no diretorio.</p>
      </article>

      <article class="a11yhubbr-card">
        <h2>6. Seus Direitos (LGPD)</h2>
        <ul>
          <li>Confirmacao e acesso</li>
          <li>Correcao de dados incompletos ou incorretos</li>
          <li>Anonimizacao, bloqueio ou eliminacao</li>
          <li>Portabilidade</li>
          <li>Revogacao de consentimento</li>
          <li>Oposicao ao tratamento em legitimo interesse</li>
        </ul>
        <p>Para exercer seus direitos: <a href="mailto:privacidade@acessibilidadebrasil.org">privacidade@acessibilidadebrasil.org</a></p>
      </article>

      <article class="a11yhubbr-card">
        <h2>7. Cookies</h2>
        <ul>
          <li>Cookies essenciais</li>
          <li>Cookies de analise</li>
          <li>Cookies de preferencias</li>
        </ul>
        <p>Voce pode gerenciar cookies no navegador. Desabilitar cookies essenciais pode afetar funcionalidades.</p>
      </article>

      <article class="a11yhubbr-card">
        <h2>8. Seguranca</h2>
        <p>Aplicamos medidas tecnicas e organizacionais como HTTPS, controle de acesso, monitoramento e backups regulares. Nenhum sistema e 100% seguro.</p>
      </article>

      <article class="a11yhubbr-card">
        <h2>9. Retencao de Dados</h2>
        <p>Mantemos dados pelo tempo necessario para cumprir finalidades e obrigacoes legais. Dados de iniciativas permanecem enquanto a iniciativa estiver ativa no diretorio.</p>
      </article>

      <article class="a11yhubbr-card">
        <h2>10. Menores de Idade</h2>
        <p>A plataforma nao e direcionada a menores de 18 anos. Se voce acreditar que coletamos dados de menores, entre em contato imediatamente.</p>
      </article>

      <article class="a11yhubbr-card">
        <h2>11. Alteracoes nesta Politica</h2>
        <p>Podemos atualizar esta politica periodicamente. Alteracoes significativas serao comunicadas na plataforma.</p>
      </article>

      <article class="a11yhubbr-card">
        <h2>12. Contato</h2>
        <p>Email: <a href="mailto:privacidade@acessibilidadebrasil.org">privacidade@acessibilidadebrasil.org</a><br>
        DPO: <a href="mailto:dpo@acessibilidadebrasil.org">dpo@acessibilidadebrasil.org</a></p>
      </article>
    </div>
  </section>
</main>
<?php get_footer(); ?>