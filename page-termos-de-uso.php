<?php
/*
Template Name: Termos de Uso
*/
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-legal-page">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Pagina inicial', 'url' => home_url('/')),
      array('label' => 'Termos de Uso'),
    ),
    'icon' => 'fa-regular fa-file-lines',
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container a11yhubbr-legal-stack">
      <article class="a11yhubbr-card">
        <p><strong>Ultima atualizacao:</strong> Janeiro de 2025</p>
      </article>

      <article class="a11yhubbr-card"><h2>1. Aceitacao dos Termos</h2><p>Ao acessar e utilizar a plataforma <strong>A11YBR</strong>, voce concorda com estes Termos de Uso. Se nao concordar com qualquer parte, nao utilize a plataforma.</p></article>
      <article class="a11yhubbr-card"><h2>2. Descricao do Servico</h2><p>A A11YBR e um diretorio colaborativo que documenta e conecta iniciativas de acessibilidade digital no Brasil.</p><ul><li>Agrega informacoes sobre iniciativas de acessibilidade</li><li>Facilita descoberta e conexao entre pessoas e organizacoes</li><li>Promove compartilhamento de conhecimento sobre acessibilidade digital</li></ul><p><strong>Importante:</strong> a plataforma nao valida, certifica ou garante a qualidade das iniciativas listadas. As informacoes sao fornecidas "como estao".</p></article>
      <article class="a11yhubbr-card"><h2>3. Cadastro de Iniciativas</h2><p>Ao cadastrar uma iniciativa, voce declara que:</p><ul><li>As informacoes fornecidas sao verdadeiras e precisas</li><li>Voce tem autorizacao para divulgar a iniciativa</li><li>O conteudo nao viola direitos de terceiros</li><li>A iniciativa esta em conformidade com as leis brasileiras</li></ul></article>
      <article class="a11yhubbr-card"><h2>4. Propriedade Intelectual</h2><p>O codigo-fonte da plataforma e disponibilizado como software de codigo aberto. O conteudo cadastrado permanece de propriedade de seus autores ou organizacoes.</p></article>
      <article class="a11yhubbr-card"><h2>5. Conduta do Usuario</h2><ul><li>Fornecer informacoes falsas ou enganosas</li><li>Violar direitos de propriedade intelectual de terceiros</li><li>Publicar conteudo discriminatorio, ofensivo ou ilegal</li><li>Tentar acessar areas restritas ou interferir no funcionamento da plataforma</li><li>Usar a plataforma para spam ou publicidade nao autorizada</li><li>Coletar dados de outros usuarios sem consentimento</li></ul></article>
      <article class="a11yhubbr-card"><h2>6. Limitacao de Responsabilidade</h2><p>A plataforma e fornecida "como esta", sem garantias de qualquer tipo.</p></article>
      <article class="a11yhubbr-card"><h2>7. Links Externos</h2><p>A plataforma contem links para sites de terceiros e nao temos controle sobre esses conteudos.</p></article>
      <article class="a11yhubbr-card"><h2>8. Modificacoes</h2><p>Podemos modificar estes Termos a qualquer momento. O uso continuado apos alteracoes constitui aceitacao dos novos termos.</p></article>
      <article class="a11yhubbr-card"><h2>9. Lei Aplicavel</h2><p>Estes Termos sao regidos pelas leis da Republica Federativa do Brasil, foro da comarca de Sao Paulo, SP.</p></article>
      <article class="a11yhubbr-card"><h2>10. Contato</h2><p>Para duvidas sobre estes Termos de Uso: <a href="mailto:contato@acessibilidadebrasil.org">contato@acessibilidadebrasil.org</a></p></article>
    </div>
  </section>
</main>
<?php get_footer(); ?>