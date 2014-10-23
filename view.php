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

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }


// Get settings
$query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_accordion_settings` WHERE section_id='$section_id' LIMIT 1");
$fetch_settings = $query_settings->fetchRow();

$header= $admin->strip_slashes($fetch_settings['header']);
$footer= $admin->strip_slashes($fetch_settings['footer']);
$icon= $admin->strip_slashes($fetch_settings['icon']);
$icon_placement= $admin->strip_slashes($fetch_settings['icon_placement']);
$accordion_method = $admin->strip_slashes($fetch_settings['accordion_method']);

//Check if method is set to accordion
if($accordion_method=="accordion"){
	$accordion_true = 'accordion: true,';
}
else{
	$accordion_true = '';
}

// Print header
if ( $header <> "" ) {
	echo $header;
}

// Loop through existing questions
$query_quests = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_accordion_questions` WHERE section_id='".$section_id."' ORDER BY pos ASC");
if($query_quests->numRows() > 0)
	{
		echo '<div class="wb-accordion '.$icon_placement.' '.$icon.' sid-'.$section_id.'">';
		while($quest = $query_quests->fetchRow())
		{
			$content= $admin->strip_slashes($quest['answer']);
			$question = $admin->strip_slashes($quest['question']);
			$qid = $admin->strip_slashes($quest['question_id']);
			$status_on_pageload = $admin->strip_slashes($quest['status_on_pageload']);
			$wb->preprocess($content);
			
			echo '<span class="accordion-title '.$status_on_pageload.'">'.$question.'</span>';
			echo '<div class="accordion-content">'.$content.'</div>';
		}
		echo '</div>';
		echo '<script type="text/javascript">
				$(".sid-'.$section_id.'").collapse({
					open: function() {this.slideDown(150);},
					close: function() {this.slideUp(150);},
					'.$accordion_true.'
				});
			</script>';
	}

// Print footer
if ( $footer <> "" ) {
	echo $footer;
}

?>
