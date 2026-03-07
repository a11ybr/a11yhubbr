<?php
/*
Template Name: Diretrizes da Comunidade
*/
if (!defined('ABSPATH')) {
  exit;
}

$accepted = array(
  'Produtos digitais (sites, apps, sistemas) com foco em acessibilidade;',
  'Podcasts sobre acessibilidade e inclusão;',
  'Canais de YouTube com conteúdo educativo sobre o tema;',
  'Newsletters e publicações digitais;',
  'Livros, apostilas e materiais educativos;',
  'Comunidades e grupos online;',
  'Eventos recorrentes com presenéa digital;',
  'Projetos de pesquisa em acessibilidade;',
  'Ferramentas e bibliotecas de código aberto;',
);

$not_accepted = array(
  'Produtos ou serviéos puramente comerciais sem foco em acessibilidade;',
  'Iniciativas sem presenéa ou componente digital;',
  'Conteúdo que promova discriminação ou exclusão;',
  'Projetos inativos há mais de 2 anos sem previsão de retorno;',
  'Iniciativas com informações que não podem ser verificadas;',
);

$quality = array(
  array('icon' => 'fa-solid fa-circle-check', 'title' => 'Informações precisas', 'text' => 'Todas as informações cadastradas devem ser verificáveis e atualizadas. Links devem estar funcionando e descrições devem refletir fielmente a iniciativa.'),
  array('icon' => 'fa-solid fa-comment-dots', 'title' => 'Descrições claras', 'text' => 'Use linguagem acessível e evite jargões técnicos desnecessários. Descreva objetivamente o que a iniciativa faz e para quem ela é destinada.'),
  array('icon' => 'fa-solid fa-tags', 'title' => 'Categorização correta', 'text' => 'Escolha a categoria e as tags que melhor representam a iniciativa. Uma boa categorização ajuda outros usuérios a encontrarem o conteúdo.'),
  array('icon' => 'fa-solid fa-ban', 'title' => 'Sem autopromoção excessiva', 'text' => 'Descrições devem ser informativas, não publicitárias. O objetivo é documentar, não fazer marketing.'),
);

$rules = array(
  array('icon' => 'fa-regular fa-handshake', 'title' => 'Respeito mútuo', 'text' => 'Trate todos os membros da comunidade com respeito e cordialidade. Divergências são bem-vindas quando expressas de forma construtiva.'),
  array('icon' => 'fa-solid fa-shield-heart', 'title' => 'Ambiente seguro', 'text' => 'Não toleramos assédio, discriminação ou qualquer forma de violéncia. A comunidade deve ser um espaéo seguro para todas as pessoas.'),
  array('icon' => 'fa-solid fa-scale-balanced', 'title' => 'Neutralidade', 'text' => 'A plataforma não endossa, certifica ou ranqueia iniciativas. Nosso papel é documentar e conectar, não julgar ou classificar.'),
  array('icon' => 'fa-regular fa-file-lines', 'title' => 'Transparéncia', 'text' => 'Informações sobre moderação e decisées editoriais são compartilhadas abertamente. Valorizamos a transparência em todos os processos.'),
);

get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-guidelines-page">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Página inicial', 'url' => home_url('/')),
      array('label' => 'Diretrizes da comunidade'),
    ),
    'icon' => 'fa-solid fa-list-check',
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-section-title">O que aceitamos</h2>
      <div class="a11yhubbr-cards-grid">
        <article class="a11yhubbr-guideline-list-card is-accepted">
          <h3><i class="fa-regular fa-circle-check"></i> Iniciativas aceitas</h3>
          <ul>
            <?php foreach ($accepted as $item): ?>
              <li><?php echo esc_html($item); ?></li><?php endforeach; ?>
          </ul>
        </article>

        <article class="a11yhubbr-guideline-list-card is-rejected">
          <h3><i class="fa-regular fa-circle-xmark"></i> O que não aceitamos</h3>
          <ul>
            <?php foreach ($not_accepted as $item): ?>
              <li><?php echo esc_html($item); ?></li><?php endforeach; ?>
          </ul>
        </article>
      </div>
    </div>
  </section>

  <section class="a11yhubbr-section a11yhubbr-section-soft">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-section-title">Padrões de qualidade</h2>
      <div class="a11yhubbr-cards-grid">
        <?php foreach ($quality as $item): ?>
          <article class="a11yhubbr-about-flow-card">
            <span class="a11yhubbr-about-flow-icon" aria-hidden="true">
              <i class="<?php echo esc_attr($item['icon']); ?>"
                aria-hidden="true"></i>
              </span>

            <h3><?php echo esc_html($item['title']); ?></h3>
            <p><?php echo esc_html($item['text']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-section-title">Regras da comunidade</h2>
      <div class="a11yhubbr-cards-grid">
        <?php foreach ($rules as $item): ?>
          <article class="a11yhubbr-about-flow-card">
            <span class="a11yhubbr-about-flow-icon" aria-hidden="true"><i class="<?php echo esc_attr($item['icon']); ?>"
                aria-hidden="true"></i>
              </span>


            <h3> <?php echo esc_html($item['title']); ?></h3>
            <p><?php echo esc_html($item['text']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php get_template_part('inc/sections/como-funciona'); ?>


  <section class="a11yhubbr-section a11yhubbr-section-cta">
    <div class="a11yhubbr-container">
      <?php get_template_part('inc/components/cta-box', null, array(
        'title' => 'Dúvidas sobre as diretrizes?',
        'description' => 'Se tiver dúvidas, entre em contato com a equipe para orientação antes de submeter.',
        'primary' => array(
          'label' => 'Submeter',
          'url' => home_url('/submeter'),
        ),
        'secondary' => array(
          'label' => 'Fale conosco',
          'url' => '/contato',
        ),
      )); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>