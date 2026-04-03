<?php
/*
Template Name: Eventos
*/
if (!defined('ABSPATH')) {
  exit;
}

$event_modalities = array(
  'presencial' => array(
    'label' => 'Presencial',
    'icon' => 'fa-solid fa-location-dot',
    'aliases' => array('Presencial'),
  ),
  'hibrido' => array(
    'label' => 'Híbrido',
    'icon' => 'fa-solid fa-code-merge',
    'aliases' => array('Híbrido', 'Hibrido', 'Híbrido', 'Híbrido'),
  ),
  'online' => array(
    'label' => 'Online',
    'icon' => 'fa-solid fa-video',
    'aliases' => array('Online'),
  ),
);

$slug_from_value = static function ($value) use ($event_modalities) {
  $normalized = sanitize_title((string) $value);
  foreach ($event_modalities as $slug => $item) {
    if ($normalized === sanitize_title($slug) || $normalized === sanitize_title($item['label'])) {
      return $slug;
    }
    foreach ($item['aliases'] as $alias) {
      if ($normalized === sanitize_title($alias)) {
        return $slug;
      }
    }
  }
  return '';
};

$meta_values_by_slug = array();
foreach ($event_modalities as $slug => $item) {
  $values = array($item['label'], $slug);
  foreach ($item['aliases'] as $alias) {
    $values[] = $alias;
  }
  $meta_values_by_slug[$slug] = array_values(array_unique($values));
}

$raw_type = isset($_GET['tipo']) ? sanitize_text_field(wp_unslash($_GET['tipo'])) : '';
$selected_type = $raw_type !== '' ? $slug_from_value($raw_type) : '';

$allowed_sort = array('recentes', 'antigos', 'titulo_az', 'titulo_za');
$sort = isset($_GET['ordem']) ? sanitize_key(wp_unslash($_GET['ordem'])) : 'recentes';
if (!in_array($sort, $allowed_sort, true)) {
  $sort = 'recentes';
}

$allowed_per_page = array(8, 12, 24);
$per_page = isset($_GET['itens']) ? absint($_GET['itens']) : 8;
if (!in_array($per_page, $allowed_per_page, true)) {
  $per_page = 8;
}

$search_term = isset($_GET['busca']) ? sanitize_text_field(wp_unslash($_GET['busca'])) : '';

$paged = isset($_GET['pg']) ? absint($_GET['pg']) : 1;
if ($paged < 1) {
  $paged = 1;
}

$order = 'DESC';
$orderby = 'date';
if ($sort === 'antigos') {
  $order = 'ASC';
} elseif ($sort === 'titulo_az') {
  $order = 'ASC';
  $orderby = 'title';
} elseif ($sort === 'titulo_za') {
  $order = 'DESC';
  $orderby = 'title';
}

$query_args = array(
  'post_type' => 'a11y_evento',
  'post_status' => 'publish',
  'paged' => $paged,
  'posts_per_page' => $per_page,
  'orderby' => $orderby,
  'order' => $order,
);

if ($selected_type !== '' && isset($meta_values_by_slug[$selected_type])) {
  $query_args['meta_query'] = array(
    array(
      'key' => '_a11yhubbr_event_modality',
      'value' => $meta_values_by_slug[$selected_type],
      'compare' => 'IN',
    ),
  );
}

if ($search_term !== '') {
  $search_ids = function_exists('a11yhubbr_find_posts_by_term')
    ? a11yhubbr_find_posts_by_term(
      'a11y_evento',
      $search_term,
      array(
        '_a11yhubbr_event_modality',
        '_a11yhubbr_event_type',
        '_a11yhubbr_event_location',
        '_a11yhubbr_event_organizer',
      )
    )
    : array();

  $query_args['post__in'] = !empty($search_ids) ? $search_ids : array(0);
}

$events_query = new WP_Query($query_args);

$type_counts = array();
foreach ($event_modalities as $slug => $item) {
  $count_query = new WP_Query(array(
    'post_type' => 'a11y_evento',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'fields' => 'ids',
    'meta_query' => array(
      array(
        'key' => '_a11yhubbr_event_modality',
        'value' => $meta_values_by_slug[$slug],
        'compare' => 'IN',
      ),
    ),
  ));
  $type_counts[$slug] = (int) $count_query->found_posts;
}

$base_url = get_permalink();
$current_args = array(
  'tipo' => $selected_type,
  'ordem' => $sort,
  'itens' => $per_page,
  'busca' => $search_term,
);

$build_url = static function ($overrides = array ()) use ($base_url, $current_args) {
  $args = array_merge($current_args, $overrides);

  if (isset($args['tipo']) && $args['tipo'] === '') {
    unset($args['tipo']);
  }
  if (isset($args['busca']) && $args['busca'] === '') {
    unset($args['busca']);
  }
  if (isset($args['ordem']) && $args['ordem'] === 'recentes') {
    unset($args['ordem']);
  }
  if (isset($args['itens']) && (int) $args['itens'] === 8) {
    unset($args['itens']);
  }
  if (isset($args['pg']) && is_numeric($args['pg']) && (int) $args['pg'] <= 1) {
    unset($args['pg']);
  }

  return add_query_arg($args, $base_url);
};

$title_suffix = ($selected_type !== '' && isset($event_modalities[$selected_type])) ? ': ' . $event_modalities[$selected_type]['label'] : ' recentes';
$has_active_filters = ($selected_type !== '' || $search_term !== '' || $sort !== 'recentes' || $per_page !== 8);

