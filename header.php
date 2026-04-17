<?php
if (!defined('ABSPATH')) {
  exit;
}

$search_base_url = esc_url(function_exists('a11yhubbr_get_search_page_url') ? a11yhubbr_get_search_page_url() : home_url('/busca'));
$search_term_header = isset($_GET['busca']) ? sanitize_text_field(wp_unslash($_GET['busca'])) : '';
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
  <nav class="a11yhubbr-skip-links" aria-label="Atalhos de navega&ccedil;&atilde;o">
    <a href="#conteudo-principal" class="a11yhubbr-skip-link">Ir para o conte&uacute;do [1]</a>
    <a href="#menu-principal" class="a11yhubbr-skip-link">Ir para o menu principal [2]</a>
    <a href="#pesquisa-site" class="a11yhubbr-skip-link">Ir para pesquisa [3]</a>
    <a href="#rodape-site" class="a11yhubbr-skip-link">Ir para o rodap&eacute; [4]</a>
  </nav>
  <div class="a11yhubbr-site-header">
    <div class="a11yhubbr-container a11yhubbr-header-inner">
      <a class="a11yhubbr-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="A11YBR - P&aacute;gina inicial">
        <span class="a11yhubbr-logo">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo-a11ybr.svg'); ?>"
            alt="Comunidade de Acessibilidade Digital Brasileira"
            loading="eager" decoding="async">
        </span>
      </a>

      <button type="button" class="a11yhubbr-menu-toggle" aria-expanded="false" aria-controls="a11yhubbr-header-panel"
        aria-label="Abrir menu principal">
        <i class="fa-solid fa-bars" aria-hidden="true"></i>
      </button>

      <div class="a11yhubbr-header-panel" id="a11yhubbr-header-panel">
        <nav id="menu-principal" aria-label="Navega&ccedil;&atilde;o principal" class="a11yhubbr-nav" tabindex="-1">
          <?php
          wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => false,
            'fallback_cb' => false,
            'menu_class' => 'a11yhubbr-menu',
          ));
          ?>
          <?php if (!has_nav_menu('primary')): ?>
            <ul class="a11yhubbr-menu">
              <li><a href="<?php echo esc_url(home_url('/conteudos')); ?>">Conte&uacute;dos</a></li>
              <li><a href="<?php echo esc_url(home_url('/rede')); ?>">Rede</a></li>
              <li><a href="<?php echo esc_url(home_url('/eventos')); ?>">Eventos</a></li>
              <li><a href="<?php echo esc_url(home_url('/sobre')); ?>">Sobre</a></li>
            </ul>
          <?php endif; ?>
        </nav>

        <div class="a11yhubbr-header-actions">
          <a id="pesquisa-site" class="a11yhubbr-header-search-btn" href="<?php echo $search_base_url; ?>" aria-label="Buscar no site">
           <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
          </a>
          <?php if ($search_term_header !== ''): ?>
            <button type="button" class="a11yhubbr-header-search-clear" data-clear-url="<?php echo $search_base_url; ?>"
              aria-label="Limpar busca do site">
              <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
          <?php endif; ?>
          <a class="a11yhubbr-btn a11yhubbr-btn-alternative a11yhubbr-header-submit-btn"
            href="<?php echo esc_url(home_url('/submeter')); ?>">
            <i class="fa-solid fa-arrow-up-from-bracket" aria-hidden="true"></i>
            Submeter
          </a>
        </div>
      </div>
    </div>
  </div>
