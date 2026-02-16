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
 * Post-install hook for ltisource_playquiznow.
 *
 * Creates a preconfigured External Tool type for PlayQuizNow with LTI 1.3
 * and auto-registers with the PlayQuizNow backend so deep linking and
 * launches work immediately.
 *
 * @package     ltisource_playquiznow
 * @copyright   2025 PlayQuizNow
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Run post-install tasks for ltisource_playquiznow.
 *
 * @return bool
 */
function xmldb_ltisource_playquiznow_install() {
    global $CFG, $DB;

    // Skip if a PlayQuizNow tool type already exists (e.g. from Dynamic Registration).
    if ($DB->record_exists('lti_types', ['tooldomain' => 'api.playquiznow.com'])) {
        return true;
    }

    require_once($CFG->dirroot . '/mod/lti/locallib.php');
    require_once($CFG->libdir . '/filelib.php');

    $apiurl = 'https://api.playquiznow.com';

    // Generate a unique client ID for this Moodle <-> PlayQuizNow connection.
    $clientid = random_string(15);

    // Moodle's standard LTI endpoints.
    $issuer = $CFG->wwwroot;
    $authloginurl = $CFG->wwwroot . '/mod/lti/auth.php';
    $authtokenurl = $CFG->wwwroot . '/mod/lti/token.php';
    $keyseturl = $CFG->wwwroot . '/mod/lti/certs.php';

    // Auto-register with PlayQuizNow's backend so LTI 1.3 works immediately.
    $registered = false;
    try {
        $curl = new \curl();
        $curl->setHeader(['Content-Type: application/json']);
        $response = $curl->post($apiurl . '/lti/auto-register/', json_encode([
            'issuer' => $issuer,
            'client_id' => $clientid,
            'auth_login_url' => $authloginurl,
            'auth_token_url' => $authtokenurl,
            'key_set_url' => $keyseturl,
        ]));

        if ($curl->get_errno() === 0) {
            $result = json_decode($response, true);
            if (!empty($result['success'])) {
                $registered = true;
            }
        }
    } catch (\Exception $e) {
        // Auto-registration failed (e.g. site not publicly accessible).
        // The tool type will be created as pending — admin can complete
        // setup via Dynamic Registration later.
        debugging('PlayQuizNow auto-registration failed: ' . $e->getMessage(), DEBUG_DEVELOPER);
    }

    // Create preconfigured tool type using Moodle's lti_add_type().
    // This properly strips the lti_ prefix from config keys, generates
    // servicesalt, and populates lti_types fields — matching what
    // dynamic registration does.
    $type = new stdClass();
    $type->state = $registered ? LTI_TOOL_STATE_CONFIGURED : LTI_TOOL_STATE_PENDING;
    $type->course = SITEID;

    $config = new stdClass();
    $config->lti_typename = 'PlayQuizNow';
    $config->lti_toolurl = $apiurl . '/lti/launch/';
    $config->lti_tooldomain = 'api.playquiznow.com';
    $config->lti_description = get_string('plugindescription', 'ltisource_playquiznow');
    $config->lti_ltiversion = LTI_VERSION_1P3;
    $config->lti_clientid = $clientid;
    $config->lti_coursevisible = LTI_COURSEVISIBLE_ACTIVITYCHOOSER;
    $config->lti_publickeyset = $apiurl . '/lti/jwks/';
    $config->lti_keytype = 'JWK_KEYSET';
    $config->lti_initiatelogin = $apiurl . '/lti/login/';
    $config->lti_redirectionuris = $apiurl . '/lti/launch/';
    $config->lti_contentitem = 1;
    $config->lti_toolurl_ContentItemSelectionRequest = $apiurl . '/lti/launch/';
    $config->lti_sendname = LTI_SETTING_ALWAYS;
    $config->lti_sendemailaddr = LTI_SETTING_ALWAYS;
    $config->lti_acceptgrades = LTI_SETTING_ALWAYS;
    $config->lti_launchcontainer = LTI_LAUNCH_CONTAINER_EMBED_NO_BLOCKS;

    $typeid = lti_add_type($type, $config);

    return true;
}
