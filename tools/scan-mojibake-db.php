<?php
if (PHP_SAPI !== 'cli') {
    exit("Execute via CLI.\n");
}

$wp_load = dirname(__DIR__, 4) . '/wp-load.php';
if (!file_exists($wp_load)) {
    exit("wp-load.php nao encontrado em: {$wp_load}\n");
}

require $wp_load;

global $wpdb;

if (!isset($wpdb) || !($wpdb instanceof wpdb)) {
    exit("Falha ao inicializar $wpdb.\n");
}

$patterns = array('%Ã%', '%Â%', '%�%');

$targets = array(
    'posts.post_title' => array($wpdb->posts, 'post_title'),
    'posts.post_content' => array($wpdb->posts, 'post_content'),
    'posts.post_excerpt' => array($wpdb->posts, 'post_excerpt'),
    'postmeta.meta_value' => array($wpdb->postmeta, 'meta_value'),
    'options.option_value' => array($wpdb->options, 'option_value'),
    'terms.name' => array($wpdb->terms, 'name'),
);

$result = array();
foreach ($targets as $label => $cfg) {
    list($table, $column) = $cfg;
    $where = array();
    foreach ($patterns as $_) {
        $where[] = "{$column} LIKE %s";
    }

    $sql = "SELECT COUNT(*) FROM {$table} WHERE (" . implode(' OR ', $where) . ')';
    $count = (int) $wpdb->get_var($wpdb->prepare($sql, ...$patterns));
    $result[$label] = $count;
}

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), PHP_EOL;
