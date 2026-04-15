# CHANGELOG

## 2026-04-11

- Date and Time of changes: 2026-04-11
- Detailed description of changes:
  - Refined WooCommerce shop product cards for clearer visual hierarchy, accessibility (focus rings on links), and a more premium shop feel.
  - Added optional sale and new badges on product thumbnails when applicable.
  - Improved hover overlay behavior on desktop and a touch-friendly always-visible action panel on mobile within the image area.
  - Tuned overlay gradient, panel glass styling, and badge colors for better contrast and readability.
  - **Shop overlay (responsive):** Fixed the product image overlay panel shrinking to a very narrow width in some layouts by forcing full-width, `min-width: 0` on the media box, and box sizing on the overlay layer.
  - **Tablet + phone:** Overlay actions stay always visible below 1024px (no reliance on hover); desktop mouse users keep hover-to-reveal on large screens. Devices reporting `hover: none` always show the overlay for touch ergonomics.
  - Larger tap targets for the overlay CTA on narrow viewports and coarse pointers; safe-area padding on the overlay for notched phones.
  - Slightly adjusted overlay typography (more lines for scent notes on small screens) and panel padding per breakpoint.
- Components affected:
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:19:14

- Date and Time of changes: 2026-04-09 13:19:14
- Detailed description of changes:
  - Removed the extra injected helper card from the shop product loop to keep the product grid purely product-driven.
  - Eliminated artificial grid interruptions that could create perceived empty spaces within product rows.
- Components affected:
  - `woocommerce/archive-product.php`

----

## 2026-04-09 14:26:28

- Date and Time of changes: 2026-04-09 14:26:28
- Detailed description of changes:
  - Updated shop products layout to render 4 cards per row on desktop.
  - Adjusted both template-level grid utility classes and CSS media-query grid definition to keep layout behavior consistent.
  - Rebuilt frontend assets and validated modified files with no lint issues.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 14:23:28

- Date and Time of changes: 2026-04-09 14:23:28
- Detailed description of changes:
  - Applied a full visual polish pass to shop product cards for a more attractive and user-friendly experience while preserving current structure.
  - Improved card elevation and hover depth, refined media-area finishing with a subtle bottom gradient, and tuned typography emphasis for product titles.
  - Enhanced price block readability with better baseline alignment and cleaner sale/current price treatment.
  - Added a dedicated styled divider wrapper for the price area to improve visual hierarchy in each card.
  - Rebuilt assets and verified updated files with no lint errors.
- Components affected:
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 14:22:07

- Date and Time of changes: 2026-04-09 14:22:07
- Detailed description of changes:
  - Enhanced product-card hover overlay with the requested full content block (`bg-white rounded-xl p-4 space-y-3`) including scent row, two size options, and a shopping-cart CTA.
  - Connected overlay scent text to product attribute fallback logic for cleaner dynamic output.
  - Rebuilt assets and verified no linter issues in updated template files.
- Components affected:
  - `woocommerce/content-product.php`
  - `assets/dist/css/app.css`

----

## 2026-04-09 14:20:21

- Date and Time of changes: 2026-04-09 14:20:21
- Detailed description of changes:
  - Aligned the `خانواده بویایی` filter summary row and all checkbox option rows for consistent visual baseline and spacing.
  - Updated scent-family checkbox markup with explicit input utility classes (`w-4 h-4 rounded ...`) and `group/item` row class for cleaner interactive alignment.
  - Refined filter row CSS layout to a stable icon/text/checkbox grid structure so all rows align with the accordion toggle row.
  - Rebuilt CSS and confirmed no lint issues in the updated filter UI.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 14:18:08

- Date and Time of changes: 2026-04-09 14:18:08
- Detailed description of changes:
  - Updated shop pagination rendering so the pagination block is always visible in its target position and layout.
  - Added a fallback single-page pagination item (`۱`) when WooCommerce has only one page, preserving the same pagination container placement.
- Components affected:
  - `woocommerce/archive-product.php`

----

## 2026-04-09 14:16:59

