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

global $result,$client;
$customerid = $_SESSION['customer_id'];
$username = $_SESSION['customer_name'];
$sessionid = $_SESSION['customer_sessionid'];

$onlymine=$_REQUEST['onlymine'];
if($onlymine == 'true') {
    $mine_selected = 'selected';
    $all_selected = '';
} else {
    $mine_selected = '';
    $all_selected = 'selected';
}


$module = 'Services';	

		$params = Array('id'=>$customerid,'module'=>$module,'sessionid'=>$sessionid,'onlymine'=>$onlymine);
		$result = $client->call('get_service_list_values', $params, $Server_Path, $Server_Path);

	echo '<aside class="right-side">';
	echo '<section class="content-header" style="box-shadow:none;"><div class="row-pad">';
	echo '<div class="col-sm-10"><b style="font-size:20px;">'.getTranslatedString("LBL_SERVICE").'</b></div>';    
		$allow_all = $client->call('show_all',array('module'=>$module),$Server_Path, $Server_Path);
	
	    if($allow_all == 'true'){
	      		echo '<div class="col-sm-2 search-form"> <b>'.getTranslatedString('SHOW').'</b>&nbsp; <select name="list_type"  class="form control input-sm" onchange="getList(this, \'Services\');">
	 			<option value="mine" '. $mine_selected .'>'.getTranslatedString('MINE').'</option>
				<option value="all"'. $all_selected .'>'.getTranslatedString('ALL').'</option>
				</select></div></div></section>';
	    		}
	      		
	      		echo '<section class="content"><div class="row">';
	    		echo '<div class="col-xs-12">';
	      		echo '<div class="box-body table-responsive no-padding"><table class="table table-hover">';
	      
	echo getblock_fieldlistview_product($result,$module);
	echo '</table></td></tr></table></td></tr></table>';
echo '<!-- --End--  -->';


?>
