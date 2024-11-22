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

$data = [];
parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $data);

// Xác định options cho từng loại `zoom_user_role`
$options = [];
if ($data['zoom_user_role'] === "Admin") {
    $options = [
        ['value' => 0, 'label' => 'Học viên'],
        ['value' => 1, 'label' => 'Giáo viên']
    ];
} elseif ($data['zoom_user_role'] === "Teacher") {
    $options = [
        ['value' => 1, 'label' => 'Giáo viên']
    ];
} else {
    $options = [
        ['value' => 0, 'label' => 'Học viên']
    ];
}

$data['options'] = $options;

// Xác định `selected` cho ngôn ngữ
$data['is_en'] = ($data['lang'] === 'en-US');
$data['is_vi'] = ($data['lang'] === 'vi-VN' || empty($data['lang']));

echo $OUTPUT->render_from_template("mod_ttthzoom/index", $data);