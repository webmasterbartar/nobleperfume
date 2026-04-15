# Noble Theme Design System

این فایل قانون مرجع توسعه UI در قالب است. تمام تغییرات ظاهری باید بر اساس این سند انجام شود.

## 1) اصول کلی

- سبک کلی: لوکس، مینیمال، فضای روشن با کنتراست بالا.
- جهت متن: RTL در کل سایت.
- اولویت: خوانایی، سرعت لود، سازگاری با WooCommerce.
- ممنوع: اضافه کردن CDN برای Tailwind یا آیکن‌ها در محیط تولید.

## 2) توکن‌های رنگ

- `--noble-primary`: `#051061`
- `--noble-background`: `#ebf1ff`
- `--noble-surface`: `#ffffff`
- `--noble-accent`: `#c8a84b`
- `--noble-border`: `#c9d5f7`
- `--noble-on-surface`: `#151c26`

کلاس‌های مرجع:

- `bg-background`, `bg-primary`, `bg-surface`
- `text-primary`, `text-on-surface`, `text-on-surface-variant`, `text-tertiary`
- `border-border-light`, `border-outline-variant`

## 3) تایپوگرافی

- عنوان برند/Display: `font-playfair`
- متن بدنه: `Vazirmatn` یا fallback استاندارد
- سایزها:
  - H1: بزرگ و برجسته (در Hero تا `text-6xl` یا بالاتر)
  - H2: `text-4xl` تا `text-6xl`
  - متن بدنه: `text-sm` تا `text-base`

## 4) کامپوننت‌ها

- Product Card:
  - پایه: `product-card`
  - hover overlay: `product-card-overlay`
  - گوشه‌های گرد بزرگ (`rounded-2xl`)
- Tab فیلترها: pill button با border نرم و hover روشن.
- سایدبار فیلتر: white card + border light + sticky.

## 5) آیکن‌ها

- کلاس استاندارد: `.material-symbols-outlined`
- در صورت نبود فونت آیکن، fallback داخلی باید نمایش داده شود (در `assets/src/css/app.css` و `assets/src/js/app.js`).
- هر آیکن باید `data-icon` معتبر داشته باشد یا از متن داخلی آن استخراج شود.

## 6) قوانین SEO/Performance UI

- برای تصاویر محصولات:
  - lazy loading برای آیتم‌های لیست
  - alt معتبر
- JS غیرحیاتی باید defer باشد.
- از انیمیشن‌های سبک و کوتاه استفاده شود.

## 7) قانون توسعه

هر PR یا تغییر UI باید این چک‌لیست را پاس کند:

1. تطابق با توکن‌های رنگی این سند
2. عدم استفاده از CDN در تولید
3. حفظ کلاس‌های مرجع کامپوننت‌ها
4. عدم شکست RTL
5. بررسی نمایش موبایل و دسکتاپ
