<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'heading' => '',
    'base_url' => '',
    'selected_type' => '',
    'show_type_input' => false,
    'search_term' => '',
    'clear_search_url' => '',
    'sort_name' => 'ordem',
    'sort_options' => array(),
    'current_sort' => '',
    'per_page_name' => 'itens',
    'per_page_options' => array(),
    'current_per_page' => 8,
    'per_page_label_suffix' => 'itens',
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;
?>
<div class="a11yhubbr-content-toolbar">
  <h2 class="a11yhubbr-content-heading a11yhubbr-content-heading-results"><?php echo esc_html($args['heading']); ?></h2>
  <form method="get" action="<?php echo esc_url($args['base_url']); ?>" class="a11yhubbr-content-controls">
    <?php if (!empty($args['show_type_input']) && $args['selected_type'] !== ''): ?>
      <input type="hidden" name="tipo" value="<?php echo esc_attr($args['selected_type']); ?>">
    <?php endif; ?>

    <label class="a11yhubbr-content-search">
      <span class="screen-reader-text">Buscar</span>
      <input type="search" name="busca" value="<?php echo esc_attr($args['search_term']); ?>" placeholder="Buscar por palavra-chave">
      <?php if ($args['search_term'] !== '' && $args['clear_search_url'] !== ''): ?>
        <a class="a11yhubbr-content-search-clear" href="<?php echo esc_url($args['clear_search_url']); ?>" aria-label="Limpar busca">&times;</a>
      <?php endif; ?>
    </label>

    <div class="a11yhubbr-content-control-group" title="Ordenar por">
      <label class="a11yhubbr-content-select-wrap">
        <span class="screen-reader-text">Ordenar por</span>
        <i class="fa-solid fa-arrow-down-up-across-line a11yhubbr-content-select-icon" aria-hidden="true"></i>
        <i class="fa-solid fa-chevron-down a11yhubbr-content-select-chevron" aria-hidden="true"></i>
        <select name="<?php echo esc_attr($args['sort_name']); ?>" onchange="this.form.submit()">
          <?php foreach ($args['sort_options'] as $value => $label): ?>
            <option value="<?php echo esc_attr((string) $value); ?>" <?php selected($args['current_sort'], (string) $value); ?>><?php echo esc_html((string) $label); ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>

    <div class="a11yhubbr-content-control-group">
      <label class="a11yhubbr-content-select-wrap" title="Exibir">
        <span class="screen-reader-text">Exibir itens</span>
        <i class="fa-solid fa-table-cells a11yhubbr-content-select-icon" aria-hidden="true"></i>
        <i class="fa-solid fa-chevron-down a11yhubbr-content-select-chevron" aria-hidden="true"></i>
        <select name="<?php echo esc_attr($args['per_page_name']); ?>" onchange="this.form.submit()">
          <?php foreach ($args['per_page_options'] as $amount): ?>
            <option value="<?php echo esc_attr((string) $amount); ?>" <?php selected((int) $args['current_per_page'], (int) $amount); ?>>
              <?php echo esc_html((string) $amount . ' ' . (string) $args['per_page_label_suffix']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>
  </form>
</div>
