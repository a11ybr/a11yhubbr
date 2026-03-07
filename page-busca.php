<?php
/*
Template Name: Busca
*/
if (!defined('ABSPATH')) {
    exit;
}

$query_text = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
$scope = isset($_GET['tipo']) ? sanitize_key(wp_unslash($_GET['tipo'])) : 'todos';
$order = isset($_GET['ordem']) ? sanitize_key(wp_unslash($_GET['ordem'])) : 'recentes';
$paged = isset($_GET['pg']) ? max(1, absint($_GET['pg'])) : 1;

$valid_scope = array('todos', 'conteudos', 'eventos', 'rede');
if (!in_array($scope, $valid_scope, true)) {
    $scope = 'todos';
}

$valid_order = array('recentes', 'antigos', 'titulo_az', 'titulo_za');
if (!in_array($order, $valid_order, true)) {
    $order = 'recentes';
}

$post_types = array('post', 'a11y_evento', 'a11y_perfil');
if ($scope === 'conteudos') {
    $post_types = array('post');
} elseif ($scope === 'eventos') {
    $post_types = array('a11y_evento');
} elseif ($scope === 'rede') {
    $post_types = array('a11y_perfil');
}

$orderby = 'date';
$order_dir = 'DESC';
if ($order === 'antigos') {
    $order_dir = 'ASC';
} elseif ($order === 'titulo_az') {
    $orderby = 'title';
    $order_dir = 'ASC';
} elseif ($order === 'titulo_za') {
    $orderby = 'title';
    $order_dir = 'DESC';
}

$search_query = null;
if ($query_text !== '') {
    $search_query = new WP_Query(array(
        'post_type' => $post_types,
        'post_status' => 'publish',
        's' => $query_text,
        'orderby' => $orderby,
        'order' => $order_dir,
        'posts_per_page' => 12,
        'paged' => $paged,
    ));
}

$base_url = get_permalink();
$build_url = static function ($args = array()) use ($base_url, $query_text, $scope, $order) {
    $merged = array_merge(array(
        'q' => $query_text,
        'tipo' => $scope,
        'ordem' => $order,
    ), $args);

    if (($merged['q'] ?? '') === '') {
        unset($merged['q']);
    }
    if (($merged['tipo'] ?? 'todos') === 'todos') {
        unset($merged['tipo']);
    }
    if (($merged['ordem'] ?? 'recentes') === 'recentes') {
        unset($merged['ordem']);
    }
    if (isset($merged['pg']) && (int) $merged['pg'] <= 1) {
        unset($merged['pg']);
    }
    return add_query_arg($merged, $base_url);
};

