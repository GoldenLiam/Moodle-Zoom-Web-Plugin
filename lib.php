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

require_once(dirname(__FILE__) . '/../../config.php');
require_once("$CFG->libdir/formslib.php");
require_once("{$CFG->dirroot}/lib/navigationlib.php");
global $COURSE, $OUTPUT, $PAGE, $CFG;


/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function ttthzoom_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_COMPLETION_HAS_RULES:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_MOD_PURPOSE:
            return MOD_PURPOSE_COLLABORATION;
        default:
            return null;
    }
}


/**
 * Saves a new instance of the ttthzoom object into the database.
 *
 * Given an object containing all the necessary data (defined by the form in mod_form.php), this function
 * will create a new instance and return the id number of the new instance.
 *
 * @param stdClass $moduleinstance Submitted data from the form in mod_form.php
 * @param mod_ttthzoom_mod_form|null $mform The form instance (included because the function is used as a callback)
 * @return int The id of the newly inserted zoom record
 */
function ttthzoom_add_instance(stdClass $moduleinstance, ?mod_ttthzoom_mod_form $mform = null){
    global $DB;

    //$moduleinstance->name = get_string('new_column_title', 'stickynotes');
    $moduleinstance->timecreated = time();
    $id = $DB->insert_record('ttthzoom', $moduleinstance);

    return $id;
}


/**
 * Updates an instance of the ttthzoom in the database
 *
 * Given an object containing all the necessary data (defined by the form in mod_form.php), this function
 * will update an existing instance with new data.
 *
 * @param stdClass $moduleinstance An object from the form in mod_form.php
 * @param mod_ttthzoom_mod_form|null $mform The form instance (included because the function is used as a callback)
 * @return boolean Success/Failure
 */
function ttthzoom_update_instance(stdClass $moduleinstance, ?mod_ttthzoom_mod_form $mform = null){
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    return $DB->update_record('ttthzoom', $moduleinstance);
}


/**
 * Removes an instance of the ttthzoom from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function ttthzoom_delete_instance($id){
    global $DB;

    $exists = $DB->get_record('ttthzoom', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('ttthzoom', array('id' => $id));

    return true;
}