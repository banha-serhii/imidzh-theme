# Imidzh Theme Architecture

## Purpose
WordPress classic theme for a public education institution with strong accessibility, legal transparency navigation, and maintainable modular code.

## Core Principles
- Keep business logic in `inc/*.php`; keep templates thin.
- Avoid editing `functions.php` for feature growth; load modules from `inc/`.
- Preserve WCAG 2.1 AA behavior (keyboard, focus, semantic landmarks).
- Keep SEO ownership clear: Yoast owns meta/canonical; theme extends safely.

## Current Module Map
- `inc/class-mega-menu-walker.php` - mega-menu rendering and accessibility structure.
- `inc/menu-setup.php` - menu seeding, fallback updates, nav assignment.
- `inc/search.php` - header search integration, assets, search behavior.
- `inc/fonts.php` - self-hosted font loading and Google Fonts deactivation.
- `inc/yoast-seo.php` - Yoast-safe schema and SEO defaults.

## URL and Slug Policy
- Use English canonical slugs for public URLs (for consistency and SEO).
- Never use legacy slugs like `/novyny/` or temporary external Wix links.
- Parent menu items must be real pages, not `#`.

## Navigation Policy
- Primary menu excludes `Головна` (logo is home link).
- Top-level IA: About, Transparency, Education, Parents, Safety, Teachers, News.
- Mobile and desktop must share one source of truth for menu items.

## Breakpoint Policy
- Header/nav collapse should happen before wrapping or overlap.
- JS and CSS breakpoints for navigation must stay synchronized.

## Asset Policy
- Prefer optimized local assets (`webp/png`) for theme graphics.
- Avoid remote runtime dependencies unless strictly required.

