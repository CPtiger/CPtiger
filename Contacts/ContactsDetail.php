<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

global $result;
global $client;
global $Server_Path;

$customerid = $_SESSION['customer_id'];
$sessionid = $_SESSION['customer_sessionid'];
if($id != '')
{

	$params = array('id' => "$id", 'block'=>"$block",'contactid'=>$customerid,'sessionid'=>"$sessionid");
	$result = $client->call('get_details', $params, $Server_Path, $Server_Path);
	// Check for Authorization
	if (count($result) == 1 && $result[0] == "#NOT AUTHORIZED#") {
		echo '<aside class="right-side">';
		echo '<section class="content-header" style="box-shadow:none;"><div class="alert"><b>'.getTranslatedString('LBL_NOT_AUTHORISED').'</b></div></section></aside>';
		include("footer.html");
		die();
	}
	$noteinfo = $result[0][$block];
	echo '<aside class="right-side">';
	echo '<section class="content-header" style="box-shadow:none;">
			<div class="row-pad"><div class="col-sm-10">
				<input class="btn btn-primary btn-flat" type="button" value="'.getTranslatedString('LBL_BACK_BUTTON').'" onclick="window.history.back();"/>
				</div></div></section>';
	echo getblock_fieldlist($noteinfo);

	global $Custom_client;
	
	global $Custom_Server_Path;
	
	$comments  = getblock_fieldComments($block,$Custom_client,$Custom_Server_Path, $accountid);
	
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

	echo '</table></td></tr>';	
	echo '</table></td></tr></table></td></tr></table>';
	echo '<!-- --End--  -->';
}

?>
