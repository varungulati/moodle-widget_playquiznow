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

namespace mod_playquiznow\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/mod/playquiznow/lib.php');

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use context_module;
use invalid_parameter_exception;

/**
 * External function to submit a quiz grade.
 *
 * @package     mod_playquiznow
 * @copyright   2026 PlayQuizNow <support@playquiznow.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class submit_grade extends external_api {

    /**
     * Describe the parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'cmid'     => new external_value(PARAM_INT, 'Course module ID'),
            'score'    => new external_value(PARAM_FLOAT, 'Score achieved (0-100)'),
            'maxscore' => new external_value(PARAM_FLOAT, 'Maximum possible score'),
        ]);
    }

    /**
     * Submit the grade.
     *
     * @param int $cmid Course module ID.
     * @param float $score Score achieved.
     * @param float $maxscore Maximum score.
     * @return array Result.
     */
    public static function execute(int $cmid, float $score, float $maxscore): array {
        global $DB, $USER;

        $params = self::validate_parameters(self::execute_parameters(), [
            'cmid'     => $cmid,
            'score'    => $score,
            'maxscore' => $maxscore,
        ]);

        $cm = get_coursemodule_from_id('playquiznow', $params['cmid'], 0, false, MUST_EXIST);
        $context = context_module::instance($cm->id);

        self::validate_context($context);
        require_capability('mod/playquiznow:submit', $context);

        $instance = $DB->get_record('playquiznow', ['id' => $cm->instance], '*', MUST_EXIST);

        if ($params['maxscore'] <= 0) {
            throw new invalid_parameter_exception('maxscore must be greater than 0');
        }

        // Normalize score to the activity's grade scale.
        $normalized = ($params['score'] / $params['maxscore']) * $instance->grade;
        $normalized = max(0, min($instance->grade, $normalized));

        $grade = new \stdClass();
        $grade->userid   = $USER->id;
        $grade->rawgrade = $normalized;

        playquiznow_grade_item_update($instance, $grade);

        return [
            'success' => true,
            'grade'   => $normalized,
        ];
    }

    /**
     * Describe the return value.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the grade was saved'),
            'grade'   => new external_value(PARAM_FLOAT, 'The normalised grade that was recorded'),
        ]);
    }
}
