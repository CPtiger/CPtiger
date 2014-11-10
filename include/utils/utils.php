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

function checkFileAccess($filepath) {
	$root_directory = '';

	// Set the base directory to compare with
	$use_root_directory = $root_directory;
	if(empty($use_root_directory)) {
		$use_root_directory = realpath(dirname(__FILE__).'/../../.');
	}

	$realfilepath = realpath($filepath);

	/** Replace all \\ with \ first */
	$realfilepath = str_replace('\\\\', '\\', $realfilepath);
	$rootdirpath  = str_replace('\\\\', '\\', $use_root_directory);

	/** Replace all \ with / now */
	$realfilepath = str_replace('\\', '/', $realfilepath);
	$rootdirpath  = str_replace('\\', '/', $rootdirpath);

	if(stripos($realfilepath, $rootdirpath) !== 0) {
		die("Sorry! Attempt to access restricted file.");
	}
	return true;
}

function getblock_header($str,$headerspan='4',$ticketcloselink=false)
{
	$list = '';
	if ($ticketcloselink == true) {
		$list .='<tr><td colspan="'. ($headerspan-1) .'" class="detailedViewHeader"><b>'.getTranslatedString($str).'</b></td>';
		$list .='<td class="detailedViewHeader"><div align="right">'.$ticketcloselink.'</div></td></tr>';
	} else {
		$list .='<tr><td colspan="'. ($headerspan) .'" class="detailedViewHeader"><b>'.getTranslatedString($str).'</b></td></tr>';
	}	
	return $list;

}


function getblock_fieldlist($block_array)
{
	$list='';$z=0;
	$field_count=count($block_array);
	if($field_count != 0)
	{
		$list .= '<div style = "clear:both;"></div>';
		for($i=0;$i<$field_count;$i++,$z++)
		{
			$blockname = $block_array[$i]['blockname'];
			$data = $block_array[$i]['fieldvalue'];
			if($block_array[$i]['fieldlabel'] == 'Note'){
    			$data = html_entity_decode($data);
    		}
    		if($data =='')
				$data ='&nbsp;';
			if(strcmp($blockname,$block_array[$i-1]['blockname'])){
				if($z != 0)
					$list .= '</div></div></div></div>';
				if($blockname != 'Ticket Information') //hardcoded for tickets information block so that it ll not be displayed
					$list .= '<div class="widget-box"><div class = "widget-header">
							<h5 class = "widget-title">'.getblock_header($blockname).'</h5></div>';
					$z = 0;
				
					$list .= '<div class = "widget-body"><div class="widget-main no-padding single-entity-view">
						<div style="width:auto;padding:12px;display:block;" id="tblLeadInformation">';
			}
			
			if($z==0 || $z%2==0)
			$list .= '<div class="row">';
			$list .= '<div class="form-group col-sm-6"><label class="col-sm-3 control-label no-padding-right">'
					.getTranslatedString($block_array[$i]['fieldlabel']).'</label>';
			if(($z == 0 || $z%2 == 0) &&($i == ($field_count-1))){
				$list .= '<div class="col-sm-9 dvtCellInfo" align="left" valign="top">'.$data.'</div></div>';
			}
			else {
				$list .= '<div class="col-sm-9 dvtCellInfo" align="left" valign="top">&nbsp;'.$data.'</div></div>';
			}
			if($z%2 == 1 ||($i == ($field_count-1) )){
				$list .= '</div>';
			}
		}	
	}
	$list.= '<tr><td colspan="4">&nbsp;</td></tr>';
	return $list;
}

function getTranslatedString($str)
{
	global $app_strings;
	return (isset($app_strings[$str]))?$app_strings[$str]:$str;
}

