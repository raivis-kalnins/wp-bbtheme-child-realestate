# Real Estate v3.3

v3.3 adds the complete demo-page layer: richer About history, Contact form/map/details, five sector demo articles with local media, homepage case studies and Swiper gallery, exclusive FAQ behavior, scroll-to-top, icon-only header search, refreshed Appearance screenshot and additional archive/account polish where relevant. Run **Appearance → Starter Setup → Import / Refresh Starter Website** after upgrading so the stored demo content is rebuilt.

## 3.3.0
- Added complete Contact/About/Blog demos, case studies, gallery Swiper, exclusive FAQ accordion, scroll-to-top, icon-only header search, stronger Woo/account/archive styling and refreshed theme previews.

# v3.2 update note

See the suite README for the v3.2 navigation, demo-switching and visual-system changes.

# WP BBTheme Child — Real Estate 3.0.0

Non-WooCommerce property theme with its own `property` CPT, property type taxonomy, editable demo listings and AJAX finder. Starter Setup switches the project out of Woo mode without deleting Woo plugins.

The finder supports listing intent, location/postcode, property type, min/max price, bedrooms, bathrooms, tenure/furnishing, keyword, sorting, grid/list results, reset and saved-search UI. Property content is exposed to Polylang Free when available.

Patterns and demo pages use WP BBuilder + Bootstrap grid; area sliders use the BBuilder Swiper block. Header/footer, AJAX search, mega menus, light/dark, languages and newsletter integration use the shared parent system.

Source is in `src/scss` and `src/js`; run `yarn prod`. The child contains no `clamp()` sizing and no WooCommerce presentation/runtime code.
