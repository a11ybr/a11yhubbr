<?php
/**
 * SEO + Schema.org (JSON-LD)
 * Meta tags Open Graph, Twitter Card, canonical e dados estruturados
 * para todos os tipos de conteúdo do hub.
 */

if (!defined('ABSPATH')) {
    exit;
}


// Injetar meta tags no <head>
add_action('wp_head', 'a11yhubbr_inject_seo_head', 1);

// Injetar schemas JSON-LD no <head>
add_action('wp_head', 'a11yhubbr_inject_schema', 5);

// Separador do title tag
add_filter('document_title_separator', function () {
    return '—';
});


/* =============================================
   META TAGS
   ============================================= */

function a11yhubbr_inject_seo_head() {
    // Ceder para Yoast/RankMath se estiver ativo
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }

    $title       = a11yhubbr_seo_get_title();
    $description = a11yhubbr_seo_get_description();
    $image       = a11yhubbr_seo_get_image();
    $url         = a11yhubbr_seo_get_canonical();
    $site_name   = get_bloginfo('name');

    // Canonical
    echo '<link rel="canonical" href="' . esc_url($url) . '">' . "\n";

    // Meta description
    echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";

    // Robots
    echo '<meta name="robots" content="' . esc_attr(a11yhubbr_seo_get_robots()) . '">' . "\n";

    // Open Graph
    echo '<meta property="og:type"        content="' . esc_attr(a11yhubbr_seo_get_og_type()) . '">' . "\n";
    echo '<meta property="og:title"       content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url"         content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:site_name"   content="' . esc_attr($site_name) . '">' . "\n";
    echo '<meta property="og:locale"      content="pt_BR">' . "\n";

    if ($image) {
        $img_ext  = strtolower(pathinfo(wp_parse_url($image['url'], PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
        $img_mime = ($img_ext === 'jpg' || $img_ext === 'jpeg') ? 'image/jpeg' : 'image/' . ($img_ext ?: 'png');
        echo '<meta property="og:image"        content="' . esc_url($image['url']) . '">' . "\n";
        echo '<meta property="og:image:type"   content="' . esc_attr($img_mime) . '">' . "\n";
        echo '<meta property="og:image:width"  content="' . esc_attr((string) $image['width']) . '">' . "\n";
        echo '<meta property="og:image:height" content="' . esc_attr((string) $image['height']) . '">' . "\n";
        echo '<meta property="og:image:alt"    content="' . esc_attr($image['alt']) . '">' . "\n";
    }

    // Twitter Card
    echo '<meta name="twitter:card"        content="summary_large_image">' . "\n";
    echo '<meta name="twitter:site"        content="@a11yhubbr">' . "\n";
    echo '<meta name="twitter:title"       content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    if ($image) {
        echo '<meta name="twitter:image"     content="' . esc_url($image['url']) . '">' . "\n";
        echo '<meta name="twitter:image:alt" content="' . esc_attr($image['alt']) . '">' . "\n";
    }
}


/* =============================================
   HELPERS DE SEO
   ============================================= */

function a11yhubbr_seo_get_title() {
    $site = get_bloginfo('name');

    if (is_singular('a11y_conteudo') || is_singular('a11y_evento') || is_singular('a11y_perfil')) {
        return get_the_title() . ' — ' . $site;
    }
    if (is_front_page()) {
        return $site . ' — Hub de Acessibilidade Digital';
    }
    if (is_page()) {
        return get_the_title() . ' — ' . $site;
    }
    if (is_404()) {
        return 'Página não encontrada — ' . $site;
    }

    return wp_title('—', false, 'right') . $site;
}


function a11yhubbr_seo_get_description() {
    global $post;

    if (is_singular() && $post) {
        if (!empty($post->post_excerpt)) {
            return wp_strip_all_tags($post->post_excerpt);
        }
        $meta_desc = get_post_meta($post->ID, '_a11yhubbr_meta_description', true);
        if ($meta_desc) {
            return sanitize_text_field((string) $meta_desc);
        }
        return wp_trim_words(wp_strip_all_tags($post->post_content), 30, '…');
    }

    if (is_front_page()) {
        return 'Hub colaborativo de acessibilidade digital em português. Conteúdos, eventos e perfis da comunidade brasileira de a11y.';
    }

    return get_bloginfo('description');
}


function a11yhubbr_seo_get_image() {
    global $post;

    if (is_singular() && $post && has_post_thumbnail($post->ID)) {
        $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
        if ($img) {
            $alt = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true);
            return array(
                'url'    => $img[0],
                'width'  => $img[1],
                'height' => $img[2],
                'alt'    => $alt ?: get_the_title(),
            );
        }
    }

    // Imagem OG customizada (meta field)
    if (is_singular() && $post) {
        $ext_img = get_post_meta($post->ID, '_a11yhubbr_og_image', true);
        if ($ext_img) {
            return array(
                'url'    => esc_url_raw((string) $ext_img),
                'width'  => 1200,
                'height' => 630,
                'alt'    => get_the_title(),
            );
        }
    }

    // Fallback: imagem padrão do site
    $default = get_template_directory_uri() . '/assets/img/og-default.png';
    return array(
        'url'    => $default,
        'width'  => 1200,
        'height' => 630,
        'alt'    => get_bloginfo('name'),
    );
}


