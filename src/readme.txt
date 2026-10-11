=== Better RTL Support ===
Contributors: salar6990
Tags: rtl, persian, farsi, fonts, vazirmatn
Requires at least: 5.3
Tested up to: 7.1
Stable tag: 3.0.0
Requires PHP: 7.2
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Better right-to-left support for popular themes: the free Vazirmatn Persian font and RTL layout fixes.

== Description ==

پشتیبانی بهتر از زبان‌های راست به چپ برای وردپرس

این افزونه کمک می‌کند سایت وردپرسی شما با پوسته‌های پرطرفدار، مانند پوسته‌های پیش‌فرض وردپرس، آسترا، هیومن و دیگر پوسته‌ها، در زبان فارسی و دیگر زبان‌های راست به چپ درست‌تر و زیباتر نمایش داده شود.

*   فونت وزیرمتن، همراه افزونه و بدون درخواست به سرورهای خارجی. این فونت حاصل دسترنج زنده‌یاد صابر راستی‌کردار است.
*   سازگار با پوسته‌های بلوکی (مانند دو هزار و بیست و پنج): فونت‌های پوسته جایگزین می‌شوند و انتخاب فونت در ویرایشگر سایت همچنان کار می‌کند.
*   فونت در سایت، ویرایشگر بلوک و (به انتخاب شما) پیشخوان.
*   رفع مشکلات چیدمان راست به چپ پوسته هیومن.
*   فقط وقتی زبان سایت راست به چپ است کاری انجام می‌دهد.

افزونه با پوسته‌های بالا آزمایش شده است، اما احتمالاً با پوسته‌های دیگری که آزمایش نشده‌اند هم به خوبی کار می‌کند.

تنظیمات: نمایش ← تنظیمات راست به چپ

**English**

Makes WordPress sites in Persian and other right-to-left languages look right with popular themes.

*   Bundled Vazirmatn font (one variable font file, all weights; no requests to external servers).
*   Block themes (Twenty Twenty-Five, Twenty Twenty-Four, ...): the theme's font presets are switched to Vazirmatn, so font choices in the Site Editor keep working. Monospace fonts are left alone.
*   Choose where the font is used: the site, the block editor, the dashboard and admin bar.
*   RTL layout fixes for the Hueman theme.
*   Does nothing unless the current language is right-to-left.

Settings: Appearance → RTL Settings.

Tested themes: Astra, Hueman, Twenty Twenty-Five, Twenty Twenty-Four, Twenty Twenty-Three, Twenty Twenty-Two, Twenty Twenty-One, Twenty Twenty, Twenty Nineteen, Twenty Seventeen, Twenty Sixteen. The plugin may also work well with other, untested themes.

Found a problem with your theme? Report it in the [support forum](https://wordpress.org/support/plugin/better-rtl-support/). Development happens on GitHub: [salar90/better-rtl-support](https://github.com/salar90/better-rtl-support).

== Installation ==

1. Install and activate the plugin from Plugins → Add New, or upload it to `wp-content/plugins`.
2. Go to Appearance → RTL Settings to choose where the font is used.

== Frequently Asked Questions ==

= Nothing changed on my site =
The plugin only works when the site language (Settings → General) or, in the dashboard, your profile language is a right-to-left language such as Persian or Arabic.

= Can I use another font? =
Yes. Choose "Theme default" to keep your theme's fonts, or set your own font stack in code (load its @font-face yourself):

`add_filter( 'brtls_font_stack', function () { return "'Sahel', Tahoma, sans-serif"; } );`

= Can I turn the font off for some pages? =
Use the `brtls_font_enabled` filter. It receives the current value and the context (`site`, `editor` or `admin`):

`add_filter( 'brtls_font_enabled', function ( $enabled, $context ) { return 'site' === $context && is_page( 'english' ) ? false : $enabled; }, 10, 2 );`

The CSS variable `--brtls-font-text` holds the font stack, if you want to use it in your own styles.

== Screenshots ==

1. Twenty Sixteen in Persian with the plugin active (Vazirmatn font).
2. The same page without the plugin.

== Changelog ==

= 3.0.0 =
* New: settings page (Appearance → RTL Settings): turn the font off, and choose to use it in the site, the block editor and the dashboard.
* New: block theme support. The theme's font presets switch to Vazirmatn, so the Site Editor and per-block font choices keep working.
* New: the font in the block editor and, optionally, in the dashboard and admin bar.
* New: `brtls_font_stack` and `brtls_font_enabled` filters.
* Improved: one variable Vazirmatn file covers every weight (100-900); the plugin is about 470 KB smaller and light, medium and semibold text no longer uses fake weights.
* Improved: nothing is loaded on sites in left-to-right languages, and theme fixes load only for their theme.
* Improved: stylesheets are versioned, so browsers pick up changes after an update.
* Changed: text domain is now `better-rtl-support`, so translations from translate.wordpress.org work. Persian translation included.
* Changed: requires WordPress 5.3 and PHP 7.2.
* Added the Vazirmatn license (SIL Open Font License).
* Settings are removed when the plugin is deleted.

= 2.1.0 =
* Updated plugin description
* Test with WP 6.4.1
* Added Screenshots

= 2.0.2 =
* Fix versioning

= 2.0.1 =
* Fix a bug where fonts did not load on some installs

= 2.0 =
* Switch from Iransans to Vazirmatn free font
* Updated Hueman social network display
* fixed some issues with default themes
* checked compatibility with WordPress 6.3.1

= 1.0.10 =
* Added Translation files

= 1.0.6 =
* Hueman Search position

= 1.0.5 =
* Hueman Nav menu

= 1.0.2 =
* tested with WordPress 5.2.2
* fix comment reply link style

= 1.0 =
* first release.

== Upgrade Notice ==

= 3.0.0 =
Settings page, block theme support and a smaller variable font. Requires PHP 7.2 and WordPress 5.3.