// The function to get html format list data
// input array
// output htmlsource list array
//only for product
function getblock_fieldlistview_product($block_array,$module)
{
	
 $header = array();
 $header[0] = getTranslatedString($module);
 $header[1] = getTranslatedString('QUOTE_RELATED').getTranslatedString($module);	
 $header[2] = getTranslatedString('INVOICE_RELATED').getTranslatedString($module);	
 
 if($block_array == '')
 {
	$list.='<tr><td colspan="6">'.$module." ".getTranslatedString('LBL_NOT_AVAILABLE').'</td></tr>';
	return $list;
 }

for($k=0;$k<=2;$k++)
{

$header_arr =$block_array[$k][$module]['head'][0];	
$nooffields=count($header_arr);
$data_arr=$block_array[$k][$module]['data'];
	$noofdata=count($data_arr);
	
	$list .='<tr><td colspan="6"><b>'.getTranslatedString($header[$k]).'</b></td></tr>';
	
	if($block_array[$k][$module]['data'] == ''){
		$list.='<tr><td colspan="6">'.$header[$k]." ".getTranslatedString('LBL_NOT_AVAILABLE').'</td></tr>';
	}
	
	if(count($nooffields) > 0 ){
		$list .='<tr>';
		for($i=0;$i<$nooffields;$i++)
			$list .= "<th>".getTranslatedString($header_arr[$i]['fielddata'])."</th>";
		$list .='</tr>';
	}
		
	if(count($noofdata) > 0)
	{
		for($j=0;$j<$noofdata;$j++)
		{
			if($j==0||$j%2==0)
				$list .='<tr>';
			else
				$list .='<tr>';

			for($i=0;$i<$nooffields;$i++)
			{
				$data =$data_arr[$j][$i]['fielddata'];
				if($data == '')
					$data ='&nbsp;';
				$list .= "<td>".$data."</td>";
			}
			$list .='</tr>';
		}
	}	
   $list .= '<tr><td colspan ="'.$nooffields.'">&nbsp;</td></tr>';
}
return $list;
}

// The function to get html format list data
// input array
// output htmlsource list array
//for quotes,notes and invoice

function getblock_fieldlistview($block_array,$block)
{
	if($block_array[0] == "#MODULE INACTIVE#"){
		$list.='<div class="row"><div class="form-group col-sm-12">'.getTranslatedString($block)." ".getTranslatedString('MODULE_INACTIVE').'</div></div>';
		return $list;
	}
	if($block_array == ''){
		$list.='<div class ="row"><div class="form-group col-sm-12">'.getTranslatedString($block)." ".getTranslatedString('LBL_NOT_AVAILABLE').'</div></div>';
		return $list;
	}
	$header_arr =$block_array[0][$block]['head'][0];	
	$nooffields=count($header_arr);
	$data_arr=$block_array[1][$block]['data'];
	$noofdata=count($data_arr);
	if($nooffields!='')
	{
		$list .= '<div class="row"><div class="form-group col-sm-12">';
		$list .='<div class="box-body table-responsive no-padding"><table class="table table-hover"><tbody><tr>';
		for($i=0;$i<$nooffields;$i++)
			$list .= "<th>".getTranslatedString($header_arr[$i]['fielddata'])."</th>";
		$list .='</tr>';
	}	
	if($noofdata != '')
	{
		for($j=0;$j<$noofdata;$j++)
		{
			if($j==0 || $j%2==0)
				$list .='<tr>';
			else
				$list .='<tr>';

			for($i=0;$i<$nooffields;$i++)
			{
				$data =$data_arr[$j][$i]['fielddata'];
				if($data == '')
					$data ='&nbsp;';
				$list .= "<td>".$data."</td>";
			}
			$list .='</tr>';
		}
		
        $list .= '<tr><td colspan ="'.$nooffields.'">&nbsp;</td></tr>';
        $list .= '</table></div></div></div>';
}

return $list;
}





