<?php
get_header();

$counts = wp_count_posts('post');
$count_post = (int) ($counts && isset($counts->publish) ? $counts->publish : 0);

$counts = wp_count_posts('a11y_evento');
$count_event = (int) ($counts && isset($counts->publish) ? $counts->publish : 0);

$counts = wp_count_posts('a11y_perfil');
$count_profile = (int) ($counts && isset($counts->publish) ? $counts->publish : 0);

$total_collaborations = $count_post + $count_event + $count_profile;
?>
<main class="a11yhubbr-site-main">
  <section class="a11yhubbr-home-v2-hero">
    <div class="a11yhubbr-container">
      <div class="a11yhubbr-home-v2-hero-content">
        <div class="a11yhubbr-home-v2-kicker-row">
          <p class="a11yhubbr-home-v2-kicker" aria-label="<?php echo esc_attr(number_format_i18n($total_collaborations)); ?> colaborações publicadas">
            <?php echo esc_html(number_format_i18n($total_collaborations)); ?> colaborações
          </p>
        </div>
        <h1><mark class="a11yhubbr-hero-mark">Acessibilidade</mark> digital em português, <mark class="a11yhubbr-hero-mark">pela comunidade</mark></h1>
        <p>Um diretório colaborativo que documenta, organiza e amplifica o trabalho de quem constrói um Brasil digital mais inclusivo.</p>
        <div class="a11yhubbr-actions">
          <a class="a11yhubbr-btn a11yhubbr-btn-light" href="<?php echo esc_url(home_url('/conteudos')); ?>">Explorar conteúdos<i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
          <a class="a11yhubbr-btn a11yhubbr-btn-outline" href="<?php echo esc_url(function_exists('a11yhubbr_get_submit_content_url') ? a11yhubbr_get_submit_content_url() : home_url('/submeter/submeter-conteudo')); ?>">Submeter conteúdo</a>
        </div>
      </div>
    </div>
  </section>

  <section class="a11yhubbr-home-v2-section a11yhubbr-home-v2-categories">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-home-v2-title">Categorias de conteúdo</h2>
      <p class="a11yhubbr-home-v2-subtitle">Explore recursos organizados por tipo para facilitar sua busca por conhecimento em acessibilidade.</p>

      <?php
      $content_categories = array(
        array('title' => 'Artigos', 'desc' => 'conteúdos escritos com anélise, opiniéo ou estudo de caso', 'icon' => 'fa-regular fa-file-lines', 'tipo' => 'artigos'),
        array('title' => 'Comunidades', 'desc' => 'espaéos de networking e troca sobre acessibilidade', 'icon' => 'fa-solid fa-users', 'tipo' => 'comunidades'),
        array('title' => 'Livros e materiais', 'desc' => 'livros, guias e materiais de referéncia', 'icon' => 'fa-solid fa-book-open', 'tipo' => 'cursos-materiais'),
        array('title' => 'Ferramentas', 'desc' => 'recursos técnicos para auditoria e testes de acessibilidade', 'icon' => 'fa-solid fa-wrench', 'tipo' => 'ferramentas'),
        array('title' => 'Multimédia', 'desc' => 'podcasts e canais de védeo', 'icon' => 'fa-solid fa-headphones', 'tipo' => 'multimidia'),
        array('title' => 'Sites e sistemas', 'desc' => 'produtos digitais com foco em acessibilidade', 'icon' => 'fa-solid fa-desktop', 'tipo' => 'sites-sistemas'),
      );

      $content_category_counts = array();
      foreach ($content_categories as $item) {
        $term = get_term_by('slug', $item['tipo'], 'category');
        $content_category_counts[$item['tipo']] = ($term && !is_wp_error($term)) ? (int) $term->count : 0;
      }
      ?>

      <div class="a11yhubbr-home-v2-grid a11yhubbr-home-v2-grid-3">
        <?php foreach ($content_categories as $item): ?>
          <?php
          $count = isset($content_category_counts[$item['tipo']]) ? (int) $content_category_counts[$item['tipo']] : 0;
          $count_label = $count === 1 ? '1 item' : $count . ' itens';
          ?>
          <a class="a11yhubbr-home-v2-card a11yhubbr-home-v2-card-link"
            href="<?php echo esc_url(add_query_arg('tipo', $item['tipo'], home_url('/conteudos'))); ?>"
            aria-label="Filtrar conteúdos por <?php echo esc_attr($item['title']); ?>">
            <span class="a11yhubbr-home-v2-icon" aria-hidden="true"><i class="<?php echo esc_attr($item['icon']); ?>"></i></span>
            <span class="a11yhubbr-home-v2-card-copy">
              <span class="a11yhubbr-home-v2-card-copy-top">
                <h3><?php echo esc_html($item['title']); ?></h3>
                <p><?php echo esc_html($item['desc']); ?></p>
              </span>
              <span class="a11yhubbr-home-v2-card-count"><?php echo esc_html($count_label); ?></span>
            </span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php
  $home_events_query = new WP_Query(array(
    'post_type' => 'a11y_evento',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'orderby' => 'date',
    'order' => 'DESC',
  ));

  $format_home_event_date = static function ($post_id) {
    $slots_raw = get_post_meta($post_id, '_a11yhubbr_event_slots', true);
    if (!is_string($slots_raw) || $slots_raw === '') {
      return get_the_date('d/m/Y', $post_id);
    }

    $slots = json_decode($slots_raw, true);
    if (!is_array($slots) || empty($slots[0]['start'])) {
      return get_the_date('d/m/Y', $post_id);
    }

    $timestamp = strtotime((string) $slots[0]['start']);
    if ($timestamp === false) {
      return get_the_date('d/m/Y', $post_id);
    }

    return wp_date('d/m/Y', $timestamp);
  };

  $format_home_event_time = static function ($post_id) {
    $slots_raw = get_post_meta($post_id, '_a11yhubbr_event_slots', true);
    if (!is_string($slots_raw) || $slots_raw === '') {
      return '';
    }

    $slots = json_decode($slots_raw, true);
    if (!is_array($slots) || empty($slots[0]['start'])) {
      return '';
    }

    $timestamp = strtotime((string) $slots[0]['start']);
    if ($timestamp === false) {
      return '';
    }

    return wp_date('H:i', $timestamp);
  };
  ?>
  <section class="a11yhubbr-home-v2-section a11yhubbr-home-v2-events">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-home-v2-title">Eventos recentes</h2>
      <p class="a11yhubbr-home-v2-subtitle">Acompanhe workshops, confer?ncias, meetups e webinars da comunidade.</p>

      <?php if ($home_events_query->have_posts()): ?>
        <div class="a11yhubbr-content-results-grid">
          <?php while ($home_events_query->have_posts()): $home_events_query->the_post(); ?>
            <?php
            $modality = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_modality', true);
            if ($modality === '') {
              $modality = 'Evento';
            }
            $excerpt = get_the_excerpt();
            if ($excerpt === '') {
              $excerpt = wp_trim_words(wp_strip_all_tags(get_the_content(null, false, get_the_ID())), 22);
            }
            $external_url = trim((string) get_post_meta(get_the_ID(), '_a11yhubbr_event_link', true));
            $location = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_location', true);
            $organizer = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_organizer', true);
            ?>
            <?php get_template_part('inc/components/event-card', null, array(
              'label' => $modality,
              'title' => get_the_title(),
              'external_url' => $external_url,
              'date_text' => $format_home_event_date(get_the_ID()),
              'time_text' => $format_home_event_time(get_the_ID()),
              'location' => $location,
              'excerpt' => $excerpt,
              'organizer' => $organizer,
            )); ?>
          <?php endwhile; ?>
        </div>

        <div class="a11yhubbr-home-v2-center">
          <a class="a11yhubbr-btn" href="<?php echo esc_url(home_url('/eventos')); ?>">Ver todos os eventos</a>
        </div>
      <?php else: ?>
        <?php get_template_part('inc/components/empty-state', null, array(
          'title' => 'Nenhum evento publicado ainda',
          'message' => 'Quando novos eventos forem aprovados eles v?o aparecer aqui.',
          'cta_label' => 'Submeter evento',
          'cta_url' => function_exists('a11yhubbr_get_submit_event_url') ? a11yhubbr_get_submit_event_url() : home_url('/submeter/submeter-eventos'),
          'icon' => 'fa-regular fa-calendar',
        )); ?>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>
  <section class="a11yhubbr-home-v2-section a11yhubbr-home-v2-section-soft a11yhubbr-home-v2-community">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-home-v2-title">Rede</h2>
      <p class="a11yhubbr-home-v2-subtitle">Rede de profissionais e organizações que atuam com acessibilidade.</p>

      <?php
      $community_categories = array(
        array('title' => 'Profissionais de tecnologia', 'desc' => 'designers, desenvolvedores, QA, product managers', 'icon' => 'fa-solid fa-briefcase', 'tipo' => 'Profissionais de tecnologia'),
        array('title' => 'Empresas e ONGs', 'desc' => 'organizações comprometidas com acessibilidade', 'icon' => 'fa-regular fa-building', 'tipo' => 'Empresas e ONGs'),
        array('title' => 'Intérpretes de Libras', 'desc' => 'profissionais de comunicação em língua de sinais', 'icon' => 'fa-regular fa-hand', 'tipo' => 'Interpretes de Libras'),
        array('title' => 'Audiodescritores', 'desc' => 'especialistas em descrição de conteúdo visual', 'icon' => 'fa-regular fa-eye', 'tipo' => 'Audiodescritores'),
        array('title' => 'Tradutores de Braille', 'desc' => 'profissionais especializados em escrita tátil', 'icon' => 'fa-solid fa-braille', 'tipo' => 'Tradutores de Braille'),
      );

      $community_aliases = array(
        'Profissionais de tecnologia' => array('Profissional de tecnologia', 'Profissionais de tecnologia', 'Profissionais de IT'),
        'Empresas e ONGs' => array('Empresa ou ONG', 'Empresas e ONGs'),
        'Interpretes de Libras' => array('Intérprete de Libras', 'Interprete de Libras', 'Intérpretes de Libras'),
        'Audiodescritores' => array('Audiodescritor', 'Audiodescritores'),
        'Tradutores de Braille' => array('Tradutor de Braille', 'Tradutores de Braille'),
      );

      $community_counts = array();
      foreach ($community_categories as $item) {
        $aliases = isset($community_aliases[$item['tipo']]) ? $community_aliases[$item['tipo']] : array($item['tipo']);
        $count_query = new WP_Query(array(
          'post_type' => 'a11y_perfil',
          'post_status' => 'publish',
          'posts_per_page' => 1,
          'fields' => 'ids',
          'meta_query' => array(
            array(
              'key' => '_a11yhubbr_profile_type',
              'value' => $aliases,
              'compare' => 'IN',
            ),
          ),
        ));
        $community_counts[$item['tipo']] = (int) $count_query->found_posts;
      }
      ?>

      <div class="a11yhubbr-home-v2-grid a11yhubbr-home-v2-grid-3">
        <?php foreach ($community_categories as $item): ?>
          <?php
          $count = isset($community_counts[$item['tipo']]) ? (int) $community_counts[$item['tipo']] : 0;
          $count_label = $count === 1 ? '1 perfil' : $count . ' perfis';
          ?>
          <a class="a11yhubbr-home-v2-card a11yhubbr-home-v2-card-link"
            href="<?php echo esc_url(add_query_arg('tipo', $item['tipo'], home_url('/rede'))); ?>"
            aria-label="Filtrar rede por <?php echo esc_attr($item['title']); ?>">
            <span class="a11yhubbr-home-v2-icon" aria-hidden="true"><i class="<?php echo esc_attr($item['icon']); ?>"></i></span>
            <span class="a11yhubbr-home-v2-card-copy">
              <span class="a11yhubbr-home-v2-card-copy-top">
                <h3><?php echo esc_html($item['title']); ?></h3>
                <p><?php echo esc_html($item['desc']); ?></p>
              </span>
              <span class="a11yhubbr-home-v2-card-count"><?php echo esc_html($count_label); ?></span>
            </span>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="a11yhubbr-home-v2-center">
        <a class="a11yhubbr-btn" href="<?php echo esc_url(home_url('/rede')); ?>">Ver toda a rede</a>
      </div>
    </div>
  </section>

</main>
<?php get_footer(); ?>

