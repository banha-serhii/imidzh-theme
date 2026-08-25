# Деплой теми на shared hosting

Локальні CSS/JS **не** мініфікуються в git. `npm run build` збирає продакшн-копію в `dist/imidzh-theme/`. На сервер іде лише цей каталог (не весь Local WP).

## Швидкий старт

```bash
cd wp-content/themes/imidzh-theme
npm install
cp .env.example .env
# заповніть .env — див. ключі нижче

npm run build
npm run deploy -- --dry-run
npm run deploy
```

Після реального деплою **закомітьте bump версії** (`package.json`, `style.css`, `functions.php`).

## Команди

| Команда | Дія |
|---|---|
| `npm run build` | Синхронізує версію, копіює тему в `dist/imidzh-theme/`, мініфікує CSS/JS **лише в dist** |
| `npm run build -- --version 1.2.0` | Встановлює точну версію, потім збирає |
| `npm run bump` | Patch-бамп (`1.1.0` → `1.1.1`) у трьох файлах |
| `npm run bump -- --level minor` | Minor (`1.1.0` → `1.2.0`) |
| `npm run bump -- --level major` | Major (`1.1.0` → `2.0.0`) |
| `npm run bump -- --version 1.2.0` | Точна версія |
| `npm run release:patch` / `release:minor` / `release:major` | Бамп + build (без upload) |
| `npm run deploy` | Patch-бамп → build → upload |
| `npm run deploy -- --dry-run` | Перевіряє `.env` і показує список файлів **без** запису версії й **без** upload |
| `npm run deploy -- --no-bump` | Upload поточної версії |
| `npm run deploy -- --level minor` | Minor-бамп, потім upload |
| `npm run deploy -- --version 1.2.0` | Точна версія, потім upload |
| `npm run deploy -- --delete` | Після upload видаляє на сервері файли, яких немає в dist (**не** типово) |
| `npm run zip` | `dist/imidzh-theme-x.y.z.zip` для ручного завантаження в cPanel |

Прапорці після `npm run … --` обов’язкові: без `--` npm з’їсть аргументи.

## Версії (cache-busting)

Одне джерело правди. Скрипти завжди пишуть **однаково** в:

1. `package.json` → `"version"`
2. `style.css` → рядок `Version:` у Theme Header
3. `functions.php` → `define( 'IMIDZH_VERSION', 'x.y.z' );`

WordPress підставляє `IMIDZH_VERSION` як `?ver=` для `style.css`, `assets/css/*`, `assets/js/*`. Після деплою жорстке оновлення має підтягнути новий `?ver=`.

`npm run deploy` за замовчуванням бампає **patch**. `--dry-run` версію в робочому дереві **не** змінює.

## Що потрапляє в dist

**Є:** PHP-шаблони, `inc/`, `patterns/`, мініфіковані `style.css` + `assets/css|js`, шрифти `.woff2`, оптимізовані зображення (`logo.webp` / `logo.png` / favicon), `README.md`, `THEME.md`.

**Немає:** `.git`, `.cursor`, `docs/`, `node_modules/`, `package.json` / lock, `scripts/`, `.env*`, source maps, `assets/img/logo-source.jpg`, `.DS_Store`.

Заголовок WordPress у `style.css` (`Theme Name`, `Version`, …) зберігається зверху; тіло CSS/JS мініфікується через esbuild (без source maps). Відносні `url(...)` у `@font-face` зберігаються.

Enqueue-шляхи в PHP не змінюються: у dist ті самі імена файлів із мініфікованим вмістом.

## `.env` (секрети)

Файл `.env` у git **не** комітиться. Скопіюйте `.env.example`.

| Ключ | Опис |
|---|---|
| `DEPLOY_PROTOCOL` | `sftp` (рекомендовано) або `ftps` |
| `DEPLOY_HOST` | Хост (без `sftp://`) |
| `DEPLOY_PORT` | Типово `22` для SFTP, `21` для FTPS |
| `DEPLOY_USER` | Логін |
| `DEPLOY_PASSWORD` | Пароль (порожній, якщо ключ) |
| `DEPLOY_PRIVATE_KEY` | Шлях до OpenSSH-ключа (SFTP), абсолютний або `~/…` |
| `DEPLOY_PRIVATE_KEY_PASSPHRASE` | Passphrase ключа, якщо є |
| `DEPLOY_REMOTE_PATH` | Каталог теми на хості, напр. `/home/USER/public_html/wp-content/themes/imidzh-theme` |
| `DEPLOY_TLS_REJECT_UNAUTHORIZED` | FTPS: `false` лише якщо сертифікат хоста self-signed |

