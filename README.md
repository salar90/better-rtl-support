# Better RTL Support

WordPress plugin for better right-to-left support in popular themes: the bundled, free Vazirmatn Persian font
(classic and block themes, block editor, optionally the dashboard) and RTL layout fixes for themes like Hueman.
Settings under **Appearance → RTL Settings**.

پشتیبانی بهتر از زبان‌های راست به چپ برای وردپرس: فونت وزیرمتن (حاصل دسترنج زنده‌یاد صابر راستی‌کردار) و رفع مشکلات چیدمان پوسته‌ها.

- wordpress.org: https://wordpress.org/plugins/better-rtl-support/
- This git repo is the source of truth. SVN on wordpress.org is only a deploy target.

## Layout

```
src/              The plugin itself (exactly what ships). readme.txt lives here.
.wordpress-org/   wp.org listing assets: icon, banners, screenshots (→ SVN assets/)
bin/build.sh      Builds src/ → dist/better-rtl-support/ + dist/better-rtl-support-<ver>.zip
dist/             Build output (git-ignored)
.github/workflows CI: lint, GitHub release on tags
bin/deploy.sh     Local deploy to wordpress.org SVN (reads .env)
```

## Local development

Link `src/` into a WordPress install that uses an RTL language (the plugin does nothing otherwise):

```bash
# Linux
ln -s "$PWD/src" /path/to/wp-content/plugins/better-rtl-support
```
```powershell
# Windows
cmd /c mklink /J C:\path\to\wp-content\plugins\better-rtl-support $PWD\src
```

`composer install`, then `vendor/bin/phpcs` must be clean.

## Releasing

wordpress.org SVN credentials live in a local, git-ignored `.env` (see `.env.example`). They are never stored on GitHub.

1. Bump `Version:` in `src/better-rtl-support.php`, `BRTLS_VERSION`, and `Stable tag:` in `src/readme.txt`; add a changelog entry.
2. `bash bin/deploy.sh`: builds, syncs into `./svn` (git-ignored) and shows the SVN changes (dry run).
3. Commit, then `git tag v3.0.0 && git push origin main v3.0.0`. CI attaches the zip to a GitHub release.
4. `bash bin/deploy.sh --commit` publishes `trunk/`, `tags/3.0.0/` and `assets/` to wordpress.org.

Readme/asset-only change (e.g. bump *Tested up to*): commit, then `bash bin/deploy.sh --readme-only [--commit]`.
It refuses if code differs from the released tag.

Needs `svn` on PATH (Windows: TortoiseSVN with command-line tools; Ubuntu: `apt install subversion`).
