# Changelog

## 2.0.0 — 2026-02-16

**Breaking:** Upgraded from simple redirect to full LTI 1.3 integration.

- Removed broken `handle_launch()` redirect — Moodle's core LTI 1.3 module handles launches
- Auto-creates preconfigured External Tool type on install with correct LTI 1.3 URLs
- Added `db/install.php` for auto-configuration of tool type (launch, login, JWKS, deep linking)
- Added `db/upgrade.php` for seamless upgrade from 1.x
- Added `db/uninstall.php` to clean up tool type on uninstall
- Fixed default URL from `playquiznow.com` (frontend, 404) to `api.playquiznow.com`
- Updated admin settings page with Dynamic Registration URL and setup instructions
- Supports deep linking (quiz picker), grade passback, and iframe embedding
- Updated language strings

## 1.1.0 — 2026-02-14

- Fixed PHP coding style issues (phpcs compliance)
- Corrected language string ordering in lang file
- Removed unexpected MOODLE_INTERNAL check from lib.php
- Bumped maturity to MATURITY_STABLE

## 1.0.0 — 2025-02-14

Initial release.

- LTI source provider integration for Moodle
- Admin settings for LTI launch URL
- Moodle 4.1+ compatible