- Date and Time of changes: 2026-04-09 14:16:59
- Detailed description of changes:
  - Added the dashed `search_insights` helper card back into the shop products grid as the final item after all product cards.
  - Matched the requested visual structure and content for this last grid card (icon, title, supportive text, and `امتحان کنید` CTA).
  - Rebuilt assets and checked template lint status successfully.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/dist/css/app.css`

----

## 2026-04-09 14:14:44

- Date and Time of changes: 2026-04-09 14:14:44
- Detailed description of changes:
  - Refined product card background treatment with a softer cool-tone gradient to improve contrast and visual consistency.
  - Rebalanced spacing across the products section: updated grid gaps, card padding, media/body spacing, and mobile density values.
  - Improved vertical rhythm inside each card by tuning body gap and block padding for cleaner alignment between brand, title, and price.
  - Rebuilt compiled CSS and confirmed no lint issues after spacing/background adjustments.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 14:12:23

- Date and Time of changes: 2026-04-09 14:12:23
- Detailed description of changes:
  - Simplified WooCommerce product card template to match the requested minimal variant exactly: overlay with only `انتخاب گزینه`, then brand/title/price content block.
  - Removed extra card elements from loop item output (badge stack, scent row details, size selectors, favorite button, and rating chip) to keep product cards visually aligned with the provided reference.
  - Added dedicated simple price styling for clean regular/sale price rendering in the new compact card layout.
  - Rebuilt assets and verified updated files with no linter issues.
- Components affected:
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 14:09:59

- Date and Time of changes: 2026-04-09 14:09:59
- Detailed description of changes:
  - Fixed shop products grid start direction so cards align from the right side in RTL desktop layout.
  - Removed WooCommerce default `ul.products` pseudo-elements (`::before` / `::after`) that can appear as phantom grid items and cause perceived empty cards between products.
  - Added dense grid flow tuning for more stable card packing behavior.
  - Rebuilt assets and verified no lint issues in updated styles.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 14:07:56

- Date and Time of changes: 2026-04-09 14:07:56
- Detailed description of changes:
  - Reintroduced the shop products grid loop in `archive-product.php` and rebuilt the section to render cards in a clean, uniform `1/2/3` responsive layout.
  - Ensured all product cards are rendered from the same `content-product.php` template for consistent structure and spacing.
  - Kept the grid free from interruption blocks by not injecting any promotional perfume card in the middle of the products list.
- Components affected:
  - `woocommerce/archive-product.php`

----

## 2026-04-09 14:04:16

- Date and Time of changes: 2026-04-09 14:04:16
- Detailed description of changes:
  - Removed the entire shop products grid block (`ul.products.shop-products-grid ...`) from the WooCommerce archive template as requested.
  - Ensured no product-card list markup from that section remains in output.
- Components affected:
  - `woocommerce/archive-product.php`

----

## 2026-04-09 14:00:47

- Date and Time of changes: 2026-04-09 14:00:47
- Detailed description of changes:
  - Finalized product card class structure to match the requested reference pattern exactly at component root level.
  - Set card container markup so the visual card element uses `product-card flex flex-col group` directly, while keeping WooCommerce loop wrapper clean.
  - Rebuilt frontend assets and validated the updated product template without lint issues.
- Components affected:
  - `woocommerce/content-product.php`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:57:46

- Date and Time of changes: 2026-04-09 13:57:46
- Detailed description of changes:
  - Rebuilt shop product card visuals from the ground up with a fresh base style system instead of incremental patching.
  - Redesigned card surface/background treatment, spacing scale, border radius, shadow, hover elevation, and media container styling for a cleaner premium UI.
  - Reworked internal layout rhythm (media, body, footer, price chip, favorite action, overlay panel, size buttons, CTA) to ensure consistent alignment and hierarchy.
  - Tuned responsive behavior for mobile card density and touch targets while preserving desktop composition and readability.
  - Rebuilt CSS assets and validated updated styles with no linter issues.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:53:21

- Date and Time of changes: 2026-04-09 13:53:21
- Detailed description of changes:
  - Fine-aligned the shop products section DOM/class structure to match the requested reference blocks more precisely.
  - Updated product card shell class pattern to `product-card flex flex-col group` and adjusted overlay class ordering and spacing to the requested `p-6` layout.
  - Matched products grid class ordering and pagination wrapper class ordering with the provided target snippets for more exact front-end parity.
  - Rebuilt assets and validated the updated templates with no linter issues.
- Components affected:
  - `woocommerce/content-product.php`
  - `woocommerce/archive-product.php`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:33:06

- Date and Time of changes: 2026-04-09 13:33:06
- Detailed description of changes:
  - Redesigned mobile filter/sorting UX into a floating action experience with a fixed bottom trigger and a professional bottom-sheet panel.
  - Replaced the old inline mobile filter block with a cleaner, more accessible structure that includes integrated WooCommerce sorting inside the sheet.
  - Added mobile-first UI styling for floating trigger, sheet header/body/actions, sticky action row, and improved spacing for touch usage.
  - Added bottom safe spacing for shop mobile view so product content does not get covered by floating controls.
  - Rebuilt CSS assets and validated updated files without lint issues.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:30:56

- Date and Time of changes: 2026-04-09 13:30:56
- Detailed description of changes:
  - Fully reset and rebuilt the shop products section structure to match the requested grid pattern and product-card behavior.
  - Removed the extra non-product injected tile from the products list so the loop starts directly with the first real product.
  - Reconfigured the products grid to exact requested layout flow: `grid-cols-1`, `md:grid-cols-2`, `lg:grid-cols-3`, with updated gap scales.
  - Reimplemented product card markup with cleaner semantic structure and consistent UI hierarchy: badge row, scent/size/CTA overlay, favorite action, and price-rating footer.
  - Removed legacy RTL direction forcing from the grid layer to prevent visual first-slot empty behavior in desktop rendering.
  - Rebuilt CSS bundle and validated updated files for lint issues.
- Components affected:
  - `woocommerce/archive-product.php`
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:28:19

- Date and Time of changes: 2026-04-09 13:28:19
- Detailed description of changes:
  - Reimplemented the shop products section structure for cleaner, maintainable markup and stronger UI consistency in RTL layout.
  - Refactored product card template into a clearer semantic hierarchy (`article`, media wrapper, overlay panel, body meta/footer) while preserving WooCommerce dynamic data.
  - Standardized card interaction pattern to match requested details: top dual badges, scent row, size options, product CTA with icon, favorite action, and price/rating footer.
  - Updated grid markup and spacing behavior for responsive consistency, keeping desktop four-column layout and improving mobile density.
  - Rebuilt frontend assets to apply the rewritten product section and card styles.
- Components affected:
  - `woocommerce/archive-product.php`
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:26:14

- Date and Time of changes: 2026-04-09 13:26:14
- Detailed description of changes:
  - Reworked WooCommerce shop product cards to closely match the requested reference structure and visual hierarchy.
  - Updated top badge area to show `آنباکس/اورجینال` plus `EXCLUSIVE` label in the same row.
  - Updated overlay panel content to match requested pattern: scent line, `دکانت ۱۰میل` and `فول سایز` options, and CTA with shopping cart icon.
  - Added a dedicated favorite icon button in the product meta area and preserved truncated title + price/rating footer layout.
  - Added and tuned supporting CSS classes for the new badge variant and favorite action styling; rebuilt compiled assets.
- Components affected:
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:24:39

- Date and Time of changes: 2026-04-09 13:24:39
- Detailed description of changes:
  - Fixed desktop product grid start behavior by forcing RTL grid flow on the shop products container so the first visible card aligns from the correct side in Persian layout.
  - Added the requested dashed helper tile ("هنوز مطمئن نیستی؟") as the final grid item after product loop rendering, instead of interrupting or affecting the beginning of product rows.
  - Styled the helper tile with proper centered icon/title/description/button states and hover feedback to match the provided reference card behavior.
  - Rebuilt compiled assets to apply layout and card-style updates on the live shop page.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:22:54

- Date and Time of changes: 2026-04-09 13:22:54
- Detailed description of changes:
  - Replaced default WooCommerce pagination output on the shop archive with a custom numeric pagination block for precise visual control.
  - Implemented centered pagination with previous/next chevron icons, compact Persian-friendly number pills, and proper current/hover/ellipsis states to match the provided DOM layout.
  - Added responsive pagination sizing so tap targets remain usable on mobile while maintaining desktop spacing and alignment.
  - Rebuilt compiled CSS assets to ensure the updated pagination styles are applied immediately.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:21:26

- Date and Time of changes: 2026-04-09 13:21:26
- Detailed description of changes:
  - Rebuilt WooCommerce shop product card structure to match the requested premium reference style in `archive-product.php`.
  - Added a hover overlay panel inside each product image with scent line, two size options, and a full-width "مشاهده محصول" action.
  - Updated card meta/footer layout to a cleaner baseline: brand + truncated product title + price and star rating row.
  - Kept the shop grid behavior intact so cards stay in four columns on desktop while preserving responsive behavior.
  - Recompiled frontend assets to apply new CSS classes and interaction styles.
- Components affected:
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:17:44

- Date and Time of changes: 2026-04-09 13:17:44
- Detailed description of changes:
  - Improved hover interaction for the sidebar quiz CTA button in shop page.
  - Added smoother transition timing, cleaner border behavior, and subtle lift/shadow feedback on hover.
  - Kept styling consistent with existing shop sidebar visual language.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:15:48

- Date and Time of changes: 2026-04-09 13:15:48
- Detailed description of changes:
  - Applied a strict shop-grid enforcement fix to prevent WooCommerce default product-list behavior from breaking custom card layout.
  - Forced `.shop-products-grid` to render as a true responsive CSS grid with explicit breakpoint column templates (1 / 2 / 4).
  - Tightened product-card item box model and overflow handling to eliminate cross-column visual breakage.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:13:44

- Date and Time of changes: 2026-04-09 13:13:44
- Detailed description of changes:
  - Fixed vertical alignment of the shop toolbar meta row (result count + sorting control) for cleaner visual balance.
  - Removed default WooCommerce result-count margin and normalized line-height/alignment across toolbar elements.
  - Added explicit alignment rules for ordering container to keep all toolbar meta items on a consistent baseline.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:12:39

- Date and Time of changes: 2026-04-09 13:12:39
- Detailed description of changes:
  - Refined shop product-grid composition for cleaner visual alignment and less crowded card density.
  - Tuned card spacing, footer rhythm, title sizing, price chip scale, cart button size, and CTA size to keep all elements balanced in a 4-column desktop layout.
  - Added grid/card stretch controls so product cards stay visually consistent and aligned row-by-row.
- Components affected:
  - `woocommerce/archive-product.php`
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:11:09

- Date and Time of changes: 2026-04-09 13:11:09
- Detailed description of changes:
  - Hid desktop sidebar filter cards on mobile and introduced a dedicated mobile-friendly filter block.
  - Added a collapsible mobile filter UI with brand chips, scent-family toggles, price range control, and clear/apply action buttons.
  - Preserved desktop sidebar behavior while improving usability and touch interaction quality for mobile users.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:08:04

- Date and Time of changes: 2026-04-09 13:08:04
- Detailed description of changes:
  - Implemented smooth animated open/close behavior for shop sidebar filter sections.
  - Added dedicated accordion panel classes and JS-driven height/opacity transitions so both expand and collapse feel soft.
  - Preserved existing visual styling while improving interaction quality of filter details blocks.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/src/js/app.js`
  - `assets/dist/css/app.css`
  - `assets/dist/js/app.js`

