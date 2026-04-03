# Auditoria de Melhorias Ã¢â‚¬â€ A11YHubbr
Data: 2026-04-01 | Baseada nas skills: wordpress-acessibilidade, wordpress-seguranca, wordpress-design, wordpress-dev

---

## SumÃƒÂ¡rio Executivo

O projeto tem uma base tÃƒÂ©cnica sÃƒÂ³lida Ã¢â‚¬â€ sanitizaÃƒÂ§ÃƒÂ£o rigorosa, nonces, honeypot, design system consistente e otimizaÃƒÂ§ÃƒÂµes de performance relevantes. Os gaps identificados concentram-se em **acessibilidade de formulÃƒÂ¡rios e navegaÃƒÂ§ÃƒÂ£o por teclado**, **headers HTTP de seguranÃƒÂ§a ausentes**, e algumas **oportunidades de performance** com dependÃƒÂªncias externas.

Severidade usada: Ã°Å¸â€Â´ CrÃƒÂ­tico | Ã°Å¸Å¸Â  Alto | Ã°Å¸Å¸Â¡ MÃƒÂ©dio | Ã°Å¸Å¸Â¢ Baixo

---

## 1. ACESSIBILIDADE (WCAG 2.1 AA)

### Ã°Å¸â€Â´ Skip link ausente (WCAG 2.4.1)
**Arquivo:** `header.php`

NÃƒÂ£o hÃƒÂ¡ link "Pular para o conteÃƒÂºdo principal" como primeiro elemento do `<body>`. Ãƒâ€° requisito mÃƒÂ­nimo WCAG e exigÃƒÂªncia da LBI 13.146/2015.

```html
<!-- Adicionar como PRIMEIRO elemento apÃƒÂ³s <body> em header.php -->
<a href="#conteudo-principal" class="a11yhubbr-skip-link">
    Pular para o conteÃƒÂºdo principal
</a>
```

```css
.a11yhubbr-skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    background: var(--a11y-primary);
    color: #fff;
    padding: 8px 16px;
    z-index: 9999;
    transition: top 0.2s;
    text-decoration: none;
}
.a11yhubbr-skip-link:focus {
    top: 0;
}
```

---

### Ã°Å¸â€Â´ `outline: none` no foco do menu sem substituto adequado (WCAG 2.4.11)
**Arquivo:** `assets/scss/partials/base/_tokens-and-reset.scss` (linha ~239)

O seletor `.a11yhubbr-menu a:focus-visible` remove o outline sem garantir contraste mÃƒÂ­nimo de 3:1 como indicador de foco. MudanÃƒÂ§a de `background` e `border` pode nÃƒÂ£o ser suficiente.

```css
/* ATUAL Ã¢â‚¬â€ problemÃƒÂ¡tico */
.a11yhubbr-menu a:hover,
.a11yhubbr-menu a:focus-visible {
  outline: none; /* Ã¢â€ Â REMOVER ou substituir */
}

/* CORRETO Ã¢â‚¬â€ manter mudanÃƒÂ§a visual E adicionar outline */
.a11yhubbr-menu a:focus-visible {
  background: var(--a11y-menu-hover-bg);
  color: var(--a11y-menu-hover-color);
  border-color: var(--a11y-menu-hover-border);
  outline: 2px solid var(--a11y-primary);
  outline-offset: 2px;
}
```

---

### Ã°Å¸â€Â´ `<main>` sem `id` para o skip link funcionar (WCAG 2.4.1)
**Arquivos:** todos os templates de pÃƒÂ¡gina (`pages/*.php`, `front-page.php`, etc.)

Os templates tÃƒÂªm `<main class="...">` mas sem `id="conteudo-principal"`. O skip link (quando adicionado) nÃƒÂ£o terÃƒÂ¡ destino.

```php
<!-- PadrÃƒÂ£o a adotar em todos os templates -->
<main id="conteudo-principal" class="a11yhubbr-submit-page" tabindex="-1">
```

