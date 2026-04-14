<?php
if (!defined('ABSPATH')) {
    exit;
}

$defaults = array(
    'label' => 'Conteudo',
    'badge_icon' => 'fa-regular fa-file-lines',
    'date_iso' => '',
    'date_text' => '',
    'title' => '',
    'excerpt' => '',
    'author' => '',
    'external_url' => '',
    'title_url' => '',
    'show_external_icon' => true,
    'tags' => array(),
    'action_url' => '',
    'action_label' => 'Acessar',
    'badge_class' => '',
);

$args = isset($args) && is_array($args) ? wp_parse_args($args, $defaults) : $defaults;

$label = trim((string) $args['label']);
$badge_icon = preg_replace('/[^a-z0-9\-\s]/i', '', (string) $args['badge_icon']);
$date_iso = trim((string) $args['date_iso']);
$date_text = trim((string) $args['date_text']);
$title = trim((string) $args['title']);
$excerpt = trim((string) $args['excerpt']);
$author = trim((string) $args['author']);
$external_url = trim((string) $args['external_url']);
$title_url = trim((string) $args['title_url']);
$show_external_icon = !empty($args['show_external_icon']);
$tags = isset($args['tags']) && is_array($args['tags']) ? array_values(array_filter(array_map('strval', $args['tags']))) : array();
$action_url = trim((string) $args['action_url']);
$action_label = trim((string) $args['action_label']);
$badge_class = trim((string) $args['badge_class']);

if ($title_url === '' && $external_url !== '') {
    $title_url = $external_url;
}

if ($action_url === '' && $title_url !== '') {
    $action_url = $title_url;
}

if ($action_label === '') {
    $action_label = 'Acessar';
}
?>
<article class="a11yhubbr-content-card a11yhubbr-card-base card-hover">
    <div class="a11yhubbr-content-item-meta">
        <span class="a11yhubbr-content-item-badge<?php echo $badge_class !== '' ? ' ' . esc_attr($badge_class) : ''; ?>"><i
                class="<?php echo esc_attr($badge_icon !== '' ? $badge_icon : 'fa-regular fa-file-lines'); ?>"
                aria-hidden="true"></i><?php echo esc_html($label); ?></span>
        <?php if ($date_text !== ''): ?>
            <time
                datetime="<?php echo esc_attr($date_iso !== '' ? $date_iso : current_time('c')); ?>"><?php echo esc_html($date_text); ?></time>
        <?php endif; ?>
    </div>
    <h3 class="a11yhubbr-content-card-title">
        <?php if ($title_url !== ''): ?>
            <a href="<?php echo esc_url($title_url); ?>">
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

    <?php if (!empty($tags)): ?>
        <div class="a11yhubbr-content-card-footer">
            <div class="a11yhubbr-content-card-tags">
                <?php foreach (array_slice($tags, 0, 2) as $tag): ?>
                    <span><?php echo esc_html($tag); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($action_url !== '' && $action_url !== $title_url): ?>
        <a class="a11yhubbr-content-card-action" href="<?php echo esc_url($action_url); ?>"
           <?php if ($external_url !== '' && $show_external_icon): ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>>
            <?php echo esc_html($action_label); ?>
            <span class="a11yhubbr-sr-only"><?php echo esc_html(': ' . $title); ?></span>
            <?php if ($external_url !== '' && $show_external_icon): ?>
                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                <span class="a11yhubbr-sr-only"> (abre em nova aba)</span>
            <?php endif; ?>
        </a>
    <?php endif; ?>
</article>
