# WP BBTheme Child Real Estate 3.7.0

Non-WooCommerce property/estate-agency starter built on WP BBTheme Core, Bootstrap grid and WP BBuilder/Gutenberg.

## v3.7

- Adopts the expanded BBuilder-first starter composition while preserving the property CPT, AJAX finder, archive/single templates and sector mega menus.
- Retains the v3.5 responsive header, form system, Property Journal AJAX blog, Contact/map, About timeline, gallery Swiper and case studies.
- No WooCommerce dependency is introduced.

Run `yarn prod` to compile `src/scss` + `src/js`. After upgrading, run **Appearance → Starter Setup → Import / Refresh Starter Website**.

## SCSS structure (3.8.10.9)

Frontend styles are split into `tokens`, `tools`, `base`, `header`, `footer`, `components`, `swiper`, `motion`, `forms`, `blog`, `quality`, `sector`, `responsive` and `features`. Fluid typography uses the suite `fluid-font()` mixin and explicit viewport guards rather than `clamp()`. The generated production CSS intentionally contains no `!important` declarations.

### Build compatibility

The child build is dependency-free and works with Yarn 1.22.x as well as newer Yarn versions. No Corepack step is required. Use:

```sh
yarn prod
```

The command runs `node tools/build.mjs` and rebuilds the hashed CSS/JS manifest directly.