----

## 2026-04-09 13:06:08

- Date and Time of changes: 2026-04-09 13:06:08
- Detailed description of changes:
  - Removed the mid-grid promotional banner block from the shop product archive as requested.
  - Kept product loop flow continuous while preserving the remaining custom grid elements.
- Components affected:
  - `woocommerce/archive-product.php`

----

## 2026-04-09 13:04:11

- Date and Time of changes: 2026-04-09 13:04:11
- Detailed description of changes:
  - Fixed empty starting gap in shop product grid by adjusting the insertion points of special mid-grid blocks.
  - Moved promo banner insertion to after the 4th product and helper card insertion to after the 8th product for cleaner 4-column flow.
- Components affected:
  - `woocommerce/archive-product.php`

----

## 2026-04-09 13:03:10

- Date and Time of changes: 2026-04-09 13:03:10
- Detailed description of changes:
  - Added desktop-only product highlight chips to shop cards to surface key product traits near the price/action row.
  - Implemented dynamic highlights in `content-product.php` based on product state (e.g. on-sale, in-stock, high-rating).
  - Added dedicated styles for the highlight chip row to keep the card footer informative without visual clutter.
- Components affected:
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:01:55

- Date and Time of changes: 2026-04-09 13:01:55
- Detailed description of changes:
  - Refined visual alignment of the shop toolbar block (breadcrumb, result count, and sorting control).
  - Added dedicated toolbar classes and adjusted spacing/padding to improve baseline rhythm and reduce visual crowding.
  - Improved ordering control alignment and line-height for cleaner layout consistency across responsive breakpoints.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 13:00:34

- Date and Time of changes: 2026-04-09 13:00:34
- Detailed description of changes:
  - Updated shop product grid layout to display 4 product cards per row on desktop.
  - Kept responsive behavior for smaller screens unchanged (1 column mobile, 2 columns tablet).
- Components affected:
  - `woocommerce/archive-product.php`

----

## 2026-04-09 12:59:18

- Date and Time of changes: 2026-04-09 12:59:18
- Detailed description of changes:
  - Added refined animation behavior to the shop filter sidebar card for better UX polish.
  - Implemented soft entrance animation, hover lift/shadow feedback, and smoother accordion content transitions.
  - Enhanced apply-filter button interaction with subtle motion and added reduced-motion accessibility fallback.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 12:56:59

