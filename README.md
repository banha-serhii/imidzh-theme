# Ліцей «Імідж» — WordPress theme

Classic PHP theme for [imidzh.uz.ua](https://imidzh.uz.ua/). Feature docs: [THEME.md](THEME.md). Architecture: [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md).

## Local development

Work in this directory (Local WP). CSS and JS stay readable. Do not minify sources in place.

```bash
npm install
npm run build          # → dist/imidzh-theme/ (production copy)
npm run deploy -- --dry-run
```

## Deploy (shared hosting)

Full pipeline, secrets, and rollback: **[docs/DEPLOY.md](docs/DEPLOY.md)**.

| Command | What it does |
|---|---|
| `npm run build` | Sync version, copy + minify into `dist/imidzh-theme/` |
| `npm run deploy` | Patch-bump, build, SFTP/FTPS upload |
| `npm run zip` | `dist/imidzh-theme-x.y.z.zip` for cPanel |

Credentials live in `.env` (gitignored). Copy `.env.example` first.

## Version

Keep these three in sync (the build scripts do it):

- `package.json` → `version`
- `style.css` header → `Version:`
- `functions.php` → `IMIDZH_VERSION` (cache-buster query string)
