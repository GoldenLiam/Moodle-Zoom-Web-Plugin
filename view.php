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

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/../../lib/outputcomponents.php');
global $DB, $USER;

$zoom_user_role = "";
$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$t = optional_param('t', 0, PARAM_INT); // ttthzoom ID

if($id){
    $cm = get_coursemodule_from_id('ttthzoom', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('ttthzoom', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($t) {
    $moduleinstance = $DB->get_record('ttthzoom', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('ttthzoom', $moduleinstance->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);
$modulecontext = context_module::instance($cm->id);

// Lấy tất cả các vai trò của người dùng trong ngữ cảnh này
$user_roles = get_user_roles($modulecontext, $USER->id);
foreach ($user_roles as $role){
    if($role->roleid == '1') {
        $zoom_user_role = "Admin";
    }
    else if($role->roleid == '3' && $zoom_user_role != "Admin") {
        $zoom_user_role = "Teacher";
    }
}

$PAGE->set_url('/mod/ttthzoom/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);


echo $OUTPUT->header();

$data = [
    'meeting_number' => $moduleinstance->meetingnumber,
    'password' => $moduleinstance->password,
    'zoom_user_role' => $zoom_user_role,
    'fullname' => $USER->firstname . " " . $USER->lastname,
    'lang' => 'vi-VN'
];
echo '<iframe src="index_iframe.php?' . http_build_query($data) . '" sandbox="allow-forms allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox" allow="microphone; camera; fullscreen;" frameBorder="0" scrolling="no" width="100%" height="600px"></iframe>';

echo $OUTPUT->footer();