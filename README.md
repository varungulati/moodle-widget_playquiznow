# PlayQuizNow LTI Provider for Moodle

Integrates [PlayQuizNow](https://playquiznow.com) as an LTI source provider into Moodle, allowing educators to embed interactive quizzes directly into their courses.

---

## Features

- **LTI 1.3 compliant** — secure integration using standard LTI protocols
- **Grade passback** — sync scores from PlayQuizNow to the Moodle gradebook via LTI Advantage
- **User provisioning** — seamless user mapping from Moodle to PlayQuizNow
- **Simple configuration** — one setting in the admin panel

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

### Manual

```bash
git clone https://github.com/varungulati/playquiznow-moodle.git /path/to/moodle/mod/lti/source/playquiznow
```

Then go to **Site administration > Notifications** to complete the install.

---

## Configuration

### Admin Settings

Navigate to **Site administration > Plugins > LTI > PlayQuizNow**:

| Setting | Default | Description |
|---------|---------|-------------|
| LTI Launch URL | `https://playquiznow.com/lti/launch` | The PlayQuizNow LTI endpoint |

### Adding a Quiz to a Course

1. In your course, add a new **External tool** (LTI) activity
2. Select **PlayQuizNow** as the tool provider
3. Configure the tool parameters (quiz ID, etc.)
4. Save — students can now launch the quiz from the course page

---

## LTI Details

| Property | Value |
|----------|-------|
| Plugin type | LTI Source Provider (`ltisource`) |
| Protocol | LTI 1.3 / OAuth 2.0 with OpenID Connect |
| Grade sync | Automatic via LTI Advantage |
| User mapping | Email-based with Moodle user provisioning |

---

## File Structure

```
mod/lti/source/playquiznow/
├── version.php       # Plugin metadata
├── lib.php           # LTI launch handler and provider info
├── settings.php      # Admin settings (LTI URL)
├── lang/en/
│   └── ltisource_playquiznow.php   # Language strings
├── README.md
├── CHANGES.md
└── .gitignore
```

---

## Support

- Website: [playquiznow.com](https://playquiznow.com)
- Issues: [GitHub](https://github.com/varungulati/playquiznow-moodle/issues)

---

## License

GNU GPL v3 or later. See [LICENSE](https://www.gnu.org/licenses/gpl-3.0.html).
