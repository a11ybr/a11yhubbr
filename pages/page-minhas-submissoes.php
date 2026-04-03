<?php
/*
Template Name: Minhas submissões
*/
if (!defined('ABSPATH')) {
    exit;
}

$is_logged_in = is_user_logged_in();
$login_url = function_exists('a11yhubbr_get_submission_login_url') ? a11yhubbr_get_submission_login_url(get_permalink()) : wp_login_url(get_permalink());
$registration_url = function_exists('a11yhubbr_get_submission_registration_url') ? a11yhubbr_get_submission_registration_url(get_permalink()) : '';
$current_user = $is_logged_in ? wp_get_current_user() : null;

$type_labels = array(
    'a11y_conteudo' => 'Conteúdo',
    'a11y_evento' => 'Evento',
    'a11y_perfil' => 'Perfil',
);

$status_labels = array(
    'pending' => 'Em revisão',
    'publish' => 'Publicado',
    'draft' => 'Rascunho',
    'private' => 'Privado',
);

$status_classes = array(
    'pending' => 'is-pending',
    'publish' => 'is-publish',
    'draft' => 'is-draft',
    'private' => 'is-private',
);

$selected_type = isset($_GET['tipo']) ? sanitize_key(wp_unslash($_GET['tipo'])) : '';
if (!isset($type_labels[$selected_type])) {
    $selected_type = '';
}

$selected_status = isset($_GET['status']) ? sanitize_key(wp_unslash($_GET['status'])) : '';
if (!isset($status_labels[$selected_status])) {
    $selected_status = '';
}

$per_page = 12;
$paged = isset($_GET['pg']) ? absint(wp_unslash($_GET['pg'])) : 1;
if ($paged < 1) {
    $paged = 1;
}

$base_url = get_permalink();
$current_args = array(
    'tipo' => $selected_type,
    'status' => $selected_status,
);

$build_url = static function ($overrides = array()) use ($base_url, $current_args) {
    $args = array_merge($current_args, $overrides);

    if (isset($args['tipo']) && $args['tipo'] === '') {
        unset($args['tipo']);
    }
    if (isset($args['status']) && $args['status'] === '') {
        unset($args['status']);
    }
    if (isset($args['pg']) && is_numeric($args['pg']) && (int) $args['pg'] <= 1) {
        unset($args['pg']);
    }

    return add_query_arg($args, $base_url);
};

$summary = array(
    'total' => 0,
    'types' => array_fill_keys(array_keys($type_labels), 0),
    'statuses' => array_fill_keys(array_keys($status_labels), 0),
);

$visible_ids = array();
$total_pages = 1;

