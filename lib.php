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
 * This plugin auto-configures PlayQuizNow as a preconfigured External Tool
 * in Moodle with LTI 1.3 support, deep linking, and grade passback.
 *
 * The actual LTI 1.3 launch flow is handled by Moodle's core mod_lti module.
 * This plugin provides the tool type configuration and admin settings.
 *
 * @package     ltisource_playquiznow
 * @copyright   2025 PlayQuizNow
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
    $config = ltisource_playquiznow_get_config();
    $baseurl = !empty($config->api_url) ? $config->api_url : 'https://api.playquiznow.com';

    return [
        'name'        => get_string('pluginname', 'ltisource_playquiznow'),
        'description' => get_string('plugindescription', 'ltisource_playquiznow'),
        'url'         => $baseurl . '/lti/launch/',
    ];
}
