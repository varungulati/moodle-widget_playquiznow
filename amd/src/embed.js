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
 * Frontend module for PlayQuizNow embed — handles auto-resize and grade passback.
 *
 * @module     mod_playquiznow/embed
 * @copyright  2026 PlayQuizNow <support@playquiznow.com>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['core/ajax', 'core/notification'], function(Ajax, Notification) {

    var ALLOWED_ORIGIN = 'https://playquiznow.com';
    var MAX_HEIGHT = 5000;

    return {
        /**
         * Initialise the embed listener.
         *
         * @param {string} quizId   The PlayQuizNow quiz ID.
         * @param {number} cmid     The course module ID.
         * @param {boolean} canSubmit Whether the user can submit grades.
         * @param {string} sesskey  The session key.
         */
        init: function(quizId, cmid, canSubmit, sesskey) {
            window.addEventListener('message', function(event) {
                if (event.origin !== ALLOWED_ORIGIN) {
                    return;
                }

                var data = event.data;
                if (!data || typeof data !== 'object' || !data.type) {
                    return;
                }

                switch (data.type) {
                    case 'playquiznow:resize':
                        handleResize(event, data, quizId);
                        break;
                    case 'playquiznow:quiz-complete':
                        if (canSubmit) {
                            handleQuizComplete(data, cmid);
                        }
                        break;
                }
            });
        }
    };

    /**
     * Handle resize messages from the embed.
     *
     * @param {MessageEvent} event  The original message event.
     * @param {object} data         Parsed message data.
     * @param {string} quizId       The expected quiz ID.
     */
    function handleResize(event, data, quizId) {
        var height = parseInt(data.height, 10);
        if (!height || height < 100) {
            return;
        }
        if (height > MAX_HEIGHT) {
            height = MAX_HEIGHT;
        }

        var iframes = document.querySelectorAll('iframe.playquiznow-iframe');
        for (var i = 0; i < iframes.length; i++) {
            var iframe = iframes[i];
            if (data.quizId) {
                if (iframe.dataset.quizId !== data.quizId) {
                    continue;
                }
            } else {
                try {
                    if (iframe.contentWindow !== event.source) {
                        continue;
                    }
                } catch (e) {
                    continue;
                }
            }
            iframe.style.height = height + 'px';
        }
    }

    /**
     * Handle quiz completion — submit grade via AJAX.
     *
     * @param {object} data The message data with score and maxScore.
     * @param {number} cmid The course module ID.
     */
    function handleQuizComplete(data, cmid) {
        var score = parseFloat(data.score) || 0;
        var maxScore = parseFloat(data.maxScore) || 100;

        Ajax.call([{
            methodname: 'mod_playquiznow_submit_grade',
            args: {
                cmid: cmid,
                score: score,
                maxscore: maxScore,
            },
        }])[0].fail(Notification.exception);
    }
});