get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-search-page">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Pagina inicial', 'url' => home_url('/')),
      array('label' => 'Busca'),
    ),
    'icon' => 'fa-solid fa-magnifying-glass',
    'title' => 'Buscar no site',
    'summary' => 'Encontre conteudos, eventos e perfis em um unico fluxo de pesquisa.',
    'use_page_context' => false,
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container">
      <form class="a11yhubbr-search-form" method="get" action="<?php echo esc_url($base_url); ?>">
        <label for="a11yhubbr-search-q">Buscar</label>
        <input id="a11yhubbr-search-q" type="search" name="q" value="<?php echo esc_attr($query_text); ?>" placeholder="Digite um termo, ex: WCAG, libras, audiodcricao" required>

        <label for="a11yhubbr-search-tipo">Tipo</label>
        <select id="a11yhubbr-search-tipo" name="tipo">
          <option value="todos"<?php selected($scope, 'todos'); ?>>Todos</option>
          <option value="conteudos"<?php selected($scope, 'conteudos'); ?>>Conteudos</option>
          <option value="eventos"<?php selected($scope, 'eventos'); ?>>Eventos</option>
          <option value="rede"<?php selected($scope, 'rede'); ?>>Rede</option>
        </select>

        <label for="a11yhubbr-search-ordem">Ordenar</label>
        <select id="a11yhubbr-search-ordem" name="ordem">
          <option value="recentes"<?php selected($order, 'recentes'); ?>>Mais recentes</option>
          <option value="antigos"<?php selected($order, 'antigos'); ?>>Mais antigos</option>
          <option value="titulo_az"<?php selected($order, 'titulo_az'); ?>>Titulo A-Z</option>
          <option value="titulo_za"<?php selected($order, 'titulo_za'); ?>>Titulo Z-A</option>
        </select>

        <button class="a11yhubbr-btn" type="submit">Buscar</button>
      </form>

      <?php if ($query_text === ''): ?>
        <?php get_template_part('inc/components/empty-state', null, array(
          'title' => 'Comece sua pesquisa',
          'message' => 'Digite um termo para buscar conteudos, eventos e perfis.',
          'icon' => 'fa-solid fa-magnifying-glass',
        )); ?>
      <?php elseif ($search_query && $search_query->have_posts()): ?>
        <div class="a11yhubbr-search-results-head">
          <h2><?php echo esc_html($search_query->found_posts); ?> resultados para "<?php echo esc_html($query_text); ?>"</h2>
          <a href="<?php echo esc_url($build_url(array('q' => '', 'pg' => 1))); ?>" class="a11yhubbr-content-reset-link">Limpar</a>
        </div>

        <div class="a11yhubbr-content-results-grid">
          <?php while ($search_query->have_posts()): $search_query->the_post(); ?>
            <?php if (get_post_type() === 'post'): ?>
              <?php
              $external_url = (string) get_post_meta(get_the_ID(), '_a11yhubbr_source_link', true);
              $external_url = trim($external_url);
              $author_name = (string) get_post_meta(get_the_ID(), '_a11yhubbr_submitter_name', true);
              if ($author_name === '') {
                  $author_name = get_the_author();
              }
              $term_names = wp_get_post_terms(get_the_ID(), 'category', array('fields' => 'names'));
              $label = !empty($term_names) ? (string) $term_names[0] : 'Conteudo';
              ?>
              <?php get_template_part('inc/components/content-card', null, array(
                'label' => $label,
                'date_iso' => get_the_date('c'),
                'date_text' => get_the_date('d/m/Y'),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'author' => $author_name,
                'external_url' => $external_url,
              )); ?>
            <?php elseif (get_post_type() === 'a11y_evento'): ?>
              <?php
              $modality = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_modality', true);
              $event_link = trim((string) get_post_meta(get_the_ID(), '_a11yhubbr_event_link', true));
              $location = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_location', true);
              $organizer = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_organizer', true);
              ?>
              <?php get_template_part('inc/components/event-card', null, array(
                'label' => $modality !== '' ? $modality : 'Evento',
                'title' => get_the_title(),
                'external_url' => $event_link,
                'date_text' => get_the_date('d/m/Y'),
                'time_text' => '',
                'location' => $location,
                'excerpt' => get_the_excerpt(),
                'organizer' => $organizer,
              )); ?>
            <?php else: ?>
              <?php get_template_part('inc/components/profile-card', null, array(
                'post_id' => get_the_ID(),
                'badge_label' => (string) get_post_meta(get_the_ID(), '_a11yhubbr_profile_type', true),
                'details_url' => get_permalink(),
                'show_details_link' => true,
                'details_label' => 'Ver detalhes',
                'show_external_link' => true,
                'external_label' => 'Site',
                'show_social' => true,
              )); ?>
            <?php endif; ?>
          <?php endwhile; ?>
        </div>

        <?php
        $pagination_base = $build_url(array('pg' => '%#%'));
        $pagination_base = str_replace(array('%2523', '%23'), '%#%', $pagination_base);
        $pagination = paginate_links(array(
          'base' => $pagination_base,
          'format' => '',
          'current' => $paged,
          'total' => max(1, (int) $search_query->max_num_pages),
          'type' => 'array',
          'prev_text' => '&lsaquo; Anterior',
          'next_text' => 'Proxima &rsaquo;',
        ));
        ?>
        <?php if (!empty($pagination)): ?>
          <nav class="a11yhubbr-content-pagination" aria-label="Paginacao da busca">
            <?php foreach ($pagination as $page_link): ?>
              <?php echo wp_kses_post($page_link); ?>
            <?php endforeach; ?>
          </nav>
        <?php endif; ?>
      <?php else: ?>
        <?php get_template_part('inc/components/empty-state', null, array(
          'title' => 'Nenhum resultado encontrado',
          'message' => 'Tente outro termo de busca ou ajuste o filtro de tipo.',
          'icon' => 'fa-regular fa-folder-open',
        )); ?>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>

