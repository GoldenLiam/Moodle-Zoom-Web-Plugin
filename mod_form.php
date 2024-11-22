<?php 
// This file is part of the TTTH Zoom plugin for Moodle - http://moodle.org/
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
 * TTTH Zoom meetings.
 *
 * @package    mod_ttthzoom
 * @copyright  2024 Trung Tâm Tin Học Trường Đại học Khoa học Tự nhiên
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_ttthzoom_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;
    
        $mform = $this->_form;
    
        // Adding the "general" fieldset, where all the common settings are shown.
        $mform->addElement('header', 'general', get_string('general', 'form'));
    
        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('ttthzoom_name', 'mod_ttthzoom'), array('size' => '64'));
        $mform->setType('name', PARAM_TEXT);
        
        // Adding the standard "meetingnumber" field.
        $mform->addElement('text', 'meetingnumber', get_string('ttthzoom_meetingnumber', 'mod_ttthzoom'), array('size' => '64'));
        $mform->setType('meetingnumber', PARAM_TEXT);

        // Adding the standard "password" field.
        $mform->addElement('text', 'password', get_string('ttthzoom_password', 'mod_ttthzoom'), array('size' => '32'));
        $mform->setType('password', PARAM_TEXT);

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }
}
