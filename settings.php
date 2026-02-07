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
 * Admin settings for mod_playquiznow.
 *
 * @package     mod_playquiznow
 * @copyright   2026 PlayQuizNow <support@playquiznow.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configtext(
        'mod_playquiznow/baseurl',
        get_string('setting_baseurl', 'mod_playquiznow'),
        get_string('setting_baseurl_desc', 'mod_playquiznow'),
        'https://playquiznow.com',
        PARAM_URL
    ));

    $settings->add(new admin_setting_configtext(
        'mod_playquiznow/defaultwidth',
        get_string('setting_defaultwidth', 'mod_playquiznow'),
        get_string('setting_defaultwidth_desc', 'mod_playquiznow'),
        '100%',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'mod_playquiznow/defaultheight',
        get_string('setting_defaultheight', 'mod_playquiznow'),
        get_string('setting_defaultheight_desc', 'mod_playquiznow'),
        '500',
        PARAM_INT
    ));

    $settings->add(new admin_setting_configcheckbox(
        'mod_playquiznow/showbranding',
        get_string('setting_showbranding', 'mod_playquiznow'),
        get_string('setting_showbranding_desc', 'mod_playquiznow'),
        1
    ));
}
