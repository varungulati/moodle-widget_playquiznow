<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * English strings for mod_playquiznow.
 *
 * @package     mod_playquiznow
 * @copyright   2026 PlayQuizNow <support@playquiznow.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Module metadata.
$string['modulename'] = 'PlayQuizNow';
$string['modulenameplural'] = 'PlayQuizNow quizzes';
$string['modulename_help'] = 'The PlayQuizNow activity lets you embed interactive quizzes from PlayQuizNow into your course. Students complete the quiz inline, and scores are recorded in the gradebook automatically.';
$string['pluginname'] = 'PlayQuizNow';
$string['pluginadministration'] = 'PlayQuizNow administration';

// Activity form.
$string['activityname'] = 'Activity name';
$string['quizsettings'] = 'Quiz settings';
$string['quizid'] = 'Quiz ID';
$string['quizid_help'] = 'The unique quiz identifier from your PlayQuizNow dashboard. It contains only letters, numbers, hyphens, and underscores (e.g. "my-quiz-id").';
$string['width'] = 'Width';
$string['height'] = 'Height (px)';
$string['theme'] = 'Theme';
$string['themelight'] = 'Light';
$string['themedark'] = 'Dark';

// Validation.
$string['invalidquizid'] = 'Invalid quiz ID. Use only letters, numbers, hyphens, and underscores.';
$string['invalidheight'] = 'Height must be at least 100 pixels.';
$string['invalidwidth'] = 'Width must be a valid CSS value (e.g. "100%", "600px").';

// View page.
$string['quizembed'] = 'PlayQuizNow quiz: {$a}';
$string['poweredby'] = 'Powered by PlayQuizNow';

// Index page.
$string['nonewmodules'] = 'There are no PlayQuizNow activities in this course.';

// Admin settings.
$string['setting_baseurl'] = 'PlayQuizNow base URL';
$string['setting_baseurl_desc'] = 'The base URL of the PlayQuizNow platform. Do not change this unless you run a self-hosted instance.';
$string['setting_defaultwidth'] = 'Default width';
$string['setting_defaultwidth_desc'] = 'Default container width for new activities (any CSS value, e.g. "100%", "600px").';
$string['setting_defaultheight'] = 'Default height (px)';
$string['setting_defaultheight_desc'] = 'Default iframe height in pixels for new activities.';
$string['setting_showbranding'] = 'Show branding';
$string['setting_showbranding_desc'] = 'Display a "Powered by PlayQuizNow" link below the quiz embed.';

// Capabilities.
$string['playquiznow:addinstance'] = 'Add a new PlayQuizNow activity';
$string['playquiznow:view'] = 'View PlayQuizNow activity';
$string['playquiznow:submit'] = 'Submit quiz scores';

// Privacy.
$string['privacy:metadata'] = 'The PlayQuizNow plugin does not store personal data. Quiz scores are recorded in the Moodle gradebook.';