- Date and Time of changes: 2026-04-09 12:56:59
- Detailed description of changes:
  - Fixed broken shop product-card layout by enforcing full-width grid behavior against WooCommerce default column/floating styles.
  - Added stronger archive-scoped overrides for product list item sizing (`width/max-width/min-width`) and alignment.
  - Normalized shop product image box rendering to prevent shrunken thumbnail appearance inside cards.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 12:55:43

- Date and Time of changes: 2026-04-09 12:55:43
- Detailed description of changes:
  - Removed custom shop-page header and footer blocks from the archive template as requested.
  - Kept only the core archive content area (tabs, sidebar, product grid, pagination) for a cleaner focused shop layout.
  - Updated top spacing after header removal to keep the page aligned correctly.
- Components affected:
  - `woocommerce/archive-product.php`

----

## 2026-04-09 12:53:21

- Date and Time of changes: 2026-04-09 12:53:21
- Detailed description of changes:
  - Fixed shop product-card visual breakage caused by WooCommerce default list-item styles conflicting with the custom grid layout.
  - Added strong archive-scoped CSS overrides for product `<li>` sizing/float/margins and decorative pseudo-elements.
  - Normalized product card link/price spacing to keep cards consistent with the intended custom UI.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 12:51:19

- Date and Time of changes: 2026-04-09 12:51:19
- Detailed description of changes:
  - Fixed WooCommerce template routing so shop and product-taxonomy archive pages load the dedicated `woocommerce/archive-product.php` template.
  - Resolved the issue where `woocommerce.php` fallback template was overriding archive layout and hiding the custom sidebar implementation.
- Components affected:
  - `woocommerce.php`

----

## 2026-04-09 12:49:45

- Date and Time of changes: 2026-04-09 12:49:45
- Detailed description of changes:
  - Fixed shop sidebar visibility by enforcing explicit layout classes and stable sidebar width behavior across breakpoints.
  - Added `shop-layout` and `shop-sidebar` structural classes to guarantee sidebar rendering in desktop and mobile flows.
  - Applied responsive width constraints to prevent sidebar collapse or hidden layout states.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 12:47:17

- Date and Time of changes: 2026-04-09 12:47:17
- Detailed description of changes:
  - Performed a high-precision shop archive refinement pass to better align the layout and sidebar behavior with `shop-page/code.html`.
  - Reworked sidebar composition into a closer reference structure (accordion filters, brand chips with icon circles, scent-family rows, price slider block, clear apply action).
  - Updated shop header/top tabs/content spacing and ordering UI details for a cleaner and more consistent archive experience.
  - Polished related shop styles in CSS including accordion interactions, brand icon pills, ordering prefix label, and filter control hierarchy.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 12:40:13

- Date and Time of changes: 2026-04-09 12:40:13
- Detailed description of changes:
  - Performed a focused UI refinement pass on the shop page sidebar and filter panel for better structure and visual quality.
  - Reworked sidebar filter sections into cleaner accordion blocks with improved brand pills, checkbox rows, price range styling, and a stronger apply-filter action.
  - Improved spacing hierarchy and card polish in the sidebar while preserving overall layout consistency with the shop reference.
- Components affected:
  - `woocommerce/archive-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 12:35:41

- Date and Time of changes: 2026-04-09 12:35:41
- Detailed description of changes:
  - Completed a professional shop archive implementation aligned with `shop-page/code.html` and visual reference.
  - Rebuilt `woocommerce/archive-product.php` with full shop experience: top navigation bar, category tabs, advanced sidebar filters, breadcrumb/sort strip, responsive product grid, mid-grid promo banner, helper card, and rich footer.
  - Upgraded `woocommerce/content-product.php` to a premium product card layout with badges, brand/rating metadata, price chip, cart quick action, and clear CTA.
  - Expanded shop-specific styling in `assets/src/css/app.css` for tabs, filters, product cards, promo blocks, ordering controls, and pagination states.
- Components affected:
  - `woocommerce/archive-product.php`
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 12:31:22

- Date and Time of changes: 2026-04-09 12:31:22
- Detailed description of changes:
  - Started full implementation of the WooCommerce shop archive page based on the provided `shop-page/code.html` reference.
  - Rebuilt `archive-product.php` with a modern structure: top category tabs, sidebar filters, breadcrumb/sort toolbar, responsive product grid, and styled pagination.
  - Redesigned `content-product.php` cards to match the project product-card system (badges, brand/rating rows, price chip, cart action, quick-view CTA).
  - Added dedicated shop UI styles in `assets/src/css/app.css` for tabs, filters, product cards, ordering controls, and pagination.
- Components affected:
  - `woocommerce/archive-product.php`
  - `woocommerce/content-product.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 12:18:29

- Date and Time of changes: 2026-04-09 12:18:29
- Detailed description of changes:
  - Increased the global size of all eyebrow-style section labels across the homepage for stronger visual presence.
  - Tuned letter spacing and mobile eyebrow size to keep readability balanced after the size increase.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 12:14:15

- Date and Time of changes: 2026-04-09 12:14:15
- Detailed description of changes:
  - Simplified the gift section design by removing visually heavy decorative elements and reducing component complexity.
  - Replaced premium-style feature blocks and CTAs with cleaner, minimal alternatives for a more standard UI presentation.
  - Kept layout structure intact while improving readability and visual balance of text/content actions.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 11:48:06

- Date and Time of changes: 2026-04-09 11:48:06
- Detailed description of changes:
  - Improved hero content block with a cleaner and simpler layout approach while preserving brand style.
  - Reduced visual density in hero text spacing and switched to simpler CTA button styles for clearer UX.
  - Introduced dedicated simple hero button classes for a minimal and more consistent interaction pattern.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 11:44:23

- Date and Time of changes: 2026-04-09 11:44:23
- Detailed description of changes:
  - Changed the background styling of the special-discount products section to a distinct premium gradient surface.
  - Replaced the generic background utility usage with a dedicated section style class for better design control.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-09 11:42:18

