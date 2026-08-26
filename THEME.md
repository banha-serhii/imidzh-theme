# Тема WordPress «Ліцей Імідж»

Модульний blueprint теми на базі однофайлового HTML-шаблону: WCAG 2.1 AA, mega-menu, hero-slider shortcode, адаптивність.

## Структура

```
imidzh-theme/
├── style.css                          # Тема + компоненти (без нових mega-правил)
├── functions.php                      # Setup, enqueue, Customizer, shortcode helper
├── header.php                         # A11y bar, brand, mega-menu
├── footer.php                         # Колонки, контакти, wp_footer
├── front-page.php                     # Hero slider / fallback, новини, переваги
├── page.php                           # Sidebar + контент
├── page-hub.php                       # Хаб розділу: Gutenberg + сітка дітей
├── index.php                          # Архів / блог
├── THEME.md
├── assets/css/mega-menu.css           # Щільність навігації + collapse 1100px + mega-menu--wide
├── assets/css/hub.css                 # Сітка хаба та CTA патернів
├── assets/js/main.js                  # A11y + mega-menu keyboard / mobile (MQ_DESKTOP = 1100px)
├── inc/class-mega-menu-walker.php     # Walker_Nav_Menu для mega-menu
├── inc/menu-setup.php                 # IA, seeding, fallback, enqueue mega-menu.css
├── inc/hub-pages.php                  # Шаблон хабів, стартовий контент, сітка дітей
├── patterns/hub-*.php                 # Gutenberg-патерни вступів хабів
└── template-parts/content-news-card.php
```

## Встановлення

1. Скопіюйте `imidzh-theme` у `wp-content/themes/`.
2. Увімкніть тему в **Зовнішній вигляд → Теми**. При активації `after_switch_theme` один раз створює сторінки та меню (якщо опції `imidzh_ia_seeded` ще немає).
3. Якщо тема вже була активна — **Зовнішній вигляд → Інформаційна архітектура → Створити сторінки та меню**.
4. **Зовнішній вигляд → Налаштувати**: назва, слоган, логотип, shortcode слайдера, контакти.
5. (Рекомендація) **Налаштування → Читання**: сторінку «Новини» (`/news/`) призначте **Сторінкою записів**. Тема не змінює Reading з коду.

## Hero Slider

**Зовнішній вигляд → Налаштувати → Hero / Слайдер** — поле shortcode, напр.:

- `[smartslider3 slider="2"]`
- `[hero_slider]`
- або порожнє / невалідне → показується fallback (заголовок + CTA + віджет `hero-notice`)

Програмно:

```php
add_filter( 'imidzh_hero_slider_shortcode', function () {
	return '[smartslider3 slider="2"]';
} );
```

## Інформаційна архітектура

Джерело правди — англійські slug. «Головна» **не** входить у primary nav: посилання `.brand` веде на `/`.

Батьківські пункти — опубліковані сторінки-хаби (не `href="#"`). Два рівні: батько + діти. Контакти в меню під «Про ліцей», але permalink — `/contacts/`.

### Канонічні permalink

| Пункт | Slug |
|---|---|
| Про ліцей | `/about/` |
| Адміністрація | `/about/administration/` |
| Педагогічний колектив | `/about/staff/` |
| Рада ліцею | `/about/council/` |
| Матеріально-технічна база | `/about/facilities/` |
| Інклюзія та умови доступності | `/about/accessibility/` |
| Вакансії | `/about/vacancies/` |
| Контакти та розташування | `/contacts/` |
| Прозорість та звітність | `/transparency/` |
| Статут закладу | `/transparency/statute/` |
| Ліцензія | `/transparency/license/` |
| Річний звіт керівника | `/transparency/annual-report/` |
| Забезпечення якості освіти | `/transparency/quality-assurance/` |
| Мережа та наповнюваність класів | `/transparency/class-network/` |
| Мова освітнього процесу | `/transparency/language/` |
| Правила внутрішнього розпорядку | `/transparency/internal-regulations/` |
| Фінансовий звіт та кошторис | `/transparency/finance/` |
| Штатний розпис | `/transparency/staffing-table/` |
| Договори та публічні закупівлі | `/transparency/procurement/` |
| Освітній процес | `/education/` |
| Структура навчального року | `/education/academic-year/` |
| Освітні програми та навчальні плани | `/education/curriculum/` |
| Розклади та графіки занять | `/education/timetables/` |
| Дистанційне навчання | `/education/distance-learning/` |
| Електронні підручники | `/education/e-textbooks/` |
| Олімпіади та конкурси | `/education/olympiads/` |
| Підсумкова атестація (НМТ / ДПА) | `/education/assessment/` |
| Учнівське самоврядування | `/education/student-government/` |
| Вступникам та батькам | `/parents/` |
| Правила прийому та територія обслуговування | `/parents/admission/` |
| Графік особистого прийому громадян | `/parents/visiting-hours/` |
| Організація харчування | `/parents/meals/` |
| Правила поведінки учнів | `/parents/code-of-conduct/` |
| Психологічна служба та логопед | `/parents/support-services/` |
| Безпека та захист | `/safety/` |
| Алгоритм дій під час повітряної тривоги | `/safety/air-raid/` |
| Протидія булінгу та омбудсман | `/safety/anti-bullying/` |
| Запобігання домашньому насильству | `/safety/domestic-violence/` |
| Безпечний інтернет | `/safety/safer-internet/` |
| Охорона праці та цивільний захист | `/safety/civil-protection/` |
| Педагогам | `/teachers/` |
| Підвищення кваліфікації | `/teachers/professional-development/` |
| Атестація педагогів | `/teachers/attestation/` |
| Замовлення та вибір підручників | `/teachers/textbooks/` |
| Новини | `/news/` |

