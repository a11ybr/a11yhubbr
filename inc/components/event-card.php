<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'label' => 'Evento',
    'title' => '',
    'title_url' => '',
    'external_url' => '',
    'date_text' => '',
    'time_text' => '',
    'location' => '',
    'excerpt' => '',
    'organizer' => '',
    'badge_class' => '',
    'tags' => array(),
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;

$label = trim((string) $args['label']);
$title = trim((string) $args['title']);
$title_url = trim((string) $args['title_url']);
$external_url = trim((string) $args['external_url']);
$date_text = trim((string) $args['date_text']);
$time_text = trim((string) $args['time_text']);
$location = trim((string) $args['location']);
$excerpt = trim((string) $args['excerpt']);
$organizer = trim((string) $args['organizer']);
$badge_class = trim((string) $args['badge_class']);
$tags = isset($args['tags']) && is_array($args['tags']) ? array_values(array_filter(array_map('strval', $args['tags']))) : array();
?>
<article class="a11yhubbr-event-card a11yhubbr-card-base card-hover">
  <div class="a11yhubbr-event-card-top">
    <?php if ($label !== ''): ?>
      <span class="a11yhubbr-content-item-badge<?php echo $badge_class !== '' ? ' ' . esc_attr($badge_class) : ''; ?>"><?php echo esc_html($label); ?></span>
    <?php endif; ?>
    <div class="a11yhubbr-event-card-top-meta">
      <?php if ($date_text !== ''): ?>
        <span class="a11yhubbr-event-card-date"><?php echo esc_html($date_text); ?></span>
      <?php endif; ?>
      <?php if ($time_text !== ''): ?>
        <span class="a11yhubbr-event-card-time"><?php echo esc_html($time_text); ?></span>
      <?php endif; ?>
    </div>
  </div>

  <div class="a11yhubbr-event-card-body">
    <h3 class="a11yhubbr-content-card-title">
      <?php if ($title_url !== ''): ?>
        <a href="<?php echo esc_url($title_url); ?>">
          <?php echo esc_html($title); ?>
        </a>
      <?php else: ?>
        <span><?php echo esc_html($title); ?></span>
      <?php endif; ?>
    </h3>

    <?php if ($excerpt !== ''): ?>
      <p class="a11yhubbr-content-card-excerpt"><?php echo esc_html($excerpt); ?></p>
    <?php endif; ?>

    <?php if ($location !== ''): ?>
      <p class="a11yhubbr-event-card-location">
        <?php if ($location !== ''): ?>
          <span><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?php echo esc_html($location); ?></span>
        <?php endif; ?>
      </p>
    <?php endif; ?>

    <?php if ($organizer !== ''): ?>
      <p class="a11yhubbr-event-card-organizer">Organização: <span><?php echo esc_html($organizer); ?></span></p>
    <?php endif; ?>

    <?php if (!empty($tags)): ?>
      <div class="a11yhubbr-content-card-tags">
        <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
          <span><?php echo esc_html($tag); ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</article>
