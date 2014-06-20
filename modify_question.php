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

// Get id
if(!isset($_GET['question_id']) OR !is_numeric($_GET['question_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$question_id = $_GET['question_id'];
}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Load Language file
if(LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/accordion/languages/EN.php');
    if(file_exists(WB_PATH.'/modules/accordion/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/accordion/languages/'.LANGUAGE.'.php');
    }
}

// Get info on question
$query_question = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_accordion_questions WHERE question_id='$question_id' AND section_id='".$section_id."' ");
$fetch_question = $query_question->fetchRow();
?>

<style type="text/css">
.setting_name {
	vertical-align: top;
}
</style>

<form name="modify" action="<?php echo WB_URL; ?>/modules/accordion/save_question.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="question_id" value="<?php echo $question_id; ?>">

<table class="row_a" cellpadding="2" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td class="setting_name">
		<label for="question" accesskey="q"><b><?php echo $MODIFY_QUESTION['QUESTION']; ?>:</b></label>
	</td>
</tr>
<tr>
	<td class="setting_name">
		<input type="text" name="question" id="question" value="<?php echo $admin->strip_slashes($fetch_question['question']); ?>" style="width: 98%;" maxlength="255" />
		
	</td>
</tr>
<tr>
	<td class="setting_name">
		<label for="question" accesskey="q"><b><?php echo $MODIFY_QUESTION['STATUS_ON_LOAD']; ?>:</b></label>
		<?php $status = $admin->strip_slashes($fetch_question['status_on_pageload']);?>
	</td>
</tr>
<tr>
	<td class="setting_name">
		<select name="status_on_pageload" style="width: 100%;">
			<option value="" <?php if ($status == ""){echo 'selected="selected"';} ?>>Closed</option>
			<option value="open" <?php if ($status == "open"){echo 'selected="selected"';} ?>>Open</option>
  		</select> 
	</td>
</tr>
<tr>
	<td class="setting_name">
		<label for="answer" accesskey="a"><b><?php echo $MODIFY_QUESTION['ANSWER']; ?>:</b></label>
	</td>
</tr>
</table>

<?php
$content = $fetch_question['answer'];
$name="answer";

if(!isset($wysiwyg_editor_loaded)) {
	$wysiwyg_editor_loaded=true;

	if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
		function show_wysiwyg_editor($name,$id,$content,$width,$height) {
			echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
		}
	} else {
		$id_list=array();
		$query_wysiwyg = $database->query("SELECT section_id FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' AND module = 'accordion'");
		if($query_wysiwyg->numRows() > 0) {
			while($wysiwyg_section = $query_wysiwyg->fetchRow()) {
				$entry='content'.$wysiwyg_section['section_id'];
				array_push($id_list,$entry);
			}
			require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
		}
	}
}

show_wysiwyg_editor('answer','content'.$section_id,htmlspecialchars($content),'100%','350px');

?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;"></form>
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

<?php
// Print admin footer
$admin->print_footer();

?>