- Date and Time of changes: 2026-04-09 11:42:18
- Detailed description of changes:
  - Added a new homepage section for special discounted products using the same product-card design language as other product sections.
  - Wired the section to WooCommerce on-sale products and added a styled demo fallback set when discounted products are unavailable.
  - Kept mobile carousel behavior and card interactions consistent with existing homepage product sections.
  - Improved discounted price readability by styling `del/ins` elements inside product price chips.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:30:50

- Date and Time of changes: 2026-04-08 17:30:50
- Detailed description of changes:
  - Adjusted category section header alignment so heading text stays on the right and the action link stays on the left.
  - Improved responsive behavior in mobile/desktop by explicitly controlling link alignment and preventing wrap breaks.
- Components affected:
  - `front-page.php`

----

## 2026-04-08 17:30:05

- Date and Time of changes: 2026-04-08 17:30:05
- Detailed description of changes:
  - Converted the homepage trust/benefits section into a swipeable mobile carousel.
  - Added touch-friendly horizontal scroll and snap behavior for trust cards on small screens.
  - Kept desktop/tablet grid behavior intact while improving mobile section usability.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:29:06

- Date and Time of changes: 2026-04-08 17:29:06
- Detailed description of changes:
  - Reduced category-card media height on mobile to make the category carousel section more compact.
  - Kept tablet/desktop category media heights unchanged while tightening only small-screen presentation.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:28:04

- Date and Time of changes: 2026-04-08 17:28:04
- Detailed description of changes:
  - Improved mobile section-title UX across homepage by reducing visual density and tightening heading hierarchy.
  - Tuned eyebrow label size/letter-spacing and reduced heading scales (`title-lg`, `title-md`) for cleaner small-screen readability.
  - Added mobile constraints for heading width and section-heading spacing to avoid cluttered title blocks.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:26:55

- Date and Time of changes: 2026-04-08 17:26:55
- Detailed description of changes:
  - Increased mobile product-card width inside homepage product carousel to improve visual clarity and card prominence.
  - Adjusted the viewport card ratio so the grid-like card structure is more clearly perceived during horizontal swipe.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:24:06

- Date and Time of changes: 2026-04-08 17:24:06
- Detailed description of changes:
  - Unified all homepage product-card sections to use the same card design system as the "پرفروش‌ترین‌ها" section.
  - Updated the "تازه‌ترین‌ها" cards to match the exact structure: brand label, rating row, price chip row, cart action, and "مشاهده سریع" CTA.
  - Applied the same consistency to demo fallback cards in the new products section.
- Components affected:
  - `front-page.php`

----

## 2026-04-08 17:20:31

- Date and Time of changes: 2026-04-08 17:20:31
- Detailed description of changes:
  - Reduced mobile product price chip typography and padding for a cleaner, less crowded card footer appearance.
  - Updated category section data count to keep desktop display at 4 items and adjusted placeholder fill logic accordingly.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:19:40

- Date and Time of changes: 2026-04-08 17:19:40
- Detailed description of changes:
  - Improved quiz strip visual impact by upgrading the navy background to a richer brand-gradient palette.
  - Updated CTA button color to a more attention-grabbing gold tone while keeping the layout simple and clean.
  - Preserved minimal UI approach and avoided heavy visual effects.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:18:13

- Date and Time of changes: 2026-04-08 17:18:13
- Detailed description of changes:
  - Updated hero headline to stay on a single line in mobile view.
  - Added a dedicated responsive class to control mobile title size and preserve clean one-line layout.
  - Removed manual line break from headline markup to keep consistent text flow across breakpoints.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:16:55

- Date and Time of changes: 2026-04-08 17:16:55
- Detailed description of changes:
  - Updated mobile category carousel item width to display approximately 2.5 cards per viewport.
  - Kept swipe/snap behavior unchanged while improving preview visibility of the next category card.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:14:57

- Date and Time of changes: 2026-04-08 17:14:57
- Detailed description of changes:
  - Refined product overlay interaction styles to look cleaner and more standard across desktop and mobile.
  - Improved overlay panel visual balance with lighter transition, cleaner border radius, and reduced heavy shadow treatment.
  - Optimized mobile overlay container spacing/padding so action controls are no longer visually deformed.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:13:57

- Date and Time of changes: 2026-04-08 17:13:57
- Detailed description of changes:
  - Enhanced the category section visual quality with stronger card styling and cleaner hover depth.
  - Increased category feed capacity and added smart placeholder cards when real categories are fewer, so the section always looks complete.
  - Converted category cards to a mobile swipe carousel for a more modern and user-friendly browsing experience.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:12:44

- Date and Time of changes: 2026-04-08 17:12:44
- Detailed description of changes:
  - Converted the quiz area into a simple, narrow navy strip with minimal UI and clear CTA focus.
  - Removed decorative visual complexity (glow/shadow-heavy treatment) and kept a clean solid brand background.
  - Simplified CTA styling to a straightforward high-contrast button for better usability and click intent.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:11:46

- Date and Time of changes: 2026-04-08 17:11:46
- Detailed description of changes:
  - Fixed layout issues in the "پرفروش‌ترین‌ها" section header for both mobile and desktop.
  - Reworked heading row alignment and spacing to keep title and action link clean, stable, and visually balanced across breakpoints.
  - Added safer text/link wrapping behavior to prevent broken header composition on small screens.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:10:57

- Date and Time of changes: 2026-04-08 17:10:57
- Detailed description of changes:
  - Fixed the mobile visual deformation of the cart icon button in "تازه‌ترین‌ها" product cards.
  - Added a dedicated class for this button and tuned mobile dimensions, corner radius, and icon size for a cleaner UI.
  - Improved button surface styling with balanced shadow and background for a more polished appearance.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:08:27

- Date and Time of changes: 2026-04-08 17:08:27
- Detailed description of changes:
  - Added single-line truncation behavior for product card titles so long names are shown with ellipsis (`...`).
  - Applied this behavior consistently to real and demo products in both top and bottom product sections.
- Components affected:
  - `front-page.php`

----

## 2026-04-08 17:07:28

