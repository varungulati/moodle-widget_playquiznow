# PlayQuizNow LTI Provider for Moodle

Integrates [PlayQuizNow](https://playquiznow.com) as an LTI 1.3 tool in Moodle, allowing educators to embed interactive quizzes directly into their courses with automatic grading.

---

## Features

- **LTI 1.3 compliant** — secure integration via OIDC and signed JWTs
- **Deep linking** — instructors browse and select quizzes from within Moodle
- **Grade passback** — sync scores from PlayQuizNow to the Moodle gradebook
- **Iframe embedding** — quizzes load inside Moodle, no page navigation
- **Auto-configuration** — plugin creates a preconfigured tool type on install

---

## Requirements

| Requirement | Version |
|-------------|---------|
| Moodle      | 4.1+    |
| PHP         | 7.4+    |

---

## Installation

### From ZIP

1. Download the latest release from [GitHub releases](https://github.com/varungulati/playquiznow-moodle/releases)
2. In Moodle, go to **Site administration > Plugins > Install plugins**
3. Upload the ZIP and follow the prompts
4. Complete setup (see below)

### Manual

```bash
git clone https://github.com/varungulati/playquiznow-moodle.git /path/to/moodle/mod/lti/source/playquiznow
```

Then go to **Site administration > Notifications** to complete the install.

---

## Setup

After installing the plugin, complete these steps to enable LTI 1.3:

### Step 1: Run Dynamic Registration

1. Go to **Site administration > Plugins > Activity modules > External tool > Manage tools**
2. In the **Tool URL** field, paste:
   ```
   https://api.playquiznow.com/lti/registration/
   ```
3. Click **Add LTI Advantage**
4. On the PlayQuizNow registration page, click **Register PlayQuizNow**
5. Done — PlayQuizNow is now connected to your Moodle site

### Step 2: Add a Quiz to a Course

1. In your course, turn editing on and click **Add an activity or resource**
2. Select **External tool**
3. Under **Preconfigured tool**, select **PlayQuizNow**
4. Click **Select content** to browse and pick a quiz (deep linking)
5. Save — students can now launch the quiz from the course page

---

## How It Works

1. **Plugin install** — creates a preconfigured External Tool type with all PlayQuizNow URLs pre-filled (launch, OIDC login, JWKS, deep linking)
2. **Dynamic Registration** — establishes the two-way trust between Moodle and PlayQuizNow (exchanges client_id, keys, etc.)
3. **Deep linking** — instructors pick quizzes from PlayQuizNow's catalog without leaving Moodle
4. **Quiz launch** — Moodle's LTI 1.3 module handles the OIDC flow; PlayQuizNow loads in an iframe
5. **Grade passback** — after a student completes a quiz, the score is sent back to Moodle's gradebook

---

## LTI Endpoints

| Endpoint | URL |
|----------|-----|
| Launch URL | `https://api.playquiznow.com/lti/launch/` |
| OIDC Login | `https://api.playquiznow.com/lti/login/` |
| JWKS (Public Key) | `https://api.playquiznow.com/lti/jwks/` |
| Dynamic Registration | `https://api.playquiznow.com/lti/registration/` |
| Deep Linking | `https://api.playquiznow.com/lti/launch/` |

---

## File Structure

```
mod/lti/source/playquiznow/
├── version.php          # Plugin metadata (v2.0.0)
├── lib.php              # Provider info utilities
├── settings.php         # Admin settings (API URL, setup instructions)
├── db/
│   ├── install.php      # Auto-create tool type on install
│   ├── upgrade.php      # Upgrade from 1.x
│   └── uninstall.php    # Clean up tool type on uninstall
├── classes/privacy/
│   └── provider.php     # Privacy API (null provider)
├── lang/en/
│   └── ltisource_playquiznow.php
├── CHANGES.md
└── README.md
```

---

## Upgrading from 1.x

Version 2.0.0 replaces the broken simple redirect with proper LTI 1.3 integration. On upgrade:

1. The old `handle_launch()` redirect is removed
2. A preconfigured tool type is created with correct URLs
3. You must complete **Dynamic Registration** (see Setup above) for LTI 1.3 to work

---

## Support

- Website: [playquiznow.com](https://playquiznow.com)
- Setup guide: [playquiznow.com/integrations/moodle](https://playquiznow.com/integrations/moodle)
- Issues: [GitHub](https://github.com/varungulati/playquiznow-moodle/issues)

---

## License

GNU GPL v3 or later. See [LICENSE](https://www.gnu.org/licenses/gpl-3.0.html).
