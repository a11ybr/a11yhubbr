<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'title' => '',
    'description' => '',
    'primary' => array('label' => '', 'url' => ''),
    'secondary' => array('label' => '', 'url' => ''),
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;
?>
<div class="a11yhubbr-submit-cta-box a11yhubbr-section-cta">
  <?php if ($args['title'] !== ''): ?>
    <h2><?php echo esc_html($args['title']); ?></h2>
  <?php endif; ?>

  <?php if ($args['description'] !== ''): ?>
    <p><?php echo esc_html($args['description']); ?></p>
  <?php endif; ?>

  <div class="a11yhubbr-actions">
    <?php if (!empty($args['primary']['label']) && !empty($args['primary']['url'])): ?>
      <a class="a11yhubbr-btn" href="<?php echo esc_url($args['primary']['url']); ?>"><?php echo esc_html($args['primary']['label']); ?></a>
    <?php endif; ?>
    <?php if (!empty($args['secondary']['label']) && !empty($args['secondary']['url'])): ?>
      <a class="a11yhubbr-btn a11yhubbr-btn-light" href="<?php echo esc_url($args['secondary']['url']); ?>"><?php echo esc_html($args['secondary']['label']); ?></a>
    <?php endif; ?>
  </div>
</div>