- Date and Time of changes: 2026-04-08 17:07:28
- Detailed description of changes:
  - Made the "تازه‌ترین‌ها" card footer (price + cart + CTA area) more minimal and polished on mobile with tighter spacing and cleaner CTA treatment.
  - Reworked the "پرفروش‌ترین‌ها" cards to match the successful visual language of the lower section cards for consistent UX.
  - Removed the heavy overlay-style product action panel in top product cards and replaced it with the same clean footer action pattern used in new arrivals.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:05:16

- Date and Time of changes: 2026-04-08 17:05:16
- Detailed description of changes:
  - Fixed desktop hero layering issue where body text could be visually hidden behind the image frame.
  - Raised hero text column stacking context with a higher z-index to ensure consistent readability.
- Components affected:
  - `front-page.php`

----

## 2026-04-08 17:04:16

- Date and Time of changes: 2026-04-08 17:04:16
- Detailed description of changes:
  - Simplified the quiz banner visual style to a cleaner and more minimal UI presentation.
  - Removed extra decorative glow layers and heavy effects from the section background.
  - Kept a straightforward brand-gradient background, refined panel surface, and adjusted heading scale for better readability.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:02:55

- Date and Time of changes: 2026-04-08 17:02:55
- Detailed description of changes:
  - Redesigned the gift section into a more emotional and premium visual style with richer gradients and stronger image overlay depth.
  - Added a highlighted image badge, improved feature-list UI with icons, and upgraded CTA buttons for better conversion-focused hierarchy.
  - Refined typography and interactive feedback across gift section elements for a more attractive and modern UX.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:01:26

- Date and Time of changes: 2026-04-08 17:01:26
- Detailed description of changes:
  - Redesigned the product card bottom price/action row for better mobile UI quality and visual balance.
  - Added a dedicated price chip style and a refined cart action button with improved spacing and touch-friendly sizing.
  - Optimized mobile typography and layout constraints in the price row to prevent cramped appearance.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 17:00:21

- Date and Time of changes: 2026-04-08 17:00:21
- Detailed description of changes:
  - Reduced hero text column width on large screens to improve visual balance and prevent overly wide text lines.
  - Added responsive max-width constraints to the hero content block for cleaner desktop readability.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:59:22

- Date and Time of changes: 2026-04-08 16:59:22
- Detailed description of changes:
  - Updated trust-bar behavior for mobile by showing 4 items on small screens and keeping the 5th item for medium/desktop.
  - Fixed product-card overlay behavior so hover-based action panel is no longer always visible on tablet/desktop; persistent panel behavior is now limited to mobile.
  - Improved mobile product carousel stability with better touch scrolling, snap behavior, and overflow handling for smoother 2-card viewport sliding.
  - Rewrote and upgraded the quiz banner with a richer visual style, larger "موتور شخصی‌سازی" title area, and an enhanced premium panel layout.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:57:19

- Date and Time of changes: 2026-04-08 16:57:19
- Detailed description of changes:
  - Redesigned "تازه‌ترین‌ها" product cards with a more premium UI: cleaner container, badge, stronger hierarchy, and better CTA visibility.
  - Improved product card UX with clearer title area, balanced spacing, refined price/action row, and dedicated full-width "مشاهده سریع" button.
  - Applied matching styles for both real products and demo fallback cards to keep visual consistency before/after real data population.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:55:50

- Date and Time of changes: 2026-04-08 16:55:50
- Detailed description of changes:
  - Refined product overlay action area with standardized spacing, button sizing, and typography for both mobile and desktop.
  - Improved visual hierarchy by introducing dedicated styles for option buttons and the main CTA button.
  - Increased action panel clarity with subtle border/shadow and tuned overlay contrast for better readability.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:54:35

- Date and Time of changes: 2026-04-08 16:54:35
- Detailed description of changes:
  - Redesigned hero section background with a dark navy brand-aligned gradient and soft accent glows.
  - Updated hero text colors for stronger contrast on the new dark background (title and body copy).
  - Introduced dedicated hero CTA styles with high-contrast primary gold button and glass-like secondary button.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:53:34

- Date and Time of changes: 2026-04-08 16:53:34
- Detailed description of changes:
  - Updated mobile product carousel sizing so two product cards are visible side-by-side on small screens.
  - Preserved horizontal swipe carousel behavior for remaining products with snap scrolling.
  - Adjusted mobile carousel gap and card width calculation for cleaner two-up layout.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:52:59

- Date and Time of changes: 2026-04-08 16:52:59
- Detailed description of changes:
  - Added a fifth trust-bar item (`کیفیت ممتاز`) to improve section completeness.
  - Updated desktop layout from 4 to 5 columns for this section and kept mobile as 2-column layout.
  - Standardized card vertical alignment by converting all trust items to full-height flex cards and aligning text baselines consistently.
- Components affected:
  - `front-page.php`

----

## 2026-04-08 16:51:58

- Date and Time of changes: 2026-04-08 16:51:58
- Detailed description of changes:
  - Added demo fallback content for homepage category section when no WooCommerce categories are available.
  - Added demo fallback product cards for both "پرفروش‌ترین‌ها" and "تازه‌ترین‌ها" sections when product query returns empty.
  - Kept current dynamic WooCommerce behavior intact; demo content only appears as temporary visual placeholder for initial site appearance.
- Components affected:
  - `front-page.php`

----

## 2026-04-08 16:49:31

- Date and Time of changes: 2026-04-08 16:49:31
- Detailed description of changes:
  - Converted product sections to mobile swipe carousel behavior while keeping tablet/desktop grid layout unchanged.
  - Added dedicated mobile carousel classes for product containers and product cards to enable horizontal snapping.
  - Implemented touch-friendly snap scrolling, hidden scrollbars, and stable card width for cleaner mobile UX.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:48:36

- Date and Time of changes: 2026-04-08 16:48:36
- Detailed description of changes:
  - Aligned testimonial cards visually by enforcing equal-height card behavior in the grid layout.
  - Converted each testimonial card to a vertical flex container and pinned customer meta row to the bottom for consistent baseline alignment.
  - Ensured all cards stay in one visual row with cleaner rhythm across different text lengths.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:47:35

