<?php
/***************************************************************************
*			NA7KR Log Program 
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
include_once (__DIR__ . '/../config.php');
require_once(__DIR__ . '/Lookup.class.php');
$db = new Db();

// Get the URL
$url = $_SERVER['SERVER_NAME'];

// Get the IP Address
$ipAddress = $_SERVER['REMOTE_ADDR'];
// Get the script name
$scriptName = $_SERVER['SCRIPT_NAME'];

//format the URL
$visitURL = $url.$scriptName;

// Get the URL
$url = $_SERVER['SERVER_NAME'];

// Get the IP Address
$ipAddress = $_SERVER['REMOTE_ADDR'];
// Get the script name
$scriptName = $_SERVER['SCRIPT_NAME'];

//format the URL
$visitURL = $url.$scriptName;
$browser = get_browser(null, true);
$browser_type = str_replace("IE", "Internet Explorer", $browser['browser']);
$version = $browser['version'] ;
$os = str_replace("Win", "Windows ", $browser['platform']);
$user_os = str_replace("Win", "Windows ", $browser['platform_version']);

$SQL = " INSERT INTO `visit` (`visitorIP`  ,`visitURL`, `browser`  ,`version`,`os`, `osversion`) VALUES ( '$ipAddress'  , '$visitURL', '$browser_type', '$version', '$os','$user_os' )";
 $update = $db->query($SQL);
?>