O `tabindex="-1"` ÃƒÂ© necessÃƒÂ¡rio para que navegadores antigos movam o foco programaticamente ao pular.

---

### Ã°Å¸Å¸Â  Campos `required` sem `aria-required="true"` (WCAG 1.3.1)
**Arquivo:** `pages/page-submeter-conteudo.php` e outros formulÃƒÂ¡rios

HTML5 `required` ÃƒÂ© suportado, mas `aria-required="true"` garante compatibilidade com tecnologias assistivas mais antigas.

```html
<!-- Atual -->
<input id="content-title" type="text" name="title" required>

<!-- Melhorado -->
<input id="content-title" type="text" name="title"
       required aria-required="true"
       aria-describedby="content-title-hint">
<span id="content-title-hint" class="a11yhubbr-help">MÃƒÂ¡ximo 200 caracteres</span>
```

---

### Ã°Å¸Å¸Â  Labels readonly sem `for` associado (WCAG 1.3.1)
**Arquivo:** `pages/page-submeter-conteudo.php` (seÃƒÂ§ÃƒÂ£o "Conta responsÃƒÂ¡vel")

Os campos readonly de Nome e Email nÃƒÂ£o tÃƒÂªm `for` nas labels nem `id` nos inputs:

```html
<!-- Atual Ã¢â‚¬â€ label nÃƒÂ£o associada -->
<label>Nome</label>
<input type="text" value="..." readonly>

<!-- Correto -->
<label for="contact-name">Nome</label>
<input id="contact-name" type="text" value="..." readonly aria-readonly="true">
```

---

### Ã°Å¸Å¸Â  Cards de conteÃƒÂºdo com link genÃƒÂ©rico (WCAG 2.4.6)
**Arquivo:** `inc/components/content-card.php`

O link de aÃƒÂ§ÃƒÂ£o (`Acessar`) ÃƒÂ© o mesmo texto para todos os cards. Screen readers nÃƒÂ£o conseguem distinguir destinos ao navegar por links.

```php
<!-- Atual -->
<a class="a11yhubbr-content-card-action" href="...">
    Acessar
</a>

<!-- Correto Ã¢â‚¬â€ adicionar sr-only com tÃƒÂ­tulo -->
<a class="a11yhubbr-content-card-action" href="<?php echo esc_url($action_url); ?>">
    <?php echo esc_html($action_label); ?>
    <span class="a11yhubbr-sr-only"><?php echo esc_html($title); ?></span>
    <?php if ($show_external_icon): ?>
        <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
        <span class="a11yhubbr-sr-only">(abre em nova aba)</span>
    <?php endif; ?>
</a>
```

A classe `.a11yhubbr-sr-only` ainda precisa ser adicionada ao CSS (padrÃƒÂ£o visually-hidden).

---

### Ã°Å¸Å¸Â  `scroll-behavior: smooth` sem respeitar `prefers-reduced-motion` (WCAG 2.3.3)
**Arquivo:** `assets/scss/partials/base/_tokens-and-reset.scss` (linha ~133)

```css
/* Atual */
html {
  scroll-behavior: smooth;
}

/* Correto */
@media (prefers-reduced-motion: no-preference) {
  html {
    scroll-behavior: smooth;
  }
}
```

---

### Ã°Å¸Å¸Â¡ Headings semÃƒÂ¢nticos no footer questionÃƒÂ¡veis
**Arquivo:** `footer.php`

O footer usa `<h2 class="a11yhubbr-footer-logo">` para o logo e `<h2>` para "PLATAFORMA", "COMUNIDADE", "LEGAL". Headings dentro de `<footer>` sem hierarquia clara podem confundir leitores de tela. Considerar usar `<p>` ou `<strong>` com role adequado para os tÃƒÂ­tulos de seÃƒÂ§ÃƒÂ£o do footer, ou manter h2 apenas para navegaÃƒÂ§ÃƒÂ£o semÃƒÂ¢ntica das seÃƒÂ§ÃƒÂµes.

