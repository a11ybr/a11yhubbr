---
name: wordpress-design
description: >
  Sistema de design do projeto a11yhubbr — hub de acessibilidade brasileiro. Use esta skill SEMPRE que for criar, editar ou revisar qualquer código CSS, HTML, PHP de template, bloco Gutenberg ou componente visual do projeto. Aplique proativamente ao criar botões, cards, formulários, seções, headers, menus, badges, ou qualquer elemento de UI. Garante consistência visual com o design system existente: tokens CSS, tipografia Inter, paleta de cores por contexto, acessibilidade nativa e responsividade.
---

# Design System — a11yhubbr

## Visão Geral
- **Tema:** `a11yhubbr` (FSE/Block Theme)
- **Fonte:** Inter (sistema), fallback: system-ui, -apple-system, Segoe UI, sans-serif
- **Prefixo CSS:** `--a11y-` para variáveis, `.a11yhubbr-` para classes
- **Filosofia:** Minimalista, clean, acolhedor, forte em acessibilidade

---

## 1. Tokens CSS (variáveis :root)

### Cores Base
```css
--a11y-primary: #2f343d          /* Cinza escuro (padrão global) */
--a11y-primary-2: #242931        /* Hover do primary */
--a11y-text: #0f1a2d             /* Texto principal */
--a11y-soft: #f2f3f5             /* Fundo suave */
--a11y-soft-2: #e6e8eb           /* Fundo suave mais escuro */
--a11y-border: #d8dfec           /* Bordas */
--a11y-danger: #d41745           /* Erro/Alerta */
--a11y-primary-contrast: #ffffff /* Texto sobre primary */
```

### Sombra e Radius
```css
--a11y-radius: 0.25rem
--a11y-shadow: 0 8px 20px rgba(11, 32, 78, 0.08)
```

### Gradientes de Marca
```css
--a11y-blue-gradient: linear-gradient(140deg, #2a313d, #171b22)
--a11y-content-gradient: linear-gradient(140deg, #0d2a5f, #1f4f9d)
--a11y-event-gradient: linear-gradient(140deg, #165a3f, #2f9468)
--a11y-network-gradient: linear-gradient(140deg, #7d5a08, #b8891a)
```

### Degrade hero (bordas e banners)
```css
linear-gradient(90deg, #3ca174 0%, #3ca174 74%, #fad47a 100%)
/* Verde (#3ca174) → Amarelo (#fad47a) */
```

---

## 2. Sistema de Contextos (por seção do hub)

O site tem 3 contextos que mudam a cor primária via classe no `<body>`:

| Contexto | Classe body | Primary | Hover bg | Uso |
|----------|-------------|---------|----------|-----|
| **Global** | *(padrão)* | `#2f343d` | `#edf0f4` | Home, perfis |
| **Conteúdos** | `a11yhubbr-context-conteudos` | `#132d6b` | `#edf3ff` | Artigos, links |
| **Eventos** | `a11yhubbr-context-eventos` | `#1f7a53` | `#eaf7f0` | Eventos |
| **Rede** | `a11yhubbr-context-rede` | `#8f6706` | `#fff6dd` | Comunidade, perfis |

**Como usar:**
```php
// No template PHP, adicionar classe ao body:
add_filter('body_class', function($classes) {
    if (is_singular('hub_evento')) $classes[] = 'a11yhubbr-context-eventos';
    if (is_singular('hub_recurso')) $classes[] = 'a11yhubbr-context-conteudos';
    if (is_page('rede') || is_author()) $classes[] = 'a11yhubbr-context-rede';
    return $classes;
});
```

---

## 3. Tipografia

- **Família:** `'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif`
- **Tamanho base:** `18px`
- **Line-height:** `1.55`
- **Headings line-height:** `1.22`
- **Headings letter-spacing:** `-0.01rem`

| Peso | Variável | Uso |
|------|----------|-----|
| 400 | `--a11y-weight-regular` | Corpo |
| 500 | `--a11y-weight-medium` | Destaque sutil |
| 600 | `--a11y-weight-semibold` | Labels, botões, nav |
| 700 | `--a11y-weight-bold` | Headings |

```css
/* Heading responsivo — padrão do projeto */
font-size: clamp(2rem, 1.4rem + 1.8vw, 3.25rem);  /* h1 hero */
font-size: clamp(1.4rem, 1.1rem + 0.8vw, 2rem);    /* section title */
font-size: clamp(1.8rem, 1.2rem + 0.7vw, 2.6rem);  /* card title grande */
```

