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

$share_text = $share_title . ' - ' . $share_url;
$whatsapp = add_query_arg(array('text' => $share_text), 'https://api.whatsapp.com/send');
$linkedin = add_query_arg(array('url' => $share_url), 'https://www.linkedin.com/sharing/share-offsite/');
$telegram = add_query_arg(array('url' => $share_url, 'text' => $share_title), 'https://t.me/share/url');
$threads = add_query_arg(array('text' => $share_text), 'https://www.threads.net/intent/post');
$bluesky = add_query_arg(array('text' => $share_text), 'https://bsky.app/intent/compose');
$x = add_query_arg(array('url' => $share_url, 'text' => $share_title), 'https://twitter.com/intent/tweet');
$facebook = add_query_arg(array('u' => $share_url), 'https://www.facebook.com/sharer/sharer.php');
?>
<div class="a11yhubbr-side-card a11yhubbr-single-engagement">
  <h2>Compartilhar</h2>
  <div class="a11yhubbr-single-share-buttons">
    <button type="button" class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light a11yhubbr-copy-link"
      data-copy-url="<?php echo esc_attr($share_url); ?>">
      <i class="fa-solid fa-link" aria-hidden="true"></i> Copiar link
    </button>
    <div class="a11yhubbr-single-share-grid" aria-label="Canais de compartilhamento">
      <a title="WhatsApp" aria-label="Compartilhar no WhatsApp" class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light a11yhubbr-share-icon-btn is-whatsapp"
        href="<?php echo esc_url($whatsapp); ?>" target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
      </a>
      <a title="LinkedIn" aria-label="Compartilhar no LinkedIn" class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light a11yhubbr-share-icon-btn is-linkedin"
        href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
      </a>
      <a title="X" aria-label="Compartilhar no X" class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light a11yhubbr-share-icon-btn is-x" href="<?php echo esc_url($x); ?>"
        target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-x-twitter" aria-hidden="true"></i>
      </a>
      <a title="Facebook" aria-label="Compartilhar no Facebook" class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light a11yhubbr-share-icon-btn is-facebook"
        href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
      </a>
      <a title="Telegram" aria-label="Compartilhar no Telegram" class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light a11yhubbr-share-icon-btn is-telegram" href="<?php echo esc_url($telegram); ?>"
        target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-telegram" aria-hidden="true"></i>
      </a>
      <a title="Threads" aria-label="Compartilhar no Threads" class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light a11yhubbr-share-icon-btn is-threads" href="<?php echo esc_url($threads); ?>"
        target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-threads" aria-hidden="true"></i>
      </a>
      <a title="Bluesky" aria-label="Compartilhar no Bluesky" class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light a11yhubbr-share-icon-btn is-bluesky" href="<?php echo esc_url($bluesky); ?>"
        target="_blank" rel="noopener noreferrer">
        <i class="fa-brands fa-bluesky" aria-hidden="true"></i>
      </a>
    </div>
  </div>
</div>

<div class="a11yhubbr-side-card a11yhubbr-single-suggest">
  <p>Informações desatualizadas ou incorretas?</p>
  <a class="a11yhubbr-btn a11yhubbr-btn-secondary a11yhubbr-btn-light"
    href="<?php echo esc_url((string) $args['contact_url']); ?>">
    <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
    <?php echo esc_html((string) $args['suggest_label']); ?>
  </a>
</div>