---

### Ã°Å¸Å¸Â¡ Link de rede social com apenas ÃƒÂ­cone Ã¢â‚¬â€ verificar contraste do foco
**Arquivo:** `footer.php`

Os links de redes sociais tÃƒÂªm `aria-label` correto. Verificar se o estado de foco (outline) tem contraste 3:1 contra o fundo escuro do footer.

---

## 2. SEGURANÃƒâ€¡A

### Ã°Å¸Å¸Â  Headers HTTP de seguranÃƒÂ§a ausentes
**Arquivo:** `inc/core/setup.php` ou `.htaccess`

Nenhum header de seguranÃƒÂ§a foi encontrado no cÃƒÂ³digo. Os mais crÃƒÂ­ticos:

```php
// Adicionar em inc/core/setup.php
add_action('send_headers', function () {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    header('X-XSS-Protection: 1; mode=block');
});
```

Ou equivalente no `.htaccess` (preferÃƒÂ­vel na Hostinger/Apache para performance).

---

### Ã°Å¸Å¸Â  EnumeraÃƒÂ§ÃƒÂ£o de usuÃƒÂ¡rios via `?author=N` nÃƒÂ£o bloqueada
**Arquivo:** `inc/core/security.php`

O arquivo `security.php` cuida do Turnstile, mas nÃƒÂ£o hÃƒÂ¡ bloqueio de enumeraÃƒÂ§ÃƒÂ£o de usuÃƒÂ¡rios via query string.

```php
// Adicionar em inc/core/security.php
add_action('init', function () {
    if (isset($_GET['author']) && !is_admin()) {
        wp_redirect(home_url(), 301);
        exit;
    }
});

// Ocultar qual campo errou no login
add_filter('login_errors', function () {
    return 'UsuÃƒÂ¡rio ou senha incorretos.';
});
```

---

### Ã°Å¸Å¸Â  Sem limitaÃƒÂ§ÃƒÂ£o de tentativas de login
**Arquivo:** ausente

NÃƒÂ£o hÃƒÂ¡ proteÃƒÂ§ÃƒÂ£o nativa contra forÃƒÂ§a bruta. Como estÃƒÂ¡ na Hostinger, verificar se o Wordfence ou LiteSpeed Cache jÃƒÂ¡ fornece isso. Caso contrÃƒÂ¡rio, adicionar ao `security.php`.

---

### Ã°Å¸Å¸Â¡ LGPD Ã¢â‚¬â€ exportaÃƒÂ§ÃƒÂ£o de dados de usuÃƒÂ¡rio nÃƒÂ£o implementada
A skill `wordpress-seguranca` exige exportaÃƒÂ§ÃƒÂ£o via `wp_privacy_personal_data_exporters`. UsuÃƒÂ¡rios do hub tÃƒÂªm bio, links sociais e submissÃƒÂµes vinculadas Ã¢â‚¬â€ todos sÃƒÂ£o dados pessoais LGPD.

```php
add_filter('wp_privacy_personal_data_exporters', function ($exporters) {
    $exporters['a11yhubbr-dados'] = [
        'exporter_friendly_name' => 'Dados A11YHubbr',
        'callback' => 'a11yhubbr_exportar_dados_usuario',
    ];
    return $exporters;
});
```

---

### Ã°Å¸Å¸Â¡ Google Fonts carregado de servidor externo (LGPD)
**Arquivo:** `inc/core/setup.php` (linha ~17)

RequisiÃƒÂ§ÃƒÂµes para `fonts.googleapis.com` transmitem o IP do visitante para o Google, o que pode ser uma violaÃƒÂ§ÃƒÂ£o LGPD sem consentimento explÃƒÂ­cito. RecomendaÃƒÂ§ÃƒÂ£o: auto-hospedar a fonte Inter.

```bash
# Baixar com google-webfonts-helper e colocar em /assets/fonts/
# Depois usar @font-face local
```

