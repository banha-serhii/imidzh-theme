# Тема WordPress «Ліцей Імідж»

Модульний blueprint теми на базі однофайлового HTML-шаблону: WCAG 2.1 AA, mega-menu, hero-slider shortcode, адаптивність.

## Структура

```
imidzh-theme/
├── style.css                          # Тема + усі стилі (BEM-подібні блоки)
├── functions.php                      # Setup, enqueue, Customizer, shortcode helper
├── header.php                         # A11y bar, brand, mega-menu
├── footer.php                         # Колонки, контакти, wp_footer
├── front-page.php                     # Hero slider / fallback, новини, переваги
├── page.php                           # Sidebar + контент
├── index.php                          # Архів / блог
├── assets/js/main.js                  # A11y + mega-menu keyboard / mobile
├── inc/class-mega-menu-walker.php     # Walker_Nav_Menu для mega-menu
└── template-parts/content-news-card.php
```

## Встановлення

1. Скопіюйте `imidzh-theme` у `wp-content/themes/`.
2. Увімкніть тему в **Зовнішній вигляд → Теми**.
3. **Зовнішній вигляд → Налаштувати**: назва, слоган, логотип, shortcode слайдера, контакти.
4. Призначте меню (див. нижче).

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

## Mega-Menu: як зібрати в Appearance → Menus

### Крок 1. Створіть меню і призначте локацію «Головне меню (Mega Menu)»

### Крок 2. Ієрархія

| Рівень | Призначення |
|--------|-------------|
| 0 | Пункти верхньої панелі: Головна, Про ліцей, Навчальний процес… |
| 1 | Або посилання простого dropdown, або **заголовки колонок** mega-menu |
| 2 | Посилання всередині колонки |

### Крок 3. Увімкніть multi-column mega

На **батьківському** пункті 0-го рівня додайте CSS-клас:

- `mega-menu--columns` (або коротко `mega`)

Увімкніть «CSS-класи» в **Параметри екрана** редактора меню.

### Приклад структури

```
Про ліцей                    ← клас: mega-menu--columns
├── Про заклад               ← колонка
│   ├── Історія
│   ├── Адміністрація
│   └── Педагогічний колектив
├── Структура                ← колонка
│   ├── Відділення
│   └── Класи
└── Документи                ← колонка
    ├── Статут
    └── Ліцензія

Навчальний процес            ← клас: mega-menu--columns
├── Освітні програми
│   ├── Базова освіта
│   └── Профільна освіта
├── Розклад
│   ├── Уроки
│   └── Дзвінки
└── Оцінювання
    └── Критерії

Прозорість                   ← без класу mega → звичайний dropdown
├── Фінансова звітність
├── Запобігання булінгу
└── Публічна інформація

Батькам та учням             ← mega-menu--columns
├── Батькам
│   ├── Оголошення
│   └── Збори
└── Учням
    ├── Гуртки
    └── Харчування
```

### Клавіатура

- `Tab` / `Shift+Tab` — фокус
- `Enter` / `Space` — відкрити / закрити
- `ArrowDown` — у панель
- `ArrowUp` / `ArrowDown` — посилання в панелі
- `ArrowLeft` / `ArrowRight` — сусідні top-level (desktop)
- `Esc` — закрити панель / мобільне меню

На `<768px` меню стає drawer + accordion (панелі в потоці документа, без CLS від absolute overlay).

## Інші меню

- **Бічне меню** — `page.php` (або віджети Sidebar)
- **Меню в підвалі (Навігація / Прозорість)** — колонки футера

## A11y

- Skip link, `:focus-visible`, контрастний режим, масштаб шрифту (localStorage)
- `prefers-reduced-motion`
- Тригери mega: `button` + `aria-expanded` / `aria-controls` / `aria-haspopup`
- Мінімальна зона кліку ~44px
