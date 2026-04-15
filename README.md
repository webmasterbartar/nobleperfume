# Noble Theme

Classic WordPress theme for a perfume store, based on provided HTML layouts.

## Features

- Classic PHP theme architecture
- Local Tailwind CSS output loaded from theme assets (no CDN in runtime)
- Homepage template (`front-page.php`) adapted from the provided design
- WooCommerce support with custom loop templates
- Blog templates, breadcrumbs, pagination, related posts
- CPT/Taxonomy registration and customizer-based theme options (without ACF plugin)
- Basic SEO schema output and performance improvements (deferred JS)

## Project Structure

- `functions.php` theme bootstrap
- `inc/` modular core files
- `front-page.php` homepage implementation
- `template-parts/components/` reusable content blocks
- `woocommerce/` WooCommerce template overrides
- `assets/dist/` compiled CSS/JS loaded by WordPress

## Deployment Note

Server runtime is PHP-only; no Node runtime is required in production. Theme assets are loaded from local files in `assets/dist/`.
