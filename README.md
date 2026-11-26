# ECWRD Institutional — WordPress theme

Bilingual (English / Arabic) theme for the Emirates Council for Work Relation Development, rebuilt from the
Emergent prototype (`ecwrd-institutional.preview.emergentagent.com`) as a classic WordPress theme. Every piece of
copy, every leader profile, the logo, contact details and social links are editable in wp-admin.

- **Requirements:** WordPress 6.5+ · PHP 7.4+ · [Secure Custom Fields](https://wordpress.org/plugins/secure-custom-fields/) (free) **or** ACF PRO
- **No build step.** Plain PHP, CSS and JavaScript — edit and refresh.

---

## 1. Local setup (LocalWP)

1. Create a new site in LocalWP (PHP 8.2+ recommended).
2. Put this repository in the site's themes folder **as a real folder, not a symlink** (LocalWP's push to WP Engine
   does not follow symlinks):
   ```bash
   cd ~/Local\ Sites/<site>/app/public/wp-content/themes
   git clone git@github-eightysix:HensalDeon/ECWRD.git ecwrd-institutional
   ```
3. In wp-admin: **Plugins → Add New → “Secure Custom Fields”** → install and activate.
   - If the free *Advanced Custom Fields* plugin is active, deactivate it first. It lacks repeaters and
     options pages. SCF is a drop-in replacement and reads the same data.
4. **Appearance → Themes → ECWRD Institutional → Activate.**
5. **Tools → ECWRD Setup → Run setup.** This:
   - creates the *Home*, *About Us* and *Leadership* pages and sets Home as the front page;
   - fills all English and Arabic content;
   - adds the 8 leader profiles with portraits (copied into the Media Library);
   - builds the *Primary — English* and *Primary — Arabic* menus;
   - switches permalinks to “Post name” (required for the `/ar/` URLs).

   It is safe to run again; existing content is kept unless “Overwrite” is ticked.
   WP-CLI equivalent: `wp ecwrd import [--overwrite]`.

## 2. Deploying to WP Engine (via LocalWP)

Use LocalWP's **Push** to the connected WP Engine environment, including the **database** on the first push
(pages, fields, leaders and menus live in the database). After the first push:

- Install/activate **Secure Custom Fields** on WP Engine if it isn't already there.
- Visit **Settings → Permalinks** and click **Save** once to refresh rewrite rules (the theme also flushes
  them automatically on activation).
- Clear WP Engine's cache. English and Arabic have separate URLs, so page caching is safe.

For later code-only changes, push files only so you don't overwrite content the client has edited on production.

## 3. Editing content (client guide)

| What | Where |
| --- | --- |
| Home / About / Leadership copy | **Pages → (page) →** *English* and *Arabic — العربية* tabs |
| People on the Leadership page | **Leaders** → add/edit. Portrait = *Featured image* (3:4). Section = *Board* or *Executives*. Order = *Page Attributes → Order*. |
| Logo, sharing image, phone, email, social links | **Site Settings → General / Social** (empty social link = icon hidden) |
| Footer text, organisation name, 404 page, default SEO description | **Site Settings →** *English* / *Arabic* tabs |
| Navigation | **Appearance → Menus** (*Primary — English*, *Primary — Arabic*) |
| Search titles & descriptions | Each page → *Search engine (SEO)* box in each language tab |
| Any other page (e.g. Privacy Policy) | Normal editor for English + the *Arabic Version* box below it |

- Headings marked *“One line per row”* animate line by line — press Enter where the line should break.
- `{year}` in the copyright line is replaced with the current year.

## 4. How it works

### Languages

English is served at `/`, Arabic under `/ar/` (`/ar/about/`, `/ar/leadership/`…). The `/ar/` routes are rewrite
rules (`inc/i18n.php`). While browsing in Arabic, page links, menus and canonicals are rewritten automatically,
`<html>` gets `lang="ar" dir="rtl"`, and `hreflang` alternates are output. With plain permalinks the theme falls
back to `?ecwrd_lang=ar`.

