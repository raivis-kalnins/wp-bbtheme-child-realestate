# 3.8.11.43 — Travel-theme hero slider parity

- Ports the proven Travel v137 hero geometry to Real Estate: the photographic pane now occupies 72% of the hero and fades softly underneath the left-side copy instead of looking like a hard 50/50 split.
- Replaces the v142 fade runtime with a real horizontal three-slide track, 8.5-second autoplay, pause on hover/focus, keyboard navigation and touch/pointer swipe.
- Rebuilds the pager to the exact compact Travel treatment: 8px dots, a 24px active pill, 7px gaps and a small 28px-high translucent container. All dimensions use `!important` so global button styles cannot inflate the bullets.
- Keeps the v142 finder/card/footer fixes and the deterministic v141/v142 homepage markup; only the carousel runtime and hero geometry change in this release.

# 3.8.11.42 — annotated visual polish and Events-style hero pager

- Replaces the one-image v141 hero with a deterministic three-slide property carousel and the same compact centred pager treatment proven in Woo Events, without depending on Swiper or BBuilder fallback content.
- Uses the clean 1200x900 property master photography in the hero pane instead of the visibly over-processed v136 hero source, with no blur/filter/transform scaling.
- Normalises the header CTA dimensions so the final navigation button matches the rest of the header rather than reading as a floating green block.
- Locks property-search labels, fields and submit control to a shared 44px baseline; aligns the More Filters control, results view toggle, property status pills and favourite buttons.
- Makes property cards equal-height with identical 4:3 media geometry and consistent overlay offsets.
- Corrects footer colour ownership: stronger newsletter contrast, white form fields, dark readable footer headings/links on the white footer, and a consistent dark-green footer bottom band.
- Keeps the v139/v141 single-owner isolation model, so none of the retired v118-v140 Real Estate emergency layout layers are reactivated.

# 3.8.11.41 — deterministic Real Estate homepage recovery

- Restores the v139 Real Estate single-owner frontend model; v140 is retired because it re-enabled older suite layers that replaced the intended hero and icon-card data with BBuilder fallback content.
- Replaces the homepage Swiper hero with direct semantic markup and the bundled 2560px property artwork, removing the “Build a stronger homepage / Reusable slide” fallback path entirely.
- Replaces service, property-journey, area, outcome and process BBuilder icon-card blocks with deterministic Real Estate cards so “Card title / Add a short description” cannot leak into the live page.
- Rebuilds the About split, valuation CTA, proof rows, gallery and FAQ as stable scoped markup on one 1180px / 24px grid system.
- Keeps the property finder dynamic, preserves its 3-column 4:3 listing grid, and explicitly loads the finder script when the v141 owner shortcode is the stored front-page content.
- Keeps the Events-style trigger-anchored mega-menu positioning while leaving header/footer/property primitives on the proven v139 Real Estate base.
- The frontend render guard outputs the repaired markup directly; wp-admin persists a one-line owner shortcode so legacy demo rebuilders cannot reintroduce broken block serialization.

# 3.8.11.40 — Events-parity hero and grid recovery

This release ports the layout-ownership fix proven in Woo Events 3.8.11.40. Real Estate now keeps `suite-v118` as the stable frontend foundation, retires the conflicting v119-v139 visual enqueue owners, and adds one scoped v140 layer. The hero again uses the stable right-hand media pane/gradient model; all homepage sections take their rail from the actual header container; service, journey, area, case-study, process, gallery, statistics and blog rows use scoped grids; property cards keep 4:3 media with direct lazy-image hydration; and the CTA/FAQ/mega-menu geometry follows the same stable pattern as Events. The v139 Real Estate content cleanup and property media migration remain active.

## 3.8.11.39 Real Estate homepage reset and layout ownership
- Rebuilds the managed Real Estate front page once with a clean property-only composition, removing the inherited partner strip, Automotive catalogue and WooCommerce fragments.
- Retires the accumulated v82-v138 visual patch styles/scripts on the frontend so one v139 stylesheet owns section width, spacing, grids and media ratios.
- Uses a consistent 1180px shell and 24px desktop gap across property, service, journey, proof, case-study, gallery, process and article sections.
- Property cards now render the featured 1200x900 image directly instead of the lazy gallery/thumb overlay that produced large grey panels in full-page captures.
- Refreshes the bundled demo property photography with distinct 1200x900 sources and keeps the 2560px hero artwork.
- Adds a front-page render guard so managed/shared demo content is property-specific immediately; the admin pass then persists the repaired page.

