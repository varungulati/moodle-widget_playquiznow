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
 * Uninstall hook for ltisource_playquiznow.
 *
 * Cleans up the preconfigured tool type created on install.
 * Does NOT remove tool types created via Dynamic Registration
 * (those have a client_id set).
 *
 * @package     ltisource_playquiznow
 * @copyright   2025 PlayQuizNow
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Run uninstall tasks for ltisource_playquiznow.
 *
 * @return bool
 */
function xmldb_ltisource_playquiznow_uninstall() {
    global $DB;

    // Only remove plugin-created tool types (no client_id = not from Dynamic Registration).
    $types = $DB->get_records_select('lti_types',
        "tooldomain = :domain AND (clientid IS NULL OR clientid = '')",
        ['domain' => 'api.playquiznow.com']
    );

    foreach ($types as $type) {
        $DB->delete_records('lti_types_config', ['typeid' => $type->id]);
        $DB->delete_records('lti_types', ['id' => $type->id]);
    }

    return true;
}
