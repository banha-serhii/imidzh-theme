# QA Checklist

## Navigation
- [ ] Primary menu has canonical sections and correct local slugs.
- [ ] No `#` parent links for top-level menu pages.
- [ ] No legacy slugs in rendered markup.
- [ ] Desktop menu does not wrap under logo.
- [ ] Mobile menu and keyboard navigation work.

## Search
- [ ] Desktop search is discoverable and keyboard accessible.
- [ ] Mobile search is accessible from drawer flow.
- [ ] `/?s=тест` returns valid results page.

## SEO
- [ ] Yoast remains owner of title/canonical/meta tags.
- [ ] No duplicated Organization schema blobs.
- [ ] Canonical URLs use new slug map.

## Accessibility
- [ ] Focus visible is clear on nav, search, and buttons.
- [ ] Skip link works.
- [ ] Contrast mode remains readable after new components.

## Performance
- [ ] No Google Fonts network requests after fonts migration.
- [ ] Header/logo loads without layout shift spikes.

