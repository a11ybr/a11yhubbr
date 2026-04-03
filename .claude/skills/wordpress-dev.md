---
name: wordpress-dev
description: >
  Especialista em desenvolvimento WordPress para aplicações hub/social com compartilhamento de links, perfis de usuário e conteúdo dinâmico. Use esta skill SEMPRE que o usuário mencionar WordPress, WP, tema, plugin, hook, shortcode, Gutenberg, bloco, CPT, taxonomia, WP_Query, REST API, wp-config, functions.php, ou qualquer desenvolvimento/configuração WordPress. Use também para debugging, performance, estrutura de banco de dados WP e integração com APIs externas. Cobre todo o stack: temas custom, plugins, Full Site Editing, e arquitetura de aplicações sociais/hub no WordPress.
---

# WordPress Dev — Hub Social

## Contexto do Projeto
Aplicação hub onde usuários compartilham links (eventos, livros, materiais online) e exibem perfis públicos. Hospedado na Hostinger.

**Stack assumido:**
- WordPress + Full Site Editing (FSE / Gutenberg)
- Tema custom ou block theme
- Custom Post Types para links/recursos
- User roles customizados
- Hostinger (PHP 8.x, MySQL)

---

## 1. Arquitetura Recomendada

### Custom Post Types (CPT)
```php
// Em plugin custom ou functions.php do tema
function hub_register_cpts() {
    // CPT: Recurso compartilhado
    register_post_type('hub_recurso', [
        'labels'        => ['name' => 'Recursos', 'singular_name' => 'Recurso'],
        'public'        => true,
        'show_in_rest'  => true, // obrigatório para Gutenberg
        'supports'      => ['title', 'editor', 'author', 'custom-fields'],
        'menu_icon'     => 'dashicons-admin-links',
        'rewrite'       => ['slug' => 'recursos'],
    ]);
}
add_action('init', 'hub_register_cpts');
```

### Taxonomias
```php
register_taxonomy('hub_categoria', 'hub_recurso', [
    'hierarchical' => true,
    'show_in_rest' => true,
    'rewrite'      => ['slug' => 'categoria'],
]);
// Valores: eventos, livros, cursos, artigos, ferramentas
```

### Campos de Usuário Extendidos
```php
// Adicionar campos ao perfil
add_action('show_user_profile', 'hub_extra_user_fields');
add_action('edit_user_profile', 'hub_extra_user_fields');
function hub_extra_user_fields($user) {
    ?>
    <h3>Perfil Hub</h3>
    <table class="form-table">
        <tr>
            <th><label for="hub_bio">Bio Curta</label></th>
            <td><textarea name="hub_bio" id="hub_bio" rows="3" class="regular-text"><?php echo esc_textarea(get_user_meta($user->ID, 'hub_bio', true)); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="hub_links_sociais">Links Sociais (JSON)</label></th>
            <td><input type="text" name="hub_links_sociais" value="<?php echo esc_attr(get_user_meta($user->ID, 'hub_links_sociais', true)); ?>" class="regular-text"></td>
        </tr>
    </table>
    <?php
}
add_action('personal_options_update', 'hub_save_user_fields');
add_action('edit_user_profile_update', 'hub_save_user_fields');
function hub_save_user_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) return;
    update_user_meta($user_id, 'hub_bio', sanitize_textarea_field($_POST['hub_bio']));
    // Para JSON, validar antes de salvar
    $links = sanitize_text_field($_POST['hub_links_sociais']);
    if (json_decode($links) !== null) {
        update_user_meta($user_id, 'hub_links_sociais', $links);
    }
}
```

---

## 2. Hooks Essenciais para Hub Social

### Filtrar conteúdo por autor (perfil público)
```php
// URL: /perfil/{username}
add_action('init', function() {
    add_rewrite_rule('^perfil/([^/]+)/?$', 'index.php?hub_perfil=$matches[1]', 'top');
    add_rewrite_tag('%hub_perfil%', '([^&]+)');
});

add_filter('template_include', function($template) {
    if (get_query_var('hub_perfil')) {
        $custom = get_template_directory() . '/templates/perfil.php';
        return file_exists($custom) ? $custom : $template;
    }
    return $template;
});
```

