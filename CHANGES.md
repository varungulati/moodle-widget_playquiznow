# Changelog

## 2.0.2 — 2026-02-16

- Fixed "Select content" (deep linking) button not appearing in External Tool form
- Switched from manual DB inserts to Moodle's `lti_add_type()` for tool type creation, which properly strips `lti_` prefix from config keys, generates `servicesalt`, and matches dynamic registration behavior
- Added upgrade step to fix config keys on existing installs (strips erroneous `lti_` prefix)
- Updated install.php and upgrade.php for consistent tool type creation

## 2.0.1 — 2026-02-16

- Fixed dynamic registration "Close Window" button not working in iframe (use LTI postMessage instead of window.close)
- Fixed re-registration creating duplicate platform records causing "Multiple platforms" error on launch
- Improved platform lookup to use most recent registration when duplicates exist

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
