# Auditoria Técnica (Arquivos + Banco)

Data: 2026-03-08

## Escopo executado

1. Varredura de caracteres quebrados (mojibake) em arquivos PHP do tema.
2. Varredura heurística de CSS potencialmente não utilizado.
3. Varredura heurística de funções PHP potencialmente sem referência direta.
4. Tentativa de varredura de banco (posts/metas/opções/termos).

## Resultado da varredura de arquivos

- Foi detectado e corrigido problema de caractere inválido (`�`) no formulário da página de contato.
- Após correção, não restaram ocorrências de `�` nos arquivos PHP.

## Resultado da varredura de banco

A varredura automatizada no banco **não pôde ser executada nesta sessão** por limitação do PHP CLI local:

- erro: extensão `mysqli` ausente no CLI (WordPress não inicializa via `wp-load.php`).

Script pronto para execução quando o CLI estiver com `mysqli`:
- `tools/scan-mojibake-db.php`

Comando:
```bash
php tools/scan-mojibake-db.php
```

## CSS potencialmente legado (heurístico)

Observação: lista com falso-positivo possível (classes usadas dinamicamente por WP/JS).

- `a11yhubbr-about-avatar`
- `a11yhubbr-about-checklist`
- `a11yhubbr-about-crosslist`
- `a11yhubbr-about-location`
- `a11yhubbr-about-report-card`
- `a11yhubbr-about-role`
- `a11yhubbr-about-tag`
- `a11yhubbr-about-team-card`
- `a11yhubbr-about-team-social`
- `a11yhubbr-brand-flag`
- `a11yhubbr-brand-word`
- `a11yhubbr-brand-word-green`
- `a11yhubbr-brand-word-primary`
- `a11yhubbr-btn-li-link`
- `a11yhubbr-btn-link`
- `a11yhubbr-btn-outline`
- `a11yhubbr-community-cta`
- `a11yhubbr-content-control-label`
- `a11yhubbr-content-item-author`
- `a11yhubbr-content-item-ext`
- `a11yhubbr-content-item-inline-links`
- `a11yhubbr-content-reset-link`
- `a11yhubbr-context-conteudos`
- `a11yhubbr-context-eventos`
- `a11yhubbr-context-rede`
- `a11yhubbr-error`
- `a11yhubbr-event-card-cta`
- `a11yhubbr-event-card-datetime`
- `a11yhubbr-footer-social`
- `a11yhubbr-home-collab-badge`
- `a11yhubbr-home-newsletter`
- `a11yhubbr-list-card`
- `a11yhubbr-page-header--conteudos`
- `a11yhubbr-page-header--eventos`
- `a11yhubbr-page-header--rede`
- `a11yhubbr-search-form`
- `a11yhubbr-search-reset-wrap`
- `a11yhubbr-search-results-head`
- `a11yhubbr-single-source-btn`
- `a11yhubbr-submit-hub-flow`
- `a11yhubbr-submit-hub-step-number`
- `a11yhubbr-submit-hub-steps`
- `a11yhubbr-success`
- `a11yhubbr-text-link`

## PHP potencialmente legado (heurístico)

Observação: a maioria aparece como “sem referência direta” porque é acionada por `add_action` / `add_filter`, então **não deve ser removida automaticamente**.

Itens detectados (amostra):
- `a11yhubbr_enqueue_assets`
- `a11yhubbr_theme_setup`
- `a11yhubbr_register_submission_cpts`
- `a11yhubbr_handle_form_submissions`
- `a11yhubbr_seed_legal_pages_once`
- `a11yhubbr_page_slug_template_fallback`
- `a11yhubbr_virtual_busca_template_fallback`
- `a11yhubbr_columns_post_content`
- `a11yhubbr_columns_event_content`
- `a11yhubbr_columns_profile_content`

## Recomendação de limpeza segura

1. Rodar cobertura real de rotas (home, arquivos, singles, submeter, busca).
2. Marcar classes usadas por render dinâmico antes de remover CSS.
3. Remover CSS em lote pequeno e validar visual por página.
4. Só remover função PHP após confirmar que não está ligada a hook.

## Lote removido (fase 8)

Foi removido um primeiro lote de CSS legado com baixo risco (sem referência em PHP/JS e sem uso nas telas atuais):

- `a11yhubbr-about-avatar`
- `a11yhubbr-about-checklist`
- `a11yhubbr-about-crosslist`
- `a11yhubbr-about-location`
- `a11yhubbr-about-report-card`
- `a11yhubbr-about-role`
- `a11yhubbr-about-tag`
- `a11yhubbr-about-team-card`
- `a11yhubbr-about-team-social`

Arquivos alterados:
- `assets/scss/partials/pages/_archives.scss`
- `assets/scss/partials/utilities/_tuning.scss`
- `style.css` (recompilado)

## Lote removido (fase 8 - lote 2)

Foi removido um segundo lote conservador de CSS legado (sem referência em PHP/JS e sem classes dinâmicas de contexto):

- `a11yhubbr-brand-word`
- `a11yhubbr-brand-word-primary`
- `a11yhubbr-brand-word-green`
- `a11yhubbr-brand-flag`
- `a11yhubbr-btn-outline`
- `a11yhubbr-btn-link`
- `a11yhubbr-btn-li-link`
- `a11yhubbr-home-collab-badge`
- `a11yhubbr-content-control-label`
- `a11yhubbr-content-reset-link`
- `a11yhubbr-event-card-datetime`
- `a11yhubbr-event-card-cta`
- `a11yhubbr-content-item-author`
- `a11yhubbr-community-cta`
- `a11yhubbr-single-source-btn`
- `a11yhubbr-content-item-ext`
- `a11yhubbr-content-item-inline-links`
- `a11yhubbr-search-results-head`
- `a11yhubbr-search-reset-wrap`
- `a11yhubbr-search-form`
- `a11yhubbr-home-newsletter`
- `a11yhubbr-list-card`
- `a11yhubbr-text-link`
- `a11yhubbr-submit-hub-flow`
- `a11yhubbr-submit-hub-steps`
- `a11yhubbr-submit-hub-step-number`
- `a11yhubbr-footer-social`

Arquivos alterados:
- `assets/scss/partials/base/_tokens-and-reset.scss`
- `assets/scss/partials/layout/_header-and-nav.scss`
- `assets/scss/partials/pages/_home.scss`
- `assets/scss/partials/pages/_single-and-submit.scss`
- `assets/scss/partials/utilities/_tuning.scss`
- `style.css` (recompilado)

## Lote removido (fase 8 - lote 3)

Remoção final de classes sem referência literal em PHP/JS (após varredura completa dos seletores `a11yhubbr-*`):

- `a11yhubbr-success`
- `a11yhubbr-error`

Observação:
- As classes `a11yhubbr-context-*` e `a11yhubbr-page-header--*` permanecem no CSS porque são geradas dinamicamente no PHP (não aparecem como string literal completa nos templates).

Arquivos alterados:
- `assets/scss/partials/pages/_home.scss`
- `style.css` (recompilado)
