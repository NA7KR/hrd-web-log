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
<<<<<<< HEAD
require_once(__DIR__ . '/Lookup.class.php');
$db = new Db();

=======
require_once(__DIR__ . '/lookup.class.php');
$db = new Db();
>>>>>>> eb0032157d2001b7f4d85e1dacc3c0b6fa1d8b0d
// Get the URL
$url = $_SERVER['SERVER_NAME'];
// Get the IP Address
$ipAddress = $_SERVER['REMOTE_ADDR'];
// Get the script name
$scriptName = $_SERVER['SCRIPT_NAME'];
//format the URL
$visitURL = $url.$scriptName;
<<<<<<< HEAD

// Get the URL
$url = $_SERVER['SERVER_NAME'];

=======
// Get the URL
$url = $_SERVER['SERVER_NAME'];
>>>>>>> eb0032157d2001b7f4d85e1dacc3c0b6fa1d8b0d
// Get the IP Address
$ipAddress = $_SERVER['REMOTE_ADDR'];
// Get the script name
$scriptName = $_SERVER['SCRIPT_NAME'];
<<<<<<< HEAD

//format the URL
$visitURL = $url.$scriptName;
$browser = get_browser(null, true);
$browser_type = str_replace("IE", "Internet Explorer", $browser['browser']);
$version = $browser['version'] ;
$os = str_replace("Win", "Windows ", $browser['platform']);
$user_os = str_replace("Win", "Windows ", $browser['platform_version']);

$SQL = " INSERT INTO `visit` (`visitorIP`  ,`visitURL`, `browser`  ,`version`,`os`, `osversion`) VALUES ( '$ipAddress'  , '$visitURL', '$browser_type', '$version', '$os','$user_os' )";
=======
//format the URL
$visitURL = $url.$scriptName;
//must have browser.ini in php.ini file
$browser = get_browser(null, true);
//format the browser info
$browser_type = str_replace("IE", "Internet Explorer", $browser['browser']);
//format the browser version
$version = $browser['version'] ;
//format the OS
$os = str_replace("Win", "Windows ", $browser['platform']);
//format the OS version
$user_os = str_replace("Win", "Windows ", $browser['platform_version']);
$SQL = " INSERT INTO `visit` (`visitorIP`  ,`visitURL`, `browser`  ,`version`,`os`, `osversion`) "
. " VALUES ( '$ipAddress'  , '$visitURL', '$browser_type', '$version', '$os','$user_os' )";
>>>>>>> eb0032157d2001b7f4d85e1dacc3c0b6fa1d8b0d
 $update = $db->query($SQL);
?>
