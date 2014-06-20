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

if(defined('WB_URL')) {

	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_accordion_questions`");
	$mod_accordion_questions = 'CREATE TABLE `'.TABLE_PREFIX.'mod_accordion_questions` ( '
		. '`section_id` INT(10) NOT NULL DEFAULT \'0\','
		. '`page_id` INT NOT NULL DEFAULT \'0\' ,'
		. '`question_id` INT(10) NOT NULL AUTO_INCREMENT,'
		. '`question` VARCHAR(255) NOT NULL DEFAULT \'\','
		. '`answer` TEXT NOT NULL,'
		. '`status_on_pageload` TEXT NOT NULL,'
		. '`pos` INT(10) NOT NULL DEFAULT \'0\','
		. 'PRIMARY KEY (question_id)'
		. ')';
	$database->query($mod_accordion_questions);
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_accordion_settings`");
	$mod_accordion_settings = 'CREATE TABLE `'.TABLE_PREFIX.'mod_accordion_settings` ( '
		. '`section_id` INT(10) NOT NULL DEFAULT \'0\','
		. '`page_id` INT NOT NULL DEFAULT \'0\' ,'
		. '`header` TEXT NOT NULL,'
		. '`footer` TEXT NOT NULL,'
		. '`icon` TEXT NOT NULL,'
		. '`accordion_method` TEXT NOT NULL,'
		. 'PRIMARY KEY (section_id)'
		. ')';
	$database->query($mod_accordion_settings);
	
	// Insert info into the search table
	// Module query info
	$field_info = array();
	$field_info['page_id'] = 'page_id';
	$field_info['title'] = 'page_title';
	$field_info['link'] = 'link';
	$field_info['description'] = 'description';
	$field_info['modified_when'] = 'modified_when';
	$field_info['modified_by'] = 'modified_by';
	$field_info = serialize($field_info);
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'accordion', '$field_info')");
	// Query start
	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_accordion_questions, [TP]pages WHERE ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'accordion')");
	// Query body
	$query_body_code = "
	[TP]pages.page_id = [TP]mod_accordion_questions.page_id AND [TP]mod_accordion_questions.question LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_accordion_questions.page_id AND [TP]mod_accordion_questions.answer LIKE \'%[STRING]%\' ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'accordion')");
	// Query end
	$query_end_code = "";	
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'accordion')");
	
	// Insert blank row (there needs to be at least on row for the search to work)
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_accordion_questions (page_id,section_id) VALUES ('0','0')");
	
}

?>