$format_event_date = static function ($post_id) {
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

$format_event_time = static function ($post_id) {
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

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-site-main a11yhubbr-content-page a11yhubbr-events-page">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Página inicial', 'url' => home_url('/')),
      array('label' => 'Eventos'),
    ),
    'icon' => 'fa-regular fa-calendar',
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-content-heading">Navegue por modalidade</h2>
      <div class="a11yhubbr-content-types-grid a11yhubbr-events-types-grid">
        <?php foreach ($event_modalities as $slug => $item): ?>
          <?php
          $is_active = $selected_type === $slug;
          $item_count = isset($type_counts[$slug]) ? (int) $type_counts[$slug] : 0;
          $count_label = $item_count === 1 ? '1 evento' : $item_count . ' eventos';
          $type_url = $is_active
            ? $build_url(array('tipo' => '', 'pg' => 1))
            : $build_url(array('tipo' => $slug, 'pg' => 1));
          ?>
          <a class="a11yhubbr-content-type-card<?php echo $is_active ? ' is-active' : ''; ?>"
            href="<?php echo esc_url($type_url); ?>"
            aria-current="<?php echo $is_active ? 'true' : 'false'; ?>">
            <span class="a11yhubbr-content-type-icon" aria-hidden="true"><i
                class="<?php echo esc_attr($item['icon']); ?>"></i></span>
            <strong><?php echo esc_html($item['label']); ?></strong>
            <span><?php echo esc_html($count_label); ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <?php get_template_part('inc/components/archive-toolbar', null, array(
        'heading' => 'Eventos' . $title_suffix,
        'base_url' => $base_url,
        'selected_type' => $selected_type,
        'show_type_input' => ($selected_type !== '' && isset($event_modalities[$selected_type])),
        'search_term' => $search_term,
        'clear_search_url' => $build_url(array('busca' => '', 'pg' => 1)),
        'sort_name' => 'ordem',
        'sort_options' => array(
          'recentes' => 'Data (mais recentes)',
          'antigos' => 'Data (mais antigos)',
          'titulo_az' => 'Título A-Z',
          'titulo_za' => 'Título Z-A',
        ),
        'current_sort' => $sort,
        'per_page_name' => 'itens',
        'per_page_options' => $allowed_per_page,
        'current_per_page' => $per_page,
        'per_page_label_suffix' => 'eventos',
        'show_reset' => $has_active_filters,
        'reset_url' => $build_url(array('tipo' => '', 'busca' => '', 'ordem' => 'recentes', 'itens' => 8, 'pg' => 1)),
        'reset_label' => 'Limpar filtros',
      )); ?>

      <?php if ($events_query->have_posts()): ?>
        <div class="a11yhubbr-content-results-grid">
          <?php while ($events_query->have_posts()):
            $events_query->the_post(); ?>
            <?php
            $modality = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_modality', true);
            if ($modality === '') {
              $modality = 'Evento';
            } else {
              $normalized_modality = $slug_from_value($modality);
              if ($normalized_modality !== '' && isset($event_modalities[$normalized_modality])) {
                $modality = (string) $event_modalities[$normalized_modality]['label'];
              }
            }
            $excerpt = get_the_excerpt();
            if ($excerpt === '') {
              $excerpt = wp_trim_words(wp_strip_all_tags(get_the_content(null, false, get_the_ID())), 24);
            }
            $external_url = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_link', true);
            $external_url = trim($external_url);
            $location = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_location', true);
            $organizer = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_organizer', true);
            $tag_names = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
            if (!is_array($tag_names)) {
              $tag_names = array();
            }
            ?>
            <?php get_template_part('inc/components/event-card', null, array(
              'label' => $modality,
              'title_url' => get_permalink(),
              'date_text' => $format_event_date(get_the_ID()),
              'time_text' => $format_event_time(get_the_ID()),
              'title' => get_the_title(),
              'location' => $location,
              'excerpt' => $excerpt,
              'organizer' => $organizer,
              'external_url' => $external_url,
              'tags' => $tag_names,
            )); ?>
          <?php endwhile; ?>
        </div>

        <?php
        $pagination_base = $build_url(array('pg' => '%#%'));
        $pagination_base = str_replace(array('%2523', '%23'), '%#%', $pagination_base);

        $pagination = paginate_links(array(
          'base' => $pagination_base,
          'format' => '',
          'current' => $paged,
          'total' => max(1, (int) $events_query->max_num_pages),
          'type' => 'array',
          'prev_text' => '&lsaquo; Anterior',
          'next_text' => 'Próxima &rsaquo;',
        ));
        ?>

        <?php if (!empty($pagination)): ?>
          <nav class="a11yhubbr-content-pagination" aria-label="Paginação de eventos">
            <?php foreach ($pagination as $page_link): ?>
              <?php echo wp_kses_post($page_link); ?>
            <?php endforeach; ?>
          </nav>
        <?php endif; ?>
      <?php else: ?>
        <?php get_template_part('inc/components/empty-state', null, array(
          'title' => 'Nenhum evento encontrado',
          'message' => 'N?o encontramos resultados para os filtros selecionados.',
          'cta_label' => 'Submeter evento',
          'cta_url' => function_exists('a11yhubbr_get_submit_event_url') ? a11yhubbr_get_submit_event_url() : home_url('/submeter/submeter-eventos'),
          'cta_class' => 'a11yhubbr-btn-context',
          'icon' => 'fa-regular fa-calendar',
        )); ?>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>




