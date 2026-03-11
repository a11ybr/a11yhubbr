<?php
/*
Template Name: Busca
*/
if (!defined('ABSPATH')) {
    exit;
}

$query_text = isset($_GET['busca']) ? sanitize_text_field(wp_unslash($_GET['busca'])) : '';
if ($query_text === '' && isset($_GET['q'])) {
    $query_text = sanitize_text_field(wp_unslash($_GET['q']));
}

$scope = isset($_GET['tipo']) ? sanitize_key(wp_unslash($_GET['tipo'])) : '';
$order = isset($_GET['ordem']) ? sanitize_key(wp_unslash($_GET['ordem'])) : 'recentes';
$per_page = isset($_GET['itens']) ? absint($_GET['itens']) : 12;
$paged = isset($_GET['pg']) ? max(1, absint($_GET['pg'])) : 1;

$valid_scope = array('', 'conteudos', 'eventos', 'rede', 'todos');
if (!in_array($scope, $valid_scope, true)) {
    $scope = '';
}
if ($scope === 'todos') {
    $scope = '';
}

$valid_order = array('recentes', 'antigos', 'titulo_az', 'titulo_za');
if (!in_array($order, $valid_order, true)) {
    $order = 'recentes';
}

$allowed_per_page = array(8, 12, 24);
if (!in_array($per_page, $allowed_per_page, true)) {
    $per_page = 12;
}

$content_type_map = function_exists('a11yhubbr_get_content_type_map') ? a11yhubbr_get_content_type_map() : array();

$post_types = array('a11y_conteudo', 'a11y_evento', 'a11y_perfil');
if ($scope === 'conteudos') {
    $post_types = array('a11y_conteudo');
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
        'posts_per_page' => $per_page,
        'paged' => $paged,
    ));
}

$count_by_scope = static function ($scope_key, $term) {
    $types = array('a11y_conteudo', 'a11y_evento', 'a11y_perfil');
    if ($scope_key === 'conteudos') {
        $types = array('a11y_conteudo');
    } elseif ($scope_key === 'eventos') {
        $types = array('a11y_evento');
    } elseif ($scope_key === 'rede') {
        $types = array('a11y_perfil');
    }

    if ($term === '') {
        $total = 0;
        foreach ($types as $type) {
            $counts = wp_count_posts($type);
            $total += (int) (($counts && isset($counts->publish)) ? $counts->publish : 0);
        }
        return $total;
    }

    $q = new WP_Query(array(
        'post_type' => $types,
        'post_status' => 'publish',
        's' => $term,
        'posts_per_page' => 1,
        'fields' => 'ids',
    ));

    return (int) $q->found_posts;
};

$scope_cards = array(
    'conteudos' => array('label' => 'Conteúdos', 'icon' => 'fa-regular fa-file-lines'),
    'eventos' => array('label' => 'Eventos', 'icon' => 'fa-regular fa-calendar'),
    'rede' => array('label' => 'Rede', 'icon' => 'fa-solid fa-circle-nodes'),
);

$scope_counts = array();
foreach (array_keys($scope_cards) as $scope_key) {
    $scope_counts[$scope_key] = $count_by_scope($scope_key, $query_text);
}

$base_url = get_permalink();
$build_url = static function ($args = array()) use ($base_url, $query_text, $scope, $order, $per_page) {
    $merged = array_merge(array(
        'busca' => $query_text,
        'tipo' => $scope,
        'ordem' => $order,
        'itens' => $per_page,
    ), $args);

    if (($merged['busca'] ?? '') === '') {
        unset($merged['busca']);
    }
    if (($merged['tipo'] ?? '') === '' || ($merged['tipo'] ?? '') === 'todos') {
        unset($merged['tipo']);
    }
    if (($merged['ordem'] ?? 'recentes') === 'recentes') {
        unset($merged['ordem']);
    }
    if (($merged['itens'] ?? 12) === 12) {
        unset($merged['itens']);
    }
    if (isset($merged['pg']) && (int) $merged['pg'] <= 1) {
        unset($merged['pg']);
    }

    return add_query_arg($merged, $base_url);
};

$heading_suffix = $query_text !== '' ? ' para "' . $query_text . '"' : '';
$result_count = ($search_query instanceof WP_Query) ? (int) $search_query->found_posts : 0;
$has_active_filters = ($query_text !== '' || $scope !== '' || $order !== 'recentes' || $per_page !== 12);