if ($is_logged_in && $current_user) {
    $allowed_post_types = array_keys($type_labels);
    $allowed_statuses = array_keys($status_labels);
    $current_user_id = (int) $current_user->ID;

    $authored_ids = get_posts(array(
        'post_type' => $allowed_post_types,
        'post_status' => $allowed_statuses,
        'author' => $current_user_id,
        'posts_per_page' => -1,
        'fields' => 'ids',
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    $owned_profile_ids = get_posts(array(
        'post_type' => 'a11y_perfil',
        'post_status' => $allowed_statuses,
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => '_a11yhubbr_owner_user_id',
        'meta_value' => $current_user_id,
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    $all_ids = array_values(array_unique(array_map('intval', array_merge($authored_ids, $owned_profile_ids))));

    usort($all_ids, static function ($left, $right) {
        $left_time = (int) get_post_time('U', true, $left);
        $right_time = (int) get_post_time('U', true, $right);

        if ($left_time === $right_time) {
            return $right <=> $left;
        }

        return $right_time <=> $left_time;
    });

    foreach ($all_ids as $post_id) {
        $post_type = get_post_type($post_id);
        $status = get_post_status($post_id);

        if (!isset($summary['types'][$post_type]) || !isset($summary['statuses'][$status])) {
            continue;
        }

        $summary['total']++;
        $summary['types'][$post_type]++;
        $summary['statuses'][$status]++;
    }

    $filtered_ids = array_values(array_filter($all_ids, static function ($post_id) use ($selected_type, $selected_status) {
        $post_type = get_post_type($post_id);
        $status = get_post_status($post_id);

        if ($selected_type !== '' && $post_type !== $selected_type) {
            return false;
        }

        if ($selected_status !== '' && $status !== $selected_status) {
            return false;
        }

        return true;
    }));

    $total_pages = max(1, (int) ceil(count($filtered_ids) / $per_page));
    if ($paged > $total_pages) {
        $paged = $total_pages;
    }

    $offset = ($paged - 1) * $per_page;
    $visible_ids = array_slice($filtered_ids, $offset, $per_page);
}

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-site-main a11yhubbr-content-page a11yhubbr-my-submissions-page">
  <?php
  a11yhubbr_render_page_header(array(
      'breadcrumbs' => array(
          array('label' => 'Página inicial', 'url' => home_url('/')),
          array('label' => 'Minhas submissões'),
      ),
      'icon' => 'fa-regular fa-folder-open',
  ));
  ?>

  <section class="a11yhubbr-section">
    <div class="a11yhubbr-container">
      <?php if (!$is_logged_in) : ?>
        <section class="a11yhubbr-card a11yhubbr-form-section">
          <h2>Entre para acompanhar suas submissões</h2>
          <p>Esta área reúne conteúdos, eventos e perfis enviados pela sua conta. Faça login para ver o histórico e o status editorial de cada item.</p>
          <div class="a11yhubbr-form-actions">
            <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($login_url); ?>">Entrar</a>
            <?php if ($registration_url !== '') : ?>
              <a class="a11yhubbr-btn" href="<?php echo esc_url($registration_url); ?>">Criar conta</a>
            <?php endif; ?>
          </div>
        </section>
      <?php else : ?>
        <div class="a11yhubbr-my-submissions-shell">
          <div class="a11yhubbr-my-submissions-actions-bar">
            <a class="a11yhubbr-btn a11yhubbr-btn-outline a11yhubbr-my-submissions-create a11yhubbr-my-submissions-create--content" href="<?php echo esc_url(function_exists('a11yhubbr_get_submit_content_url') ? a11yhubbr_get_submit_content_url() : home_url('/submeter/submeter-conteudo')); ?>">Novo conteúdo</a>
            <a class="a11yhubbr-btn a11yhubbr-btn-outline a11yhubbr-my-submissions-create a11yhubbr-my-submissions-create--event" href="<?php echo esc_url(function_exists('a11yhubbr_get_submit_event_url') ? a11yhubbr_get_submit_event_url() : home_url('/submeter/submeter-eventos')); ?>">Novo evento</a>
            <a class="a11yhubbr-btn a11yhubbr-btn-outline a11yhubbr-my-submissions-create a11yhubbr-my-submissions-create--profile" href="<?php echo esc_url(function_exists('a11yhubbr_get_submit_profile_url') ? a11yhubbr_get_submit_profile_url() : home_url('/submeter/submeter-perfil')); ?>">Novo perfil</a>
          </div>

          <div class="a11yhubbr-my-submissions-layout">
            <section class="a11yhubbr-card a11yhubbr-form-section a11yhubbr-my-submissions-filter-card">
              <div class="a11yhubbr-my-submissions-section-head">
                <div>
                  <p class="a11yhubbr-my-submissions-eyebrow">Filtro e contexto</p>
                  <h2>Minhas submissões</h2>
                </div>
              </div>
              <div class="a11yhubbr-my-submissions-filter-bar">
                <div class="a11yhubbr-my-submissions-filter-group" aria-label="Filtros de submissões">
                  <a class="a11yhubbr-my-submissions-stat-card a11yhubbr-my-submissions-stat-card--wide <?php echo ($selected_type === '' && $selected_status === '') ? 'is-active' : ''; ?>" href="<?php echo esc_url($build_url(array('status' => '', 'tipo' => '', 'pg' => 1))); ?>">
                    <span class="a11yhubbr-my-submissions-stat-icon" aria-hidden="true"><i class="fa-regular fa-folder-open"></i></span>
                    <strong><?php echo esc_html((string) $summary['total']); ?></strong>
                    <span>Total enviado</span>
                  </a>

                  <?php foreach ($type_labels as $type_key => $type_label) : ?>
                    <a class="a11yhubbr-my-submissions-stat-card <?php echo $selected_type === $type_key ? 'is-active' : ''; ?>" href="<?php echo esc_url($build_url(array('tipo' => $type_key, 'pg' => 1))); ?>">
                      <span class="a11yhubbr-my-submissions-stat-icon" aria-hidden="true"><i class="<?php echo esc_attr($type_key === 'a11y_conteudo' ? 'fa-regular fa-file-lines' : ($type_key === 'a11y_evento' ? 'fa-regular fa-calendar' : 'fa-regular fa-id-card')); ?>"></i></span>
                      <strong><?php echo esc_html((string) ($summary['types'][$type_key] ?? 0)); ?></strong>
                      <span><?php echo esc_html($type_label); ?></span>
                    </a>
                  <?php endforeach; ?>

                  <?php foreach ($status_labels as $status_key => $status_label) : ?>
                    <a class="a11yhubbr-my-submissions-stat-card <?php echo $selected_status === $status_key ? 'is-active' : ''; ?>" href="<?php echo esc_url($build_url(array('status' => $status_key, 'pg' => 1))); ?>">
                      <span class="a11yhubbr-my-submissions-stat-icon <?php echo esc_attr($status_classes[$status_key] ?? ''); ?>" aria-hidden="true"><i class="<?php echo esc_attr($status_key === 'pending' ? 'fa-regular fa-clock' : ($status_key === 'publish' ? 'fa-regular fa-circle-check' : 'fa-regular fa-pen-to-square')); ?>"></i></span>
                      <strong><?php echo esc_html((string) ($summary['statuses'][$status_key] ?? 0)); ?></strong>
                      <span><?php echo esc_html($status_label); ?></span>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            </section>

            <?php if (!empty($visible_ids)) : ?>
              <div class="a11yhubbr-my-submissions-list">
                <?php foreach ($visible_ids as $item_id) : ?>
                  <?php
                  $item_type = get_post_type($item_id);
                  $item_status = get_post_status($item_id);
                  $item_excerpt = get_the_excerpt($item_id);
                  if ($item_excerpt === '') {
                      $item_excerpt = wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $item_id)), 24);
                  }

                  $label = $type_labels[$item_type] ?? 'Item';
                  $status_label = $status_labels[$item_status] ?? ucfirst((string) $item_status);
                  $status_class = $status_classes[$item_status] ?? '';
                  $details_url = '';
                  $details_label = '';
                  $submit_url = '';

                  if ($item_type === 'a11y_conteudo') {
                      $submit_url = function_exists('a11yhubbr_get_submit_content_url')
                          ? a11yhubbr_get_submit_content_url()
                          : home_url('/submeter/submeter-conteudo');
                  } elseif ($item_type === 'a11y_evento') {
                      $submit_url = function_exists('a11yhubbr_get_submit_event_url')
                          ? a11yhubbr_get_submit_event_url()
                          : home_url('/submeter/submeter-eventos');
                  } elseif ($item_type === 'a11y_perfil') {
                      $submit_url = function_exists('a11yhubbr_get_submit_profile_url')
                          ? a11yhubbr_get_submit_profile_url()
                          : home_url('/submeter/submeter-perfil');
                  }

                  if ($submit_url !== '' && function_exists('a11yhubbr_can_manage_submission_post') && a11yhubbr_can_manage_submission_post($item_id)) {
                      $details_url = add_query_arg('source_post', $item_id, $submit_url);
                      if ($item_type === 'a11y_conteudo') {
                          $details_label = 'Editar conteúdo';
                      } elseif ($item_type === 'a11y_evento') {
                          $details_label = 'Editar evento';
                      } elseif ($item_type === 'a11y_perfil') {
                          $details_label = 'Editar perfil';
                      } else {
                          $details_label = 'Editar item';
                      }
                  } elseif ($item_status === 'publish') {
                      $details_url = get_permalink($item_id);
                      $details_label = 'Abrir publicação';
                  }
                  ?>
                  <article class="a11yhubbr-my-submission-card">
                    <div class="a11yhubbr-my-submission-card-top">
                      <div class="a11yhubbr-my-submission-card-badges">
                        <span class="a11yhubbr-content-item-badge"><?php echo esc_html($label); ?></span>
                        <span class="a11yhubbr-my-submission-status <?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_label); ?></span>
                      </div>
                      <time datetime="<?php echo esc_attr(get_the_date('c', $item_id)); ?>"><?php echo esc_html(get_the_date('d/m/Y', $item_id)); ?></time>
                    </div>
                    <h3><?php echo esc_html(get_the_title($item_id)); ?></h3>
                    <p><?php echo esc_html($item_excerpt); ?></p>
                    <div class="a11yhubbr-my-submission-card-bottom">
                      <?php if ($item_status === 'pending') : ?>
                        <span class="a11yhubbr-my-submission-note">Sua submissão foi recebida e está aguardando análise editorial.</span>
                      <?php elseif ($item_status === 'publish') : ?>
                        <span class="a11yhubbr-my-submission-note">Este item já está público na plataforma e pode ser ajustado na interface.</span>
                      <?php else : ?>
                        <span class="a11yhubbr-my-submission-note">Este item ainda está em modo interno e pode ser ajustado na interface.</span>
                      <?php endif; ?>

                      <?php if ($details_url !== '') : ?>
                        <a class="a11yhubbr-content-item-details" href="<?php echo esc_url($details_url); ?>"><?php echo esc_html($details_label); ?></a>
                      <?php endif; ?>
                    </div>
                  </article>
                <?php endforeach; ?>
              </div>

              <?php
              $pagination_base = $build_url(array('pg' => '%#%'));
              $pagination_base = str_replace(array('%2523', '%23'), '%#%', $pagination_base);

              $pagination = paginate_links(array(
                  'base' => $pagination_base,
                  'format' => '',
                  'current' => $paged,
                  'total' => $total_pages,
                  'type' => 'array',
                  'prev_text' => '&lsaquo; Anterior',
                  'next_text' => 'Próxima &rsaquo;',
              ));
              ?>

              <?php if (!empty($pagination)) : ?>
                <nav class="a11yhubbr-content-pagination" aria-label="Paginação de minhas submissões">
                  <?php foreach ($pagination as $page_link) : ?>
                    <?php echo wp_kses_post($page_link); ?>
                  <?php endforeach; ?>
                </nav>
              <?php endif; ?>
            <?php else : ?>
              <?php get_template_part('inc/components/empty-state', null, array(
                  'title' => 'Nenhuma submissão encontrada',
                  'message' => 'Você ainda não enviou itens com os filtros atuais. Use os atalhos abaixo para criar um novo conteúdo, evento ou perfil.',
                  'cta_label' => 'Submeter conteúdo',
                  'cta_url' => function_exists('a11yhubbr_get_submit_content_url') ? a11yhubbr_get_submit_content_url() : home_url('/submeter/submeter-conteudo'),
              )); ?>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
