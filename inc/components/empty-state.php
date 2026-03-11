<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'title' => 'Nada por aqui ainda',
    'message' => 'Ainda nÃ£o hÃ¡ itens para exibir nesta seÃ§Ã£o.',
    'cta_label' => '',
    'cta_url' => '',
    'cta_class' => 'a11yhubbr-btn-alternative',
    'icon' => 'fa-regular fa-folder-open',
    'icon_key' => '',
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;
?>
<article class="a11yhubbr-empty-state a11yhubbr-card-base">
  <h3>
    <?php
    if (!empty($args['icon_key']) && function_exists('a11yhubbr_render_icon')) {
        echo a11yhubbr_render_icon((string) $args['icon_key']);
    } elseif (!empty($args['icon'])) {
        echo '<i class="' . esc_attr((string) $args['icon']) . '" aria-hidden="true"></i>';
    }
    ?>
    <?php echo esc_html((string) $args['title']); ?>
  </h3>
  <p><?php echo esc_html((string) $args['message']); ?></p>
  <?php if (!empty($args['cta_label']) && !empty($args['cta_url'])): ?>
    <a class="a11yhubbr-btn <?php echo esc_attr((string) $args['cta_class']); ?>" href="<?php echo esc_url((string) $args['cta_url']); ?>">
      <?php echo esc_html((string) $args['cta_label']); ?>
    </a>
  <?php endif; ?>
</article>