## 3.8.11.38 Real Estate sector/content repair
- Real Estate now supplies a complete demo profile, preventing Automotive copy, vehicle sections and WooCommerce shop content from leaking in after a theme switch/reset.
- A guarded migration repairs the front page and other managed pages that still contain Automotive demo copy, and converts clearly generated Automotive demo articles back to property editorial content.
- v138 replaces the competing v136/v137 runtime layout mutation with one safer DOM owner that only promotes real card rows to grids.
- Stale section-level grid classes are neutralised, preventing the narrow one-character product cards shown in the broken demo screenshot.
- Hero media uses the bundled 2560px Real Estate artwork; property demo photography is now bundled at 1200x900 and refreshed into existing demo attachments.
- Section shells, card gaps, equal heights and 4:3 media treatment are consistent across rich and fallback homepage sections.

## 3.8.11.37 real-estate visual consistency
- All non-hero homepage/archive sections share the same 1180px content shell and responsive gutters.
- Property, service, industry, case-study, insight/blog and proof grids now use one 24px gap system with column counts derived from the actual cards.
- The three-item proof strip no longer inherits a four-column desktop grid.
- Listing/editorial/gallery card imagery uses a consistent 4:3 crop, removes rendering filters/hover scaling and requests an appropriate responsive source size.
- New property uploads get a 1200x900 cropped image size at quality 90; the property hero pattern now references the bundled 2560px hero artwork.

## 3.8.11.16 reset-safe final release fixes
- Demo reset/import now re-runs the canonical managed BBuilder page rebuild and all v116 repairs automatically.
- The old migration that removed legitimate responsive BBuilder column widths is disabled; desktop multi-column layouts survive a clean demo reset.
- Desktop mega menus use the measured header bottom plus a hover bridge, matching the close Jobs positioning across all children.
- Home hero sliders use three distinct child-owned images, visible pagination, 8.5-second autoplay and pause-on-hover with sharp natural-scale rendering.
- Managed demo/editorial/catalogue/gallery/Woo media is restored from bundled files; Woo Clothes keeps the complete bundled product-image pool.
- Quote drawers use resilient trigger detection and sit flush to the right viewport edge on quote-enabled themes.
- Cookie-consent acceptance persists across reloads using a stable browser marker.
- Legal pages remain left-aligned on the normal grid; Latest Thinking media/card edges are normalized.
- Partner/brand serialization is normalized idempotently to prevent repeated wrappers and the `wpbb/column` validation warning.
- Theme Settings retain child-owned controls for disabling dark mode and keeping English-only Polylang content.

## 3.8.11.14 child-only settings, editor, legal, editorial and hero finish

- Latest Thinking card rows now use the same 1320px grid as their headings; the 1440px row override that shifted the first card left has been removed.
- Hero images use a direct child-owned native source, stronger left-edge gradient masking and no CSS blur/viewport stretching.
- Appearance > Theme Settings adds switches to disable dark mode and disable translations/keep English only. Enabling English-only moves non-English Polylang Pages and Posts to Trash and hides the language switcher.
- Privacy/Terms/Cookies content spans the normal site grid and is left-aligned instead of being forced into a centered narrow column.
- The known raw partner-heading serialization defect is repaired in imported pages and on future page saves, resolving the wpbb/column validation error.
- Child editor CSS is moved from enqueue_block_editor_assets to enqueue_block_assets for the WordPress editor iframe.

## 3.8.11.13 child-only hero and editorial grid finish

- Latest Thinking / related editorial cards use the full 1440px site grid; the legacy outer BBuilder row and list start padding can no longer create a first-card left inset.
- Homepage heroes use a native-resolution child asset without viewport-width stretching.
- A stronger white-to-transparent hero gradient crosses the photograph's left edge so the image seam is hidden.
- Existing and translated managed hero blocks are refreshed from the same child-owned asset after upgrade.

## 3.8.11.12 child-only visual/media fixes

- Latest Thinking card grid now inherits the section grid with no first-card left inset.
- Sharper child-owned hero source and late frontend override.
- Managed catalogue and editorial media are re-synchronised from bundled child assets.
- Media/text CTA buttons align to the copy edge.

## 3.8.11.11 media and WooCommerce finalisation

- Repairs missing demo media from bundled local assets, including cloned-site WooCommerce product images and Automotive vehicle finder thumbnails.
- Forces WooCommerce filters, ranges, compare controls and product actions to the child theme accent instead of the plugin blue fallback.
- Uses a two-column desktop Basket, Checkout and My Account shell with mobile stacking only below 821px.
- Uses the highest-resolution bundled hero source during managed demo rebuilds; Business uses the 1600x1000 office source.
- Requires parent WP BBTheme 3.8.10.23 for reliable My Account header URLs on cloned sites.

