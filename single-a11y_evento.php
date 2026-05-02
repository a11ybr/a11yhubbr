<?php
if (!defined('ABSPATH')) {
    exit;
}

$format_slot = static function ($value) {
    $timestamp = strtotime((string) $value);
    if ($timestamp === false) {
        return '';
    }
    return wp_date('d/m/Y H:i', $timestamp);
};

get_header();
?>
<main id="conteudo-principal" tabindex="-1" class="a11yhubbr-site-main a11yhubbr-single-page a11yhubbr-single-event-page">
  <?php while (have_posts()): the_post(); ?>
    <?php
    $post_id = get_the_ID();
    $modality = (string) get_post_meta($post_id, '_a11yhubbr_event_modality', true);
    $event_type = (string) get_post_meta($post_id, '_a11yhubbr_event_type', true);
    $location = (string) get_post_meta($post_id, '_a11yhubbr_event_location', true);
    $postal_code = (string) get_post_meta($post_id, '_a11yhubbr_event_postal_code', true);
    $online_location = (string) get_post_meta($post_id, '_a11yhubbr_event_online_location', true);
    $organizer = (string) get_post_meta($post_id, '_a11yhubbr_event_organizer', true);
    $event_link = trim((string) get_post_meta($post_id, '_a11yhubbr_event_link', true));
    $slots_raw = (string) get_post_meta($post_id, '_a11yhubbr_event_slots', true);
    $slots = json_decode($slots_raw, true);
    if (!is_array($slots)) {
        $slots = array();
    }

    $tags = get_the_terms($post_id, 'post_tag');
    $tag_ids = wp_get_post_terms($post_id, 'post_tag', array('fields' => 'ids'));
    $permalink = get_permalink($post_id);

    $raw_content = (string) get_post_field('post_content', $post_id);
    $legacy_markers = array(
        'Modalidade:',
        'Tipo de evento:',
        'Localizacao:',
        'Organizador:',
        'Link:',
        'Datas e horarios:',
        'Tags:',
    );

    $filtered_lines = array();
    foreach (preg_split('/\R/u', $raw_content) as $line) {
        $trimmed = ltrim((string) $line);
        $is_legacy = false;
        foreach ($legacy_markers as $marker) {
            if (stripos($trimmed, $marker) === 0) {
                $is_legacy = true;
                break;
            }
        }
        if ($is_legacy) {
            break;
        }
        $filtered_lines[] = $line;
    }

    $content_to_render = trim(implode("\n", $filtered_lines));
    if ($content_to_render === '') {
        $content_to_render = $raw_content;
    }
    $content_to_render = apply_filters('the_content', $content_to_render);
    $event_badge_parts = array();
    $modality_map = array(
        'presencial' => 'Presencial',
        'online' => 'Online',
        'hibrido' => 'Hibrido',
    );
    $modality_label = $modality_map[sanitize_title($modality)] ?? $modality;
    $modality_slug = sanitize_title($modality);
    $is_physical_event = in_array($modality_slug, array('presencial', 'hibrido'), true);
    $is_online_event = in_array($modality_slug, array('online', 'hibrido'), true);
    $platform_value = '';
    if ($is_online_event) {
        $platform_value = trim((string) $online_location);
        if ($platform_value === '' && $modality_slug === 'online') {
            $platform_value = trim((string) $location);
        }
    }
    $map_query = '';
    if ($postal_code !== '') {
        $map_query = 'CEP ' . $postal_code;
    } elseif ($is_physical_event && $location !== '') {
        $map_query = $location;
    }
    $map_query = trim((string) $map_query);
    $map_link = '';
    $map_embed = '';
    if ($map_query !== '' && $is_physical_event) {
        $map_link = add_query_arg(array(
            'api' => 1,
            'query' => $map_query,
        ), 'https://www.google.com/maps/search/');
        $map_embed = 'https://www.google.com/maps?q=' . rawurlencode($map_query) . '&output=embed';
    }
    if ($event_type !== '') {
        $event_badge_parts[] = $event_type;
    }
    if ($modality_label !== '') {
        $event_badge_parts[] = $modality_label;
    }
    $event_badge_label = trim(implode(' ', $event_badge_parts));
    if ($event_badge_label === '') {
        $event_badge_label = 'Evento';
    }

    $related_event_query = null;
    if (!empty($tag_ids)) {
        $related_event_query = new WP_Query(array(
            'post_type' => 'a11y_evento',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => true,
            'tag__in' => array_map('intval', $tag_ids),
        ));
    }

    if (!($related_event_query instanceof WP_Query) || !$related_event_query->have_posts()) {
        $related_event_query = new WP_Query(array(
            'post_type' => 'a11y_evento',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => true,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    }

    a11yhubbr_render_page_header(array(
        'breadcrumbs' => array(
            array('label' => 'Pagina inicial', 'url' => home_url('/')),
            array('label' => 'Eventos', 'url' => home_url('/eventos')),
            array('label' => get_the_title()),
        ),
        'icon' => '',
        'title' => get_the_title(),
        'summary' => '',
        'use_page_context' => false,
        'context' => 'eventos',
    ));
    ?>

    <section class="a11yhubbr-section">
      <div class="a11yhubbr-container a11yhubbr-single-layout">
        <article class="a11yhubbr-card a11yhubbr-rich-content">
          <div class="a11yhubbr-single-meta-head">
            <span class="a11yhubbr-content-item-badge a11yhubbr-content-item-badge--eventos"><?php echo esc_html($event_badge_label); ?></span>
          </div>

          <div class="a11yhubbr-single-content-body"><?php echo wp_kses_post($content_to_render); ?></div>

          <?php if ($platform_value !== ''): ?>
            <section class="a11yhubbr-single-platform-box" aria-label="Plataforma do evento">
              <h3>Plataforma do evento</h3>
              <p><?php echo esc_html($platform_value); ?></p>
            </section>
          <?php endif; ?>

          <?php if ($map_embed !== ''): ?>
            <section class="a11yhubbr-single-map-box" aria-label="Mapa do local do evento">
              <div class="a11yhubbr-single-map-head">
                <h3>Mapa do local</h3>
                <?php if ($map_link !== ''): ?>
                  <a href="<?php echo esc_url($map_link); ?>" target="_blank" rel="noopener noreferrer">
                    Abrir no Google Maps <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                  </a>
                <?php endif; ?>
              </div>
              <p><?php echo esc_html($map_query); ?></p>
              <div class="a11yhubbr-single-map-embed">
                <iframe
                  src="<?php echo esc_url($map_embed); ?>"
                  title="<?php echo esc_attr('Mapa do local: ' . $map_query); ?>"
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
            </section>
          <?php endif; ?>
  <?php if (!empty($slots)): ?>
            <div class="a11yhubbr-single-slot-list">
              <h3>Datas e horarios</h3>
              <ul>
                <?php foreach ($slots as $index => $slot): ?>
                  <?php
                  $start = isset($slot['start']) ? $format_slot($slot['start']) : '';
                  $end = isset($slot['end']) ? $format_slot($slot['end']) : '';
                  ?>
                  <li>
                    <strong><?php echo esc_html('Data ' . ((int) $index + 1)); ?></strong>
                    <span><?php echo esc_html($start !== '' ? $start : '-'); ?></span>
                    <span><?php echo esc_html($end !== '' ? 'ate ' . $end : ''); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <?php if (!empty($tags) && !is_wp_error($tags)): ?>
            <h3>Tags</h3>
            <div class="a11yhubbr-single-tags">
              <?php foreach ($tags as $tag): ?>
                <a href="<?php echo esc_url(add_query_arg(array('busca' => $tag->name, 'tipo' => 'eventos'), home_url('/busca/'))); ?>"><?php echo esc_html($tag->name); ?></a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php get_template_part('inc/components/single-side-engagement', null, array(
            'share_url' => $permalink,
            'share_title' => get_the_title(),
            'layout' => 'inline',
            'show_suggest' => false,
          )); ?>

          <?php if (get_the_date() !== ''): ?>
            <div class="a11yhubbr-single-muted-meta" aria-label="Metadados da submissao">
              <span>Enviado em <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time></span>
            </div>
          <?php endif; ?>

        
        </article>

        <aside class="a11yhubbr-single-aside-stack">
          <?php if ($event_link !== ''): ?>
            <div class="a11yhubbr-side-card a11yhubbr-single-primary-action">
              <h2>Acessar evento</h2>
              <p>Abra o link principal para inscricao, detalhes ou transmissao.</p>
              <a class="a11yhubbr-btn a11yhubbr-btn-primary" href="<?php echo esc_url($event_link); ?>" target="_blank" rel="noopener noreferrer">
                Abrir evento <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
              </a>
            </div>
          <?php endif; ?>
          <div class="a11yhubbr-side-card a11yhubbr-single-side a11yhubbr-single-side-meta">
            <h2>Ficha tecnica</h2>
            <dl>
              <div><dt>Tipo</dt><dd><?php echo esc_html($event_type !== '' ? $event_type : 'Evento'); ?></dd></div>
              <?php if ($organizer !== ''): ?><div><dt>Organizador</dt><dd><?php echo esc_html($organizer); ?></dd></div><?php endif; ?>
              <?php if ($postal_code !== ''): ?><div><dt>CEP</dt><dd><?php echo esc_html($postal_code); ?></dd></div><?php endif; ?>
              <?php if ($location !== ''): ?><div><dt><?php echo esc_html(sanitize_title($modality) === 'online' ? 'Plataforma' : 'Localizacao'); ?></dt><dd><?php echo esc_html($location); ?></dd></div><?php endif; ?>
              <?php if ($online_location !== '' && sanitize_title($modality) === 'hibrido'): ?><div><dt>Plataforma online</dt><dd><?php echo esc_html($online_location); ?></dd></div><?php endif; ?>
            </dl>
          </div>

          <?php get_template_part('inc/components/single-side-engagement', null, array(
            'contact_url' => home_url('/contato'),
            'suggest_url' => add_query_arg('source_post', $post_id, function_exists('a11yhubbr_get_submit_event_url') ? a11yhubbr_get_submit_event_url() : home_url('/submeter/submeter-eventos')),
            'suggest_label' => 'Sugerir alteracao',
            'show_share' => false,
          )); ?>
        </aside>
      </div>
    </section>

    <?php if ($related_event_query instanceof WP_Query && $related_event_query->have_posts()): ?>
      <section class="a11yhubbr-section a11yhubbr-single-related">
        <div class="a11yhubbr-container">
          <h2 class="a11yhubbr-content-heading">Eventos relacionados</h2>
          <div class="a11yhubbr-content-results-grid">
            <?php while ($related_event_query->have_posts()): $related_event_query->the_post(); ?>
              <?php
              $related_modality = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_modality', true);
              $related_type = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_type', true);
              $related_badge_parts = array();
              if ($related_type !== '') {
                  $related_badge_parts[] = $related_type;
              }
              if ($related_modality !== '') {
                  $related_badge_parts[] = $modality_map[sanitize_title($related_modality)] ?? $related_modality;
              }
              $related_badge_label = trim(implode(' ', $related_badge_parts));
              if ($related_badge_label === '') {
                  $related_badge_label = 'Evento';
              }
              $related_link = trim((string) get_post_meta(get_the_ID(), '_a11yhubbr_event_link', true));
              $related_location = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_location', true);
              $related_organizer = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_organizer', true);
              $related_excerpt = get_the_excerpt();
              $related_tags = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
              if (!is_array($related_tags)) {
                  $related_tags = array();
              }
              if ($related_excerpt === '') {
                  $related_excerpt = wp_trim_words(wp_strip_all_tags(get_post_field('post_content', get_the_ID())), 22);
              }
              ?>
              <?php get_template_part('inc/components/event-card', null, array(
                'label' => $related_badge_label,
                'title' => get_the_title(),
                'title_url' => get_permalink(),
                'external_url' => $related_link,
                'date_text' => get_the_date('d/m/Y'),
                'time_text' => '',
                'location' => $related_location,
                'excerpt' => $related_excerpt,
                'organizer' => $related_organizer,
                'tags' => $related_tags,
                'badge_class' => 'a11yhubbr-content-item-badge--eventos',
              )); ?>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>