---

### Ã°Å¸Å¸Â¡ FontAwesome de CDN sem Subresource Integrity (SRI)
**Arquivo:** `inc/core/setup.php` (linha ~32)

```php
// Atual Ã¢â‚¬â€ sem integridade
wp_enqueue_style('a11yhubbr-fontawesome',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', ...);

// Melhorado Ã¢â‚¬â€ adicionar SRI via wp_style_add_data
wp_style_add_data('a11yhubbr-fontawesome', 'integrity',
    'sha512-[hash-aqui]');
wp_style_add_data('a11yhubbr-fontawesome', 'crossorigin', 'anonymous');
```

---

### Ã°Å¸Å¸Â¢ Secret key do Turnstile visÃƒÂ­vel via `get_option()`
A secret key do Turnstile ÃƒÂ© armazenada em `wp_options`. Considerar mover para constante em `wp-config.php` (jÃƒÂ¡ suportado pelo cÃƒÂ³digo via `A11YHUBBR_TURNSTILE_SECRET_KEY`), documentar essa prÃƒÂ¡tica para o ambiente de produÃƒÂ§ÃƒÂ£o.

---

## 3. PERFORMANCE

### Ã°Å¸Å¸Â  FontAwesome completo carregado (4.000+ ÃƒÂ­cones)
**Arquivo:** `inc/core/setup.php`

FontAwesome "all.min.css" tem ~400KB. O projeto usa ~20 ÃƒÂ­cones. Alternativas:

- **OpÃƒÂ§ÃƒÂ£o A:** Migrar ÃƒÂ­cones para SVG inline (jÃƒÂ¡ em uso nos ÃƒÂ­cones prÃƒÂ³prios)
- **OpÃƒÂ§ÃƒÂ£o B:** Usar kit customizado do FontAwesome com apenas as classes necessÃƒÂ¡rias
- **OpÃƒÂ§ÃƒÂ£o C:** Auto-hospedar apenas os ÃƒÂ­cones usados

---

### Ã°Å¸Å¸Â¡ Cache de rotas sem transient (nÃƒÂ£o persiste entre requests)
**Arquivo:** `inc/core/routing.php`

`a11yhubbr_get_page_url_by_template()` usa `static $cache` Ã¢â‚¬â€ funciona por request mas refaz `get_posts()` em cada novo request. Adicionar transient:

```php
// Exemplo de melhoria
$transient_key = 'a11yhubbr_tpl_url_' . md5($template);
$cached = get_transient($transient_key);
if ($cached !== false) return $cached;
// ... busca
set_transient($transient_key, $url, HOUR_IN_SECONDS);
```

Limpar transients quando pÃƒÂ¡ginas sÃƒÂ£o salvas com `save_post_page`.

---

### Ã°Å¸Å¸Â¡ Busca sem cache (duas queries por pesquisa)
**Arquivo:** `inc/core/content.php` Ã¢â‚¬â€ `a11yhubbr_find_posts_by_term()`

A funÃƒÂ§ÃƒÂ£o executa 2 `get_posts()` sem cache. Para buscas repetidas, adicionar transient com chave baseada em `md5($term . $post_type)`.

---

### Ã°Å¸Å¸Â¢ Google Fonts sem `font-display: swap`
Verificar se a URL de carregamento do Google Fonts inclui `&display=swap`. JÃƒÂ¡ estÃƒÂ¡ na URL atual (`display=swap`), mas vale confirmar que a versÃƒÂ£o auto-hospedada futura tambÃƒÂ©m inclua essa diretiva no `@font-face`.

---

## 4. ARQUITETURA / DEV

### Ã°Å¸Å¸Â  CPTs sem `show_in_rest => true`
**Arquivo:** `inc/core/submissions.php`

