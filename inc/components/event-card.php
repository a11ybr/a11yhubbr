<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'label' => 'Evento',
    'title' => '',
    'external_url' => '',
    'date_text' => '',
    'time_text' => '',
    'location' => '',
    'excerpt' => '',
    'organizer' => '',
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;

$label = trim((string) $args['label']);
$title = trim((string) $args['title']);
$external_url = trim((string) $args['external_url']);
$date_text = trim((string) $args['date_text']);
$time_text = trim((string) $args['time_text']);
$location = trim((string) $args['location']);
$excerpt = trim((string) $args['excerpt']);
$organizer = trim((string) $args['organizer']);
?>
<article class="a11yhubbr-event-card a11yhubbr-card-base">
  <div class="a11yhubbr-content-item-meta">
    <span class="a11yhubbr-content-item-badge"><?php echo esc_html($label); ?></span>
  </div>
  <h3 class="a11yhubbr-content-card-title">
    <?php if ($external_url !== ''): ?>
      <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener noreferrer">
        <?php echo esc_html($title); ?>
        <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
      </a>
    <?php else: ?>
      <span><?php echo esc_html($title); ?></span>
    <?php endif; ?>
  </h3>

  <?php if ($date_text !== '' || $time_text !== ''): ?>
    <div class="a11yhubbr-event-card-datetime">
      <?php if ($date_text !== ''): ?>
        <span><i class="fa-regular fa-calendar" aria-hidden="true"></i> <?php echo esc_html($date_text); ?></span>
      <?php endif; ?>
      <?php if ($time_text !== ''): ?>
        <span><i class="fa-regular fa-clock" aria-hidden="true"></i> <?php echo esc_html($time_text); ?></span>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php if ($location !== ''): ?>
    <p class="a11yhubbr-event-card-location"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> <?php echo esc_html($location); ?></p>
  <?php endif; ?>

  <?php if ($excerpt !== ''): ?>
    <p class="a11yhubbr-content-card-excerpt"><?php echo esc_html($excerpt); ?></p>
  <?php endif; ?>

  <?php if ($organizer !== ''): ?>
    <p class="a11yhubbr-event-card-organizer">Organizacao: <span><?php echo esc_html($organizer); ?></span> </p>
  <?php endif; ?>
</article>