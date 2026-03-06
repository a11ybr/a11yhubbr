<?php
$xmlFile = 'C:/Users/wagne/Documents/Projetos/a11ybr/mock-import-a11ybr-54.xml';
$wpLoad = 'C:/Users/wagne/Local Sites/a11ybr/app/public/wp-load.php';
$batch = 'mock-54-20260306';

if (!file_exists($xmlFile)) {
    fwrite(STDERR, "XML nao encontrado: {$xmlFile}\n");
    exit(1);
}
if (!file_exists($wpLoad)) {
    fwrite(STDERR, "wp-load.php nao encontrado: {$wpLoad}\n");
    exit(1);
}

require_once $wpLoad;

if (!function_exists('wp_insert_post')) {
    fwrite(STDERR, "WordPress nao carregou corretamente.\n");
    exit(1);
}

libxml_use_internal_errors(true);
$xml = simplexml_load_file($xmlFile, 'SimpleXMLElement', LIBXML_NOCDATA);
if (!$xml) {
    fwrite(STDERR, "Falha ao parsear XML.\n");
    foreach (libxml_get_errors() as $err) {
        fwrite(STDERR, trim($err->message) . "\n");
    }
    exit(1);
}

$namespaces = $xml->getNamespaces(true);
$wpNs = isset($namespaces['wp']) ? $namespaces['wp'] : 'http://wordpress.org/export/1.2/';
$excerptNs = isset($namespaces['excerpt']) ? $namespaces['excerpt'] : 'http://wordpress.org/export/1.2/excerpt/';
$contentNs = isset($namespaces['content']) ? $namespaces['content'] : 'http://purl.org/rss/1.0/modules/content/';

$imported = 0;
$skipped = 0;
$errors = 0;

$items = isset($xml->channel->item) ? $xml->channel->item : array();

foreach ($items as $item) {
    $wp = $item->children($wpNs);
    $excerptNode = $item->children($excerptNs);
    $contentNode = $item->children($contentNs);

    $postType = (string) $wp->post_type;
    $status = (string) $wp->status;
    $title = trim((string) $item->title);

    if ($title === '' || $postType === '') {
        $skipped++;
        continue;
    }

    $existing = get_posts(array(
        'post_type' => $postType,
        'post_status' => 'any',
        'title' => $title,
        'meta_key' => '_a11yhubbr_import_batch',
        'meta_value' => $batch,
        'posts_per_page' => 1,
        'fields' => 'ids',
        'suppress_filters' => true,
    ));
    if (!empty($existing)) {
        $skipped++;
        continue;
    }

    $postDate = (string) $wp->post_date;
    if ($postDate === '') {
        $postDate = current_time('mysql');
    }

    $postarr = array(
        'post_type' => $postType,
        'post_status' => $status !== '' ? $status : 'publish',
        'post_title' => $title,
        'post_content' => (string) $contentNode->encoded,
        'post_excerpt' => (string) $excerptNode->encoded,
        'post_date' => $postDate,
    );

    $cats = array();
    foreach ($item->category as $cat) {
        $attrs = $cat->attributes();
        if ((string) $attrs['domain'] === 'category') {
            $slug = (string) $attrs['nicename'];
            if ($slug !== '') {
                $term = get_term_by('slug', $slug, 'category');
                if ($term && !is_wp_error($term)) {
                    $cats[] = (int) $term->term_id;
                }
            }
        }
    }

    if ($postType === 'post' && !empty($cats)) {
        $postarr['post_category'] = array_values(array_unique($cats));
    }

    $newId = wp_insert_post($postarr, true);
    if (is_wp_error($newId)) {
        $errors++;
        fwrite(STDERR, "Erro ao inserir '{$title}': " . $newId->get_error_message() . "\n");
        continue;
    }

    if (in_array($postType, array('a11y_perfil', 'a11y_evento'), true) && !empty($cats)) {
        wp_set_post_terms($newId, array_values(array_unique($cats)), 'category', true);
    }

    foreach ($wp->postmeta as $metaNode) {
        $metaKey = (string) $metaNode->meta_key;
        $metaVal = (string) $metaNode->meta_value;
        if ($metaKey !== '') {
            update_post_meta($newId, $metaKey, $metaVal);
        }
    }

    update_post_meta($newId, '_a11yhubbr_import_batch', $batch);
    $imported++;
}

echo "Importacao concluida\n";
echo "Importados: {$imported}\n";
echo "Pulados: {$skipped}\n";
echo "Erros: {$errors}\n";
