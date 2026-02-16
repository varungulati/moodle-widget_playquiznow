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
 * Upgrade steps for ltisource_playquiznow.
 *
 * @package     ltisource_playquiznow
 * @copyright   2025 PlayQuizNow
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Run upgrade steps for ltisource_playquiznow.
 *
 * @param int $oldversion The version we are upgrading from.
 * @return bool
 */
function xmldb_ltisource_playquiznow_upgrade($oldversion) {
    global $CFG, $DB;

    if ($oldversion < 2026021600) {
        // v2.0.0: Create preconfigured tool type if it doesn't already exist.
        if (!$DB->record_exists('lti_types', ['tooldomain' => 'api.playquiznow.com'])) {
            require_once($CFG->dirroot . '/mod/lti/locallib.php');

            $type = new stdClass();
            $type->name = 'PlayQuizNow';
            $type->baseurl = 'https://api.playquiznow.com/lti/launch/';
            $type->tooldomain = 'api.playquiznow.com';
            $type->state = LTI_TOOL_STATE_PENDING;
            $type->course = SITEID;
            $type->coursevisible = LTI_COURSEVISIBLE_ACTIVITYCHOOSER;
            $type->ltiversion = LTI_VERSION_1P3;
            $type->description = get_string('plugindescription', 'ltisource_playquiznow');
            $type->timecreated = time();
            $type->timemodified = time();
            $type->createdby = get_admin()->id;

            $typeid = $DB->insert_record('lti_types', $type);

            $configs = [
                'lti_toolurl'                            => 'https://api.playquiznow.com/lti/launch/',
                'lti_publickeyset'                       => 'https://api.playquiznow.com/lti/jwks/',
                'lti_keytype'                            => 'JWK_KEYSET',
                'lti_initiatelogin'                      => 'https://api.playquiznow.com/lti/login/',
                'lti_redirectionuris'                    => 'https://api.playquiznow.com/lti/launch/',
                'lti_contentitem'                        => 1,
                'lti_toolurl_ContentItemSelectionRequest' => 'https://api.playquiznow.com/lti/launch/',
                'sendname'                               => LTI_SETTING_ALWAYS,
                'sendemailaddr'                          => LTI_SETTING_ALWAYS,
                'acceptgrades'                           => LTI_SETTING_ALWAYS,
                'launchcontainer'                        => LTI_LAUNCH_CONTAINER_EMBED_NO_BLOCKS,
            ];

            foreach ($configs as $name => $value) {
                $record = new stdClass();
                $record->typeid = $typeid;
                $record->name = $name;
                $record->value = $value;
                $DB->insert_record('lti_types_config', $record);
            }
        }

        upgrade_plugin_savepoint(true, 2026021600, 'ltisource', 'playquiznow');
    }

    return true;
}
