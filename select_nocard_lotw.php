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
$FileNoGroup = 0;
$data = "";
$SUBMIT = "false";
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
if ($Country= " ") {  $Country = "USA"; }

$query = "SELECT $dbnameWEB.$tbStates.State as `StateF`, \n"
        . "$dbnameWEB.$tbStates.ST as `State` \n"
        . "FROM $dbnameWEB.$tbStates left outer join $dbnameHRD.$tbHRD on $dbnameWEB.$tbStates.Country = $dbnameHRD.$tbHRD.COL_COUNTRY \n"
        . "AND  $dbnameWEB.$tbStates.ST = $dbnameHRD.$tbHRD.COL_STATE \n"
        . "where ( $dbnameWEB.$tbStates.sCountry like '%$Country%' ) \n"
        . "and col_state is not null \n"
        . "AND COL_LOTW_QSL_RCVD not in ( 'Y' ) \n"
        . "AND col_state not in (select col_state \n"
        . "from $dbnameHRD.$tbHRD \n"
        . "where col_state is not null \n"
        . "and COL_LOTW_QSL_RCVD <> 'N' \n"
        . "and COL_LOTW_QSL_RCVD <> 'R' \n"
        . "__REPLACE__ ) \n"
        . "group by 1,2";

if ($SUBMIT == "true") {
    if ($INPUT == "input_band") {
        $BAND = safe("%" . $BAND . "%");
        $query = str_replace("__REPLACE__", " and COL_BAND like $BAND ", $query);
    } elseif ($INPUT == "input_mode") {
        $MODE = safe("%" . $MODE . "%");
        if ($MODE == "'%SSB%'"){ 
        	$query = str_replace("__REPLACE__", " and (COL_MODE like '%LSB%' or COL_MODE like '%USB%') ", $query);
	}
	else{
	$query = str_replace("__REPLACE__", " and COL_MODE like $MODE ", $query);
	}
    } elseif ($INPUT == "input_state") {
        $STATE = safe("%" . $STATE . "%");
        $query = str_replace("__REPLACE__", " and COL_STATE like $STATE ", $query);
    } elseif ($INPUT == "input_country") {
        $COUNTRY = safe("%" . $COUNTRY . "%");
        $query = str_replace("__REPLACE__", " and COL_COUNTRY like $COUNTRY ", $query);
    } elseif ($INPUT == "input_none") {
        $query = str_replace("__REPLACE__", " ", $query);
    } else {
        $query = str_replace("__REPLACE__", " ", $query);
    }

    $id_lookup = $db->query("$query");

    $data = "<table border='0' align='center'><tbody><tr><th>State</th></tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($id_lookup as $row): {
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
} else {
    $data = '<table width=600 class="center2">' . PHP_EOL;
    $data .='<tr><td>' . PHP_EOL;
    $data .=OptionList(true, true, false, false, false, true) . PHP_EOL;
    $data .=band() . PHP_EOL;
    $data .=mode() . PHP_EOL;
//    $data .=OptionState() . PHP_EOL;
//    $data .=OptionCountry() . PHP_EOL;
    $data .='</td></tr>' . PHP_EOL;
    $data .='</table>' . PHP_EOL;
    $data .='<div class="c1">' . PHP_EOL;
    $data .='<span class="auto-style5">' . PHP_EOL;
    $data .='Select from drop down the amount of QLS would like to return<br>' . PHP_EOL;
    $data .='<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><BR>' . PHP_EOL;
}
echo $data;
$phpfile = __FILE__;
footer($phpfile);
?>