Обов’язкові: `DEPLOY_HOST`, `DEPLOY_USER`, `DEPLOY_REMOTE_PATH`, плюс **або** пароль, **або** ключ (для SFTP). Для FTPS потрібен пароль.

Приклад:

```
DEPLOY_PROTOCOL=sftp
DEPLOY_HOST=imidzh.uz.ua
DEPLOY_PORT=22
DEPLOY_USER=imidzh
DEPLOY_PRIVATE_KEY=~/.ssh/id_ed25519
DEPLOY_REMOTE_PATH=/home/imidzh/public_html/wp-content/themes/imidzh-theme
```

## Транспорт

Основний шлях — **SFTP** (`ssh2-sftp-client`, працює на macOS без `lftp`). Альтернатива — **FTPS** (`basic-ftp`). `git push` **не** є деплоєм на shared hosting.

Upload рекурсивний, існуючі файли теми перезаписуються. Видалення «зайвих» файлів на сервері лише з `--delete` (типово вимкнено, щоб не стерти випадкові remote-файли).

## Як перевірити локально

1. `npm install` — має завершитись без помилок.
2. `npm run build` — з’являється `dist/imidzh-theme/`; у `style.css` є `Theme Name` і `Version:`; CSS/JS у dist коротші за джерела; `style.css` / `assets/js/main.js` у **репозиторії** лишаються читабельними.
3. `npm run deploy -- --dry-run` без `.env` — чітка помилка про відсутні ключі.
4. З заповненим `.env`: dry-run друкує хост, шлях і список файлів, **без** upload і **без** бампу.
5. Реальний `npm run deploy` — лише коли `.env` вказує на staging або прод.

Чеклист після upload (скрипт друкує його в кінці):

- [ ] Скинути page cache / CDN / кеш хостингу
- [ ] Hard refresh (Cmd+Shift+R)
- [ ] У вихідному коді сторінки `?ver=x.y.z` збігається з `IMIDZH_VERSION`
- [ ] Mega-menu, пошук, локальні шрифти

## Rollback

```bash
npm run zip
```

Архів `dist/imidzh-theme-x.y.z.zip` розпакувати в `wp-content/themes/` (має з’явитись каталог `imidzh-theme`). Зберігайте zip попередньої версії до підтвердження прод-релізу.

Або залийте попередній `dist/imidzh-theme/` тим самим `npm run deploy -- --no-bump` після `git checkout` потрібного тегу/коміту і `npm run build`.

## Рекомендації (прод)

- **Staging:** окремий піддомен + окремий `.env` / `DEPLOY_REMOTE_PATH`. Не деплойте в прод, доки немає dry-run.
- **CI:** GitHub Actions може робити `npm run build` + `zip` як артефакт; upload з CI лише через секрети репозиторію, ніколи через committed `.env`.
- **Кеш:** після деплою скинути LiteSpeed / WP Super Cache / host opcode cache. OPcache на PHP-FPM інколи тримає старий `functions.php` кілька секунд — hard refresh + purge.
- **Object cache:** Redis/Memcached не замінює `?ver=` для статики, але корисно для WP.
- **CDN:** якщо статика на CDN, purge `style.css` і `assets/**` або покладайтесь на новий `?ver=`.
- **`node_modules/`** ніколи не завантажуйте на shared hosting — скрипт їх відсікає.
- **`--delete`:** увімкніть лише коли впевнені, що remote = dist. Інакше можна стерти файли, покладені вручну.
- **Text Domain:** у PHP використовується `imidzh`, у заголовку `style.css` досі `imidzh-2-0`. Для перекладів вирівняйте Text Domain на `imidzh`.
- **`screenshot.png`:** у темі немає скріншота для екрана «Зовнішній вигляд → Теми». Додайте `screenshot.png` (рекомендовано 1200×900) у корінь теми.
- **Ключі SSH:** окремий deploy-ключ з обмеженням лише SFTP, не root-пароль у `.env`.
- **Права:** файли теми `644`, каталоги `755`; `wp-config.php` не чіпати цим пайплайном.
