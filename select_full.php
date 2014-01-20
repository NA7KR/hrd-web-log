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
$first = \filter_input(\INPUT_POST, '1st', \FILTER_SANITIZE_STRING);
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
$FileNoGroup = 0;
$find = '.jpg';
$fileMutiply = 1000;
$counter = 0;
if (isset($_POST['Submit1'])) {
    $LOG = \filter_input(\INPUT_POST, 'Log', \FILTER_SANITIZE_STRING);
    $QTY = \filter_input(\INPUT_POST, 'Qty', \FILTER_SANITIZE_STRING);
    $SUBMIT = \filter_input(\INPUT_POST, 'Submit', \FILTER_SANITIZE_STRING);
    $CALL_SEARCH = \filter_input(\INPUT_POST, 'Call_Search', \FILTER_SANITIZE_STRING);
    $BAND = \filter_input(\INPUT_POST, 'Band', \FILTER_SANITIZE_STRING);
    $MODE = \filter_input(\INPUT_POST, 'Mode', \FILTER_SANITIZE_STRING);
    $STATE = \filter_input(\INPUT_POST, 'State', \FILTER_SANITIZE_STRING);
    $COUNTRY = \filter_input(\INPUT_POST, 'Country', \FILTER_SANITIZE_STRING);
    $INPUT = \filter_input(\INPUT_POST, 'optionlist', \FILTER_SANITIZE_STRING);
    include_once buildfiles($LOG);
    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
}
$query = "SELECT date(`COL_TIME_OFF`)AS`Date` , \n"
        . " `COL_CALL`AS`CallSign`, \n"
        . " `COL_MODE`AS`Mode` , \n"
        . " `COL_BAND`AS`Band` , \n"
        . " `COL_GRIDSQUARE`AS`Grid` , \n"
        . "`COL_COUNTRY`AS`Country` , \n"
        . "`COL_STATE`AS`State` ,"
        . "`COL_QTH`AS`QTH` FROM $dbnameHRD.$tbHRD \n"
        . " __REPLACE__ \n"
        . "ORDER BY $dbnameHRD.$tbHRD.`COL_PRIMARY_KEY` \n"
        . "DESC ";
if ($SUBMIT == "true") {
    if ($INPUT == "input_band") {
        $BAND = safe("%" . $BAND . "%");
        $query = str_replace("__REPLACE__", "where COL_BAND like $BAND ", $query);
    } elseif ($INPUT == "input_mode") {
        $MODE = safe("%" . $MODE . "%");
        $MODE = str_replace("USB", "SSB", $MODE);
        $MODE = str_replace("LSB", "SSB", $MODE);
        $query = str_replace("__REPLACE__", "where COL_MODE like $MODE or COL_MODE like 'USB' or COL_MODE like 'LSB' ", $query);
    } elseif ($INPUT == "input_state") {
        $STATE = safe("%" . $STATE . "%");
        $query = str_replace("__REPLACE__", "where COL_STATE like $STATE ", $query);
    } elseif ($INPUT == "input_country") {
        $COUNTRY = safe("%" . $COUNTRY . "%");
        $query = str_replace("__REPLACE__", "where COL_COUNTRY like $COUNTRY ", $query);
    } elseif ($INPUT == "input_none") {
        $query = str_replace("__REPLACE__", " ", $query);
    } else {
        $query = str_replace("__REPLACE__", " ", $query);
    }
    if ($QTY == "All") {
        $id_lookup = $db->query("$query ");
    } else {
        $query = str_replace("DESC", "DESC Limit $QTY ", $query);
        $id_lookup = $db->query("$query");
    }
    $id_lookup = $db->query($query);

    $data = "<table border='0' align='center'><tbody><tr>"
            . "<th>Date</th>"
            . "<th>CallSign</th>"
            . "<th>Mode</th>"
            . "<th>Band</th>"
            . "<th>Grid</th>"
            . "<th>Contury</th>"
            . "<th>State</th>"
            . "</tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($id_lookup as $row): {
            $fileName = $row['File'];
            $data .= "<td>" . $row['Date'] . "</td>";
            $data .= "<td>" . qrzcom_interface($row['CallSign']) . "</td>";
            $data .= "<td>" . $row['Mode'] . "</td>";
            $data .= "<td>" . $row['Band'] . "</td>";
            $data .= "<td>" . $row['Grid'] . "</td>";
            $data .= "<td>" . $row['Country'] . "</td>";
            $data .= "<td>" . $row['State'] . "</td>" . grid_style($i) . PHP_EOL;
            $counter++;
            $i++;
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table>" . PHP_EOL;
    $data .= "<p style='text-align: center'><BR> Counter " . $counter . "</p><BR>" . PHP_EOL;
    $data .=OptionList(false, false, false, false, false, false) . PHP_EOL;
} else {
    $data = '<table width=600 class="center2">' . PHP_EOL;
    $data .='<tr><td>' . PHP_EOL;
    $data .=OptionList(true, true, false, true, true, true) . PHP_EOL;
    $data .=band() . PHP_EOL;
    $data .=mode() . PHP_EOL;
    $data .=OptionState() . PHP_EOL;
    $data .=OptionCountry() . PHP_EOL;
    $data .='</td></tr>' . PHP_EOL;
    $data .='</table>' . PHP_EOL;
    $data .='<div class="c1">' . PHP_EOL;
    $data .='<span class="auto-style5">' . PHP_EOL;
    $data .='Select from drop down the amount of QLS would like to return<br>' . PHP_EOL;
    $data .=OptionQty() . PHP_EOL;
    $data .='<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><BR>' . PHP_EOL;
}
echo $data;
$phpfile = __FILE__;
footer($phpfile);
