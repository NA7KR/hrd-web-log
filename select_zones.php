<?php
// select_zones.php
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
$title = "ITU Zones";
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
$find = '.jpg';
$fileMutiply = 1000;
$data = "";
$counter = 0;
$SUBMIT = "false";
if (isset($_POST['Submit1'])) {
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
    if (isset($_POST['optionlist'])) {
        $INPUT = htmlspecialchars($_POST["optionlist"]);
    }

   
    //$data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
}


if ($SUBMIT == "true") {
    if ($INPUT == "input_band") {
        $TEXT = $BAND;
        $BAND = safe("%" . $BAND . "%");
        $query = getSelect_zonesITU_Band();
        $BSAND = "15m" ;
        $query = str_replace("__REPLACE__", "$BAND", $query);
      try {
             $results = $db->select($query);
         } catch (Exception $e) {
             echo "Query: " . $query ."<br>";
             echo "Error executing query: " . $e->getMessage();
         }
        
        $data .= "<div class='centered-content'>";
        $data .= "<table class='custom-table' border='0'><tbody><tr>";
        $data .= "<th>ITU Zone to Work $TEXT</th><th>Mode</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
        foreach ($results as $row) {
            $data .=  "<td>" . $row['ITU Zone to Work'] . "</td><td>" . $row['Modes'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;
            unset($row); // break the reference with the last element
        }
    } elseif ($INPUT == "input_mode") {
        $TEXT = $MODE;
        $MODE = safe("%" . $MODE . "%");
        $query = getSelect_zonesITU_Mode();
        $query = str_replace("__REPLACE__", "$MODE", $query);
         try {
                $results = $db->select($query);
            } catch (Exception $e) {
                echo "Query: " . $query ."<br>";
                echo "Error executing query: " . $e->getMessage();
            }
        // Using the select function here
        $data .= "<div class='centered-content'>";
        $data .= "<table class='custom-table' border='0'><tbody><tr>";
        $data .= "<th>ITU Zone to Work $TEXT</th><th>Band</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
        foreach ($results as $row) {
            $data .=  "<td>" . $row['ITU Zone to Work'] . "</td><td>" . $row['Band'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;
            unset($row); // break the reference with the last element
        }
    } elseif ($INPUT == "input_none") {
        $query = getSelect_zonesITU();
        try {
                 $results = $db->select($query);
            } catch (Exception $e) {
                echo "Query: " . $query ."<br>";
                echo "Error executing query: " . $e->getMessage();
            }
        $data .= "<div class='centered-content'>";
        $data .= "<table class='custom-table' border='0'><tbody><tr>";
        $data .= "<th>ITU Zone to Work</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
        foreach ($results as $row) {
            $data .=  "<td>" . $row['ITU Zone to Work'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;
            unset($row); // break the reference with the last element
        }
    }

    $data .= "</table><br><br>" . PHP_EOL;
    $data .='<div class="c1">' . PHP_EOL;
    $data .='<span class="auto-style5">' . PHP_EOL;
    $data .= "Count " .$i;
    $data .=OptionList(false, false, false, false, false, false) . PHP_EOL;
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

echo $data;

