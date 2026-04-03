---
name: wordpress-seguranca
description: >
  Especialista em cibersegurança WordPress para aplicações com usuários, conteúdo gerado por terceiros e dados pessoais. Use esta skill SEMPRE que o usuário mencionar segurança, hardening, vulnerabilidade, ataque, XSS, CSRF, SQL injection, nonce, sanitização, permissões, roles, autenticação, 2FA, SSL, headers HTTP, firewall, backup, LGPD, dados de usuário, ou qualquer configuração relacionada a proteger o WordPress. Use também proativamente ao criar formulários, endpoints REST, plugins, ou qualquer código que lide com input de usuários ou dados sensíveis. Cobre desde hardening básico até arquitetura segura para hubs sociais.
---

# Segurança WordPress — Hub Social

## Contexto de Ameaça
Hub com registro de usuários públicos, submissão de URLs externas e dados de perfil = superfície de ataque maior que site estático.

**Principais riscos:**
- XSS via links/conteúdo submetido por usuários
- CSRF em ações de perfil e submissão
- Enumeração de usuários
- Spam e abuso de registro
- Injeção via meta fields e REST API
- Escalação de privilégios

---

## 1. wp-config.php — Configuração Segura

```php
<?php
// =============================================
// CHAVES DE SEGURANÇA (gerar em: https://api.wordpress.org/secret-key/1.1/salt/)
// =============================================
define('AUTH_KEY',         'chave-gerada-unica');
define('SECURE_AUTH_KEY',  'chave-gerada-unica');
define('LOGGED_IN_KEY',    'chave-gerada-unica');
define('NONCE_KEY',        'chave-gerada-unica');
define('AUTH_SALT',        'chave-gerada-unica');
define('SECURE_AUTH_SALT', 'chave-gerada-unica');
define('LOGGED_IN_SALT',   'chave-gerada-unica');
define('NONCE_SALT',       'chave-gerada-unica');

// =============================================
// BANCO DE DADOS — prefixo customizado (não 'wp_')
// =============================================
$table_prefix = 'hub2024_'; // Definir ANTES da instalação

// =============================================
// SEGURANÇA
// =============================================
define('DISALLOW_FILE_EDIT', true);      // Bloquear editor de tema/plugin no admin
define('DISALLOW_FILE_MODS', true);      // Bloquear instalação de plugins/temas pelo admin (produção)
define('FORCE_SSL_ADMIN', true);         // Admin só via HTTPS
define('WP_DEBUG', false);               // NUNCA true em produção
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);

// =============================================
// LIMITAR REVISÕES E AUTOSAVE
// =============================================
define('WP_POST_REVISIONS', 5);
define('AUTOSAVE_INTERVAL', 300);

// =============================================
// COOKIE SEGURO
// =============================================
define('COOKIE_DOMAIN', 'cyan-crow-213082.hostingersite.com');
```

---

## 2. Sanitização e Validação — Regra de Ouro

> **Todo input é potencialmente malicioso. Sanitize ao salvar, escape ao exibir.**

### Funções WordPress por contexto

| Dado | Salvar (sanitize) | Exibir (escape) |
|------|------------------|-----------------|
| Texto simples | `sanitize_text_field()` | `esc_html()` |
| Textarea | `sanitize_textarea_field()` | `esc_html()` |
| URL | `sanitize_url()` / `esc_url_raw()` | `esc_url()` |
| Email | `sanitize_email()` | `esc_html()` |
| HTML permitido | `wp_kses($data, $allowed)` | — |
| Inteiro | `absint()` / `intval()` | `intval()` |
| Atributo HTML | — | `esc_attr()` |
| JavaScript | — | `esc_js()` |
| SQL | `$wpdb->prepare()` | — |

### Exemplo: Salvar URL de recurso
```php
function hub_salvar_recurso($post_id, $url_raw) {
    // 1. Validar que é URL real
    $url = esc_url_raw($url_raw);
    if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
        return new WP_Error('url_invalida', 'URL inválida');
    }
    
    // 2. Bloquear protocolos perigosos
    $protocolo = parse_url($url, PHP_URL_SCHEME);
    if (!in_array($protocolo, ['http', 'https'], true)) {
        return new WP_Error('protocolo_invalido', 'Apenas HTTP/HTTPS permitido');
    }
    
    // 3. Salvar
    update_post_meta($post_id, 'hub_url', $url);
}
```