### Limitar o que usuários podem submeter
```php
add_action('save_post_hub_recurso', function($post_id, $post) {
    // Só o próprio autor pode editar
    if ($post->post_author != get_current_user_id() && !current_user_can('manage_options')) {
        wp_die('Sem permissão para editar este recurso.');
    }
    // Sanitizar URL do recurso
    $url = get_post_meta($post_id, 'hub_url', true);
    if ($url && !filter_var($url, FILTER_VALIDATE_URL)) {
        delete_post_meta($post_id, 'hub_url');
    }
}, 10, 2);
```

---

## 3. REST API para Frontend Dinâmico

### Endpoint customizado para feed
```php
add_action('rest_api_init', function() {
    register_rest_route('hub/v1', '/feed', [
        'methods'             => 'GET',
        'callback'            => 'hub_api_feed',
        'permission_callback' => '__return_true', // feed público
        'args' => [
            'categoria' => ['sanitize_callback' => 'sanitize_text_field'],
            'page'      => ['sanitize_callback' => 'absint', 'default' => 1],
        ],
    ]);
});

function hub_api_feed($request) {
    $args = [
        'post_type'      => 'hub_recurso',
        'posts_per_page' => 12,
        'paged'          => $request->get_param('page'),
        'post_status'    => 'publish',
    ];
    if ($cat = $request->get_param('categoria')) {
        $args['tax_query'] = [['taxonomy' => 'hub_categoria', 'field' => 'slug', 'terms' => $cat]];
    }
    $query = new WP_Query($args);
    $posts = array_map(function($post) {
        return [
            'id'       => $post->ID,
            'titulo'   => $post->post_title,
            'url'      => get_post_meta($post->ID, 'hub_url', true),
            'autor'    => get_the_author_meta('display_name', $post->post_author),
            'data'     => $post->post_date,
        ];
    }, $query->posts);
    return rest_ensure_response(['posts' => $posts, 'total' => $query->found_posts]);
}
```

---

## 4. Gutenberg / Full Site Editing

### Registrar bloco customizado (PHP side)
```php
add_action('init', function() {
    register_block_type(__DIR__ . '/blocks/card-recurso');
});
```

### Estrutura de bloco (`block.json`)
```json
{
    "apiVersion": 3,
    "name": "hub/card-recurso",
    "title": "Card de Recurso",
    "category": "hub",
    "supports": { "html": false, "align": ["wide", "full"] },
    "attributes": {
        "postId": { "type": "number" },
        "showAuthor": { "type": "boolean", "default": true }
    },
    "editorScript": "file:./index.js",
    "render": "file:./render.php"
}
```

### Dicas FSE
- Use `theme.json` para tokens de design (cores, tipografia, espaçamento)
- Blocos de query loop (`core/query`) para feeds dinâmicos sem JS
- Template parts: `header.html`, `footer.html`, `single-hub_recurso.html`

---

## 5. Performance na Hostinger

```php
// Cache de queries pesadas com transients
function hub_get_feed_cache($categoria = '') {
    $key = 'hub_feed_' . md5($categoria);
    $cached = get_transient($key);
    if ($cached !== false) return $cached;
    
    // ... WP_Query aqui ...
    set_transient($key, $resultado, 5 * MINUTE_IN_SECONDS);
    return $resultado;
}

// Limpar cache ao publicar novo recurso
add_action('save_post_hub_recurso', function() {
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_hub_feed_%'");
});
```

**Plugins recomendados para Hostinger:**
- LiteSpeed Cache (compatível com servidor Hostinger)
- WP Offload Media (se usar S3/CloudFlare para assets)

---

## 6. Checklist de Desenvolvimento

- [ ] CPTs com `show_in_rest => true` para Gutenberg funcionar
- [ ] Nonces em todos os formulários frontend
- [ ] `sanitize_*` em todo input de usuário
- [ ] `esc_*` em todo output
- [ ] Capacidades customizadas com `add_role()` / `add_cap()`
- [ ] Flush de rewrite rules após registrar CPTs (`flush_rewrite_rules()` só uma vez)
- [ ] `wp_enqueue_scripts` para scripts (nunca hardcode no header)
- [ ] Prefixo único em funções, classes e opções (`hub_`)

---

## Referências
- Ver `references/hooks-wp.md` para lista de hooks úteis
- Ver `references/woocommerce.md` se adicionar monetização no futuro
