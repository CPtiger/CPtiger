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

require_once("PortalConfig.php");
require_once('nusoap/lib/nusoap.php');

ini_set("display_errors", 0);

global $Server_Path;
global $client;

global $Custom_Server_Path;
global $Custom_client;

$client = new nusoap_client($Server_Path."/vtigerservice.php?service=customerportal", false, $proxy_host, $proxy_port, $proxy_username, $proxy_password);

//We have to overwrite the character set which was set in nusoap/lib/nusoap.php file (line 87)
$client->soap_defencoding = $default_charset;

$Custom_client = new nusoap_client($Custom_Server_Path."/vtigerservice.php?service=cpvtiger", false, $proxy_host, $proxy_port, $proxy_username, $proxy_password);
$Custom_client->soap_defencoding = $default_charset;

?>
