# CLAUDE.md

Better RTL Support: a small WordPress plugin (slug `better-rtl-support`, wp.org contributor `salar6990`) that makes
sites in Persian and other RTL languages look right: the bundled Vazirmatn font (classic + block themes, block editor,
optionally the dashboard) and per-theme RTL layout fixes. Settings under Appearance → RTL Settings.
Sibling project: `C:\Users\Salar\Dev\api-stats-project` (wp-api-stats) uses the same layout, scripts and release flow.

## Source of truth & release flow
- **Git is the source of truth.** wordpress.org SVN is a deploy target only; never edit SVN by hand.
- Plugin code lives in `src/`. Anything outside `src/` never ships.
- `bash bin/build.sh` → `dist/better-rtl-support/` (+ zip). It fails if `Version:` in
  `src/better-rtl-support.php` ≠ `Stable tag:` in `src/readme.txt`. `*.scss`/`*.map` are excluded.
- Release: tag `vX.Y.Z` and push (CI `release.yml` builds the zip for a GitHub release), then `bash bin/deploy.sh --commit`
  publishes to SVN locally. Without `--commit` it is a dry run into `./svn`. `--readme-only` updates readme/assets of the
  current stable tag without a new version.
- SVN credentials are in git-ignored `.env` (`SVN_USERNAME`, `SVN_PASSWORD`; same wp.org account as wp-api-stats). Never
  commit them, never put them in GitHub secrets/workflows, never echo them. Publishing to wordpress.org ships to all
  users: get the user's explicit OK first.
- wp.org listing images go in `.wordpress-org/` (`icon-128x128.png`, `icon-256x256.png`, `screenshot-N.png`, ...).
- History: 1.0–2.1.0 were developed on GitHub `master` and copied to SVN by hand; git `master` == SVN `tags/2.1.0`
  (tag `v2.1.0`). The repo was restructured into `src/` on `main` for 3.0.0. Remote also has an old, merged `dev` branch.
- Default branch is `main`. Line endings: LF (enforced by `.gitattributes`).

## Code map (`src/`)
- `better-rtl-support.php`: plugin header, `BRTLS_VERSION`, `BRTLS_FILE`, instantiates `Better_RTL_Support` into global `$BRTLS`.
- `class-better-rtl-support.php`: `Better_RTL_Support` (was `better_RTL_support`; PHP class names are case-insensitive).
  - Settings: option `brtls_settings` = `font` (`vazirmatn`|`none`), `font_site`, `font_editor`, `font_admin`,
    `theme_fixes` (0/1). `defaults()`, `get_settings()`, `sanitize_settings()`; saved through `options.php`
    (`register_setting` group `brtls`, nonce + `manage_options` handled by core).
  - `font_enabled( $context )` (`site`|`editor`|`admin`): only when `is_rtl()`. Everything is a no-op on LTR locales.
  - **Classic themes**: `assets/front.css` sets `font-family: var(--brtls-font-text)` on a selector list (no `*`, so icon
    fonts survive), prefixed `html:root body.rtl` for specificity.
  - **Block themes** with font presets (`uses_theme_json_fonts()`): `filter_theme_json` on `wp_theme_json_data_theme`
    rewrites every non-monospace theme font preset to the font stack and drops its `fontFace`. Front loads only
    `assets/block-theme.css` (form controls inherit). WP 6.6+ returns presets keyed by origin (`fontFamilies.theme`).
  - Editor: `enqueue_block_assets` (admin only) → font + `assets/editor.css` (classic themes; block themes get theme.json).
  - Admin: `admin_enqueue_scripts` → `assets/admin.css`; also on the front for the admin bar.
  - Theme fixes: `THEME_FIXES` lists templates with `assets/themes/<template>.css` (`hueman`, `twentyseventeen`,
    `twentysixteen`), loaded only for that theme. Twenty Sixteen/Seventeen hang list markers outside the text column by
    design and Hueman never mirrors its list margin, so in RTL bullets stuck out on the right; the fixes keep them inside.
    Hueman ships no RTL stylesheet at all: hueman.css mirrors its LTR rules (header, widgets, nav, comments, lists,
    `.post-list` grid, related posts, `.heading i` icon). Checked against Hueman 3.7.27 (`assets/front/css/dev-common.css`). Body class `theme-<template>` is added for them.
  - `--brtls-font-text` CSS variable is printed inline after `font-faces/Vazirmatn.css` (handle `brtls-fontface-vazirmatn`).
