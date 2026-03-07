<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'share_url' => '',
    'share_title' => '',
    'contact_url' => home_url('/contato'),
    'suggest_label' => 'Sugerir alteração',
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;
$share_url = (string) $args['share_url'];
if ($share_url === '') {
    $share_url = get_permalink();
}

$share_title = (string) $args['share_title'];
if ($share_title === '') {
    $share_title = get_the_title();
}

$whatsapp = add_query_arg(array('text' => $share_title . ' - ' . $share_url), 'https://api.whatsapp.com/send');
$linkedin = add_query_arg(array('mini' => 'true', 'url' => $share_url, 'title' => $share_title), 'https://www.linkedin.com/shareArticle');
?>
<div class="a11yhubbr-side-card a11yhubbr-single-engagement">
  <h2>Compartilhar</h2>
  <div class="a11yhubbr-single-share-buttons">
    <button type="button" class="a11yhubbr-btn a11yhubbr-btn-light a11yhubbr-copy-link" data-copy-url="<?php echo esc_attr($share_url); ?>">
      <i class="fa-solid fa-link" aria-hidden="true"></i> Copiar link
    </button>
    <a class="a11yhubbr-btn a11yhubbr-btn-light" href="<?php echo esc_url($whatsapp); ?>" target="_blank" rel="noopener noreferrer">
      <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp
    </a>
    <a class="a11yhubbr-btn a11yhubbr-btn-light" href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer">
      <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i> LinkedIn
    </a>
  </div>
</div>

<div class="a11yhubbr-side-card a11yhubbr-single-suggest">
  <p>Informações desatualizadas ou incorretas?</p>
  <a class="a11yhubbr-btn a11yhubbr-btn-light" href="<?php echo esc_url((string) $args['contact_url']); ?>">
    <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i> <?php echo esc_html((string) $args['suggest_label']); ?>
  </a>
</div>