---

## 4. Botões

### Classes disponíveis
```html
<!-- Variantes -->
<button class="a11yhubbr-btn a11yhubbr-btn-primary">Primário</button>
<button class="a11yhubbr-btn a11yhubbr-btn-secondary">Secundário</button>
<button class="a11yhubbr-btn a11yhubbr-btn-tertiary">Terciário</button>
<button class="a11yhubbr-btn a11yhubbr-btn-alternative">Verde alternativo</button>
<button class="a11yhubbr-btn a11yhubbr-btn-light">Light</button>

<!-- Contextual (herda cor do contexto ativo) -->
<button class="a11yhubbr-btn a11yhubbr-btn-context">CTA contextual</button>
<button class="a11yhubbr-btn a11yhubbr-btn-context-secondary">Secundário contextual</button>
```

### Tokens de botão
```css
--a11y-btn-radius: 0.25rem
--a11y-btn-font-size: 0.95rem
--a11y-btn-font-weight: 600
--a11y-btn-padding-y: 0.72rem
--a11y-btn-padding-x: 1.12rem
--a11y-btn-gap: 0.5rem          /* gap entre ícone e texto */
```

### Cores de botão
```css
/* Primary */  bg: #2f343d  |  text: #fff  |  border: #2f343d
/* Secondary */  bg: #fff  |  text: #2f343d  |  border: #b8c8e4
/* Alternative */  bg: #3ca174  |  text: #fff  |  border: #3ca174
/* Focus */  outline: 2px solid #1f4fa8  |  offset: 2px
```

### Regras de botão
- Sempre `min-width: 9rem`
- Hover: `transform: translateY(-1px)` + escurecer bg
- Disabled: `opacity: 0.58` + `pointer-events: none`
- Sempre incluir `aria-disabled="true"` quando desabilitado

---

## 5. Cards

```html
<!-- Card padrão -->
<article class="a11yhubbr-card">
  <!-- padding: 1.2rem, gap: 0.9rem -->
</article>

<!-- Card feature (para listar funcionalidades) -->
<article class="a11yhubbr-card a11yhubbr-card-feature">
  <div class="a11yhubbr-feature-head">
    <div class="a11yhubbr-feature-icon-box"><!-- ícone --></div>
    <h3>Título</h3>
  </div>
  <p class="a11yhubbr-feature-text">Descrição</p>
</article>
```

### Tokens de card
```css
--a11y-card-bg: #fff
--a11y-card-border: 1px solid #d8dfec
--a11y-card-radius: 0.25rem
--a11y-card-shadow: 0 8px 20px rgba(11, 32, 78, 0.08)
```

### Grid de cards
```css
.a11yhubbr-cards-grid {
  display: grid;
  gap: 1rem;
  /* Responsivo: 1 col mobile → 2 cols tablet → 3 cols desktop */
  grid-template-columns: repeat(auto-fill, minmax(18rem, 1fr));
}
```

---

## 6. Formulários

### Tokens de form
```css
--a11y-form-gap: 1rem                    /* gap entre campos */
--a11y-form-control-height: 3rem
--a11y-form-control-padding-y: 0.78rem
--a11y-form-control-padding-x: 0.92rem
--a11y-form-control-radius: 0.25rem
--a11y-form-control-border: 1px solid #d8dfec
--a11y-form-control-bg: #fff
--a11y-form-focus-outline: 2px solid var(--a11y-primary)
--a11y-form-focus-offset: 2px
--a11y-form-label-weight: 600
--a11y-form-help-size: 0.93rem
```

### Estrutura de seção de formulário
```html
<div class="a11yhubbr-submit-form">
  <div class="a11yhubbr-form-section">          <!-- borda + radius -->
    <button class="a11yhubbr-section-toggle">   <!-- collapsible -->
      Seção do formulário
    </button>
    <div class="a11yhubbr-form-section-body">   <!-- padding: 1rem -->
      <!-- campos aqui -->
    </div>
  </div>
</div>
```

---

## 7. Header e Navegação

