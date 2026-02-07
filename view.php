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
 * Display the PlayQuizNow quiz embed.
 *
 * @package     mod_playquiznow
 * @copyright   2026 PlayQuizNow <support@playquiznow.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course module ID.
$p  = optional_param('p', 0, PARAM_INT);  // Instance ID.

if ($id) {
    $cm = get_coursemodule_from_id('playquiznow', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
    $instance = $DB->get_record('playquiznow', ['id' => $cm->instance], '*', MUST_EXIST);
} else {
    $instance = $DB->get_record('playquiznow', ['id' => $p], '*', MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $instance->course], '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('playquiznow', $instance->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/playquiznow:view', $context);

// Trigger course_module_viewed event.
$event = \core\event\course_module_viewed::create([
    'objectid' => $instance->id,
    'context'  => $context,
]);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('playquiznow', $instance);
$event->trigger();

// Mark as viewed for completion.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// Page setup.
$PAGE->set_url('/mod/playquiznow/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($instance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// Build embed URL.
$embedurl = playquiznow_embed_url($instance->quizid, $instance->theme);
$width = clean_param($instance->width, PARAM_TEXT);
$height = max(100, intval($instance->height));

// Check if width is valid.
if (!preg_match('/^\d+(\.\d+)?(px|%|em|rem|vw|vh)$/', $width)) {
    $width = '100%';
}

// Determine if user can submit grades.
$cansubmit = has_capability('mod/playquiznow:submit', $context);

// Initialize the AMD module for resize and grade passback.
$PAGE->requires->js_call_amd('mod_playquiznow/embed', 'init', [
    $instance->quizid,
    $cm->id,
    $cansubmit,
    sesskey(),
]);

echo $OUTPUT->header();

if (!empty($instance->intro)) {
    echo $OUTPUT->box(format_module_intro('playquiznow', $instance, $cm->id), 'generalbox', 'intro');
}

echo html_writer::start_div('playquiznow-container', ['style' => 'width:' . s($width) . ';max-width:100%;margin:0 auto;']);
echo html_writer::tag('iframe', '', [
    'src'       => $embedurl,
    'title'     => get_string('quizembed', 'mod_playquiznow', format_string($instance->name)),
    'width'     => '100%',
    'height'    => $height,
    'class'     => 'playquiznow-iframe',
    'data-quiz-id' => s($instance->quizid),
    'frameborder' => '0',
    'scrolling' => 'no',
    'allow'     => 'clipboard-write',
    'sandbox'   => 'allow-scripts allow-same-origin allow-popups allow-forms',
    'loading'   => 'lazy',
]);

$showbranding = get_config('mod_playquiznow', 'showbranding');
if ($showbranding === false || $showbranding) {
    echo html_writer::start_div('playquiznow-branding');
    echo html_writer::link(
        'https://playquiznow.com',
        get_string('poweredby', 'mod_playquiznow'),
        ['target' => '_blank', 'rel' => 'noopener noreferrer']
    );
    echo html_writer::end_div();
}

echo html_writer::end_div();

echo $OUTPUT->footer();