function a11yhubbr_seo_get_canonical() {
    if (is_singular()) {
        return (string) get_permalink();
    }
    if (is_front_page()) {
        $paged = (int) get_query_var('paged');
        return $paged > 1 ? (string) get_pagenum_link($paged) : home_url('/');
    }
    if (is_post_type_archive() || is_category() || is_tag() || is_tax()) {
        $paged = (int) get_query_var('paged');
        return $paged > 1 ? (string) get_pagenum_link($paged) : (string) get_term_link(get_queried_object());
    }
    if (is_page()) {
        return (string) get_permalink();
    }
    global $wp;
    return home_url(add_query_arg(array(), $wp->request));
}


function a11yhubbr_seo_get_og_type() {
    if (is_singular('a11y_conteudo')) return 'article';
    if (is_singular('a11y_perfil'))  return 'profile';
    return 'website';
}


function a11yhubbr_seo_get_robots() {
    // Páginas privadas/operacionais: noindex
    $noindex_templates = array(
        'pages/page-submeter.php',
        'pages/page-submeter-conteudo.php',
        'pages/page-submeter-eventos.php',
        'pages/page-submeter-perfil.php',
        'pages/page-minhas-submissoes.php',
    );

    foreach ($noindex_templates as $tpl) {
        if (is_page_template($tpl)) {
            return 'noindex, nofollow';
        }
    }

    if (is_404() || is_search()) {
        return 'noindex, follow';
    }

    return 'index, follow';
}


/* =============================================
   SCHEMA.ORG JSON-LD
   ============================================= */

function a11yhubbr_inject_schema() {
    $schemas = array();

    // WebSite + Organization apenas na homepage
    if (is_front_page()) {
        $schemas[] = a11yhubbr_schema_website();
        $schemas[] = a11yhubbr_schema_organization();
    }

    // BreadcrumbList em páginas internas
    if (!is_front_page()) {
        $breadcrumb = a11yhubbr_schema_breadcrumb();
        if ($breadcrumb) {
            $schemas[] = $breadcrumb;
        }
    }

    // Schema por tipo de conteúdo
    if (is_singular('a11y_conteudo')) {
        $schemas[] = a11yhubbr_schema_article();
    } elseif (is_singular('a11y_evento')) {
        $schemas[] = a11yhubbr_schema_event();
    } elseif (is_singular('a11y_perfil')) {
        $schemas[] = a11yhubbr_schema_person();
    } elseif (is_page()) {
        $schemas[] = a11yhubbr_schema_webpage();
    }

    foreach (array_filter($schemas) as $schema) {
        echo '<script type="application/ld+json">' . "\n";
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON-LD encoded via wp_json_encode
        echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        echo "\n</script>\n";
    }
}


function a11yhubbr_schema_website() {
    return array(
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        'name'            => get_bloginfo('name'),
        'url'             => home_url('/'),
        'inLanguage'      => 'pt-BR',
        'description'     => get_bloginfo('description'),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'       => 'EntryPoint',
                'urlTemplate' => home_url('/busca?busca={search_term_string}'),
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );
}


function a11yhubbr_schema_organization() {
    return array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => 'A11YBR',
        'url'      => home_url('/'),
        'logo'     => array(
            '@type' => 'ImageObject',
            'url'   => get_template_directory_uri() . '/assets/img/logo-a11ybr.svg',
        ),
        'sameAs'   => array(
            'https://github.com/wagnerbeethoven/a11yhubbr',
            'https://bsky.app/profile/a11yhubbr.bsky.social',
            'https://x.com/a11yhubbr',
            'https://linkedin.com/company/a11yhubbr',
            'https://instagram.com/a11yhubbr',
        ),
    );
}


function a11yhubbr_schema_article() {
    global $post;
    if (!$post) return null;

    $author_id   = (int) $post->post_author;
    $author_name = get_the_author_meta('display_name', $author_id);
    $type_slug   = get_post_meta($post->ID, '_a11yhubbr_content_type', true);
    $schema_type = in_array($type_slug, array('artigos', 'cursos-materiais'), true)
        ? 'LearningResource'
        : 'Article';

    $schema = array(
        '@context'      => 'https://schema.org',
        '@type'         => $schema_type,
        'headline'      => get_the_title(),
        'description'   => a11yhubbr_seo_get_description(),
        'url'           => get_permalink(),
        'inLanguage'    => 'pt-BR',
        'datePublished' => get_the_date('c'),
        'dateModified'  => get_the_modified_date('c'),
        'author'        => array('@type' => 'Person', 'name' => $author_name),
        'publisher'     => array(
            '@type' => 'Organization',
            'name'  => 'A11YBR',
            'url'   => home_url('/'),
        ),
        'isAccessibleForFree'  => true,
        'accessibilityFeature' => array('readingOrder', 'structuralNavigation'),
    );

    $external_url = get_post_meta($post->ID, '_a11yhubbr_link', true);
    if ($external_url) {
        $schema['mainEntityOfPage'] = esc_url((string) $external_url);
    }

    $image = a11yhubbr_seo_get_image();
    if ($image) {
        $schema['image'] = $image['url'];
    }

    $tags = wp_get_post_tags($post->ID, array('fields' => 'names'));
    if (!empty($tags)) {
        $schema['keywords'] = implode(', ', $tags);
    }

    return $schema;
}


