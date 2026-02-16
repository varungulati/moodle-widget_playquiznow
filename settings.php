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

    // Setup instructions.
    $settings->add(new admin_setting_heading(
        'ltisource_playquiznow/setup_heading',
        new lang_string('setup_heading', 'ltisource_playquiznow'),
        new lang_string('setup_desc', 'ltisource_playquiznow')
    ));

    // Dynamic Registration URL (read-only info for admin).
    $settings->add(new admin_setting_heading(
        'ltisource_playquiznow/dynamic_reg_heading',
        new lang_string('dynamic_reg_url', 'ltisource_playquiznow'),
        new lang_string('dynamic_reg_url_desc', 'ltisource_playquiznow')
    ));

    // PlayQuizNow API base URL (for custom/self-hosted deployments).
    $settings->add(new admin_setting_configtext(
        'ltisource_playquiznow/api_url',
        new lang_string('api_url', 'ltisource_playquiznow'),
        new lang_string('api_url_desc', 'ltisource_playquiznow'),
        'https://api.playquiznow.com',
        PARAM_URL
    ));

    $ADMIN->add('ltisource', $settings);
}