get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-search-page a11yhubbr-content-page">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Página inicial', 'url' => home_url('/')),
      array('label' => 'Busca'),
    ),
    'icon' => 'fa-solid fa-magnifying-glass',
    'title' => 'Buscar no site',
    'summary' => 'Encontre conteúdos, eventos e perfis em um único fluxo de pesquisa.',
    'use_page_context' => false,
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-content-heading">Filtrar por tipo</h2>
      <div class="a11yhubbr-content-types-grid a11yhubbr-search-types-grid">
        <?php foreach ($scope_cards as $scope_key => $item): ?>
          <?php
          $is_active = ($scope === $scope_key);
          $count = (int) ($scope_counts[$scope_key] ?? 0);
          $count_label = $count === 1 ? '1 resultado' : $count . ' resultados';
          $type_url = $is_active
            ? $build_url(array('tipo' => '', 'pg' => 1))
            : $build_url(array('tipo' => $scope_key, 'pg' => 1));
          ?>
          <a class="a11yhubbr-content-type-card<?php echo $is_active ? ' is-active' : ''; ?>" href="<?php echo esc_url($type_url); ?>" aria-current="<?php echo $is_active ? 'true' : 'false'; ?>">
            <span class="a11yhubbr-content-type-icon" aria-hidden="true"><i class="<?php echo esc_attr($item['icon']); ?>"></i></span>
            <strong><?php echo esc_html($item['label']); ?></strong>
            <span><?php echo esc_html($count_label); ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <?php get_template_part('inc/components/archive-toolbar', null, array(
        'heading' => 'Resultados de busca' . $heading_suffix,
        'base_url' => $base_url,
        'selected_type' => $scope,
        'show_type_input' => ($scope !== ''),
        'search_term' => $query_text,
        'clear_search_url' => $build_url(array('busca' => '', 'pg' => 1)),
        'sort_name' => 'ordem',
        'sort_options' => array(
          'recentes' => 'Mais recentes',
          'antigos' => 'Mais antigos',
          'titulo_az' => 'Título A-Z',
          'titulo_za' => 'Título Z-A',
        ),
        'current_sort' => $order,
        'per_page_name' => 'itens',
        'per_page_options' => $allowed_per_page,
        'current_per_page' => $per_page,
        'per_page_label_suffix' => 'itens',
        'show_reset' => $has_active_filters,
        'reset_url' => $build_url(array('busca' => '', 'tipo' => '', 'ordem' => 'recentes', 'itens' => 12, 'pg' => 1)),
        'reset_label' => 'Limpar filtros',
      )); ?>

      <?php if ($query_text === ''): ?>
        <?php get_template_part('inc/components/empty-state', null, array(
          'title' => 'Comece sua pesquisa',
          'message' => 'Digite um termo para buscar conteúdos, eventos e perfis.',
          'icon' => 'fa-solid fa-magnifying-glass',
        )); ?>
      <?php elseif ($search_query && $search_query->have_posts()): ?>
        <p class="a11yhubbr-search-result-summary"><?php echo esc_html($result_count); ?> resultados encontrados.</p>

        <div class="a11yhubbr-content-results-grid">
          <?php while ($search_query->have_posts()): $search_query->the_post(); ?>
            <?php if (get_post_type() === 'a11y_conteudo'): ?>
              <?php
              $author_name = (string) get_post_meta(get_the_ID(), '_a11yhubbr_submitter_name', true);
              if ($author_name === '') {
                  $author_name = get_the_author();
              }
              $terms = wp_get_post_terms(get_the_ID(), 'category');
              $label = 'Conteúdo';
              $badge_icon = 'fa-regular fa-file-lines';
              if (!empty($terms) && !is_wp_error($terms)) {
                $label = (string) $terms[0]->name;
                foreach ($terms as $term) {
                  $slug = (string) $term->slug;
                  if (isset($content_type_map[$slug]['icon'])) {
                    $badge_icon = (string) $content_type_map[$slug]['icon'];
                    break;
                  }
                }
              }
              $tag_names = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
              if (!is_array($tag_names)) {
                $tag_names = array();
              }
              ?>
              <?php get_template_part('inc/components/content-card', null, array(
                'label' => $label,
                'badge_icon' => $badge_icon,
                'date_iso' => get_the_date('c'),
                'date_text' => get_the_date('d/m/Y'),
                'title' => get_the_title(),
                'title_url' => get_permalink(),
                'excerpt' => get_the_excerpt(),
                'author' => $author_name,
                'tags' => $tag_names,
                'action_url' => get_permalink(),
                'action_label' => 'Acessar',
                'badge_class' => 'a11yhubbr-content-item-badge--conteúdos',
              )); ?>
            <?php elseif (get_post_type() === 'a11y_evento'): ?>
              <?php
              $modality = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_modality', true);
              $event_link = trim((string) get_post_meta(get_the_ID(), '_a11yhubbr_event_link', true));
              $location = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_location', true);
              $organizer = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_organizer', true);
              $event_tags = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
              if (!is_array($event_tags)) {
                $event_tags = array();
              }
              ?>
              <?php get_template_part('inc/components/event-card', null, array(
                'label' => $modality !== '' ? $modality : 'Evento',
                'title' => get_the_title(),
                'title_url' => get_permalink(),
                'external_url' => $event_link,
                'date_text' => get_the_date('d/m/Y'),
                'time_text' => '',
                'location' => $location,
                'excerpt' => get_the_excerpt(),
                'organizer' => $organizer,
                'badge_class' => 'a11yhubbr-content-item-badge--eventos',
                'tags' => $event_tags,
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
                'badge_class' => 'a11yhubbr-content-item-badge--rede',
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
          'next_text' => 'Próxima &rsaquo;',
        ));
        ?>
        <?php if (!empty($pagination)): ?>
          <nav class="a11yhubbr-content-pagination" aria-label="Paginação da busca">
            <?php foreach ($pagination as $page_link): ?>
              <?php echo wp_kses_post($page_link); ?>
            <?php endforeach; ?>
          </nav>
        <?php endif; ?>
      <?php else: ?>
        <?php get_template_part('inc/components/empty-state', null, array(
          'title' => 'Nenhum resultado encontrado',
          'message' => 'Tente outro termo de busca ou ajuste os filtros.',
          'icon' => 'fa-regular fa-folder-open',
        )); ?>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
