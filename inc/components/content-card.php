<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'label' => 'Conteudo',
    'date_iso' => '',
    'date_text' => '',
    'title' => '',
    'excerpt' => '',
    'author' => '',
    'external_url' => '',
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;

$label = trim((string) $args['label']);
$date_iso = trim((string) $args['date_iso']);
$date_text = trim((string) $args['date_text']);
$title = trim((string) $args['title']);
$excerpt = trim((string) $args['excerpt']);
$author = trim((string) $args['author']);
$external_url = trim((string) $args['external_url']);
?>
<article class="a11yhubbr-content-card a11yhubbr-card-base">
  <div class="a11yhubbr-content-item-meta">
    <span class="a11yhubbr-content-item-badge"><?php echo esc_html($label); ?></span>
    <?php if ($date_text !== ''): ?>
      <time datetime="<?php echo esc_attr($date_iso !== '' ? $date_iso : current_time('c')); ?>"><?php echo esc_html($date_text); ?></time>
    <?php endif; ?>
  </div>
  <h3 class="a11yhubbr-content-card-title">
    <?php if ($external_url !== ''): ?>
      <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener noreferrer">
        <?php echo esc_html($title); ?>
      </a>
    <?php else: ?>
      <span><?php echo esc_html($title); ?></span>
    <?php endif; ?>
  </h3>
  <?php if ($excerpt !== ''): ?>
    <p class="a11yhubbr-content-card-excerpt"><?php echo esc_html($excerpt); ?></p>
  <?php endif; ?>
  <?php if ($author !== ''): ?>
    <p class="a11yhubbr-content-card-author">Por <?php echo esc_html($author); ?></p>
  <?php endif; ?>
</article>
