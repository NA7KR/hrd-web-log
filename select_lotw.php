<?php
// select_lotw.php
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
$title = "LOTW Confirmed";
$java= true;
include_once("../config.php");
require_once('backend/db.class.php');
require_once("backend/backend.php");
require_once("backend/querybuilder.php");
include('backend/header.php');
require_once("backend/filecheck.php");
require_once('backend/email.php');

// Create a new instance of the Db class



$data =  "<form method='post' action='" . $_SERVER["PHP_SELF"] . "'>\n";
//$data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
$data .= '<input type="hidden" name="Submit" value= "true">' . PHP_EOL;
$data .= '<input type="hidden" name="1st" value= "true">' . PHP_EOL;
echo $data;

$i = 0; //style counter
$x = 0; //
$data = "";
$counter = 0;
$SUBMIT = "false";
$counter = 0;
if (isset($_POST['Submit1'])) {
   // $LOG = htmlspecialchars($_POST["Log"]);
    if (isset($_POST['Submit'])) {
        $SUBMIT = htmlspecialchars($_POST["Submit"]);
    }
    if (isset($_POST['Band'])) {
        $BAND = htmlspecialchars($_POST["Band"]);
    }
    if (isset($_POST['Mode'])) {
        $MODE = htmlspecialchars($_POST["Mode"]);
    }
    if (isset($_POST['Qty'])) {
        $QTY = htmlspecialchars($_POST["Qty"]);
    }
    if (isset($_POST['Call_Search'])) {
        $CALL_SEARCH = htmlspecialchars($_POST["Call_Search"]);
    }
  
    if (isset($_POST['State'])) {
        $STATE = htmlspecialchars($_POST["State"]);
    }
    if (isset($_POST['Country'])) {
        $COUNTRY = htmlspecialchars($_POST["Country"]);
    }
    if (isset($_POST['optionlist'])) {
        $INPUT = htmlspecialchars($_POST["optionlist"]);
    }
    
    //$data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
}
$query = getSelect_lotw();
if ($SUBMIT == "true") {
    if ($INPUT == "input_band") {
        $BAND = safe("%" . $BAND . "%");
        $query = str_replace("__REPLACE__", "and COL_BAND like '$BAND' ", $query);
    } elseif ($INPUT == "input_mode") {
        $MODE = safe("%" . $MODE . "%");
	$MODE = str_replace("SSB", "USB%' or COL_MODE like '%LSB", $MODE);
        $query = str_replace("__REPLACE__", "and COL_MODE like '$MODE' ", $query);
    } elseif ($INPUT == "input_state") {
        $STATE = safe("%" . $STATE . "%");
        $query = str_replace("__REPLACE__", "and COL_STATE like '$STATE' ", $query);
    } elseif ($INPUT == "input_country") {
        $COUNTRY = safe("%" . $COUNTRY . "%");
        $query = str_replace("__REPLACE__", "and COL_COUNTRY like '$COUNTRY' ", $query);
    } elseif ($INPUT == "input_none") {
        $query = str_replace("__REPLACE__", " ", $query);
    } else {
        $query = str_replace("__REPLACE__", " ", $query);
    }
   
     try {
            $results = $db->select($query);
            } catch (Exception $e) {
                echo "Query: " . $query ."<br>";
                echo "Error executing query: " . $e->getMessage();
            }
    $data .= "<div class='centered-content'>";
    $data .= "<div class='table-container'>";
    $data .= "<table border='0' class='custom-table small-left-table'><tbody><tr>"
        . "<th>Call</th>" . "<th>Comfirmed</th>"
        . "</tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($results as $row): {
            //
            $data .= "<td>" . $row['Call'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Confirmed'] . "</td>" . grid_style($i) . PHP_EOL; 
            $counter++;
            $i++;
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table>" . PHP_EOL;
    $data .= "<p style='text-align: center'><BR> Counter " . $counter . "</p><BR>". PHP_EOL;
    $data .=OptionList(false, false, false, false, false, false) . PHP_EOL; 
} else {
    $data = '<table width=800 class="center2">' . PHP_EOL;
    $data .='<tr><td>' . PHP_EOL;
    $data .=OptionList(true, true, false, false, false, true) . PHP_EOL;
    $data .=band() . PHP_EOL;
    $data .=mode() . PHP_EOL;
    $data .='</td></tr>' . PHP_EOL;
    $data .='</table>' . PHP_EOL;
    $data .='<div class="c1">' . PHP_EOL;
    $data .='<span class="auto-style5">' . PHP_EOL;
    $data .='none will return all<br>' . PHP_EOL;
    $data .='<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><BR>' . PHP_EOL;
}
//$data .=$query;
echo $data;

