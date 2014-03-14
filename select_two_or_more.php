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
if (isset($_POST['Submit1'])) {
    $SUBMIT = \filter_input(\INPUT_POST, 'Submit', \FILTER_SANITIZE_STRING);
    $MODE = \filter_input(\INPUT_POST, 'Mode', \FILTER_SANITIZE_STRING);
    $STATE = \filter_input(\INPUT_POST, 'State', \FILTER_SANITIZE_STRING);
    $COUNTRY = \filter_input(\INPUT_POST, 'Country', \FILTER_SANITIZE_STRING);
    $INPUT = \filter_input(\INPUT_POST, 'optionlist', \FILTER_SANITIZE_STRING);
    include_once buildfiles($LOG);
    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
}
$query = "Select count(*) as `Count`, \n"
        . " COL_CALL from(SELECT distinct COL_CALL, \n"
            . " COL_BAND, \n"
            . " COL_MODE, \n"
            . " count(*) \n"
            . " FROM NA7KR.`TABLE_HRD_CONTACTS_V01` \n"
            . " __REPLACE__ \n"
            . " group by 1,2,3 ) as CALLSIGN \n"
        . " group by 2 HAVING Count > 1 \n"
        . " ORDER BY Count DESC";


if ($SUBMIT == "true") {
    if ($INPUT == "input_mode") {
        $MODE = safe("%" . $MODE . "%");
	$MODE = str_replace("SSB", "USB%' or COL_MODE like '%LSB", $MODE);
        $query = str_replace("__REPLACE__", " where COL_MODE like $MODE ", $query);
    } elseif ($INPUT == "input_none") {
        $query = str_replace("__REPLACE__", " ", $query);
    } else {
        $query = str_replace("__REPLACE__", " ", $query);
    }

    $id_lookup = $db->query($query);
    $data = "<table border='0' align='center'><tbody><tr><th>Country</th></tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($id_lookup as $row): {
            $fileName = $row['File'];
            $data .= "<td>" . $row['Count'] . "</td>" . PHP_EOL ;
            $data .= "<td>" . $row['COL_CALL'] . "</td>" . PHP_EOL . grid_style($i) . PHP_EOL;
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
    $data .=OptionList(false, true, false, false, false, true) . PHP_EOL;
    $data .=mode() . PHP_EOL;
    $data .='</td></tr>' . PHP_EOL;
    $data .='</table>' . PHP_EOL;
    $data .='<div class="c1">' . PHP_EOL;
    $data .='<span class="auto-style5">' . PHP_EOL;
    $data .='<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><BR>' . PHP_EOL;
}
//$data .=$query;
echo $data;
$phpfile = __FILE__;
footer($phpfile);
?>
