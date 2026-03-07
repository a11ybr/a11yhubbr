<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'label' => '',
    'date_iso' => '',
    'date_text' => '',
    'title' => '',
    'excerpt' => '',
    'external_url' => '',
    'details_url' => '',
    'details_label' => 'Ver detalhes',
    'show_external_icon' => true,
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;

$label = trim((string) $args['label']);
$date_iso = trim((string) $args['date_iso']);
$date_text = trim((string) $args['date_text']);
$title = trim((string) $args['title']);
$excerpt = trim((string) $args['excerpt']);
$external_url = trim((string) $args['external_url']);
$details_url = trim((string) $args['details_url']);
$details_label = trim((string) $args['details_label']);
$show_external_icon = !empty($args['show_external_icon']);
?>
<article class="a11yhubbr-content-item-card">
  <div class="a11yhubbr-content-item-meta">
    <span class="a11yhubbr-content-item-badge"><?php echo esc_html($label !== '' ? $label : 'Item'); ?></span>
    <?php if ($date_text !== ''): ?>
      <time datetime="<?php echo esc_attr($date_iso !== '' ? $date_iso : current_time('c')); ?>"><?php echo esc_html($date_text); ?></time>
    <?php endif; ?>
  </div>
  <h3>
    <?php if ($external_url !== ''): ?>
      <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener noreferrer">
        <?php echo esc_html($title); ?>
        <?php if ($show_external_icon): ?>
          <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
        <?php endif; ?>
      </a>
    <?php else: ?>
      <span><?php echo esc_html($title); ?></span>
    <?php endif; ?>
  </h3>
  <?php if ($excerpt !== ''): ?>
    <p><?php echo esc_html($excerpt); ?></p>
  <?php endif; ?>
  <?php if ($details_url !== ''): ?>
    <a class="a11yhubbr-content-item-details" href="<?php echo esc_url($details_url); ?>"><?php echo esc_html($details_label !== '' ? $details_label : 'Ver detalhes'); ?></a>
  <?php endif; ?>
</article>