// The function to get html format url_list data
// input array
// output htmlsource list array
function getblockurl_fieldlistview($block_array,$block)
{
	$header_arr =$block_array[0][$block]['head'][0][0];	
	$nooffields=count($header_arr);
	$data_arr=$block_array[1][$block]['data'];
	$noofdata=count($data_arr);
	if($nooffields!='')
	{
		$list .='<tr class="detailedViewHeader" align="center">';
		for($i=0;$i<$nooffields;$i++)
			$list .= "<td>".getTranslatedString($header_arr[$i]['fielddata'])."</td>";
		$list .='</tr>';
	}	
	if($noofdata != '')
	{
		for($j=0;$j<$noofdata;$j++)
		{
			for($j1=0;$j1<count($data_arr[$j]);$j1++)
			{
				if($j1== '0'||$j1%2==0)
					$list .='<tr class="dvtLabel">';
				else
					$list .='<tr class="dvtInfo">';

				for($i=0;$i<$nooffields;$i++)
				{
					$data = $data_arr[$j][$j1][$i]['fielddata'];
					if($data =='')
						$data ='&nbsp;';
					if($i == '0' || $i== '1')
					{	if($j1 == '0')
						$list .= '<td rowspan="'.count($data_arr[$j]).'" >'.$data."</td>";
					}
					else
						$list .= "<td>".$data."</td>";
				}
				$list .='</tr>';
			}
		}
	}	
        $list .= '<tr><td colspan ="'.$nooffields.'">&nbsp;</td></tr>';

return $list;
}
/* 	Function to Show the languages in the Login page
*	Takes an array from PortalConfig.php file $language
*	Returns a list of available Language 	
*/
function getPortalLanguages() {
	global $languages,$default_language;
	foreach($languages as $name => $label) {
		if(strcmp($default_language,$name) == 0){
			$list .= '<option value='.$name.' selected>'.$label.'</option>';
		} else {
			$list .= '<option value='.$name.'>'.$label.'</option>';
		}
	}
	return $list;
}
/*	Function to set the Current Language
 * 	Sets the Session with the Current Language
 */
function setPortalCurrentLanguage() {
	global $default_language;
	if(isset($_REQUEST['login_language']) && $_REQUEST['login_language'] != ''){
		$_SESSION['portal_login_language'] = $_REQUEST['login_language'];
	} else {
		$_SESSION['portal_login_language'] = $default_language;
	}
}

/*	Function to get the Current Language
 * 	Returns the Current Language
 */
function getPortalCurrentLanguage() {
	global $default_language;
	if(isset($_SESSION['portal_login_language']) && $_SESSION['portal_login_language'] != ''){
		$default_language = $_SESSION['portal_login_language'];
	} else {
            $default_language = 'en_us';
        }
	return $default_language;
}


/** HTML Purifier global instance */
$__htmlpurifier_instance = false;

/*
 * Purify (Cleanup) malicious snippets of code from the input
 *
 * @param String $value
 * @param Boolean $ignore Skip cleaning of the input
 * @return String
 */
function portal_purify($input, $ignore=false) {
    global $default_charset, $__htmlpurifier_instance;
 
    $use_charset = $default_charset; 
    $value = $input; 
    if($ignore === false) {    	 
        // Initialize the instance if it has not yet done
        if(empty($use_charset)) $use_charset = 'UTF-8';
  
        if($__htmlpurifier_instance === false) {
            require_once('include/htmlpurify/library/HTMLPurifier.auto.php');
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Core.Encoding', $use_charset);
            $config->set('Cache.SerializerPath', "test/cache");
	
            $__htmlpurifier_instance = new HTMLPurifier($config);
        }
        if($__htmlpurifier_instance){
           $value = $__htmlpurifier_instance->purify($value);
        }
    }
	$value = str_replace('&amp;','&',$value);
    return $value;
}