```php
// Exibir no template:
$url = get_post_meta($post_id, 'hub_url', true);
echo '<a href="' . esc_url($url) . '" rel="noopener noreferrer" target="_blank">';
echo esc_html($titulo) . '</a>';
```

---

## 3. Nonces — Proteção CSRF

```php
// === FORMULÁRIO FRONTEND ===
// Ao renderizar:
wp_nonce_field('hub_submeter_recurso', 'hub_nonce');

// Ao processar:
add_action('wp_ajax_hub_submeter', 'hub_processar_recurso');
add_action('wp_ajax_nopriv_hub_submeter', function() {
    wp_send_json_error('Login necessário', 401);
});

function hub_processar_recurso() {
    // SEMPRE verificar nonce primeiro
    if (!check_ajax_referer('hub_submeter_recurso', 'hub_nonce', false)) {
        wp_send_json_error('Requisição inválida', 403);
    }
    // Verificar capabilities
    if (!current_user_can('read')) {
        wp_send_json_error('Sem permissão', 403);
    }
    // ... processar
}
```

```php
// === ENDPOINT REST API ===
add_action('rest_api_init', function() {
    register_rest_route('hub/v1', '/recurso', [
        'methods'             => 'POST',
        'callback'            => 'hub_api_criar_recurso',
        'permission_callback' => function() {
            return is_user_logged_in() && current_user_can('read');
        },
    ]);
});
```

---

## 4. Roles e Capabilities

```php
// Criar role customizada ao ativar plugin
register_activation_hook(__FILE__, 'hub_criar_roles');
function hub_criar_roles() {
    // Role: membro do hub
    add_role('hub_membro', 'Membro Hub', [
        'read'              => true,
        'hub_submeter_link' => true,   // cap customizada
        'hub_editar_perfil' => true,
    ]);
    
    // Role: moderador
    add_role('hub_moderador', 'Moderador Hub', [
        'read'              => true,
        'hub_submeter_link' => true,
        'hub_editar_perfil' => true,
        'hub_moderar'       => true,
        'edit_others_posts' => false,  // não editar posts alheios no admin
    ]);
    
    // Dar caps de moderação ao admin
    $admin = get_role('administrator');
    $admin->add_cap('hub_moderar');
}

// Usar capabilities (nunca checar role diretamente)
if (current_user_can('hub_submeter_link')) {
    // mostrar formulário
}
```

---

## 5. Headers HTTP de Segurança

### Via functions.php ou plugin
```php
add_action('send_headers', function() {
    // Proteção XSS
    header('X-XSS-Protection: 1; mode=block');
    // Evitar MIME sniffing
    header('X-Content-Type-Options: nosniff');
    // Controle de iframe (clickjacking)
    header('X-Frame-Options: SAMEORIGIN');
    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    // Permissions policy
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    // HSTS (só após SSL configurado e testado!)
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    // Content Security Policy (ajustar domínios conforme necessário)
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:;");
});
```

### Via .htaccess (Hostinger/Apache)
```apache
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Bloquear acesso ao wp-config
<Files wp-config.php>
    Require all denied
</Files>

# Bloquear acesso ao xmlrpc se não usar
<Files xmlrpc.php>
    Require all denied
</Files>

# Bloquear listagem de diretórios
Options -Indexes

# Bloquear acesso a arquivos sensíveis
<FilesMatch "\.(log|sql|bak|swp|~)$">
    Require all denied
</FilesMatch>
```

---

## 6. Autenticação e Registro