```css
/* Header fixo com borda inferior */
.a11yhubbr-site-header {
  border-bottom: 1px solid #d8dfec;
  position: sticky; top: 0; z-index: 30;
}

/* Barra colorida de 3px no topo do body */
body {
  border-top: 3px solid transparent;
  border-image: linear-gradient(90deg, #3ca174 0%, #3ca174 74%, #fad47a 100%) 1;
}

/* Nav items: uppercase, 0.76rem, semibold */
.a11yhubbr-menu a {
  font-size: 0.76rem;
  text-transform: uppercase;
  letter-spacing: 0.02rem;
  font-weight: 600;
}
```

---

## 8. Page Header (Banners de topo de página)

```html
<header class="a11yhubbr-page-header a11yhubbr-page-header--conteudos">
  <div class="a11yhubbr-container">
    <nav class="a11yhubbr-page-breadcrumb" aria-label="Breadcrumb">...</nav>
    <h1 class="a11yhubbr-page-header-title">
      <span class="a11yhubbr-page-header-icon" aria-hidden="true">📚</span>
      Título da página
    </h1>
    <p class="a11yhubbr-page-header-summary">Subtítulo</p>
  </div>
</header>
```

| Modificador | Cor de fundo |
|-------------|-------------|
| `--conteudos` | `#173e88` (azul) |
| `--eventos` | `#238a63` (verde) |
| `--rede` | `#9a710e` (âmbar) |
| *(padrão)* | `#3ca174` + gradiente |

---

## 9. Layout e Espaçamentos

```css
/* Container */
.a11yhubbr-container {
  max-width: 82rem;   /* contentSize do theme.json */
  margin: 0 auto;
  padding: 0 1rem;
}

/* Seções */
.a11yhubbr-section { padding: 2.3rem 0; }
.a11yhubbr-section-soft {
  background: linear-gradient(180deg, #f2f3f5, #ffffff);
  border-top: 1px solid #d8dfec;
  border-bottom: 1px solid #d8dfec;
}
```

### Escala de espaçamento (theme.json)
| Token | Valor |
|-------|-------|
| 2xs | 0.25rem |
| xs | 0.5rem |
| sm | 0.75rem |
| md | 1rem |
| lg | 1.5rem |
| xl | 2rem |
| 2xl | 3rem |

---

## 10. Feedbacks e Toasts

```css
/* Sucesso */
--a11y-toast-success-bg: #e9f8ed
--a11y-toast-success-border: #9ad7a8
--a11y-toast-success-text: #185a2a

/* Erro */
--a11y-toast-error-bg: #fdecec
--a11y-toast-error-border: #f2a8a8
--a11y-toast-error-text: #7f1d1d
```

---

## 11. Regras de Consistência

### ✅ Sempre fazer
- Usar variáveis `--a11y-*` em vez de valores hardcoded
- Prefixar classes com `.a11yhubbr-`
- Respeitar o contexto ativo (`--a11y-primary` muda por contexto)
- `border-radius` sempre `var(--a11y-radius)` (0.25rem)
- Focus: `outline: 2px solid var(--a11y-primary)` + `outline-offset: 2px`
- Transições suaves: `0.16s ease` para hover de cor/borda

### ❌ Nunca fazer
- Não usar `outline: none` sem alternativa visível
- Não hardcodar cores que existem como variável
- Não criar novas classes sem prefixo `a11yhubbr-`
- Não adicionar dark mode (não implementado no projeto)
- Não usar `box-shadow` diferente de `var(--a11y-shadow)`

### Padrão de animação
```css
/* Hover de botões */
transition: background-color 0.16s ease, color 0.16s ease,
            border-color 0.16s ease, transform 0.14s ease;

/* Animações: sempre respeitar */
@media (prefers-reduced-motion: reduce) {
  * { transition: none !important; animation: none !important; }
}
```

---

## 12. Referência de Cores Completa

| Uso | Cor | Hex |
|-----|-----|-----|
| Primary global | Cinza-escuro | `#2f343d` |
| Primary conteúdos | Azul navy | `#132d6b` |
| Primary eventos | Verde | `#1f7a53` |
| Primary rede | Âmbar | `#8f6706` |
| Texto | Quase preto | `#0f1a2d` |
| Muted | Cinza médio | `#596782` |
| Fundo | Branco | `#ffffff` |
| Fundo suave | Cinza muito claro | `#f2f3f5` |
| Borda | Cinza azulado | `#d8dfec` |
| Verde marca | Success/CTA | `#3ca174` |
| Amarelo marca | Detalhe | `#fad47a` |
| Danger | Vermelho | `#d41745` |