function getblock_fieldComments($block, $client = '', $server_path = '', $id = '') 
{
	$list = '';
	
	if($client != '' && $server_path != ''){
		
		$customerid = $_SESSION['customer_id'];
		$sessionid = $_SESSION['customer_sessionid'];
			
		$params = array('block'=>"$block", 'contactid' => $customerid,'sessionid' => "$sessionid");
			
		$response = $client->call('is_ModComments_enable',$params,$server_path, $server_path);
		
		if($response[0]['IsActive'] != 'true'){
			return $list;
		}
		
		if($id){
			$_REQUEST['id'] = $id;
		}
		if(isset($_REQUEST['productid'])) {
			$_REQUEST['id'] = $_REQUEST['productid'];
		}
			
		if(isset($_REQUEST['comments'])){
			$id = $_REQUEST['id'];
			$ownerid = $_SESSION['customer_id'];
			$comments = $_REQUEST['comments'];
			$params = Array(Array('id'=>"$customerid", 'sessionid'=>"$sessionid", 'ticketid'=>"$id",'ownerid'=>"$customerid",'comments'=>"$comments"));
			$client->call('update_ticket_comment', $params, $server_path, $server_path);
		}
		
		if($_REQUEST['id'] != ''){
			$Id = $_REQUEST['id'];
			$param = Array(Array('id'=>"$customerid", 'sessionid'=>"$sessionid", 'ticketid' => "$Id"));
			$commentresult = $client->call('get_ticket_comments', $param, $server_path, $server_path);
			$commentscount = count($commentresult);
			$list .= '</form><div class="widget-box">
				<div class = "widget-header">
					<h5 class = "widget-title"><b>'.getTranslatedString('LBL_COMMENTS').'</b></h5>
				</div>
				<div class = "widget-body">
					<div class="widget-main no-padding single-entity-view">
					<div style="width:auto;padding:12px;display:block;" id="tblLeadInformation">';
		
			if($commentscount >= 1 && is_array($commentresult)){
				$list .= '<div id="scrollTab2">
					<table width="100%"  border="0" cellspacing="5" cellpadding="5">';
					for($j=0;$j<$commentscount;$j++){
						$list .= '<tr>
									<td width="5%" valign="top">'.($commentscount-$j).'</td>
									<td width="95%">'.$commentresult[$j]['comments'].'<br><span class="hdr">'.getTranslatedString('LBL_COMMENT_BY').' : '.$commentresult[$j]['owner'].' '.getTranslatedString('LBL_ON').' '.$commentresult[$j]['createdtime'].'</span></td>
								  </tr>';
					}
					$list .=  '</table></div>';
			}
		
			$list .=  '<div class="row">';
			if(isset($_REQUEST['productid'])){
				$list .= '<form name="comments" action="index.php?module='.$block.'&action=index&productid=' . $Id . '" method="post">
						<input type="hidden" name="module" value = "'. $block . '">
						<input type="hidden" name="action" value = "index">
						<input type="hidden" name="id" value="'.$Id.'">
						<div class="form-group col-sm-12 no-padding">
							<label class="col-sm-2 control-label no-padding-right">
								Add Comment
							</label>
							<div class="col-sm-10 dvtCellInfo" align="left" style = "background-color:none;">
								<textarea name="comments" cols="55" rows="5"></textarea><br/><br/>
								<input class="btn btn-minier btn-success" title="'.getTranslatedString('LBL_SUBMIT').'" accesskey="S" class="small"  name="submit" value="'.getTranslatedString('LBL_SUBMIT').'" style="width: 70px;" type="submit"/>
							</div>
						</div>
						</form>';
			}
			else {
				$list .= '<form name="comments" action="index.php?module='.$block.'&action=index&status=true&id=' . $Id . '" method="post">
					<input type="hidden" name="module" value = "'. $block . '">
					<input type="hidden" name="action" value = "index">
					<input type="hidden" name="id" value="'.$Id.'">
					<div class="form-group col-sm-12 no-padding">
						<label class="col-sm-2 control-label no-padding-right">
							Add Comment
						</label>
						<div class="col-sm-10 dvtCellInfo" align="left" style = "background-color:none;">
							<textarea name="comments" cols="55" rows="5"></textarea><br/><br/>
							<input class="btn btn-minier btn-success" title="'.getTranslatedString('LBL_SUBMIT').'" accesskey="S" class="small"  name="submit" value="'.getTranslatedString('LBL_SUBMIT').'" style="width: 70px;" type="submit"/>
						</div>
					</div>
				</form>';
			}
			$list .= '</div>';
		}	
	
	}
	
	//$list .= '<tr><td colspan="4">&nbsp;</td></tr>';
	
	return $list;
}

