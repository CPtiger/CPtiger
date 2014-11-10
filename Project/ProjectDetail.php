<?php

global $result;
global $Custom_client;
global $Custom_Server_Path;

$customerid = $_SESSION['customer_id'];
$sessionid = $_SESSION['customer_sessionid'];

if($projectid != '') {
	$params = array('id' => "$projectid", 'block'=>"$block",'contactid'=>$customerid,'sessionid'=>"$sessionid");
	$result = $Custom_client->call('get_details', $params, $Custom_Server_Path, $Custom_Server_Path);
	// Check for Authorization
	if (count($result) == 1 && $result[0] == "#NOT AUTHORIZED#") {
		echo '<aside class="right-side">';
		echo '<section class="content-header" style="box-shadow:none;"><div class="alert"><b>'.getTranslatedString('LBL_NOT_AUTHORISED').'</b></div></section></aside>';
		die();
	}
	$projectinfo = $result[0][$block];
	echo '<aside class="right-side">';
	echo '<section class="content-header" style="box-shadow:none;"><div class="row-pad">
			<div class="col-sm-10">
				<input class="btn btn-primary btn-flat" type="button" value="'.getTranslatedString('LBL_BACK_BUTTON').'" onclick="window.history.back();"/></div>
			<div class="col-sm-2 search-form"><div class="input-group-btn">
			<input class="btn btn-primary btn-flat" type="button" value="'.getTranslatedString('LBL_RAISE_TICKET_BUTTON').'" onclick="location.href=\'index.php?module=HelpDesk&action=index&fun=newticket&projectid='.$projectid.'\'"/></td>
			</div></div></div></section>';
	echo getblock_fieldlist($projectinfo);
	
	global $Custom_client;
	
	global $Custom_Server_Path;
	
	$comments  = getblock_fieldComments($block,$Custom_client,$Custom_Server_Path);
	
	if($comments != ''){
		echo '</div></div></div></div>';
		echo $comments;
		echo '</div></div></div></div>';
	}
	$attachments = getblock_fieldAttachments($block , $Custom_client, $Custom_Server_Path);	
	
	if($attachments != '') {
		echo $attachments;
		echo '</div></div></div></div>';
	}
	
	
	
	$projecttaskblock = 'ProjectTask';
	$params = array('id' => "$projectid", 'block'=>"$projecttaskblock",'contactid'=>$customerid,'sessionid'=>"$sessionid");
	$result = $Custom_client->call('get_project_components', $params, $Custom_Server_Path, $Custom_Server_Path);	
	echo '<div class="widget-box"><div class="widget-header"><h5 class="widget-title">'.getTranslatedString('LBL_PROJECT_TASKS').'</h5></div>';
	echo '<div class = "widget-body"><div class="widget-main no-padding single-entity-view">
			<div style="width:auto;padding:12px;display:block;" id="tblLeadInformation">';
	echo getblock_fieldlistview($result,"$projecttaskblock");	
	echo '</table></td></tr>';
	
	echo '<tr><td colspan="4">&nbsp;</div></div></div></div>';
	
	$projectmilestoneblock = 'ProjectMilestone';
	$params = array('id' => "$projectid", 'block'=>"$projectmilestoneblock",'contactid'=>$customerid,'sessionid'=>"$sessionid");
	$result = $Custom_client->call('get_project_components', $params, $Custom_Server_Path, $Custom_Server_Path);	
	echo '<div class="widget-box"><div class="widget-header"><h5 class="widget-title">'.getTranslatedString('LBL_PROJECT_MILESTONES').'</h5></div>';
	echo '<div class = "widget-body"><div class="widget-main no-padding single-entity-view">
			<div style="width:auto;padding:12px;display:block;" id="tblLeadInformation">';
	echo getblock_fieldlistview($result,"$projectmilestoneblock");	
	echo '</table></td></tr>';
	
	echo '<tr><td colspan="4">&nbsp;</div></div></div></div>';
	
	$projectticketsblock = 'HelpDesk';
	$params = array('id' => "$projectid", 'block'=>"$projectticketsblock",'contactid'=>$customerid,'sessionid'=>"$sessionid");
	$result = $Custom_client->call('get_project_tickets', $params, $Custom_Server_Path, $Custom_Server_Path);	
	echo '<div class="widget-box"><div class="widget-header"><h5 class="widget-title">'.getTranslatedString('LBL_PROJECT_TICKETS').'</h5></div>';
	echo '<div class = "widget-body"><div class="widget-main no-padding single-entity-view">
			<div style="width:auto;padding:12px;display:block;" id="tblLeadInformation">';
	echo getblock_fieldlistview($result,"$projectticketsblock");		
	echo '</table></td></tr>';

	echo '</table></td></tr>';	
	echo '</table></td></tr></table></td></tr></table>';
	echo '<!-- --End--  -->';
}

?>