- Date and Time of changes: 2026-04-08 16:47:35
- Detailed description of changes:
  - Fixed product-card overlay action panel layout so the inner white action box expands to full available card width.
  - Updated button sizing to use full-width layout for stable alignment and better readability in mobile and desktop.
  - Improved CTA row consistency by enforcing full-width action area and centered label/icon alignment.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:46:36

- Date and Time of changes: 2026-04-08 16:46:36
- Detailed description of changes:
  - Standardized homepage typography scale for mobile and desktop with reusable title/body utility classes.
  - Made all major section titles visually stronger by applying consistent bold weight and responsive heading sizes.
  - Normalized supporting paragraph sizes and line-heights to improve readability and visual hierarchy across breakpoints.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:44:18

- Date and Time of changes: 2026-04-08 16:44:18
- Detailed description of changes:
  - Upgraded the trust/benefit section (`تضمین اصالت`, `ارسال فوری`, ...) to a cleaner card-based UI.
  - Added better icon containers, subtle borders, compact explanatory text, and improved hover feedback.
  - Increased readability and hierarchy for mobile/tablet while preserving brand consistency in desktop.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:43:07

- Date and Time of changes: 2026-04-08 16:43:07
- Detailed description of changes:
  - Fully redesigned the gift section (`هدیه‌ای به یاد ماندنی`) with a cleaner and more conversion-focused UI layout.
  - Replaced old split block with a structured 12-column responsive composition for better desktop/tablet/mobile behavior.
  - Added clearer content hierarchy, benefit bullets, and dual CTA actions for improved usability and user decision flow.
  - Updated copy and spacing to follow UI readability standards and maintain brand visual consistency.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:42:09

- Date and Time of changes: 2026-04-08 16:42:09
- Detailed description of changes:
  - Continued the category section enhancement by making homepage category cards manually configurable.
  - Added native theme setting in Customizer to enter WooCommerce category IDs in desired order.
  - Updated homepage category query to support manual category list and preserve manual sorting.
  - Added a user-friendly fallback notice when no categories are available to show.
- Components affected:
  - `inc/acf.php`
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:38:44

- Date and Time of changes: 2026-04-08 16:38:44
- Detailed description of changes:
  - Fully redesigned the homepage category section into a cleaner and more engaging category showcase.
  - Replaced static cards with dynamic WooCommerce parent product categories.
  - Added category image support (term thumbnail), product count, and clear CTA (`ورود به دسته`).
  - Improved hierarchy with a dedicated section heading and consistent card interaction behavior.
- Components affected:
  - `front-page.php`

----

## 2026-04-08 16:37:12

- Date and Time of changes: 2026-04-08 16:37:12
- Detailed description of changes:
  - Reduced desktop content width to avoid full-width appearance and improve visual focus.
  - Applied responsive max-width override for homepage containers on large screens.
  - Kept tablet/mobile behavior unchanged while tightening desktop layout composition.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:36:30

- Date and Time of changes: 2026-04-08 16:36:30
- Detailed description of changes:
  - Applied an additional UI/UX responsive pass focused on interaction quality in mobile/tablet.
  - Improved product card usability on touch devices by making overlay actions directly accessible (not hover-dependent).
  - Reduced visual friction on small screens by removing forced grayscale behavior on product imagery.
  - Fine-tuned quiz banner mobile readability and alignment consistency.
- Components affected:
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:35:32

- Date and Time of changes: 2026-04-08 16:35:32
- Detailed description of changes:
  - Enforced Vazir font across all homepage textual elements.
  - Added a dedicated homepage font scope wrapper and a strict CSS rule to apply Vazir to all descendants.
  - Excluded Material Symbols icon elements from the font override to preserve icon rendering.
  - Rebuilt CSS bundle to apply the font enforcement in production assets.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:34:33

- Date and Time of changes: 2026-04-08 16:34:33
- Detailed description of changes:
  - Performed a responsive UX pass for homepage across mobile, tablet, and desktop breakpoints.
  - Rebalanced section spacing, typography scale, and media heights to avoid oversized UI on small and medium screens.
  - Improved button usability by making key CTAs full-width on mobile and better sized on larger viewports.
  - Optimized card density and readability in product, magazine, and testimonial sections with breakpoint-aware paddings and text sizes.
  - Refined trust bar icon sizing and heading rhythm for clearer visual hierarchy on mobile/tablet.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:32:16

- Date and Time of changes: 2026-04-08 16:32:16
- Detailed description of changes:
  - Redesigned testimonial cards section to a more polished and user-friendly visual style.
  - Added decorative quote mark, 5-star rating row, improved text hierarchy, and customer meta area.
  - Introduced subtle hover lift and shadow for better interaction quality and readability.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:25:37

- Date and Time of changes: 2026-04-08 16:25:37
- Detailed description of changes:
  - Removed the visual site header from homepage by deleting the top navigation block.
  - Removed the visual site footer from homepage by deleting the footer strip block.
  - Adjusted hero top spacing after header removal to keep layout balanced.
- Components affected:
  - `front-page.php`

----

## 2026-04-08 16:24:52

- Date and Time of changes: 2026-04-08 16:24:52
- Detailed description of changes:
  - Redesigned best-seller product cards to match the requested attractive style from `Home/code.html`.
  - Added top badge, hover overlay panel, size option buttons, and an in-overlay primary action.
  - Enhanced card metadata with brand label, star rating, review count, and preserved add-to-cart quick action.
  - Updated card hierarchy for better visual merchandising and user engagement.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:23:38

- Date and Time of changes: 2026-04-08 16:23:38
- Detailed description of changes:
  - Redesigned the primary blue banner background layer to be visibly richer and more branded using layered gradients and glow accents.
  - Fixed quiz CTA visibility: button is now always clearly visible (not hover-dependent) with stronger contrast and depth.
  - Restored and styled hero action buttons (خرید کالکشن / مشاوره تخصصی) in the requested button container area.
  - Improved product cards by adding a permanent visible action button (`مشاهده محصول`) for better discoverability without hover.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:22:22

