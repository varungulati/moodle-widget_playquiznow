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
 * Admin settings for ltisource_playquiznow.
 *
 * @package     ltisource_playquiznow
 * @copyright   2025 PlayQuizNow
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage(
        'ltisource_playquiznow',
        new lang_string('pluginname', 'ltisource_playquiznow')
    );

    $settings->add(new admin_setting_configtext(
        'ltisource_playquiznow/lti_url',
        new lang_string('lti_url', 'ltisource_playquiznow'),
        new lang_string('lti_url_desc', 'ltisource_playquiznow'),
        'https://playquiznow.com/lti/launch',
        PARAM_URL
    ));

    $ADMIN->add('ltisource', $settings);
}
