<?php
/* * ***********************************************************************
 * 			NA7KR Log Program 
 * *************************************************************************

 * *************************************************************************
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * ************************************************************************ */
$first = "false";
$first =  htmlspecialchars($_POST["1st"]);
if ($first <> True)
{
    header( 'Location: index.php' ) ;
}
include_once (__DIR__ . '/../config.php');
require_once('db.class.php');
require_once("backend.php");
$db = new Db();
$i = 0; //style counter
$x = 0; //
$data = "";
$SUBMIT = "false";
$FileNoGroup = 0;
$counter = 0;
$find = '.jpg';
$fileMutiply = 1000;
if (isset($_POST['Submit1'])) {
    $LOG = htmlspecialchars($_POST["Log"]);
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
    include_once buildfiles($LOG);
    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
}
$query = "SELECT tb_Cards.COL_PRIMARY_KEY as 'Log ID', \n"
        . "$tbHRD.COL_CALL as 'Call', \n"
        . "tb_Cards.COL_File_Path_E as 'Card' \n"
        . "FROM HRD_Web.tb_Cards INNER JOIN $dbnameHRD.$tbHRD ON  tb_Cards.COL_PRIMARY_KEY = $tbHRD.COL_PRIMARY_KEY \n"
        . "WHERE tb_Cards.COL_File_Path_E <> '' __REPLACE__";
  
if ($SUBMIT == "true") {
    if ($INPUT == "input_band") {
        $BAND = safe("%" . $BAND . "%");
        $query = str_replace("__REPLACE__", "and COL_BAND like $BAND ", $query);
    } elseif ($INPUT == "input_mode") {
        $MODE = safe("%" . $MODE . "%");
        $MODE = str_replace("SSB", "USB%' or COL_MODE like '%LSB", $MODE);
        $query = str_replace("__REPLACE__", "and COL_MODE like $MODE ", $query);
    } elseif ($INPUT == "input_state") {
        $STATE = safe("%" . $STATE . "%");
        $query = str_replace("__REPLACE__", "and COL_STATE like $STATE ", $query);
    } elseif ($INPUT == "input_country") {
        $COUNTRY = safe("%" . $COUNTRY . "%");
        $query = str_replace("__REPLACE__", "and COL_COUNTRY like $COUNTRY ", $query);
    } elseif ($INPUT == "input_none") {
        $query = str_replace("__REPLACE__", " ", $query);
    } else {
        $query = str_replace("__REPLACE__", " ", $query);
    }

$id_lookup = $db->query("$query");
    $data = "<table border='0' align='center'><tbody><tr>"
            . "<th>Log ID</th>" . "<th>Call</th>" . "<th>Card</th>" 
            . "</tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($id_lookup as $row): {
            //
            $data .= "<td>" . $row['Log ID'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Call'] . "</td>" . PHP_EOL;
            $FileNoGroup = (($row['Log ID'] / $fileMutiply) % $fileMutiply * $fileMutiply);
            $fileNoGroupHigh = $FileNoGroup + ($fileMutiply - 1);
            $filePath = "cards/" . $FileNoGroup . "-" . $fileNoGroupHigh;
            $pathToThumbs = $filePath . '/thumbs/';
            $fileName = $row['Card'];
            $pos = strpos($fileName, $find);
            if ($pos !== false) {
                $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>" . grid_style($i) . PHP_EOL;
            } else {
                $data .= "<td>" . $row['Card'] . "</td>" . grid_style($i) . PHP_EOL;
            }


            $counter++;
            $i++;
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table>" . PHP_EOL;
    $data .= "<p style='text-align: center'><BR> Counter " . $counter . "</p><BR>". PHP_EOL;
    $data .=OptionList(false, false, false, false, false, false) . PHP_EOL; 
} else {
    $data = '<table width=600 class="center2">' . PHP_EOL;
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
$phpfile = __FILE__;
footer($phpfile);