- Date and Time of changes: 2026-04-08 16:22:22
- Detailed description of changes:
  - Added Vazir font locally inside theme assets and configured it through `@font-face`.
  - Applied Vazir as the main body font and updated font helper class to use Vazir.
  - Updated Tailwind font family config to use Vazir for sans text.
  - Persianized remaining English visible labels on homepage sections and updated key image alt texts to Persian.
  - Rebuilt assets so local Vazir fonts are bundled and served from internal files.
- Components affected:
  - `assets/fonts/Vazir-Regular.woff2`
  - `assets/fonts/Vazir-Bold.woff2`
  - `assets/src/css/app.css`
  - `tailwind.config.js`
  - `front-page.php`
  - `inc/template-tags.php`
  - `search.php`
  - `404.php`
  - `inc/setup.php`
  - `inc/acf.php`
  - `assets/dist/css/app.css`
  - `assets/dist/a360dc26407972d3cb7d.woff2`
  - `assets/dist/0135812b3d7792a1ec26.woff2`

----

## 2026-04-08 16:14:42

- Date and Time of changes: 2026-04-08 16:14:42
- Detailed description of changes:
  - Optimized homepage visual scale for desktop to be more compact and UI-standard.
  - Reduced oversized hero typography, section vertical spacing, and large media block heights.
  - Tightened container paddings and gaps across trust bar, category cards, best sellers, quiz banner, new arrivals, gift section, magazine, and testimonials.
  - Improved visual balance for a cleaner, more premium and readable desktop layout.
- Components affected:
  - `front-page.php`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:11:55

- Date and Time of changes: 2026-04-08 16:11:55
- Detailed description of changes:
  - Continued homepage implementation from `Home/code.html` and completed missing UI utility classes.
  - Added missing style primitives required by Home design: `btn-primary`, `btn-outline`, `glass-panel`, serif/vazir helpers, and body baseline styles.
  - Rebuilt CSS output to reflect the latest homepage visual parity updates.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:01:27

- Date and Time of changes: 2026-04-08 16:01:27
- Detailed description of changes:
  - Reworked the homepage implementation to follow `noble_perfume_premium_shop_experience/code.html` visual direction.
  - Rebuilt `front-page.php` structure with premium shop layout and WooCommerce-powered product grid.
  - Removed dependency on external icon fonts by adding local icon fallback mapping for `.material-symbols-outlined`.
  - Added a dedicated design rule file to govern all UI changes going forward.
  - Updated CSS component rules to align cards, overlays, color system, and utility classes with the premium design.
  - Rebuilt local assets to ensure Tailwind output remains internal and production-ready.
- Components affected:
  - `front-page.php`
  - `header.php`
  - `footer.php`
  - `assets/src/css/app.css`
  - `assets/src/js/app.js`
  - `assets/dist/css/app.css`
  - `assets/dist/js/app.js`
  - `DESIGN_SYSTEM.md`

----

## 2026-04-08 16:03:08

- Date and Time of changes: 2026-04-08 16:03:08
- Detailed description of changes:
  - Switched the homepage source of truth to `Home/code.html` as requested.
  - Replaced `front-page.php` structure so the site homepage now follows the Home layout, not the product archive layout.
  - Kept product and blog sections dynamic using WooCommerce products and WordPress posts while preserving the Home visual language.
  - Improved local icon fallback coverage for additional icons used in Home design.
  - Rebuilt local Tailwind output to apply the new homepage styles.
- Components affected:
  - `front-page.php`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`

----

## 2026-04-08 16:07:11

- Date and Time of changes: 2026-04-08 16:07:11
- Detailed description of changes:
  - Switched icon implementation to real Google Material Symbols loaded locally (no CDN).
  - Added local font file source into theme assets and configured `.material-symbols-outlined` via `@font-face`.
  - Removed temporary pseudo-icon fallback mappings and kept original ligature-based Material icon behavior.
  - Rebuilt theme assets so local icon font is bundled and served from project files.
- Components affected:
  - `assets/fonts/material-symbols-outlined.woff2`
  - `assets/src/css/app.css`
  - `assets/dist/css/app.css`
  - `assets/dist/c4fe82ca3ad65b9063e7.woff2`

----

## 2026-04-08 15:57:05

- Date and Time of changes: 2026-04-08 15:57:05
- Detailed description of changes:
  - Implemented a full classic WordPress theme skeleton with modular `inc/` architecture.
  - Added core templates: `index.php`, `single.php`, `page.php`, `archive.php`, `search.php`, `404.php`.
  - Implemented homepage development flow from `premium_noble_perfume_homepage_vazirmatn_edition/code.html` into `front-page.php`.
  - Added local Tailwind-based asset loading from theme files (no CDN at runtime).
  - Added blog features: breadcrumbs, pagination compatibility, related posts with transient caching.
  - Implemented CPT + Taxonomy registration in code.
  - Removed ACF dependency and replaced with native WordPress Customizer option(s).
  - Added WooCommerce support and template overrides for product archive and product cards.
  - Added base SEO schema output and performance improvement via deferred script loading.
  - Added documentation in `README.md`.
- Components affected:
  - `style.css`
  - `functions.php`
  - `header.php`
  - `footer.php`
  - `front-page.php`
  - `index.php`
  - `page.php`
  - `single.php`
  - `archive.php`
  - `search.php`
  - `404.php`
  - `woocommerce.php`
  - `woocommerce/archive-product.php`
  - `woocommerce/content-product.php`
  - `template-parts/components/content.php`
  - `template-parts/components/content-search.php`
  - `template-parts/components/content-none.php`
  - `inc/setup.php`
  - `inc/enqueue.php`
  - `inc/template-tags.php`
  - `inc/acf.php`
  - `inc/cpt.php`
  - `inc/woocommerce.php`
  - `inc/seo.php`
  - `assets/src/css/app.css`
  - `assets/src/js/app.js`
  - `assets/dist/css/app.css`
  - `assets/dist/js/app.js`
  - `README.md`

----
