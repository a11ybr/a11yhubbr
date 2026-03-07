<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'title' => '',
    'text' => '',
    'icon' => '',
    'tone' => '',
    'class' => 'a11yhubbr-card-feature',
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;
$class = trim('a11yhubbr-card ' . (string) $args['class']);
$title = trim((string) $args['title']);
$text = trim((string) $args['text']);
$icon = trim((string) $args['icon']);
$tone = sanitize_key((string) $args['tone']);

$palette = array(
    'blue' => array('bg' => '#dce6ff', 'fg' => '#133a82'),
    'green' => array('bg' => '#d8f2e5', 'fg' => '#1d7b56'),
    'teal' => array('bg' => '#d8f3f0', 'fg' => '#0f6f77'),
    'orange' => array('bg' => '#ffe8d5', 'fg' => '#b45309'),
    'pink' => array('bg' => '#ffe0ef', 'fg' => '#b63773'),
    'purple' => array('bg' => '#ebe3ff', 'fg' => '#5f46b2'),
);

if (!isset($palette[$tone])) {
    $keys = array_keys($palette);
    $seed = $title !== '' ? $title : $text;
    $index = $seed !== '' ? (abs(crc32($seed)) % count($keys)) : 0;
    $tone = $keys[$index];
}

$colors = $palette[$tone];
$style = sprintf('--a11y-feature-icon-bg: %s; --a11y-feature-icon-fg: %s;', $colors['bg'], $colors['fg']);
?>
<article class="<?php echo esc_attr($class); ?>" style="<?php echo esc_attr($style); ?>">
  <?php if ($title !== '' || $icon !== ''): ?>
    <?php if ($icon !== ''): ?>
      <span class="a11yhubbr-feature-icon-box" aria-hidden="true"><i class="<?php echo esc_attr($icon); ?>"></i></span>
    <?php endif; ?>
    <div class="a11yhubbr-feature-head">
      <?php if ($title !== ''): ?>
        <h3><?php echo esc_html($title); ?></h3>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php if ($text !== ''): ?>
    <p class="a11yhubbr-feature-text"><?php echo esc_html($text); ?></p>
  <?php endif; ?>
</article>
