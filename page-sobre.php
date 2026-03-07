<?php
/*
Template Name: Sobre
*/
if (!defined('ABSPATH')) {
  exit;
}

$team_query = new WP_Query(array(
  'post_type' => 'a11y_perfil',
  'post_status' => 'publish',
  'posts_per_page' => 8,
  'tax_query' => array(
    array(
      'taxonomy' => 'category',
      'field' => 'slug',
      'terms' => array('equipe'),
    ),
  ),
));

if (!$team_query->have_posts()) {
  wp_reset_postdata();
  $team_query = new WP_Query(array(
    'post_type' => 'a11y_perfil',
    'post_status' => 'publish',
    'posts_per_page' => 8,
    'meta_query' => array(
      array(
        'key' => '_a11yhubbr_profile_type',
        'value' => array('Equipe', 'equipe'),
        'compare' => 'IN',
      ),
    ),
  ));
}

$faq_groups = array(
  'Sobre a plataforma' => array(
    array(
      'q' => 'O que é a A11YBR?',
      'a' => 'A A11YBR é um diretório colaborativo que reúne e documenta iniciativas de acessibilidade digital no Brasil. Nossa missão é conectar pessoas, organizações e projetos que trabalham para tornar a web mais inclusiva.',
    ),
    array(
      'q' => 'A plataforma certifica ou valida iniciativas?',
      'a' => 'Não. A plataforma é um diretório de documentação e conexão. Não realizamos auditorias, certificações ou validações de conformidade com normas de acessibilidade. Recomendamos que você sempre verifique diretamente com cada iniciativa suas credenciais e qualificações.',
    ),
    array(
      'q' => 'Quem mantém a plataforma?',
      'a' => 'A plataforma é mantida pela comunidade brasileira de acessibilidade digital, com contribuições voluntárias de profissionais, pesquisadores e entusiastas da área. É um projeto de código aberto e sem fins lucrativos.',
    ),
    array(
      'q' => 'Como posso apoiar o projeto?',
      'a' => 'Você pode apoiar de várias formas: cadastrando iniciativas, sugerindo melhorias, reportando problemas, contribuindo com código no GitHub, seguindo os outros braços da plataforma nas diversas redes sociais ou simplesmente divulgando a plataforma nas suas redes.',
    ),
  ),
  'Cadastro de Iniciativas' => array(
    array(
      'q' => 'Quais tipos de iniciativas podem ser cadastradas?',
      'a' => 'Aceitamos ferramentas, bibliotecas, cursos, comunidades, consultorias, eventos, podcasts, blogs, canais e qualquer outro recurso relacionado à acessibilidade digital. A iniciativa deve ter presença online verificável e estar em atividade.',
    ),
    array(
      'q' => 'Quanto tempo leva para minha iniciativa ser aprovada?',
      'a' => 'O processo de moderação geralmente leva de 3 a 7 dias úteis. Você receberá uma notificação por email quando sua submissão for revisada, seja aprovada ou com solicitação de ajustes.',
    ),
    array(
      'q' => 'Posso editar minha iniciativa após o cadastro?',
      'a' => 'Sim! Você pode sugerir edições a qualquer momento através do formulário de contato ou enviando mensagem para nosso email. A equipe de moderação revisará as alterações propostas.',
    ),
    array(
      'q' => 'Por que minha iniciativa foi recusada?',
      'a' => 'Os motivos mais comuns incluem: link inválido ou inacessível, conteúdo não relacionado a acessibilidade digital, informações insuficientes, ou violação das diretrizes da comunidade. Você sempre receberá feedback sobre o motivo da recusa.',
    ),
  ),
  'Navegação e Uso' => array(
    array(
      'q' => 'Como posso buscar iniciativas específicas?',
      'a' => 'Você pode explorar por categorias, usar a busca por palavras-chave, ou filtrar por tipo de iniciativa (ferramenta, curso, comunidade, etc.). Também é possível navegar pelas páginas de pessoas e organizações para descobrir suas iniciativas associadas.',
    ),
    array(
      'q' => 'A plataforma é acessível?',
      'a' => 'Sim! Seguimos as diretrizes WCAG 2.1 nível AA e testamos regularmente com leitores de tela e outras tecnologias assistivas. Se encontrar qualquer barreira de acessibilidade, por favor nos avise.',
    ),
    array(
      'q' => 'Posso usar a plataforma sem criar uma conta?',
      'a' => 'Sim! Toda a navegação e consulta de iniciativas é livre e não requer cadastro. Apenas o envio de novas iniciativas pode requerer identificação para fins de moderação.',
    ),
  ),
  'Comunidade' => array(
    array(
      'q' => 'Como posso entrar em contato com a equipe?',
      'a' => 'Você pode nos contatar pelo email a11yhubbr@gmail.com, pelas redes sociais, manifestando sua vontade de colaborar.',
    ),
    array(
      'q' => 'Qual é o código de conduta da comunidade?',
      'a' => 'Sim! Nossa comunidade segue um código de conduta que preza pelo respeito, inclusão e colaboração. Comportamentos discriminatórios, ofensivos ou desrespeitosos não são tolerados.',
    ),
    array(
      'q' => 'Posso contribuir com o código da plataforma?',
      'a' => 'Claro! O projeto é open source e aceitamos contribuições. Visite nosso repositório no <a href="http://github.com/a11ybr/a11yhubbr">GitHub</a> para ver as issues abertas, propor melhorias ou enviar pull requests.',
    ),

  ),

);
$accepted = array(
  'Um diretório colaborativo e aberto',
  'Uma memória coletiva de iniciativas',
  'Um espaéo para descoberta e conexão',
  'Construédo pela comunidade',
  'Gratuito e acessível',
);