function a11yhubbr_schema_event() {
    global $post;
    if (!$post) return null;

    $start_date   = get_post_meta($post->ID, '_a11yhubbr_event_start', true);
    $end_date     = get_post_meta($post->ID, '_a11yhubbr_event_end', true);
    $location     = get_post_meta($post->ID, '_a11yhubbr_event_location', true);
    $event_url    = get_post_meta($post->ID, '_a11yhubbr_link', true);
    $is_online    = (bool) get_post_meta($post->ID, '_a11yhubbr_event_online', true);
    $organization = get_post_meta($post->ID, '_a11yhubbr_organization', true);

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Event',
        'name'        => get_the_title(),
        'description' => a11yhubbr_seo_get_description(),
        'url'         => get_permalink(),
        'inLanguage'  => 'pt-BR',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'organizer'   => array(
            '@type' => 'Organization',
            'name'  => $organization ?: 'A11YBR',
        ),
    );

    if ($start_date) $schema['startDate'] = sanitize_text_field((string) $start_date);
    if ($end_date)   $schema['endDate']   = sanitize_text_field((string) $end_date);

    if ($event_url) {
        $schema['eventAttendanceMode'] = $is_online
            ? 'https://schema.org/OnlineEventAttendanceMode'
            : 'https://schema.org/OfflineEventAttendanceMode';
    }

    if ($is_online) {
        $schema['location'] = array(
            '@type' => 'VirtualLocation',
            'url'   => esc_url($event_url ?: get_permalink()),
        );
    } elseif ($location) {
        $schema['location'] = array(
            '@type' => 'Place',
            'name'  => sanitize_text_field((string) $location),
        );
    }

    $image = a11yhubbr_seo_get_image();
    if ($image) $schema['image'] = $image['url'];

    return $schema;
}


function a11yhubbr_schema_person() {
    global $post;
    if (!$post) return null;

    $bio          = get_post_meta($post->ID, '_a11yhubbr_bio', true);
    $role         = get_post_meta($post->ID, '_a11yhubbr_role', true);
    $social_links = get_post_meta($post->ID, '_a11yhubbr_social_links', true);

    $person = array(
        '@type' => 'Person',
        '@id'   => get_permalink() . '#person',
        'name'  => get_the_title(),
        'url'   => get_permalink(),
    );

    if ($bio)  $person['description'] = wp_strip_all_tags((string) $bio);
    if ($role) $person['jobTitle']    = sanitize_text_field((string) $role);

    $image = a11yhubbr_seo_get_image();
    if ($image) $person['image'] = $image['url'];

    if (is_array($social_links) && !empty($social_links)) {
        $person['sameAs'] = array_map('esc_url', $social_links);
    }

    return array(
        '@context'   => 'https://schema.org',
        '@type'      => 'ProfilePage',
        'url'        => get_permalink(),
        'inLanguage' => 'pt-BR',
        'name'       => get_the_title(),
        'mainEntity' => $person,
    );
}


function a11yhubbr_schema_webpage() {
    global $post;
    if (!$post) return null;

    return array(
        '@context'    => 'https://schema.org',
        '@type'       => 'WebPage',
        'name'        => get_the_title(),
        'description' => a11yhubbr_seo_get_description(),
        'url'         => get_permalink(),
        'inLanguage'  => 'pt-BR',
        'isPartOf'    => array('@id' => home_url('/')),
    );
}


function a11yhubbr_schema_breadcrumb() {
    $items = array(
        array(
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => 'Página inicial',
            'item'     => home_url('/'),
        ),
    );

    if (is_singular('a11y_conteudo')) {
        $items[] = array('@type' => 'ListItem', 'position' => 2, 'name' => 'Conteúdos', 'item' => home_url('/conteudos'));
        $items[] = array('@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => (string) get_permalink());
    } elseif (is_singular('a11y_evento')) {
        $items[] = array('@type' => 'ListItem', 'position' => 2, 'name' => 'Eventos', 'item' => home_url('/eventos'));
        $items[] = array('@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => (string) get_permalink());
    } elseif (is_singular('a11y_perfil')) {
        $items[] = array('@type' => 'ListItem', 'position' => 2, 'name' => 'Rede', 'item' => home_url('/rede'));
        $items[] = array('@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => (string) get_permalink());
    } elseif (is_page() && !is_front_page()) {
        $items[] = array('@type' => 'ListItem', 'position' => 2, 'name' => get_the_title(), 'item' => (string) get_permalink());
    }

    if (count($items) <= 1) return null;

    return array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    );
}