- `views/settings-page.php`: Appearance → RTL Settings (`themes.php?page=rtl-settings`). Lists tested themes, says other
  themes may work too, and links to the wp.org support forum (https://wordpress.org/support/plugin/better-rtl-support/)
  for problem reports. Keep that wording in sync with readme.txt.
- `uninstall.php`: deletes `brtls_settings`.
- `fonts/Vazirmatn/Vazirmatn-Variable.woff2`: upstream Vazirmatn v33.003 `Vazirmatn[wght].woff2` (latest release as of
  2026-10), weights 100–900, plus `OFL.txt`. Upstream: https://github.com/rastikerdar/vazirmatn/releases.
- `languages/`: text domain `better-rtl-support` (= slug, needed for translate.wordpress.org). Regenerate after changing
  strings: `wp i18n make-pot src src/languages/better-rtl-support.pot --domain=better-rtl-support`, update
  `better-rtl-support-fa_IR.po`, then `wp i18n make-mo src/languages/better-rtl-support-fa_IR.po src/languages`.
- Handle names `brtls-fontface-vazirmatn` and `brtls-styles` are from 2.x; keep them (themes may dequeue them).

## Public API (documented in readme FAQ; keep backwards compatible)
- `brtls_font_stack( string $stack )`: CSS font-family value (default `'Vazirmatn', sans-serif`).
- `brtls_font_enabled( bool $enabled, string $context )`: context `site`|`editor`|`admin`.
- CSS variable `--brtls-font-text`; body class `theme-<template>`; global `$BRTLS`.

## Local dev environment (Windows box)
- Laravel **Herd** serves `C:\Users\Salar\Dev\<folder>` as `<folder>.test` (Herd must be running).
- The sample post "راهنمای شروع وبلاگ‌نویسی" (ID 72) on the test site is the screenshot content; keep it.
- Test site: **http://wordpress.test** = `C:\Users\Salar\Dev\wordpress` (WP 7.1.x, Persian `fa_IR` site language, PHP 8.3).
  Shared with wp-api-stats. Installed themes for testing: hueman (active, 3.7.27), twentysixteen, hueman, astra, betheme, twentynineteen …
  twentytwentyfive (block themes: twentytwentytwo and later).
- `wordpress\wp-content\plugins\better-rtl-support` is a **junction to this repo's `src/`**, so edits are live.
  Recreate (PowerShell/cmd, not Git Bash): `cmd /c mklink /J C:\Users\Salar\Dev\wordpress\wp-content\plugins\better-rtl-support C:\Users\Salar\Dev\better-rtl-support-project\src`.
  Remove only with `cmd /c rmdir <link>` (never `rm -rf`, which follows into `src/`).
- **Never run `wp plugin uninstall` / `wp plugin delete` on this plugin** (deletes `src/` through the junction). To test
  uninstall: `wp eval 'define("WP_UNINSTALL_PLUGIN", "better-rtl-support/better-rtl-support.php"); include WP_PLUGIN_DIR . "/better-rtl-support/uninstall.php";'`.
- WP-CLI is not installed globally: download `wp-cli.phar` from https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  (verify the `.sha512`), run it with Herd's PHP from the WP dir: `~/.config/herd/bin/php83/php.exe wp-cli.phar <cmd>`.
- PHP: Herd `~/.config/herd/bin/php83` and `php74`; system CLI PHP 8.2. MariaDB details: see the wp-api-stats CLAUDE.md.
- Admin-page checks over HTTP: create a throwaway admin with WP-CLI, log in with curl cookies, delete the user afterwards.
- On Ubuntu: symlink `src/` into any local WP's `wp-content/plugins/better-rtl-support`; set the site language to fa_IR.

## Smoke test checklist
1. Site language fa_IR. Classic theme (twentysixteen, astra): front page loads `brtls-fontface-vazirmatn` (+ inline
   `--brtls-font-text`) and `brtls-styles` (`front.css`); fonts/CSS URLs return 200; no PHP notices from this plugin
   (themes have their own `_load_textdomain_just_in_time` / `add_data` deprecation notices).
2. Hueman: also `brtls-theme-hueman`. Block theme (twentytwentyfive): `--wp--preset--font-family--manrope` is the
   Vazirmatn stack, `fira-code` unchanged, `brtls-styles` is `block-theme.css`.
3. Settings page renders (in Persian), saving works, unknown keys dropped, bad nonce → 403. Toggles: dashboard font
   (`brtls-admin` on wp-admin and front admin bar), editor font (`brtls-editor` in post-new.php for classic themes).
4. Switch site to en_US: nothing from the plugin is loaded. Switch back to fa_IR afterwards.
5. Uninstall (see above) deletes `brtls_settings`.

## wp.org screenshots
- Made 2026-10-12 with headless Edge + `puppeteer-core` (npm, in a scratch dir) at 1280px wide, `--disable-lcd-text`,
  on wordpress.test with temporary generic Persian demo posts (blogging tips; no real places or people), then removed.
  1 = Twenty Twenty-Five post, 2 = with/without comparison (same crop of both), 3 = settings page (`#wpbody-content`),
  4 = Twenty Sixteen, 5 = Hueman post incl. related posts (1280x1125). Themes print PHP notices under WP_DEBUG: strip them from the DOM before capturing.
  Turn `show_avatars` off while capturing (the admin account's Gravatar is a real photo).
  To check list bullets across themes, compare the first `li`'s right edge with the paragraph's right edge.
  Captions live in readme.txt `== Screenshots ==`.

## Checks (run before committing)
- `composer install` once, then `vendor/bin/phpcs` (WordPress-Extra + Security + PHPCompatibility 7.2+, config in
  `phpcs.xml.dist`). Must be clean. `vendor/bin/phpcbf` auto-fixes style.
- `php -l` on changed files; `bash bin/build.sh` before a release.
- CI (`.github/workflows/lint.yml`) runs the same on push/PR.

## Conventions
- Prefix `brtls` / `BRTLS` / `better_rtl` for new globals, hooks, options, handles. Text domain `better-rtl-support`.
- Minimum: WordPress 5.3, PHP 7.2. Block theme features need WP 6.1+ and must degrade (check `function_exists`/`class_exists`).
- Plain CSS, no build step. Never use `*` selectors for the font (breaks Dashicons/icon fonts).
- Escape everything on output; the font stack from the filter is stripped of `{};<>` before going into inline CSS.
- Bump `BRTLS_VERSION`, header `Version:` and readme `Stable tag:` together.

## Known issues / TODO
- readme.txt is English-only on purpose: Persian comes from translate.wordpress.org (`stable-readme` and `stable`
  projects, locale `fa`). The Persian banner on wordpress.org/plugins goes away once those are approved.
- BeTheme (commercial, not in the tested list) has the same unmirrored list margin as Hueman.
- 3.0.0 released 2026-10-12 (SVN r3740149, GitHub release v3.0.0); git `main` == SVN `tags/3.0.0`.
- GitHub default branch is still `master`; switch it to `main` in repo settings, then delete `master` (and old `dev`).
- Ideas: more theme fixes (Astra, popular Persian themes), Persian-digit Vazirmatn variant, Round-Dots variant,
  banner for wp.org, PHPUnit tests.