$not_accepted = array(
  'Um sistema de certificação',
  'Um ranking competitivo',
  'Uma entidade validadora',
  'Um espaéo publicitário',
  'Uma plataforma comercial fechada',
);



$value_cards = array(
  array('icon' => 'fa-solid fa-users', 'title' => 'Colaboração', 'text' => 'O conteúdo evolui com contribuições da comunidade.'),
  array('icon' => 'fa-regular fa-eye', 'title' => 'Visibilidade', 'text' => 'Amplificamos iniciativas e pessoas de diferentes regiões.'),
  array('icon' => 'fa-regular fa-bookmark', 'title' => 'Conhecimento aberto', 'text' => 'Recursos gratuitos para fortalecer práticas acesséveis.'),
  array('icon' => 'fa-regular fa-heart', 'title' => 'Comunidade', 'text' => 'Rede de apoio para troca conténua entre profissionais.'),
);

get_header();
?>
<main class="a11yhubbr-site-main">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Página inicial', 'url' => home_url('/')),
      array('label' => 'Sobre'),
    ),
    'icon' => 'fa-solid fa-universal-access',
  ));
  ?>

  <section class="a11yhubbr-about-section">
    <div class="a11yhubbr-container">
      <article>
        <h2>Nossa visão</h2>
        <p>O <strong>A11YBR</strong> existe para documentar, organizar e amplificar o trabalho de quem constrói um
          Brasil digital mais inclusivo. Não somos um sistema de certificação ou ranking, somos um diretório vivo, uma
          memória coletiva das iniciativas de acessibilidade no país.</p>
        <p>Acreditamos que a visibilidade é o primeiro passo para a conexão. Ao reunir iniciativas de diferentes
          formatos, regiões e abordagens, criamos oportunidades para que pessoas encontrem recursos, colaborem entre si
          e fortaleéam a rede de acessibilidade digital brasileira.</p>
      </article>
    </div>
  </section>