Os CPTs `a11y_conteudo`, `a11y_evento` e `a11y_perfil` nÃƒÂ£o declaram `show_in_rest => true`. Isso impede o uso do editor Gutenberg completo e bloqueia a REST API para esses tipos. Mesmo que nÃƒÂ£o use o editor de blocos agora, ÃƒÂ© boa prÃƒÂ¡tica para futuras integraÃƒÂ§ÃƒÂµes.

```php
register_post_type('a11y_conteudo', array(
    // ...
    'show_in_rest' => true, // Ã¢â€ Â adicionar
    // ...
));
```

---

### Ã°Å¸Å¸Â¡ CPTs usando taxonomias nativas `category` e `post_tag`
**Arquivo:** `inc/core/submissions.php`

`a11y_conteudo` e `a11y_perfil` estÃƒÂ£o vinculados ÃƒÂ s taxonomias nativas do WordPress (`category`, `post_tag`). Isso mistura conteÃƒÂºdo do hub com eventual conteÃƒÂºdo de blog. Recomendado criar taxonomias prÃƒÂ³prias (`a11y_tipo`, `a11y_tag`) para isolamento.

---

### Ã°Å¸Å¸Â¡ `functions-legacy.php` ainda no projeto
**Arquivo:** `inc/core/functions-legacy.php`

Arquivo de legado ainda presente no bundle. Verificar se pode ser removido ou se ainda contÃƒÂ©m cÃƒÂ³digo em uso. CÃƒÂ³digo legado nÃƒÂ£o rastreado ÃƒÂ© risco de seguranÃƒÂ§a e manutenÃƒÂ§ÃƒÂ£o.

---

### Ã°Å¸Å¸Â¢ Prefixo de funÃƒÂ§ÃƒÂµes inconsistente com a skill `wordpress-dev`
A skill recomenda prefixo `hub_`, mas o projeto usa `a11yhubbr_` Ã¢â‚¬â€ que ÃƒÂ© mais especÃƒÂ­fico e correto para este contexto. Sem necessidade de mudanÃƒÂ§a; apenas documentar a decisÃƒÂ£o.

---

## 5. DESIGN SYSTEM

### Ã°Å¸Å¸Â¡ Classe `.a11yhubbr-sr-only` ausente no CSS
VÃƒÂ¡rios componentes precisarÃƒÂ£o desta classe para melhorias de acessibilidade. Deve ser adicionada ao `_tokens-and-reset.scss`:

```css
.a11yhubbr-sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}
```

---

### Ã°Å¸Å¸Â¡ Cores hardcoded fora dos tokens em alguns pontos
Exemplos encontrados:
- `color: #1a2440` no menu (poderia ser `var(--a11y-text)` ou novo token)
- `color: #4f5765` em parÃƒÂ¡grafos de cards (poderia ser token `--a11y-muted`)
- `background: #eaedf1` em seÃƒÂ§ÃƒÂµes (vs `var(--a11y-soft)`)

Criar token `--a11y-muted: #596782` e usar de forma consistente.

---

### Ã°Å¸Å¸Â¢ BotÃƒÂ£o "Submeter" no header sem `aria-label` descritivo
**Arquivo:** `header.php` (linha ~59)

O botÃƒÂ£o de CTA "Submeter" no header ÃƒÂ© um `<a>` com ÃƒÂ­cone + texto Ã¢â‚¬â€ estÃƒÂ¡ OK. PorÃƒÂ©m se o ÃƒÂ­cone nÃƒÂ£o carregar, o fallback ÃƒÂ© string vazia. Garantir que o ÃƒÂ­cone SVG inline tenha `aria-hidden="true"` e o texto seja sempre renderizado.

---

## Resumo por Prioridade

