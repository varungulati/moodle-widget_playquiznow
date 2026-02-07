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
 * Library of functions for mod_playquiznow.
 *
 * @package     mod_playquiznow
 * @copyright   2026 PlayQuizNow <support@playquiznow.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Declare supported features.
 *
 * @param string $feature FEATURE_xx constant.
 * @return mixed True if supported, null if unknown.
 */
function playquiznow_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_MOD_PURPOSE:
            return MOD_PURPOSE_ASSESSMENT;
        default:
            return null;
    }
}

/**
 * Add a new PlayQuizNow activity instance.
 *
 * @param stdClass $data Form data.
 * @param mod_playquiznow_mod_form $mform The form.
 * @return int New instance ID.
 */
function playquiznow_add_instance($data, $mform = null) {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = $data->timecreated;

    $data->id = $DB->insert_record('playquiznow', $data);

    playquiznow_grade_item_update($data);

    return $data->id;
}

/**
 * Update a PlayQuizNow activity instance.
 *
 * @param stdClass $data Form data.
 * @param mod_playquiznow_mod_form $mform The form.
 * @return bool Success.
 */
function playquiznow_update_instance($data, $mform = null) {
    global $DB;

    $data->timemodified = time();
    $data->id = $data->instance;

    $result = $DB->update_record('playquiznow', $data);

    playquiznow_grade_item_update($data);

    return $result;
}

/**
 * Delete a PlayQuizNow activity instance.
 *
 * @param int $id Instance ID.
 * @return bool Success.
 */
function playquiznow_delete_instance($id) {
    global $DB;

    $instance = $DB->get_record('playquiznow', ['id' => $id]);
    if (!$instance) {
        return false;
    }

    playquiznow_grade_item_delete($instance);

    $DB->delete_records('playquiznow', ['id' => $id]);

    return true;
}

/**
 * Create or update the grade item for this activity.
 *
 * @param stdClass $instance Activity instance.
 * @param mixed $grades Optional grades to pass in.
 * @return int GRADE_UPDATE_OK etc.
 */
function playquiznow_grade_item_update($instance, $grades = null) {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');

    $item = [
        'itemname' => clean_param($instance->name, PARAM_NOTAGS),
        'gradetype' => GRADE_TYPE_VALUE,
        'grademax'  => $instance->grade,
        'grademin'  => 0,
    ];

    if ($grades === 'reset') {
        $item['reset'] = true;
        $grades = null;
    }

    return grade_update('mod/playquiznow', $instance->course, 'mod', 'playquiznow',
        $instance->id, 0, $grades, $item);
}

/**
 * Delete the grade item for this activity.
 *
 * @param stdClass $instance Activity instance.
 * @return int GRADE_UPDATE_OK etc.
 */
function playquiznow_grade_item_delete($instance) {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');

    return grade_update('mod/playquiznow', $instance->course, 'mod', 'playquiznow',
        $instance->id, 0, null, ['deleted' => 1]);
}

/**
 * Update grades for a user.
 *
 * @param stdClass $instance Activity instance.
 * @param int $userid User ID (0 for all).
 * @param bool $nullifnone Set grade to null if none.
 */
function playquiznow_update_grades($instance, $userid = 0, $nullifnone = true) {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');

    if ($instance->grade == 0) {
        playquiznow_grade_item_update($instance);
    } else if ($userid && $nullifnone) {
        $grade = new stdClass();
        $grade->userid   = $userid;
        $grade->rawgrade = null;
        playquiznow_grade_item_update($instance, $grade);
    } else {
        playquiznow_grade_item_update($instance);
    }
}

/**
 * Build the embed URL for a quiz.
 *
 * @param string $quizid The quiz ID.
 * @param string $theme light or dark.
 * @return string Full embed URL.
 */
function playquiznow_embed_url($quizid, $theme = 'light') {
    $baseurl = get_config('mod_playquiznow', 'baseurl');
    if (empty($baseurl)) {
        $baseurl = 'https://playquiznow.com';
    }
    $baseurl = rtrim($baseurl, '/');
    $theme = in_array($theme, ['light', 'dark'], true) ? $theme : 'light';

    return $baseurl . '/embed/' . urlencode($quizid) . '?theme=' . $theme . '&source=moodle';
}