<section class="a11yhubbr-section">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-section-title">O que somos e o que não somos</h2>
      <div class="a11yhubbr-cards-grid">
        <article class="a11yhubbr-guideline-list-card is-accepted">
          <h3><i class="fa-regular fa-circle-check"></i> O que somos</h3>
          <ul>
            <?php foreach ($accepted as $item): ?><li><?php echo esc_html($item); ?></li><?php endforeach; ?>
          </ul>
        </article>

        <article class="a11yhubbr-guideline-list-card is-rejected">
          <h3><i class="fa-regular fa-circle-xmark"></i> O que não somos</h3>
          <ul>
            <?php foreach ($not_accepted as $item): ?><li><?php echo esc_html($item); ?></li><?php endforeach; ?>
          </ul>
        </article>
      </div>
    </div>
  </section>

  <section class="a11yhubbr-about-section">
    <div class="a11yhubbr-container">
      <h2>Nossos valores</h2>
      <div class="a11yhubbr-about-values-grid">
        <?php foreach ($value_cards as $card): ?>
          <?php get_template_part('inc/components/feature-card', null, array(
            'icon' => $card['icon'],
            'title' => $card['title'],
            'text' => $card['text'],
          )); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php get_template_part('inc/sections/como-funciona'); ?>

  <section class="a11yhubbr-about-section">
    <div class="a11yhubbr-container">
      <p class="a11yhubbr-home-v2-kicker">Equipe</p>
      <h2>Quem mantém a A11YBR</h2>
      <div class="a11yhubbr-about-team-grid">
        <?php if ($team_query->have_posts()): ?>
          <?php while ($team_query->have_posts()):
            $team_query->the_post(); ?>
            <?php get_template_part('inc/components/profile-card', null, array(
              'post_id' => get_the_ID(),
              'badge_label' => 'Equipe',
              'details_url' => get_permalink(),
              'show_details_link' => true,
              'details_label' => 'Ver detalhes',
              'show_external_link' => true,
              'external_label' => 'Site',
              'show_social' => true,
            )); ?>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        <?php else: ?>
          <?php get_template_part('inc/components/empty-state', null, array(
            'title' => 'Nenhum perfil da equipe encontrado',
            'message' => 'Cadastre perfis e marque a categoria equipe para exibir nesta se??o.',
            'cta_label' => 'Submeter perfil',
            'cta_url' => function_exists('a11yhubbr_get_submit_profile_url') ? a11yhubbr_get_submit_profile_url() : home_url('/submeter/submeter-perfil'),
            'icon' => 'fa-solid fa-user-group',
          )); ?>
        <?php endif; ?>
      </div>

      <aside class="a11yhubbr-about-open-source">
        <div class="a11yhubbr-about-open-source-head">
          <span class="a11yhubbr-about-open-source-icon" aria-hidden="true"><i class="fa-brands fa-github"></i></span>
          <h3>Open Source</h3>
        </div>
        <p>O código da <strong>A11YBR</strong> é open source. Contribua com o desenvolvimento da plataforma.</p>
        <a class="a11yhubbr-about-open-source-link" href="https://github.com/a11ybr/a11yhubbr" target="_blank"
          rel="noopener noreferrer">
          Acessar repositório no GitHub
          <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
        </a>
      </aside>
    </div>
  </section>

  <section class="a11yhubbr-about-section ">
    <div class="a11yhubbr-container text-center">
      <div class="a11yhubbr-about-faq">
        <h2 id="faq">Perguntas frequentes</h2>
        <?php foreach ($faq_groups as $group_title => $items): ?>
          <div class="a11yhubbr-about-faq-group">
            <h3><?php echo esc_html($group_title); ?></h3>
            <?php foreach ($items as $idx => $item): ?>
              <details<?php echo $idx === 0 ? ' open' : ''; ?>>
                <summary><?php echo esc_html($item['q']); ?></summary>
                <p><?php echo esc_html($item['a']); ?></p>
                </details>
              <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php include locate_template('inc/sections/faca-parte.php'); ?>
</main>
<?php get_footer(); ?>