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
 * English strings for ltisource_playquiznow.
 *
 * @package     ltisource_playquiznow
 * @copyright   2025 PlayQuizNow
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['api_url'] = 'PlayQuizNow API URL';
$string['api_url_desc'] = 'Base URL of the PlayQuizNow API. Only change this if you are running a self-hosted instance.';
$string['dynamic_reg_url'] = 'Dynamic Registration URL';
$string['dynamic_reg_url_desc'] = 'To complete setup, go to <strong>Site administration &gt; Plugins &gt; Activity modules &gt; External tool &gt; Manage tools</strong> and paste this URL in the <em>Tool URL</em> field:<br><br><code>https://api.playquiznow.com/lti/registration/</code><br><br>Click <strong>Add LTI Advantage</strong> and follow the prompts. This registers PlayQuizNow with your Moodle site using LTI 1.3 Dynamic Registration.';
$string['plugindescription'] = 'PlayQuizNow LTI Provider — embed interactive quizzes from PlayQuizNow into Moodle courses with automatic grading.';
$string['pluginname'] = 'PlayQuizNow';
$string['privacy:metadata'] = 'The PlayQuizNow LTI source plugin does not store personal data. User data is passed to PlayQuizNow via the standard LTI launch.';
$string['setup_desc'] = 'This plugin pre-configures PlayQuizNow as an External Tool with LTI 1.3. To complete the connection, run Dynamic Registration using the URL below.';
$string['setup_heading'] = 'Setup';