```php
// Limitar tentativas de login (sem plugin, básico)
add_action('wp_login_failed', function($username) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = 'hub_login_fail_' . md5($ip);
    $tentativas = (int) get_transient($key);
    set_transient($key, $tentativas + 1, 15 * MINUTE_IN_SECONDS);
    
    if ($tentativas >= 5) {
        wp_die('Muitas tentativas. Aguarde 15 minutos.', 'Bloqueado', ['response' => 429]);
    }
});

// Esconder qual campo errou no login
add_filter('login_errors', function() {
    return 'Usuário ou senha incorretos.'; // Não revelar qual dos dois
});

// Desabilitar enumeração de usuários
add_action('template_redirect', function() {
    if (is_author() && !is_user_logged_in()) {
        // Redirecionar para perfil customizado ou 404
        wp_redirect(home_url('/perfil/' . get_queried_object()->user_nicename));
        exit;
    }
});
remove_action('template_redirect', 'redirect_canonical');

// Bloquear enumeração via ?author=1
add_action('init', function() {
    if (isset($_GET['author']) && !is_admin()) {
        wp_redirect(home_url(), 301);
        exit;
    }
});

// Registro com validação extra
add_filter('registration_errors', function($errors, $sanitized_user_login, $user_email) {
    // Bloquear emails temporários (lista parcial)
    $dominios_bloqueados = ['mailinator.com', 'guerrillamail.com', 'temp-mail.org'];
    $dominio = explode('@', $user_email)[1] ?? '';
    if (in_array($dominio, $dominios_bloqueados)) {
        $errors->add('email_invalido', 'Use um email permanente.');
    }
    return $errors;
}, 10, 3);
```

---

## 7. LGPD — Dados Pessoais

```php
// Exportação de dados do usuário (requisito LGPD)
add_filter('wp_privacy_personal_data_exporters', function($exporters) {
    $exporters['hub-dados'] = [
        'exporter_friendly_name' => 'Dados Hub',
        'callback'               => 'hub_exportar_dados_usuario',
    ];
    return $exporters;
});

function hub_exportar_dados_usuario($email, $page = 1) {
    $user = get_user_by('email', $email);
    if (!$user) return ['data' => [], 'done' => true];
    
    $dados = [
        'item_type'  => 'hub_perfil',
        'item_id'    => "user-{$user->ID}",
        'data'       => [
            ['name' => 'Bio', 'value' => get_user_meta($user->ID, 'hub_bio', true)],
            ['name' => 'Links sociais', 'value' => get_user_meta($user->ID, 'hub_links_sociais', true)],
        ],
    ];
    return ['data' => [$dados], 'done' => true];
}

// Política de privacidade — adicionar bloco sugerido
add_filter('wp_get_default_privacy_policy_content', function($content) {
    return $content . '<h2>Dados coletados pelo Hub</h2><p>Coletamos links compartilhados, bio e links de redes sociais informados voluntariamente.</p>';
});
```

---

## 8. Plugins de Segurança Recomendados

| Plugin | Função | Obs |
|--------|--------|-----|
| **Wordfence** (free) | Firewall + scanner de malware | Melhor opção gratuita |
| **WP 2FA** | Autenticação em dois fatores | Obrigatório para admins |
| **UpdraftPlus** | Backup automático | Configurar para Google Drive/S3 |
| **Sucuri Security** | Monitoramento de integridade | Alternativa ao Wordfence |

---

## 9. Checklist de Segurança

### Configuração inicial
- [ ] Prefixo de tabelas customizado (não `wp_`)
- [ ] `DISALLOW_FILE_EDIT` = true no wp-config
- [ ] `WP_DEBUG` = false em produção
- [ ] Chaves de segurança geradas (wp-config)
- [ ] SSL ativo e HSTS configurado
- [ ] xmlrpc.php bloqueado (se não usar Jetpack)
- [ ] `wp-config.php` fora da pasta pública (ou protegido no .htaccess)

### Acesso e autenticação
- [ ] URL do wp-admin customizada (WPS Hide Login)
- [ ] Limite de tentativas de login ativo
- [ ] 2FA para administradores
- [ ] Usuário admin sem username "admin"
- [ ] Senhas fortes forçadas

### Código
- [ ] Nonce em todos os formulários e ações AJAX
- [ ] `sanitize_*` em todo input
- [ ] `esc_*` em todo output
- [ ] `$wpdb->prepare()` em todas as queries SQL diretas
- [ ] Capabilities checadas antes de qualquer ação

### Monitoramento
- [ ] Backup automático diário configurado
- [ ] Notificação de login de admin por email
- [ ] Scan de malware semanal
- [ ] WordPress e plugins sempre atualizados
