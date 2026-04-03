---
name: wordpress-acessibilidade
description: >
  Especialista em acessibilidade web para WordPress seguindo WCAG 2.1/2.2 nível AA. Use esta skill SEMPRE que o usuário mencionar acessibilidade, a11y, WCAG, ARIA, screen reader, leitor de tela, contraste, navegação por teclado, foco, alt text, landmark, formulário acessível, auditoria de acessibilidade, ou quando criar/revisar qualquer componente de UI, bloco Gutenberg, tema, formulário ou página no WordPress. Aplique proativamente mesmo que o usuário não mencione acessibilidade — se estiver criando código frontend, inclua práticas acessíveis por padrão. Cobre HTML semântico, ARIA, testes práticos e remediação em contexto WordPress/Gutenberg.
---

# Acessibilidade Web — WordPress Hub

## Contexto
Aplicação hub social com feeds dinâmicos, perfis de usuário e compartilhamento de links. Usuários com deficiências visuais, motoras e cognitivas devem conseguir usar todas as funcionalidades.

**Meta:** WCAG 2.1 Nível AA (mínimo legal e ético no Brasil — LBI 13.146/2015)

---

## 1. Princípios POUR (base do WCAG)

| Princípio | O que significa no Hub |
|-----------|------------------------|
| **Perceptível** | Imagens com alt, vídeos com legendas, contraste adequado |
| **Operável** | Navegável por teclado, sem armadilhas de foco, tempo suficiente |
| **Compreensível** | Linguagem clara, erros explicados, comportamento previsível |
| **Robusto** | HTML válido, ARIA correto, funciona com tecnologias assistivas |

---

## 2. HTML Semântico no WordPress

### Estrutura de página correta
```html
<!-- Template FSE: header.html -->
<header role="banner">
    <nav aria-label="Navegação principal">
        <ul>
            <li><a href="/feed">Feed</a></li>
            <li><a href="/categorias">Categorias</a></li>
        </ul>
    </nav>
</header>

<main id="conteudo-principal" tabindex="-1"> <!-- tabindex para skip link -->
    <!-- conteúdo da página -->
</main>

<footer role="contentinfo">
    <!-- rodapé -->
</footer>
```

### Skip link (OBRIGATÓRIO)
```html
<!-- Primeiro elemento do body -->
<a href="#conteudo-principal" class="skip-link">
    Pular para o conteúdo principal
</a>
```
```css
.skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    background: #000;
    color: #fff;
    padding: 8px 16px;
    z-index: 9999;
    transition: top 0.2s;
}
.skip-link:focus {
    top: 0; /* aparece ao focar com Tab */
}
```

---

## 3. Cards de Recurso Acessíveis

### Card com link correto
```html
<!-- ❌ ERRADO: link genérico -->
<div class="card">
    <h3>Como aprender React</h3>
    <a href="...">Ver mais</a> <!-- "ver mais" não diz nada para screen reader -->
</div>

<!-- ✅ CORRETO -->
<article class="card" aria-labelledby="recurso-42-titulo">
    <h3 id="recurso-42-titulo">Como aprender React</h3>
    <p>Artigo compartilhado por <span>Maria Silva</span></p>
    <a href="https://..." 
       aria-describedby="recurso-42-titulo"
       rel="noopener noreferrer"
       target="_blank">
        Acessar recurso
        <span class="sr-only">"Como aprender React" (abre em nova aba)</span>
    </a>
</article>
```

### Classe sr-only (texto só para screen readers)
```css
.sr-only {
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

## 4. Formulários Acessíveis

### Formulário de submissão de link
```html
<form aria-labelledby="form-titulo">
    <h2 id="form-titulo">Compartilhar recurso</h2>
    
    <!-- Sempre label explícita, nunca só placeholder -->
    <div class="campo">
        <label for="recurso-titulo">
            Título do recurso
            <span aria-hidden="true" class="obrigatorio">*</span>
        </label>
        <input type="text" 
               id="recurso-titulo" 
               name="titulo"
               required
               aria-required="true"
               aria-describedby="recurso-titulo-dica recurso-titulo-erro">
        <span id="recurso-titulo-dica" class="dica">Máximo 100 caracteres</span>
        <!-- Erro — mostrar só quando inválido -->
        <span id="recurso-titulo-erro" 
              class="erro" 
              role="alert" 
              aria-live="polite"
              hidden>
            Título é obrigatório
        </span>
    </div>
    
    <div class="campo">
        <label for="recurso-url">URL do recurso <span aria-hidden="true">*</span></label>
        <input type="url" 
               id="recurso-url" 
               name="url"
               required
               aria-required="true"
               autocomplete="url"
               placeholder="https://...">
    </div>
    
    <div class="campo">
        <fieldset>
            <legend>Categoria</legend>
            <label><input type="radio" name="categoria" value="livro"> Livro</label>
            <label><input type="radio" name="categoria" value="evento"> Evento</label>
            <label><input type="radio" name="categoria" value="curso"> Curso</label>
        </fieldset>
    </div>
    
    <button type="submit">Compartilhar recurso</button>
