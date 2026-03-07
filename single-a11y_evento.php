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
<main class="a11yhubbr-site-main a11yhubbr-single-page a11yhubbr-single-event-page">
  <?php while (have_posts()): the_post(); ?>
    <?php
    $modality = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_modality', true);
    $event_type = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_type', true);
    $location = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_location', true);
    $organizer = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_organizer', true);
    $event_link = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_link', true);
    $slots_raw = (string) get_post_meta(get_the_ID(), '_a11yhubbr_event_slots', true);
    $slots = json_decode($slots_raw, true);
    if (!is_array($slots)) {
        $slots = array();
    }
    $tags = get_the_terms(get_the_ID(), 'post_tag');
    $permalink = get_permalink();

    a11yhubbr_render_page_header(array(
        'breadcrumbs' => array(
            array('label' => 'Página inicial', 'url' => home_url('/')),
            array('label' => 'Eventos', 'url' => home_url('/eventos')),
            array('label' => get_the_title()),
        ),
        'icon' => 'fa-regular fa-calendar',
        'title' => get_the_title(),
        'summary' => get_the_excerpt() !== '' ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content(null, false, get_the_ID())), 28),
        'use_page_context' => false,
    ));
    ?>

    <section class="a11yhubbr-section">
      <div class="a11yhubbr-container a11yhubbr-single-layout">
        <article class="a11yhubbr-card a11yhubbr-rich-content">
          <div class="a11yhubbr-single-meta-head">
            <span class="a11yhubbr-content-item-badge"><?php echo esc_html($modality !== '' ? $modality : 'Evento'); ?></span>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d/m/Y')); ?></time>
          </div>

          <?php the_content(); ?>

          <?php if (!empty($tags) && !is_wp_error($tags)): ?>
            <h3>Tags</h3>
            <div class="a11yhubbr-single-tags">
              <?php foreach ($tags as $tag): ?>
                <span><?php echo esc_html($tag->name); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($slots)): ?>
            <div class="a11yhubbr-single-slot-list">
              <h3>Datas e horérios</h3>
              <ul>
                <?php foreach ($slots as $index => $slot): ?>
                  <?php
                  $start = isset($slot['start']) ? $format_slot($slot['start']) : '';
                  $end = isset($slot['end']) ? $format_slot($slot['end']) : '';
                  ?>
                  <li>
                    <strong><?php echo esc_html('Data ' . ((int) $index + 1)); ?></strong>
                    <span><?php echo esc_html($start !== '' ? $start : '-'); ?></span>
                    <span><?php echo esc_html($end !== '' ? 'até ' . $end : ''); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
        </article>

        <aside class="a11yhubbr-single-aside-stack">
          <div class="a11yhubbr-side-card a11yhubbr-single-side">
            <h2>Informações do evento</h2>
            <dl>
              <?php if ($modality !== ''): ?><div><dt>Modalidade</dt><dd><?php echo esc_html($modality); ?></dd></div><?php endif; ?>
              <?php if ($event_type !== ''): ?><div><dt>Tipo de evento</dt><dd><?php echo esc_html($event_type); ?></dd></div><?php endif; ?>
              <?php if ($location !== ''): ?><div><dt>Localização</dt><dd><?php echo esc_html($location); ?></dd></div><?php endif; ?>
              <?php if ($organizer !== ''): ?><div><dt>Organizador</dt><dd><?php echo esc_html($organizer); ?></dd></div><?php endif; ?>
              <?php if ($event_link !== ''): ?><div><dt>Link externo</dt><dd><a href="<?php echo esc_url($event_link); ?>" target="_blank" rel="noopener noreferrer">Acessar evento <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a></dd></div><?php endif; ?>
            </dl>
          </div>

          <?php get_template_part('inc/components/single-side-engagement', null, array(
            'share_url' => $permalink,
            'share_title' => get_the_title(),
            'contact_url' => home_url('/contato'),
            'suggest_label' => 'Sugerir alteração',
          )); ?>
        </aside>
      </div>
    </section>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
