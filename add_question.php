<?php

/*
 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2007, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

require('../../config.php');

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

// Load Language file
if(LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/accordion/languages/EN.php');
    if(file_exists(WB_PATH.'/modules/accordion/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/accordion/languages/'.LANGUAGE.'.php');
    }
}

// Get new order
$order = new order(TABLE_PREFIX.'mod_accordion_questions', 'pos', 'question_id', 'section_id');
$position = $order->get_new($section_id);

// Insert new row into database
$database->query("INSERT INTO ".TABLE_PREFIX."mod_accordion_questions (section_id,page_id,question_id,question,answer,status_on_pageload,pos) VALUES ('$section_id','$page_id','0','','','',$position)");

// Get the id
$question_id = $database->get_one("SELECT LAST_INSERT_ID()");

// Say that a new record has been added, then redirect to modify page
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/accordion/modify_question.php?page_id='.$page_id.'&section_id='.$section_id.'&question_id='.$question_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/accordion/modify_question.php?page_id='.$page_id.'&section_id='.$section_id.'&question_id='.$question_id);
}

// Print admin footer
$admin->print_footer();

?>
