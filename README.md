# PlayQuizNow for Moodle

A Moodle activity module that lets teachers embed interactive quizzes from [PlayQuizNow](https://playquiznow.com) directly into their courses, with automatic gradebook integration.

---

## Features

- **Course activity** — shows up alongside assignments, quizzes, and other activities
- **Gradebook integration** — scores are submitted automatically when students complete a quiz
- **Auto-resize** — iframe height adjusts to content via postMessage
- **Light & Dark themes** — per-activity theme selection
- **Admin settings** — configure base URL, default dimensions, and branding globally
- **Capabilities** — separate permissions for adding, viewing, and submitting scores

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
2. In your Moodle site, go to **Site administration > Plugins > Install plugins**
3. Upload the ZIP file and follow the prompts
4. Complete the upgrade process

### Manual

1. Clone or download this repository
2. Copy the contents into `mod/playquiznow/` inside your Moodle directory:
   ```bash
   git clone https://github.com/varungulati/playquiznow-moodle.git /path/to/moodle/mod/playquiznow
   ```
3. Go to **Site administration > Notifications** to trigger the install

---

## Configuration

### Admin Settings

After installation, go to **Site administration > Plugins > Activity modules > PlayQuizNow**:

| Setting | Default | Description |
|---------|---------|-------------|
| Base URL | `https://playquiznow.com` | Only change this for self-hosted instances |
| Default width | `100%` | Default container width for new activities |
| Default height | `500` | Default iframe height in pixels |
| Show branding | On | Display "Powered by PlayQuizNow" below the embed |

### Adding a Quiz to a Course

1. Turn editing on in your course
2. Click **Add an activity or resource**
3. Select **PlayQuizNow**
4. Enter the **Quiz ID** from your PlayQuizNow dashboard
5. Optionally adjust width, height, theme, and grade settings
6. Save

Students will see the embedded quiz on the course page. When they complete it, their score is recorded in the gradebook automatically.

---

## How It Works

### Embed

The plugin renders a sandboxed iframe pointing to:

```
https://playquiznow.com/embed/{quiz-id}?theme={theme}&source=moodle
```

### Auto-Resize

A frontend AMD module listens for `postMessage` events from the embed origin (`https://playquiznow.com`):

- `playquiznow:resize` — adjusts iframe height (capped at 5000px)
- `playquiznow:quiz-complete` — submits score to the Moodle gradebook via AJAX

### Grade Passback Flow

```
Student completes quiz
  → PlayQuizNow embed sends postMessage { type: "playquiznow:quiz-complete", score, maxScore }
  → Moodle AMD module catches the message
  → Calls mod_playquiznow_submit_grade external function via AJAX
  → Score is normalised to the activity's grade scale and written to the gradebook
```

---

## Finding Your Quiz ID

1. Log in at [playquiznow.com](https://playquiznow.com)
2. Open your quiz from the dashboard
3. The quiz ID is shown on the quiz settings page (e.g. `my-quiz-id`)

Don't have a quiz yet? [Create one free](https://playquiznow.com).

---

## Capabilities

| Capability | Default roles | Description |
|------------|---------------|-------------|
| `mod/playquiznow:addinstance` | Manager, Editing teacher | Add a PlayQuizNow activity to a course |
| `mod/playquiznow:view` | Student, Teacher, Manager | View the embedded quiz |
| `mod/playquiznow:submit` | Student | Submit quiz scores to the gradebook |

---

## File Structure

```
mod/playquiznow/
├── version.php              # Plugin version metadata
├── lib.php                  # Core functions (add/update/delete instance, grades)
├── mod_form.php             # Activity add/edit form
├── view.php                 # Student-facing quiz embed page
├── index.php                # List all instances in a course
├── settings.php             # Admin settings
├── styles.css               # Frontend styles
├── db/
│   ├── install.xml          # Database schema
│   ├── access.php           # Capabilities
│   ├── services.php         # External function declarations
│   └── upgrade.php          # Upgrade steps
├── classes/external/
│   └── submit_grade.php     # AJAX grade submission endpoint
├── amd/
│   ├── src/embed.js         # AMD source (resize + grade passback)
│   └── build/embed.min.js   # Minified build
├── lang/en/
│   └── playquiznow.php      # English language strings
└── pix/
    └── monologo.svg         # Activity icon
```

---

## Development

```bash
# Clone into Moodle's mod directory
git clone https://github.com/varungulati/playquiznow-moodle.git /path/to/moodle/mod/playquiznow

# Rebuild AMD modules (from Moodle root, requires grunt)
npx grunt amd --root=mod/playquiznow

# Purge caches after changes
php admin/cli/purge_caches.php
```

---

## License

GNU GPL v3 or later. See [LICENSE](https://www.gnu.org/licenses/gpl-3.0.html).
