<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="a11yhubbr-site-header">
  <div class="a11yhubbr-container a11yhubbr-header-inner">
    <a class="a11yhubbr-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="A11YBR - Pagina inicial">
      <span class="a11yhubbr-logo" aria-hidden="true">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-a11ybr.svg'); ?>" alt="" loading="eager" decoding="async">
      </span>
    </a>

    <nav aria-label="Navegacao principal" class="a11yhubbr-nav">
      <?php
      wp_nav_menu(array(
        'theme_location' => 'primary',
        'container' => false,
        'fallback_cb' => false,
        'menu_class' => 'a11yhubbr-menu',
      ));
      ?>
      <?php if (!has_nav_menu('primary')) : ?>
        <ul class="a11yhubbr-menu">
          <li><a href="<?php echo esc_url(home_url('/conteudos')); ?>">Conteudos</a></li>
          <li><a href="<?php echo esc_url(home_url('/rede')); ?>">Rede</a></li>
          <li><a href="<?php echo esc_url(home_url('/eventos')); ?>">Eventos</a></li>
          <li><a href="<?php echo esc_url(home_url('/sobre')); ?>">Sobre</a></li>
        </ul>
      <?php endif; ?>
    </nav>

    <div class="a11yhubbr-header-actions">
      <a class="a11yhubbr-header-search-btn" href="<?php echo esc_url(function_exists('a11yhubbr_get_search_page_url') ? a11yhubbr_get_search_page_url() : home_url('/busca')); ?>" aria-label="Buscar no site">
        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
      </a>
      <a class="a11yhubbr-btn a11yhubbr-btn-alternative a11yhubbr-header-submit-btn" href="<?php echo esc_url(home_url('/submeter')); ?>">
        <i class="fa-solid fa-arrow-up-from-bracket" aria-hidden="true"></i>
        Submeter
      </a>
    </div>
  </div>
</header>
