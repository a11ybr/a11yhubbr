<?php
/*
Template Name: Conteúdos
*/
if (!defined('ABSPATH')) {
  exit;
}

$types = function_exists('a11yhubbr_get_content_type_map') ? a11yhubbr_get_content_type_map() : array();
$display_types = array_diff_key($types, array('eventos' => true, 'redes' => true, 'comunidades' => true));

$raw_type = isset($_GET['tipo']) ? sanitize_text_field(wp_unslash($_GET['tipo'])) : '';
$selected_type = '';
if ($raw_type !== '' && function_exists('a11yhubbr_get_content_type_slug_from_input')) {
  $selected_type = a11yhubbr_get_content_type_slug_from_input($raw_type);
}

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
  'post_type' => 'a11y_conteudo',
  'post_status' => 'publish',
  'paged' => $paged,
  'posts_per_page' => $per_page,
  'orderby' => $orderby,
  'order' => $order,
);

if ($selected_type !== '' && isset($display_types[$selected_type])) {
  $query_args['tax_query'] = array(
    array(
      'taxonomy' => 'category',
      'field' => 'slug',
      'terms' => array($selected_type),
    ),
  );
} else {
  $query_args['tax_query'] = array(
    array(
      'taxonomy' => 'category',
      'field' => 'slug',
      'terms' => array('eventos', 'redes', 'comunidades'),
      'operator' => 'NOT IN',
    ),
  );
}

if ($search_term !== '') {
  $query_args['s'] = $search_term;
}

$content_query = new WP_Query($query_args);

$type_counts = array();
$type_labels = array();
foreach ($display_types as $slug => $type) {
  $term = get_term_by('slug', $slug, 'category');
  $type_counts[$slug] = ($term && !is_wp_error($term)) ? (int) $term->count : 0;
  $type_labels[$slug] = ($term && !is_wp_error($term)) ? (string) $term->name : (string) $type['label'];
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

$title_suffix = ($selected_type !== '' && isset($display_types[$selected_type])) ? ': ' . ($type_labels[$selected_type] ?? $display_types[$selected_type]['label']) : ' recentes';
$has_active_filters = ($selected_type !== '' || $search_term !== '' || $sort !== 'recentes' || $per_page !== 8);
get_header();
?>
<main class="a11yhubbr-site-main a11yhubbr-content-page">
  <?php
  a11yhubbr_render_page_header(array(
    'breadcrumbs' => array(
      array('label' => 'Página inicial', 'url' => home_url('/')),
      array('label' => 'Conteúdos'),
    ),
    'icon' => 'fa-regular fa-file-lines',
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container">
      <h2 class="a11yhubbr-content-heading">Navegue por tipo</h2>
      <div class="a11yhubbr-content-types-grid">
        <?php foreach ($display_types as $slug => $type): ?>
          <?php
          $is_active = $selected_type === $slug;
          $item_count = isset($type_counts[$slug]) ? (int) $type_counts[$slug] : 0;
          $count_label = $item_count === 1 ? '1 item' : $item_count . ' itens';
          $type_url = $is_active
            ? $build_url(array('tipo' => '', 'pg' => 1))
            : $build_url(array('tipo' => $slug, 'pg' => 1));
          ?>
          <a class="a11yhubbr-content-type-card<?php echo $is_active ? ' is-active' : ''; ?>"
            href="<?php echo esc_url($type_url); ?>"
            aria-current="<?php echo $is_active ? 'true' : 'false'; ?>">
            <span class="a11yhubbr-content-type-icon" aria-hidden="true"><i
                class="<?php echo esc_attr($type['icon']); ?>"></i></span>
            <strong><?php echo esc_html($type_labels[$slug] ?? $type['label']); ?></strong>
            <span><?php echo esc_html($count_label); ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <?php get_template_part('inc/components/archive-toolbar', null, array(
        'heading' => 'Conteúdos' . $title_suffix,
        'base_url' => $base_url,
        'selected_type' => $selected_type,
        'show_type_input' => ($selected_type !== '' && isset($display_types[$selected_type])),
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
        'per_page_label_suffix' => 'itens',
        'show_reset' => $has_active_filters,
        'reset_url' => $build_url(array('tipo' => '', 'busca' => '', 'ordem' => 'recentes', 'itens' => 8, 'pg' => 1)),
        'reset_label' => 'Limpar filtros',
      )); ?>

      <?php if ($content_query->have_posts()): ?>
        <div class="a11yhubbr-content-results-grid">
          <?php while ($content_query->have_posts()):
            $content_query->the_post(); ?>
            <?php
            $category_terms = get_the_terms(get_the_ID(), 'category');
            $post_type_label = '';
            $post_type_icon = 'fa-regular fa-file-lines';
            if (!empty($category_terms) && !is_wp_error($category_terms)) {
              foreach ($types as $slug => $type) {
                foreach ($category_terms as $term) {
                  if ($term->slug === $slug) {
                    $post_type_label = $term->name;
                    $post_type_icon = isset($type['icon']) ? (string) $type['icon'] : 'fa-regular fa-file-lines';
                    break 2;
                  }
                }
              }
            }
            if ($post_type_label === '') {
              $post_type_label = 'Sem categoria';
            }

            $excerpt = get_the_excerpt();
            if ($excerpt === '') {
              $excerpt = wp_trim_words(wp_strip_all_tags(get_the_content(null, false, get_the_ID())), 24);
            }
            $author_name = (string) get_post_meta(get_the_ID(), '_a11yhubbr_submitter_name', true);
            if ($author_name === '') {
              $author_name = get_the_author();
            }
            $tag_names = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
            if (!is_array($tag_names)) {
              $tag_names = array();
            }
            ?>
            <?php get_template_part('inc/components/content-card', null, array(
              'label' => $post_type_label,
              'badge_icon' => $post_type_icon,
              'date_iso' => get_the_date('c'),
              'date_text' => get_the_date('d/m/Y'),
              'title' => get_the_title(),
              'title_url' => get_permalink(),
              'excerpt' => $excerpt,
              'author' => $author_name,
              'tags' => $tag_names,
              'action_url' => get_permalink(),
              'action_label' => 'Acessar',
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
          'total' => max(1, (int) $content_query->max_num_pages),
          'type' => 'array',
          'prev_text' => '&lsaquo; Anterior',
          'next_text' => 'Próxima &rsaquo;',
        ));
        ?>

        <?php if (!empty($pagination)): ?>
          <nav class="a11yhubbr-content-pagination" aria-label="Paginação de conteúdos">
            <?php foreach ($pagination as $page_link): ?>
              <?php echo wp_kses_post($page_link); ?>
            <?php endforeach; ?>
          </nav>
        <?php endif; ?>
      <?php else: ?>
        <?php get_template_part('inc/components/empty-state', null, array(
          'title' => 'Nenhum conte?do encontrado',
          'message' => 'N?o encontramos resultados para os filtros selecionados.',
          'cta_label' => 'Submeter conte?do',
          'cta_url' => function_exists('a11yhubbr_get_submit_content_url') ? a11yhubbr_get_submit_content_url() : home_url('/submeter/submeter-conteudo'),
          'cta_class' => 'a11yhubbr-btn-context',
          'icon' => 'fa-regular fa-file-lines',
        )); ?>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>