The prototype stored the language in `localStorage`. That doesn't work with full-page caching (WP Engine would
serve one visitor's language to everyone) and isn't indexable, so each language now has its own URL.

### Content model

```
inc/defaults.php   Default EN/AR copy + leaders — seed data AND front-end fallback
inc/fields.php     One schema per area → generates English + Arabic field tabs (registered in code)
inc/content.php    ecwrd_content(), ecwrd_setting(), ecwrd_leaders()… — templates only use these
```

Fields are stored as `content_en` / `content_ar` groups. Templates never call `get_field()` directly: the
accessors merge saved values over `defaults.php`, so the site renders fully even before import or if the fields
plugin is deactivated. To add a field, add it to the schema in `inc/fields.php` and the matching default in
`inc/defaults.php`; it appears in both language tabs automatically.

### Files

```
functions.php              Bootstrap (loads inc/*)
header.php / footer.php    Chrome: fixed header, mobile menu, footer
front-page.php             Home
page-templates/about.php   “About” template
page-templates/leadership.php
page.php / index.php / 404.php
inc/i18n.php               /ar/ routing, URL localisation, hreflang, RTL
inc/post-types.php         “Leaders” post type + admin columns
inc/seo.php                Titles, meta description, Open Graph (steps aside if Yoast/Rank Math/etc. is active)
inc/admin.php              Dependency notice, editor cleanup, admin RTL inputs
inc/importer.php           Tools → ECWRD Setup + `wp ecwrd import`
inc/setup.php              Supports, menus, image sizes, asset loading
inc/template-tags.php      Markup helpers (reveal headings, portraits, icons, language switch)
assets/css/main.css        All front-end styles (tokens, logical properties for RTL)
assets/js/main.js          Smooth scroll, header, menu, reveals, sand hero, page transitions
assets/fonts/              Self-hosted Outfit, Manrope, Alexandria (no Google Fonts requests)
assets/vendor/lenis/       Lenis 1.3.26 smooth scroll (MIT)
assets/seed/               Leader portraits used by the importer
```

### Animations (parity with the prototype)

| Prototype (React) | Theme |
| --- | --- |
| Framer Motion `RevealLines` / `FadeIn` | `[data-reveal="lines"]` / `[data-reveal="fade"]` + IntersectionObserver, same durations, delays and easing (`cubic-bezier(.16,1,.3,1)`) |
| Route fade (enter 16px / exit −12px, 0.5s) | `.page` enter animation + exit before navigating; hovered links are prefetched |
| Lenis (`duration: 1.15`) | Same library and options |
| Sand-particle canvas hero | Ported 1:1 to `initSandHero()` |

Reveal styles only apply once JS has booted (`html.js`), with a 4-second failsafe, so content can never stay
hidden. `prefers-reduced-motion` disables motion.

## 5. Fixes vs. the prototype

- **Section headings never appeared.** The scroll reveal watched the clipped inner line, which never
  intersects the viewport. The heading itself is observed now.
- **Header collision at 768–~900px.** The desktop links overlapped the centred logo. The desktop nav now
  starts at 1024px, with the menu button below that.
- **Squashed logo on phones.** The absolutely positioned logo was compressed to half the viewport width (236 → 195px).
  It now keeps its aspect ratio, and is slightly smaller below 380px so it clears the menu button.
- **Mobile hero jumps.** It uses `svh` units, and the particle canvas only rebuilds when its size actually
  changes. Mobile browsers fire `resize` while scrolling, which reshuffled every grain.
- **Accessibility.** Skip link, `Escape` closes the menu and returns focus, the closed menu is `inert`,
  `aria-current` on links, reduced-motion support.
- **Performance.** Fonts self-hosted and preloaded, logo SVG halved (297 → 148 KB), responsive `srcset`
  portraits, block-library CSS not loaded on the custom templates.

## 6. Code quality

```bash
composer install   # dev tools only — don't push /vendor to the server
composer lint      # WordPress Coding Standards (phpcs.xml.dist) — currently 0 violations
```