## 3.8.10.82 suite consistency

Requires WP BBuilder 5.6.9+ for palette inheritance and the shared hCaptcha verifier. This release keeps the sector's individual brand colour while using the same 1440px canvas, card/form rhythm, dark-mode baseline and footer/newsletter hierarchy as the rest of the 15-theme suite. The one-time cleanup is restricted to records explicitly marked as theme-managed demo content.

## 3.8.10.65

- Fixes the Theme Settings frontend-protection panel so its CSS is loaded in the admin head instead of appearing as visible text.
- Makes sector media repair load the WordPress image API safely before generating attachment metadata.
- Refines shared card, directory, gallery and responsive alignment.

## 3.8.10.47

- More compact and consistent section spacing, cards and responsive layouts.
- Smaller in-frame gallery thumbnail pagination and improved light/dark contrast.
- Reliable child-owned WooCommerce product shells where the theme includes commerce.

# WP BBTheme Child Real Estate 3.8.10.65
Non-WooCommerce property/estate-agency starter built on WP BBTheme Core, Bootstrap grid and WP BBuilder/Gutenberg.

## v3.7

- Adopts the expanded BBuilder-first starter composition while preserving the property CPT, AJAX finder, archive/single templates and sector mega menus.
- Retains the v3.5 responsive header, form system, Property Journal AJAX blog, Contact/map, About timeline, gallery Swiper and case studies.
- No WooCommerce dependency is introduced.

Run `yarn prod` to compile `src/scss` + `src/js`. After upgrading, run **Appearance → Starter Setup → Import / Refresh Starter Website**.

### 3.8.10.46
- Dashboard-safe, resumable sector media repair; no synchronous bulk image regeneration on `admin_init`.
- Password protection controls live under **Theme Settings → General**.
- Thumbnail navigation is overlaid inside the main gallery image.
- Active-sector Blog and directory media are repaired after child-theme switching.

## SCSS structure (3.8.10.9)

Frontend styles are split into `tokens`, `tools`, `base`, `header`, `footer`, `components`, `swiper`, `motion`, `forms`, `blog`, `quality`, `sector`, `responsive` and `features`. Fluid typography uses the suite `fluid-font()` mixin and explicit viewport guards rather than `clamp()`. The generated production CSS intentionally contains no `!important` declarations.

### Build compatibility

The child build is dependency-free and works with Yarn 1.22.x as well as newer Yarn versions. No Corepack step is required. Use:

```sh
yarn prod
```

The command runs `node tools/build.mjs` and rebuilds the hashed CSS/JS manifest directly.


### 3.8.10.45
- Consistent 80/64/52px section rhythm and explicit light/dark card contrast.
- Active-theme sector media repair for demo pages, blogs, directories and galleries.
- Top-aligned About imagery plus thumbnail and modal galleries on supported directory cards and single pages.

### 3.8.10.44
- Frontend password protection is enabled by default with password `wp@demo`.
- Administrators can disable it or set a new password in **Settings → Theme Settings** at `/wp-admin/options-general.php?page=wp-theme-settings`.
- Successful visitors receive a signed access cookie valid for 24 hours by default.
- Purge full-page/server/CDN caches after changing the protection setting.

### 3.8.10.42
- Replaced demo feature icons with Tabler Icons v3.46.0 outline SVGs, sized for normal UI use and coloured from the child-theme brand token.
- Single-column imported demo rows are repaired to 12 columns at every breakpoint.
- Dark-mode demo cards use explicit dark surfaces/readable text.
- Optional frontend-only demo password protection is available in Settings → Theme Settings (default password `wp@demo`).
### 3.8.10.43
- Shared alignment and dark-mode contrast fixes across service, solution, process, directory, blog and commerce cards.
- Current child-theme media is reapplied after child-theme switches, including optimised AVIF/WebP files.
- Visible slider/grid images are loaded deterministically and duplicate single-item summary text is removed.

## 3.8.10.65 BBuilder demo system
This release expects WP BBuilder 5.6.4+ and standardises demo editing around BBuilder Row/Column, Div, Icon Card, Swiper and selected native WordPress content blocks. Legacy Group/Columns demo markup is migrated automatically.


## 3.8.11.08
WooCommerce shop, basket, checkout and account layouts were normalised across the sector suite; theme preview artwork was refreshed and package documentation was reduced to this README.
