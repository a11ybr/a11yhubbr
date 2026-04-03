# Sass Architecture

This folder uses a single entrypoint:

- `main.scss`

Build output:

- `../../style.css`

Build commands:

```bash
npm run build:css
npm run watch:css
```

## Layers

- `base/*`: tokens, reset and global tuning.
- `layout/*`: shared shells and minimal legacy compatibility.
- `pages/*`: page-specific contexts.
- `components/*`: reusable UI modules.
- `utilities/*`: temporary overrides only.

## Current map

- `base/_tokens-and-reset.scss`: design tokens and global defaults.
- `base/_global-tuning.scss`: type scale, radii and cross-page tuning.
- `layout/_site-shell.scss`: app shell, container and base navigation skeleton.
- `layout/_section-shell.scss`: hero and section structure shared across pages.
- `layout/_legacy-compat.scss`: compatibility placeholder kept for import stability.
- `pages/_home.scss`: home-specific grid and card behavior.
- `pages/_archives.scss`: archive page context.
- `pages/_about.scss`: about page context.
- `pages/_contact.scss`: contact page context.
- `pages/_legal.scss`: legal page context.
- `pages/_single-and-submit.scss`: single templates and submission flows.
- `components/_site-header.scss`: header skin and responsive header behavior.
- `components/_hero-and-page-header.scss`: home hero and page-header visual treatment.
- `components/_archive-filters.scss`: shared archive filters and toolbar states.
- `components/_cards-and-content.scss`: shared cards, metadata and list surfaces.
- `components/_feature-cards.scss`: feature cards and rich-content support.
- `components/_footer-and-newsletter.scss`: footer and newsletter module.

## Maintenance rules

- Prefer moving styles to the owning layer instead of adding to `utilities/_tuning.scss`.
- Keep `layout/_legacy-compat.scss` as small as possible.
- Do not edit `style.css` manually.
- When a component is reused across routes, it belongs in `components/*`.
- When a rule only exists because of one template, it belongs in `pages/*`.
