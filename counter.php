<?php
// select_awards.php
/*
Copyright © 2024 NA7KR Kevin Roberts. All rights reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
include ("../config.php");;
require_once(__DIR__ . '/backend/lookup.class.php');

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
//must have browser.ini in php.ini file
$browser = get_browser(null, true);
//print_r ($browser['browser']);
//format the browser info
$browser_type =  $browser['browser'];
//format the browser version
$version = $browser['version'] ;
//format the OS
$os = str_replace("Win", "Windows ", $browser['platform']);
//format the OS version
$user_os = str_replace("Win", "Windows ", $browser['platform_version']);
$SQL = " INSERT INTO `visit` (`visitorIP`  ,`visitURL`, `browser`  ,`version`,`os`, `osversion`) "
. " VALUES ( '$ipAddress'  , '$visitURL', '$browser_type', '$version', '$os','$user_os' )";
//echo "SQL " . $SQL ;
 $update = $db->query($SQL);
?>