function getblock_fieldAttachments($block, $Custom_client = '', $Custom_Server_Path = '')
{
	$customerid = $_SESSION['customer_id'];
	$sessionid = $_SESSION['customer_sessionid'];
	
	if($Custom_client != '' && $Custom_Server_Path != ''){
	
	
		if(isset($_REQUEST['productid'])) {
			$_REQUEST['id'] = $_REQUEST['productid'];
		}
		
		if (isset($_REQUEST['customerfile_hidden']))
		{
			$id = $_REQUEST['id'];
			$filename = $_FILES['customerfile']['name'];
			$filetype = $_FILES['customerfile']['type'];
			$filesize = $_FILES['customerfile']['size'];
			$fileerror = $_FILES['customerfile']['error'];
			$filename = $_REQUEST['customerfile_hidden'];
				
			$upload_error = '';
			if($fileerror == 4)
			{
				$upload_error = getTranslatedString('LBL_GIVE_VALID_FILE');
			}
			elseif($fileerror == 2)
			{
				$upload_error = getTranslatedString('LBL_UPLOAD_FILE_LARGE');
			}
			elseif($fileerror == 3)
			{
				$upload_error = getTranslatedString('LBL_PROBLEM_UPLOAD');
			}
		
				//Copy the file in temp and then read and pass the contents of the file as a string to db
			global	$upload_dir;
			if(!is_dir($upload_dir)) {
				echo getTranslatedString('LBL_NOTSET_UPLOAD_DIR');
				exit;
			}
			if($filesize > 0)
			{
				if(move_uploaded_file($_FILES["customerfile"]["tmp_name"],$upload_dir.'/'.$filename))
				{
					$filecontents = base64_encode(fread(fopen($upload_dir.'/'.$filename, "r"), $filesize));
				}
				$params = Array(Array(
						'id'=>"$customerid",
						'sessionid'=>"$sessionid",
						'blockid'=>"$id",
						'block'=> "$block",
						'filename'=>"$filename",
						'filetype'=>"$filetype",
						'filesize'=>"$filesize",
						'filecontents'=>"$filecontents"
				));
				if($filecontents != ''){
					$commentresult = $Custom_client->call('add_attachment', $params, $Custom_Server_Path, $Custom_Server_Path);
				}else{
					echo getTranslatedString('LBL_FILE_HAS_NO_CONTENTS');
					exit();
				}	
			}
			else
			{
				$upload_error = getTranslatedString('LBL_UPLOAD_VALID_FILE');
			}
			
			$upload_status = $upload_error;
			if($upload_status != ''){
				echo $upload_status;
				exit(0);
			} 
		}
		
		if($_REQUEST['id'] != ''){
			
			$Id = $_REQUEST['id'];
			$customerid = $_SESSION['customer_id'];
			$sessionid = $_SESSION['customer_sessionid'];
			$params = Array(Array('id'=>"$customerid", 'block'=>"$block", 'sessionid'=>"$sessionid", 'blockid'=>"$Id"));
			$files_array = $Custom_client->call('get_attachments',$params);
	
			if($files_array[0] != "#MODULE INACTIVE#"){
							
				$list .= '<div class="widget-box">
							<div class = "widget-header">
								<h5 class = "widget-title">'.getTranslatedString('LBL_ATTACHMENTS').'</h5>
							</div>
							<div class = "widget-body">
								<div class="widget-main no-padding single-entity-view">
									<div style="width:auto;padding:12px;display:block;" id="tblLeadInformation">';
			
				$attachments_count = count($files_array);
				$z = 0;
					
				if(is_array($files_array)){
							
					for($j=0;$j<$attachments_count;$j++,$z++){
						
						$filename = $files_array[$j]['filename'];
						$filetype = $files_array[$j]['filetype'];
						$filesize = $files_array[$j]['filesize'];
						$fileid = $files_array[$j]['fileid'];
						$filelocationtype = $files_array[$j]['filelocationtype'];
						$attachments_title = '';
						
						if($j == 0)
							$attachments_title = getTranslatedString('LBL_ATTACHMENTS');
						
						if($z==0 || $z%2==0) {
							$list .= '<div class = "row">';
						}
						if($filelocationtype == 'I'){
						
							$list .= '<div class="form-group col-sm-6">
										<label class="col-sm-3 control-label no-padding-right">
											'.$attachments_title.
										'</label>
										<div class="col-sm-9 dvtCellInfo" align="left" valign="top">
											<a href="index.php?downloadfile=true&fileid='.$fileid.'&filename='.$filename.'&filetype='.$filetype.'&filesize='.$filesize.'&id='.$Id.'">'.ltrim($filename,$Id.'_').'</a>
										</div>
									</div>';
							
						} else {
							$list .= '<div class="form-group col-sm-6">
										<label class="col-sm-3 control-label no-padding-right">
											'.$attachments_title.
										'</label>
										<div class="col-sm-9 dvtCellInfo" align="left" valign="top">
										&nbsp;
											<a target="blank" href='.$filename.'>'.$filename.'</a>
										</div>
									</div>';	
						}
						if($z%2 == 1 ||($j == ($attachments_count-1) ))
								$list .= '</div>';
							
					}
				} else{
					$list .= '<div class = "row" style="margin-left:0px;">'.getTranslatedString('NO_ATTACHMENTS').'</div>';
				}
			}
				
				//To display the file upload error
			if($upload_status != ''){
				$list .= '<div class = "row">
						<b>'.getTranslatedString('LBL_FILE_UPLOADERROR').'</b>
						<font color="red">'.$upload_status.'</font>
					   </div>';
			}
			$list .= '<div class="row">';
			if(isset($_REQUEST['productid']))
			{
				$list .= '<form name="fileattachment" method="post" enctype="multipart/form-data" action="index.php?module='.$block.'&action=index&productid='.$Id.'">
						<input type="hidden" name="module" value="'.$block.'">
						<input type="hidden" name="action" value="index">
						<input type="hidden" name="id" value="'.$Id.'">
					
								<div class="form-group col-sm-6">
										<label class="col-sm-3 control-label no-padding-right">
											'.getTranslatedString('LBL_ATTACH_FILE').
										'</label>
										<div class="col-sm-9 dvtCellInfo" align="left" valign="top">
											<input type="file" size="50" name="customerfile" class="detailedViewTextBox" onchange="validateFilename(this)" />
										<input type="hidden" name="customerfile_hidden"/>
										<br/><br/>
										<input class="tn btn-minier btn-success" name="Attach" type="submit" value="'.getTranslatedString('LBL_ATTACH').'">
									</div>
									</div>
									
									<div class="form-group col-sm-6">
										<label class="col-sm-3 control-label no-padding-right">
										&nbsp;	
										</label>
									</div>
								</form>';
			} else {
				$list .= '<form name="fileattachment" method="post" enctype="multipart/form-data" action="index.php?module='.$block.'&action=index&status=true&id='.$Id.'">
						<input type="hidden" name="module" value="'.$block.'">
						<input type="hidden" name="action" value="index">
						<input type="hidden" name="id" value="'.$Id.'">
					
								<div class="form-group col-sm-6">
										<label class="col-sm-3 control-label no-padding-right">
											'.getTranslatedString('LBL_ATTACH_FILE').
										'</label>
										<div class="col-sm-9 dvtCellInfo" align="left" valign="top">
											<input type="file" size="50" name="customerfile" class="detailedViewTextBox" onchange="validateFilename(this)" />
										<input type="hidden" name="customerfile_hidden"/>
										<br/><br/>
										<input class="tn btn-minier btn-success" name="Attach" type="submit" value="'.getTranslatedString('LBL_ATTACH').'">
									</div>
									</div>
									
									<div class="form-group col-sm-6">
										<label class="col-sm-3 control-label no-padding-right">
										&nbsp;	
										</label>
									</div>
								</form>';
			}
			$list .= '</div>';
			$list .= '</div></div></div></div>';
			echo $list;
		}
	}
	
$filevalidation_script = <<<JSFILEVALIDATION
<script type="text/javascript">
                
function getFileNameOnly(filename) {
	var onlyfilename = filename;
  	// Normalize the path (to make sure we use the same path separator)
 	var filename_normalized = filename.replace(/\\\\/g, '/');
  	if(filename_normalized.lastIndexOf("/") != -1) {
    	onlyfilename = filename_normalized.substring(filename_normalized.lastIndexOf("/") + 1);
  	}
  	return onlyfilename;
}
/* Function to validate the filename */
function validateFilename(form_ele) {
if (form_ele.value == '') return true;
	var value = getFileNameOnly(form_ele.value);
	// Color highlighting logic
	var err_bg_color = "#FFAA22";
	if (typeof(form_ele.bgcolor) == "undefined") {
		form_ele.bgcolor = form_ele.style.backgroundColor;
	}
	// Validation starts here
	var valid = true;
	/* Filename length is constrained to 255 at database level */
	if (value.length > 255) {
		alert(alert_arr.LBL_FILENAME_LENGTH_EXCEED_ERR);
		valid = false;
	}
	if (!valid) {
		form_ele.style.backgroundColor = err_bg_color;
		return false;
	}
	form_ele.style.backgroundColor = form_ele.bgcolor;
	form_ele.form[form_ele.name + '_hidden'].value = value;
	return true;
}
</script>
JSFILEVALIDATION;

echo $filevalidation_script;
	
}
?>