| # | Item | ÃƒÂrea | Severidade | Arquivo |
|---|------|------|-----------|---------|
| 1 | Skip link ausente | Acessibilidade | Ã°Å¸â€Â´ | `header.php` |
| 2 | `outline: none` no foco do menu | Acessibilidade | Ã°Å¸â€Â´ | `_tokens-and-reset.scss` |
| 3 | `<main>` sem `id` para skip link | Acessibilidade | Ã°Å¸â€Â´ | Todos os templates |
| 4 | Headers HTTP ausentes | SeguranÃƒÂ§a | Ã°Å¸Å¸Â  | `setup.php` / `.htaccess` |
| 5 | EnumeraÃƒÂ§ÃƒÂ£o de usuÃƒÂ¡rios | SeguranÃƒÂ§a | Ã°Å¸Å¸Â  | `security.php` |
| 6 | `aria-required` nos formulÃƒÂ¡rios | Acessibilidade | Ã°Å¸Å¸Â  | `page-submeter-*.php` |
| 7 | Labels readonly sem `for` | Acessibilidade | Ã°Å¸Å¸Â  | `page-submeter-conteudo.php` |
| 8 | Links de cards genÃƒÂ©ricos | Acessibilidade | Ã°Å¸Å¸Â  | `content-card.php` |
| 9 | LimitaÃƒÂ§ÃƒÂ£o de login brute-force | SeguranÃƒÂ§a | Ã°Å¸Å¸Â  | `security.php` |
| 10 | FontAwesome completo (~400KB) | Performance | Ã°Å¸Å¸Â  | `setup.php` |
| 11 | `scroll-behavior` sem reduced-motion | Acessibilidade | Ã°Å¸Å¸Â  | `_tokens-and-reset.scss` |
| 12 | LGPD Ã¢â‚¬â€ exportaÃƒÂ§ÃƒÂ£o de dados | SeguranÃƒÂ§a | Ã°Å¸Å¸Â¡ | Novo arquivo |
| 13 | Google Fonts externo (LGPD) | SeguranÃƒÂ§a/Perf | Ã°Å¸Å¸Â¡ | `setup.php` |
| 14 | Cache de routing sem transient | Performance | Ã°Å¸Å¸Â¡ | `routing.php` |
| 15 | CPTs sem `show_in_rest` | Dev | Ã°Å¸Å¸Â  | `submissions.php` |
| 16 | Classe `.a11yhubbr-sr-only` ausente | Design System | Ã°Å¸Å¸Â¡ | `_tokens-and-reset.scss` |
| 17 | Cores hardcoded fora de tokens | Design System | Ã°Å¸Å¸Â¡ | CSS parcials |
| 18 | LGPD Ã¢â‚¬â€ exportaÃƒÂ§ÃƒÂ£o de dados | SeguranÃƒÂ§a | Ã°Å¸Å¸Â¡ | Novo mÃƒÂ³dulo |
| 19 | `functions-legacy.php` | Dev | Ã°Å¸Å¸Â¡ | `inc/core/` |
| 20 | SRI no FontAwesome | SeguranÃƒÂ§a | Ã°Å¸Å¸Â¡ | `setup.php` |

---

## O que estÃƒÂ¡ bem Ã¢Å“â€¦

- SanitizaÃƒÂ§ÃƒÂ£o rigorosa em todas as submissÃƒÂµes (`sanitize_*` + `esc_*`)
- Nonces em todos os formulÃƒÂ¡rios
- Honeypot implementado
- Cloudflare Turnstile integrado
- Design system consistente com tokens CSS
- Sistema de contextos bem implementado
- `prefers-reduced-motion` nos botÃƒÂµes e animaÃƒÂ§ÃƒÂ£o do hero
- OtimizaÃƒÂ§ÃƒÂµes de performance (dequeue de jQuery, emoji, oEmbed, block styles)
- `filemtime` para cache-busting de assets
- Scripts com `defer`
- `rel="noopener noreferrer"` em todos os links externos
- `aria-label` no nav, menu toggle e busca
- `aria-hidden` em ÃƒÂ­cones decorativos
- `role="alert"` / `role="status"` nos toasts
- Landmarks semÃƒÂ¢nticos corretos (header, nav, main, aside, footer)
- Flush de rewrite rules controlado
- Prefixo consistente `a11yhubbr_` em todo o projeto
