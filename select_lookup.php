<?php
// select_lookup.php
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
  
// Include necessary files

$title = "Callsign Lookup";
$java= true;

include_once("../config.php");
require_once('backend/db.class.php');
require_once("backend/backend.php");
require_once("backend/querybuilder.php");
include('backend/header.php');

// Create a new instance of the Db class



$i = 0;



$i = 0;
$x = 0;
$FileNoGroup = 0;
$find = '.jpg';
$fileMutiply = 1000;
$first = "false";
$SUBMIT = "false";
$data =  "<form method='post' action='" . $_SERVER["PHP_SELF"] . "'>\n";
//$data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
$data .= '<input type="hidden" name="Submit" value= "true">' . PHP_EOL;
$data .= '<input type="hidden" name="1st" value= "true">' . PHP_EOL;
echo $data;

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

if (isset($_POST['Submit1'])) {
    $first = htmlspecialchars($_POST["1st"]);
   //$LOG = htmlspecialchars($_POST["Log"]); 
    if (isset($_POST['Qty'])) {
        $QTY = htmlspecialchars($_POST["Qty"]);
    }
    if (isset($_POST['Submit'])) {
        $SUBMIT = htmlspecialchars($_POST["Submit"]);
    }
    if (isset($_POST['Call_Search'])) {
        $CALL_SEARCH = htmlspecialchars($_POST["Call_Search"]);
    }
    if (isset($_POST['Band'])) {
        $BAND = htmlspecialchars($_POST["Band"]);
    }
    if (isset($_POST['Mode'])) {
        $MODE = htmlspecialchars($_POST["Mode"]);
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
}



if ($SUBMIT == "true") {
    // Build query based on form input
    $query = buildQuery($first, $QTY, $SUBMIT, $CALL_SEARCH, $BAND, $MODE, $STATE, $COUNTRY, $INPUT);

    $SUBMIT = "false";
    if ($INPUT == "input_band") {
        $BAND = safe("'%" . $BAND . "%'");
        $query = str_replace("__REPLACE__", "where COL_BAND like $BAND ", $query);
    } elseif ($INPUT == "input_mode") {
        $MODE = safe("'%" . $MODE . "%'");
        $MODE = str_replace("USB", "SSB", $MODE);
        $MODE = str_replace("LSB", "SSB", $MODE);
        $query = str_replace("__REPLACE__", "where COL_MODE like $MODE or COL_MODE like 'USB' or COL_MODE like 'LSB' ", $query);
    } elseif ($INPUT == "input_search") {
        $CALL_SEARCH = safe("'%" . $CALL_SEARCH . "%'");
        $query = str_replace("__REPLACE__", "where " . DB_COL_CALL . " like $CALL_SEARCH ", $query);
    } elseif ($INPUT == "input_state") {
        $STATE = safe("'%" . $STATE . "%'");
        $query = str_replace("__REPLACE__", "where COL_STATE like $STATE ", $query);
    } elseif ($INPUT == "input_country") {
        $COUNTRY = safe("'%" . $COUNTRY . "%'");
        $query = str_replace("__REPLACE__", "where COL_COUNTRY like $COUNTRY ", $query);
    } elseif ($INPUT == "input_none") {
        $query = str_replace("__REPLACE__", " ", $query);
    } else {
        $query = str_replace("__REPLACE__", " ", $query);
    }

    if ($QTY == "All") {
    } else {
        $query = str_replace("DESC", "DESC Limit $QTY ", $query);
        try {
            $results = $db->select($query);
        } catch (Exception $e) {
            echo "Error executing query: " . $e->getMessage();
        }
    }

    $data .= "<div class='centered-content'>" .PHP_EOL;; // Add a container div
    $data .= "<table class='custom-table' border='0'>"
        . "<tbody><tr>"
        . "<th>Call </th>"
        . "<th>Band </th>"
        . "<th>State </th>"
        . "<th>Country </th>"
        . "<th>ID </th>"
        . "<th>Date </th>"
        . "<th>EQSL </th>"
        . "<th>LOTW </th>"
        . "<th>QSL </th>"
        . "<th>Mode </th>"
        . "<th>E QSL </th>"
        . "<th>Paper Front </th>"
        . "<th>Paper Back </th>"
        . "<th>Email Front </th>"
        . "<th>Email Back </th>"
        . "</tr>";

    if ($results === null) {
        $results = [];
    }

    $rowColor = "odd1";
    
    foreach ($results as $row) {
        try {
            //$data .= "<tr class='custom-table'>"; // Add class for row color
            $data .= "<td>" . qrzcom_interface($row['Call']) . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Band'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['State'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Country'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['ID'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Date'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['EQSL'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['LOTW'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['QSL'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Mode'] . "</td>" . PHP_EOL;

            $FileNoGroup = ((int)($row['ID'] / $fileMutiply) % $fileMutiply * $fileMutiply);
            $fileNoGroupHigh = $FileNoGroup + ($fileMutiply - 1);
            $filePath = "cards/" . $FileNoGroup . "-" . $fileNoGroupHigh;
            $pathToThumbs = $filePath . '/thumbs/';
            $FileName = null;
            $fileName = $row['E QSL'];
            if (isset($fileName)) {
                $pos = strpos($fileName, $find);
                if ($pos !== false) {
                    $data .= "<td><a href='$filePath/$fileName'><img src='$filePath/thumbs/$fileName' alt='$fileName'></a></td>" . PHP_EOL;
                } else {
                    $data .= "<td>" . $row['E QSL'] . "</td>" . PHP_EOL;
                }
            } else {
                $data .= "<td>" . $row['E QSL'] . "</td>" . PHP_EOL;
            }
            $FileName = null;
            $fileName = $row['File'];
            if (isset($fileName)) {
                $pos = strpos($fileName, $find);
                if ($pos !== false) {
                    $data .= "<td><a href='$filePath/$fileName'><img src='$filePath/thumbs/$fileName' alt='$fileName'></a></td>" . PHP_EOL;
                } else {
                    $data .= "<td>" . $row['File'] . "</td>" . PHP_EOL;
                }
            } else {
                $data .= "<td>" . $row['File'] . "</td>" . PHP_EOL;
            }
            $FileName = null;
            $fileName = $row['File Back'];
            if (isset($fileName)) {
                $pos = strpos($fileName, $find);
                if ($pos !== false) {
                    $data .= "<td><a href='$filePath/$fileName'><img src='$filePath/thumbs/$fileName' alt='$fileName'></a></td>"  . PHP_EOL;
                } else {
                    $data .= "<td>" . $row['File Back'] . "</td>" . PHP_EOL;
                }
            } else {
                $data .= "<td>" . $row['File Back'] . "</td>" . PHP_EOL;
            }
            $FileName = null;
            $fileName = $row['FEmailCard'];
            if (isset($fileName)) {
                $pos = strpos($fileName, $find);
                if ($pos !== false) {
                    $data .= "<td><a href='$filePath/$fileName'><img src='$filePath/thumbs/$fileName' alt='$fileName'></a></td>"  . PHP_EOL;
                } else {
                    $data .= "<td>" . $row['FEmailCard'] . "</td>" . PHP_EOL;
                }
            } else {
                $data .= "<td>" . $row['FEmailCard'] . "</td>" . PHP_EOL;
            }
            $FileName = null;
            $fileName = $row['BEmailCard'];
            if (isset($fileName)) {
                $pos = strpos($fileName, $find);
                if ($pos !== false) {
                    $data .= "<td><a href='$filePath/$fileName'><img src='$filePath/thumbs/$fileName' alt='$fileName'></a></td>" . PHP_EOL;
                } else {
                    $data .= "<td>" . $row['BEmailCard'] . "</td>" . PHP_EOL;
                }
            } else {
                $data .= "<td>" . $row['BEmailCard'] . "</td>" . PHP_EOL;
            }
            $i++; 
            unset($row); 
        } catch(Error $e) {
            $trace = $e->getTrace();
            echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine().' called from '.$trace[0]['file'].' on line '.$trace[0]['line'];
        }
        $data .= "</tr>";
        $rowColor = ($rowColor == "odd1") ? "even1" : "odd1"; // Alternate row colors
    }
    $data = str_replace("USB", "SSB", $data); 
    $data = str_replace("LSB", "SSB", $data);
    $data .= "</tbody></table><br><br>";
    $data .= OptionList(false, false, false, false, false, false);
} else {
    $data .= '<table width=800 class="center2">';
    $data .='<tr><td>';
    $data .='<div class="centered-radio-buttons">';
    $data .=OptionList(true, true, true, true, true, true);
    $data .=band();
    $data .=mode();
    $data .=OptionState();
    $data .=OptionCountry();
    $data .='<span id="Call">';
    $data .='Enter Callsign in the box';
    $data .='<input  type="text" name="Call_Search"><br>';
    $data .='</span>';
    $data .='</td></tr>';
    $data .='</table>';
    $data .='</div">';
    $data .='<div class="c1">';
    $data .='<span class="auto-style5">';
    $data .='Select from drop down the amount of QLS would like to return<br>';
    $data .=OptionQty();
    $data .='<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><br></div>';
}
echo $data;
include('backend/footer.php');
?>