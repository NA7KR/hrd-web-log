<?php
// select_nocard_eqsl.php
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
$title = "No EQSL";
$java= true;
include_once("../config.php");
require_once('backend/db.class.php');
require_once("backend/backend.php");
require_once("backend/querybuilder.php");
include('backend/header.php');

// Create a new instance of the Db class


$data =  "<form method='post' action='" . $_SERVER["PHP_SELF"] . "'>\n";
//$data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
$data .= '<input type="hidden" name="Submit" value= "true">' . PHP_EOL;
$data .= '<input type="hidden" name="1st" value= "true">' . PHP_EOL;
echo $data;

$i = 0; //style counter
$x = 0; //
$FileNoGroup = 0;
$data = "";
$SUBMIT = "false";
$find = '.jpg';
$fileMutiply = 1000;
if (isset($_POST['Submit1'])) 
{
    //$LOG = htmlspecialchars($_POST["Log"]);
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
if ($Country= " ") {  $Country = "USA"; }
$query = getSelect_nocard_eqsl($Country);

if ($SUBMIT == "true") 
{
    if ($INPUT == "input_band") {
        $BAND = safe("%" . $BAND . "%");
        $query = str_replace("__REPLACE__", " and COL_BAND like '$BAND' ", $query);
    } elseif ($INPUT == "input_mode") {
        $MODE = safe("%" . $MODE . "%");
        if ($MODE == "'%SSB%'"){ 
        	$query = str_replace("__REPLACE__", " and (COL_MODE like '%LSB%' or COL_MODE like '%USB%') ", $query);
	}
	else{
	$query = str_replace("__REPLACE__", " and COL_MODE like '$MODE' ", $query);
	}
    } elseif ($INPUT == "input_state") {
        $STATE = safe("%" . $STATE . "%");
        $query = str_replace("__REPLACE__", " and COL_STATE like '$STATE' ", $query);
    } elseif ($INPUT == "input_country") {
        $COUNTRY = safe("%" . $COUNTRY . "%");
        $query = str_replace("__REPLACE__", " and COL_COUNTRY like '$COUNTRY' ", $query);
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
        . "<th>State</th>" . "<th>Short Name</th>" 
        . "</tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    
    foreach ($results as $row): {
            //$fileName = $row['File'];
            $data .= " <td>" . $row['StateF'] . "</td> <td>" . $row['State'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table><br><br>" . PHP_EOL;
    $data .='<div class="c1">' . PHP_EOL;
    $data .='<span class="auto-style5">' . PHP_EOL;
    $data .= "Count " .$i;
    $data .=OptionList(false, false, false, false, false, false) . PHP_EOL;
} 
else
{
    $data = '<table width=800 class="center2">' . PHP_EOL;
    $data .='<tr><td>' . PHP_EOL;
    $data .=OptionList(true, true, false, false, false, true) . PHP_EOL;
    $data .=band() . PHP_EOL;
    $data .=mode() . PHP_EOL;
    //$data .=OptionState() . PHP_EOL;
    //$data .=OptionCountry() . PHP_EOL;
    $data .='</td></tr>' . PHP_EOL;
    $data .='</table>' . PHP_EOL;
    $data .='<div class="c1">' . PHP_EOL;
    $data .='<span class="auto-style5">' . PHP_EOL;
    $data .='Select from drop down the amount of QLS would like to return<br>' . PHP_EOL;
    $data .='<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><BR>' . PHP_EOL;
}
//$data .=$query;
echo $data;