</form>
```

---

## 5. Feed Dinâmico e ARIA Live Regions

```html
<!-- Feed com loading acessível -->
<section aria-labelledby="feed-titulo">
    <h2 id="feed-titulo">Recursos compartilhados</h2>
    
    <!-- Anuncia mudanças ao carregar mais -->
    <div aria-live="polite" aria-atomic="false" class="sr-only" id="feed-status">
        <!-- JS injeta: "12 recursos carregados" -->
    </div>
    
    <div id="feed-lista" role="list">
        <!-- cards aqui -->
    </div>
    
    <button id="carregar-mais" 
            aria-controls="feed-lista"
            aria-describedby="feed-status">
        Carregar mais recursos
    </button>
</section>
```

```javascript
// Ao carregar mais itens:
function anunciarCarregamento(quantidade) {
    const status = document.getElementById('feed-status');
    status.textContent = `${quantidade} novos recursos carregados`;
    // Limpar após 3s para não poluir
    setTimeout(() => status.textContent = '', 3000);
}
```

---

## 6. Contraste de Cores (WCAG 1.4.3)

| Tipo de texto | Contraste mínimo |
|---------------|-----------------|
| Texto normal (< 18pt) | 4.5:1 |
| Texto grande (≥ 18pt ou 14pt bold) | 3:1 |
| Componentes UI (bordas, ícones) | 3:1 |
| Texto decorativo | Sem requisito |

**Ferramentas:** WebAIM Contrast Checker, Colour Contrast Analyser

```css
/* theme.json — definir palette acessível */
{
    "settings": {
        "color": {
            "palette": [
                { "name": "Primária", "slug": "primary", "color": "#1a5276" },
                { "name": "Primária clara", "slug": "primary-light", "color": "#e8f4fd" },
                { "name": "Texto", "slug": "text", "color": "#1a1a1a" },
                { "name": "Fundo", "slug": "background", "color": "#ffffff" }
            ]
        }
    }
}
```

---

## 7. Navegação por Teclado

### Foco visível (WCAG 2.4.11 — AA no 2.2)
```css
/* NUNCA remover outline sem substituir */
:focus {
    outline: 3px solid #1a5276;
    outline-offset: 2px;
}
/* Para design mais moderno */
:focus-visible {
    outline: 3px solid #1a5276;
    outline-offset: 2px;
    border-radius: 2px;
}
```

### Modal/Dialog acessível
```html
<dialog id="modal-compartilhar" 
        aria-labelledby="modal-titulo"
        aria-describedby="modal-desc">
    <h2 id="modal-titulo">Compartilhar recurso</h2>
    <p id="modal-desc">Preencha os dados do recurso que deseja compartilhar.</p>
    <!-- conteúdo -->
    <button autofocus>Salvar</button>
    <button>Cancelar</button>
</dialog>
```
> Use `<dialog>` nativo — já gerencia foco e trap automaticamente em browsers modernos.

---

## 8. Gutenberg — Blocos Acessíveis

### Verificar acessibilidade no editor
- Instalar plugin **Editoria11y** para auditoria em tempo real no editor
- Usar bloco nativo `core/image` — já tem campo alt integrado
- **Nunca** usar imagens decorativas sem `alt=""` vazio

### Hooks para forçar alt em imagens
```php
// Avisar no admin quando imagem não tem alt
add_filter('wp_get_attachment_image_attributes', function($attr, $attachment) {
    if (empty($attr['alt'])) {
        // Log para auditoria
        error_log("Imagem sem alt: ID {$attachment->ID}");
    }
    return $attr;
}, 10, 2);
```

---

## 9. Testes de Acessibilidade

### Ferramentas gratuitas
| Ferramenta | Uso |
|------------|-----|
| **axe DevTools** (extensão Chrome) | Auditoria automática |
| **NVDA** (Windows, gratuito) | Screen reader real |
| **VoiceOver** (macOS/iOS nativo) | Screen reader Apple |
| **Lighthouse** (Chrome DevTools) | Score + recomendações |
| **WAVE** (extensão) | Visualização de problemas |

### Checklist rápida (testar manualmente)
- [ ] Navegar toda a página só com Tab / Shift+Tab
- [ ] Foco sempre visível e lógico
- [ ] Formulários operáveis sem mouse
- [ ] Imagens têm alt descritivo
- [ ] Erros de formulário anunciados
- [ ] Contraste aprovado em todas as cores
- [ ] Zoom 200% não quebra layout
- [ ] Testar com NVDA no Firefox

---

## 10. Plugin de Acessibilidade Recomendado

**WP Accessibility** (Joe Dolson) — gratuito, adiciona:
- Skip links automáticos
- Remoção de `tabindex` problemáticos
- Diagnóstico de alt text ausente
- Correção de IDs duplicados do WordPress

```php
// Adicionar lang ao html (obrigatório WCAG 3.1.1)
// Já feito pelo WordPress, mas verificar:
add_filter('language_attributes', function($output) {
    // Garantir que pt-BR está correto no wp-config:
    // define('WPLANG', 'pt_BR');
    return $output;
});
```
