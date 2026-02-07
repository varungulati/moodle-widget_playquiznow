<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * PlayQuizNow LTI Source Provider library.
 *
 * @package     ltisource_playquiznow
 * @copyright   2025 PlayQuizNow
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Get the plugin configuration.
 *
 * @return stdClass Configuration object.
 */
function ltisource_playquiznow_get_config() {
    return get_config('ltisource_playquiznow');
}

/**
 * Get the LTI provider information.
 *
 * @return array Provider name, description, and launch URL.
 */
function ltisource_playquiznow_get_provider_info() {
    return [
        'name'        => get_string('pluginname', 'ltisource_playquiznow'),
        'description' => get_string('plugindescription', 'ltisource_playquiznow'),
        'url'         => get_config('ltisource_playquiznow', 'lti_url'),
    ];
}

/**
 * Handle the LTI launch request.
 *
 * Prepares launch parameters from the current Moodle session
 * and redirects to the PlayQuizNow LTI endpoint.
 *
 * @return void
 */
function ltisource_playquiznow_handle_launch() {
    global $USER, $COURSE;

    $config = ltisource_playquiznow_get_config();
    $ltiurl = !empty($config->lti_url) ? $config->lti_url : '';

    if (empty($ltiurl)) {
        throw new moodle_exception('playquiznow_url_not_configured', 'ltisource_playquiznow');
    }

    $launchparams = [
        'user_id'                => $USER->id,
        'user_email'             => $USER->email,
        'user_name'              => fullname($USER),
        'course_id'              => $COURSE->id,
        'course_name'            => $COURSE->fullname,
        'custom_param_timestamp' => time(),
    ];

    $launchurl = $ltiurl . '?' . http_build_query($launchparams);
    redirect($launchurl);
}
