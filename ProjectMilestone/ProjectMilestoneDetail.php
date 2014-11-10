<?php

global $result;
global $client;
global $Server_Path;
global $Custom_client;global $Custom_Server_Path;$client = $Custom_client;$Server_Path = $Custom_Server_Path;
$customerid = $_SESSION['customer_id'];
$sessionid = $_SESSION['customer_sessionid'];

if($projectmilestoneid != '') {
	$params = array('id' => "$projectmilestoneid", 'block'=>"$block",'contactid'=>$customerid,'sessionid'=>"$sessionid");
	$result = $client->call('get_details', $params, $Server_Path, $Server_Path);
	// Check for Authorization
	if (count($result) == 1 && $result[0] == "#NOT AUTHORIZED#") {
		echo '<aside class="right-side">';
		echo '<section class="content-header" style="box-shadow:none;"><div class="alert"><b>'.getTranslatedString('LBL_NOT_AUTHORISED').'</b></div></section></aside>';
		die();
	}
	$projectmilestoneinfo = $result[0][$block];
	echo '<aside class="right-side">';
	echo '<section class="content-header" style="box-shadow:none;"><div class="row-pad">
			<div class="col-sm-10">
				<input class="btn btn-primary btn-flat" type="button" value="'.getTranslatedString('LBL_BACK_BUTTON').'" onclick="window.history.back();"/></div>
			</div></section>';
	echo getblock_fieldlist($projectmilestoneinfo,$block,$client,$Server_Path);			

	global $Custom_client;
	
	global $Custom_Server_Path;
	
	$comments  = getblock_fieldComments($block,$Custom_client,$Custom_Server_Path, $accountid);
	
	if($comments != ''){
		echo $comments;
		echo '</div></div></div></div>';
	}
	
	$attachments = getblock_fieldAttachments($block , $Custom_client, $Custom_Server_Path);	
	
	if($attachments != '') {
		echo $attachments;
		echo '</div></div></div></div>';
	}
}
?>