### Seed / re-seed

Сторінки **не** створюються на кожному `init`.

1. **Перша активація теми** — хук `after_switch_theme` (якщо опції `imidzh_ia_seeded` немає).
2. **Адмінка** — **Зовнішній вигляд → Інформаційна архітектура** (кнопка + notice, поки не засіяно).
3. **Код** — ідемпотентна `imidzh_seed_information_architecture()` (пошук сторінки за slug, без дублікатів; існуючий контент не перезаписується).

Повторний запуск:

- знову натисніть кнопку на тій самій адмін-сторінці, або
- видаліть опцію `imidzh_ia_seeded` і активуйте тему повторно.

Re-seed **перезбирає пункти меню** «Головне меню», «Футер: Навігація», «Футер: Прозорість». Сторінки з тим самим slug не дублюються. Плейсхолдер: одне речення + «Документи будуть додані.»

Бічне меню (`sidebar`) тема не заповнює — `page.php` уже використовує його як навігацію розділу.

## Hub pages / patterns

Батьківські сторінки розділів (`/about/`, `/transparency/`, `/education/`, `/parents/`, `/safety/`, `/teachers/`, `/contacts/`) використовують шаблон **«Хаб розділу»** (`page-hub.php`): Gutenberg-вступ, потім динамічна сітка опублікованих дочірніх сторінок. На хабі «Про ліцей» у сітку додаються **Контакти** (`/contacts/`). `/news/` — архів записів, не хаб.

Стартовий текст — патерни `patterns/hub-*.php`, категорія редактора **Хаби розділів**. Застосування:

1. Автоматично після активації теми або один раз в адмінці, якщо на хабі ще плейсхолдер / порожньо (`imidzh_hubs_content_version`).
2. **Зовнішній вигляд → Інформаційна архітектура → Оновити контент хабів** (`edit_theme_options`). Повторний запуск не затирає сторінки, які вже змінювали в редакторі.

Редактор: вставити або замінити вступ — **Патерни → Хаби розділів**. PDF та файли документів додавайте на дочірніх сторінках.

Щоб скинути прапорці вручну (WP-CLI):

```bash
wp option delete imidzh_ia_seeded
wp option delete imidzh_hubs_content_version
```

## Mega-Menu: як зібрати в Appearance → Menus

Seed уже створює меню **«Головне меню»** і призначає його на локацію `primary`. Нижче — якщо збираєте вручну.

### Крок 1. Меню «Головне меню» → локація «Головне меню (Mega Menu)»

Не додавайте «Головна». Сім пунктів верхнього рівня: Про ліцей, Прозорість та звітність, Освітній процес, Вступникам та батькам, Безпека та захист, Педагогам, Новини.

### Крок 2. Ієрархія (два рівні)

| Рівень | Призначення |
|--------|-------------|
| 0 | Пункти верхньої панелі (сторінки-хаби) |
| 1 | Дочірні сторінки в панелі |

### Крок 3. Широка панель (6+ дітей)

На батьківському пункті 0-го рівня додайте CSS-клас **`mega-menu--wide`**. Seed ставить його на Про ліцей, Прозорість, Освітній процес. Увімкніть «CSS-класи» в **Параметри екрана**.

Не потрібен старий 3-рівневий `mega-menu--columns` / заголовки колонок — простий список стає двома колонками через CSS.

Legacy: клас `mega-menu--columns` (або `mega`) досі вмикає багатоколонковий режим із заголовками колонок (рівень 2). Для канонічної IA його не використовуйте.

### Футер

- `footer` — Про ліцей, Освітній процес, Новини, Контакти
- `footer_2` — до 6 ключових сторінок прозорості (статут, ліцензія, річний звіт, фінанси, штатний розпис, закупівлі)

Fallback (`imidzh_fallback_menu()` у `inc/menu-setup.php`) виводить ті самі 7 top-level permalink, без «Головна».

### Клавіатура

- `Tab` / `Shift+Tab` — фокус
- `Enter` / `Space` — відкрити / закрити
- `ArrowDown` — у панель
- `ArrowUp` / `ArrowDown` — посилання в панелі
- `ArrowLeft` / `ArrowRight` — сусідні top-level (desktop)
- `Esc` — закрити панель / мобільне меню

Нижче **1100px** меню стає drawer + accordion (панелі в потоці документа). Константа JS: `MQ_DESKTOP` у `assets/js/main.js` = `min-width: 1100px`. Стилі collapse — у `assets/css/mega-menu.css`.

## Інші меню

- **Бічне меню** — `page.php` (або віджети Sidebar); seed його не чіпає
- **Меню в підвалі (Навігація / Прозорість)** — колонки футера

## A11y

- Skip link, `:focus-visible`, контрастний режим, масштаб шрифту (localStorage)
- `prefers-reduced-motion`
- Тригери mega: `button` + `aria-expanded` / `aria-controls` / `aria-haspopup`
- Мінімальна зона кліку ~44px

## Деплой (shared hosting)

Локальні CSS/JS лишаються читабельними. Продакшн-пакет: `npm run build` → `dist/imidzh-theme/`. Upload: `npm run deploy` (SFTP/FTPS, секрети в `.env`).

Повна інструкція, ключі `.env` і rollback: [docs/DEPLOY.md](docs/DEPLOY.md).
