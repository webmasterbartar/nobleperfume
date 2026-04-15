# Design System: The Editorial Scent

## 1. Overview & Creative North Star
**Creative North Star: "The Digital Atelier"**

This design system is not a store; it is a curated exhibition. To reflect the heritage and luxury of 'Noble Perfume' (نوبل پرفیوم), we move away from the "grid of boxes" common in e-commerce. Instead, we embrace **The Digital Atelier**—a philosophy that treats every screen as a high-end editorial spread. 

By leveraging **intentional asymmetry**, **tonal layering**, and **expansive whitespace**, we create an aspirational boutique experience. The layout should feel "composed" rather than "generated," using the contrast between the sharp elegance of *Playfair Display* and the functional clarity of *Vazirmatn* to guide the user through a sensory journey.

---

## 2. Colors & Surface Philosophy

The color palette is anchored in a regal Deep Navy (`primary`) and a Soft Periwinkle (`background`). We avoid the starkness of pure black and white to maintain a "velvet" atmosphere.

### The "No-Line" Rule
Standard e-commerce relies on 1px borders to separate content. **We prohibit this.** To achieve a premium feel, boundaries must be defined solely through background color shifts. Use `surface-container-low` sections against a `surface` background to denote change.

### Surface Hierarchy & Nesting
Treat the UI as a physical desk of fine stationery.
- **Base Level:** `surface` (#f8f9ff)
- **Deepest Level:** `surface-container-low` (#eff3ff) for large background sections.
- **Elevated Level:** `surface-container-lowest` (#ffffff) for primary product cards and interactive modules.
- **Nesting:** Always place a "High" tier container inside a "Low" tier background to create natural depth.

### The Glass & Gradient Rule
To prevent a "flat" appearance, apply a subtle linear gradient to Hero sections and Primary CTAs:
- **CTA Gradient:** `primary` (#00011e) to `primary-container` (#051061) at a 135-degree angle.
- **Glassmorphism:** For floating navigation or quick-view modals, use `surface` at 80% opacity with a `20px` backdrop-blur. This keeps the user connected to the "scent" of the content beneath.

---

## 3. Typography: The Bilingual Dialogue

The typography scale is designed to handle the grace of Persian calligraphy alongside the authority of Serif Latin.

*   **Display & Headlines (Playfair Display / Vazirmatn Bold):** Used for product names and editorial titles. The large `display-lg` (3.5rem) should be used with generous letter-spacing to command attention.
*   **Body & Titles (Vazirmatn / Manrope):** Manrope serves as the Latin counterpart to Vazirmatn for technical details. Use `body-lg` for product descriptions to ensure "breathability" and readability.
*   **The Brand Voice:** Headlines should often be center-aligned or intentionally offset to the right (RTL) to break the standard vertical rhythm, creating a sense of bespoke craftsmanship.

---

## 4. Elevation & Depth

We move beyond traditional box-shadows toward **Tonal Layering**.

### The Layering Principle
Depth is achieved by "stacking." A product card (`surface-container-lowest`) sitting on a category background (`surface-container-low`) provides a soft, natural lift that feels architectural rather than digital.

### Ambient Shadows
When a card must float (e.g., a hovered perfume bottle), use an **Ambient Shadow**:
- **Y-offset:** 12px | **Blur:** 32px | **Spread:** -4px
- **Color:** `on-surface` (#151c26) at **6% opacity**. 
- *Note:* The shadow should feel like a soft glow, never a dark smudge.

### The "Ghost Border" Fallback
If a container requires a border for accessibility (e.g., input fields), use a **Ghost Border**:
- **Token:** `outline-variant` (#c6c5d2) at **20% opacity**.
- **Rule:** Never use 100% opaque borders for decorative containment.

---

## 5. Components

### Buttons: The Signature Touch
- **Primary:** High-contrast `primary` background. 8px (`md`) radius. Text is `on-primary`. On hover, transition to the `secondary` indigo.
- **Tertiary (Gold Accent):** Use `tertiary` (#745b00) for "Limited Edition" or "Add to Collection" actions. This creates a visual "reward" for the user.

### Cards & Lists: The No-Divider Policy
- **Cards:** Use `md` (12px) border-radius. Forbid the use of divider lines within cards. Separate the "Price" from the "Title" using vertical white space (8px–12px) or a `surface-variant` background for the footer area of the card.
- **Lists:** Product lists should use alternating `surface` and `surface-container-low` backgrounds instead of horizontal rules.

### Input Fields
- **Style:** Underlined or "Soft Fill." Avoid the "all-around" box if possible. Use `surface-container-high` for the field background with a `ghost border` bottom-stroke.
- **Focus State:** Transition the bottom stroke to `accent gold` (#c8a84b).

### Signature Component: The "Fragrance Note" Chip
- Use a semi-transparent `secondary-container` background with `on-secondary-container` text. These should be pill-shaped (`full` radius) and used to tag scent profiles (e.g., Oud, Jasmine, Musk).

---

## 6. Do’s and Don’ts

### Do:
*   **Do** use asymmetrical margins. A product image may bleed off the edge of a container to create a high-fashion look.
*   **Do** use `tertiary-container` (#c8a84b) sparingly as a "highlight" color for luxury markers or badges.
*   **Do** prioritize RTL flow by ensuring the Playfair Display numbers (prices) are elegantly integrated with Vazirmatn text.

### Don’t:
*   **Don't** use 1px solid borders to separate sections. Use color shifts.
*   **Don't** use standard black (#000000). Use the `on-primary-fixed` (#020d5f) for maximum contrast.
*   **Don't** crowd the interface. If you think there is enough whitespace, add 20% more. Luxury is the ability to "waste" space.
*   **Don't** use heavy shadows. If the shadow is visible at a glance, it is too dark. It should be felt, not seen.