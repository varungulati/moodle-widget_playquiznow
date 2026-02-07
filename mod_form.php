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
 * Activity instance add/edit form.
 *
 * @package     mod_playquiznow
 * @copyright   2026 PlayQuizNow <support@playquiznow.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Module instance settings form.
 */
class mod_playquiznow_mod_form extends moodleform_mod {

    /**
     * Define the form fields.
     */
    public function definition() {
        $mform = $this->_form;

        // General section.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('activityname', 'mod_playquiznow'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $this->standard_intro_elements();

        // Quiz settings section.
        $mform->addElement('header', 'quizsettings', get_string('quizsettings', 'mod_playquiznow'));

        $mform->addElement('text', 'quizid', get_string('quizid', 'mod_playquiznow'), ['size' => '40']);
        $mform->setType('quizid', PARAM_ALPHANUMEXT);
        $mform->addRule('quizid', null, 'required', null, 'client');
        $mform->addHelpButton('quizid', 'quizid', 'mod_playquiznow');

        $defaultwidth = get_config('mod_playquiznow', 'defaultwidth');
        $mform->addElement('text', 'width', get_string('width', 'mod_playquiznow'), ['size' => '10']);
        $mform->setType('width', PARAM_TEXT);
        $mform->setDefault('width', $defaultwidth ?: '100%');

        $defaultheight = get_config('mod_playquiznow', 'defaultheight');
        $mform->addElement('text', 'height', get_string('height', 'mod_playquiznow'), ['size' => '10']);
        $mform->setType('height', PARAM_INT);
        $mform->setDefault('height', $defaultheight ?: 500);

        $themes = [
            'light' => get_string('themelight', 'mod_playquiznow'),
            'dark'  => get_string('themedark', 'mod_playquiznow'),
        ];
        $mform->addElement('select', 'theme', get_string('theme', 'mod_playquiznow'), $themes);
        $mform->setDefault('theme', 'light');

        // Grade section.
        $this->standard_grading_coursemodule_elements();

        // Standard course module elements.
        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    /**
     * Validate form data.
     *
     * @param array $data Form data.
     * @param array $files Uploaded files.
     * @return array Validation errors.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $data['quizid'])) {
            $errors['quizid'] = get_string('invalidquizid', 'mod_playquiznow');
        }

        $height = intval($data['height']);
        if ($height < 100) {
            $errors['height'] = get_string('invalidheight', 'mod_playquiznow');
        }

        if (!empty($data['width']) && !preg_match('/^\d+(\.\d+)?(px|%|em|rem|vw|vh)$/', $data['width'])) {
            $errors['width'] = get_string('invalidwidth', 'mod_playquiznow');
        }

        return $errors;
    }
}
