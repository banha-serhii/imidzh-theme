# Agent Boundaries

## Goal
Run multiple Cursor agents without merge conflicts or duplicated work.

## Execution Order
1. Wave 1 (parallel): `menu/IA` + `fonts`
2. Wave 2 (after menu): `search` + `yoast-seo`

## File Ownership

### Agent: menu/IA
- Allowed: `inc/menu-setup.php`, `inc/class-mega-menu-walker.php`, `assets/css/mega-menu.css`, `assets/js/main.js` (breakpoint sync only), `THEME.md`
- Avoid: search, fonts, yoast modules; logo and SEO meta logic

### Agent: fonts
- Allowed: `inc/fonts.php`, `assets/css/fonts.css`, `assets/fonts/**`
- Avoid: template markup, menu IA, search, yoast

### Agent: search
- Allowed: `inc/search.php`, `assets/css/search.css`, `assets/js/search.js`, `search.php`, `searchform.php`, minimal insertion in `header.php`
- Avoid: menu seeding logic, walker internals, yoast internals

### Agent: yoast-seo
- Allowed: `inc/yoast-seo.php`
- Avoid: templates, menu, fonts, search UI

## Global Constraints for All Agents
- No legacy or external Wix URLs in code.
- Do not reintroduce old slugs (`/novyny/`, `/prozorist/`, etc.).
- Keep `functions.php` edits minimal; prefer modules in `inc/`.
- Preserve accessibility and keyboard support.

