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
$first = htmlspecialchars($_POST["1st"]);
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
$counter = 0;
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


$query = "SET sql_mode = \" \"";
$id_lookup = $db->query($query);
if ($SUBMIT == "true") {
    if ($INPUT == "input_band") {
        $BAND = safe("%" . $BAND . "%");
		$query = "select * from HRD_Web.tb_States_Countries where st not in "
		. "( select col_state from NA7KR.TABLE_HRD_CONTACTS_V01 where COL_BAND like $BAND and col_country = HRD_Web.tb_States_Countries.country ) "
		. " and sCountry = 'USA' group by st ";
    } elseif ($INPUT == "input_mode") {
        $MODE = safe("%" . $MODE . "%");
		$query = "select * from HRD_Web.tb_States_Countries where st not in "
		. "( select col_state from NA7KR.TABLE_HRD_CONTACTS_V01 where  __REPLACE__ and col_country = HRD_Web.tb_States_Countries.country ) "
		. " and sCountry = 'USA' group by st ";
		if ( $MODE == "'%SSB%'" ){
        $query = str_replace("__REPLACE__", " (COL_MODE like $MODE or COL_MODE like '%USB%' or COL_MODE like '%LSB%') ", $query);
		}
		else {
		$query = str_replace("__REPLACE__", " COL_MODE like $MODE", $query);
		}
    } elseif ($INPUT == "input_none") {
		 $query ="SELECT $dbnameWEB.$tbStates.State as `State` , "
            . "$dbnameWEB.$tbStates.ST as `State` , "
            . "$dbnameWEB.$tbStates.Country as `Country`"
            . " FROM $dbnameWEB.$tbStates left outer join  $dbnameHRD.$tbHRD on $dbnameWEB.$tbStates.Country  = $dbnameHRD.$tbHRD.COL_COUNTRY "
            . "AND $dbnameWEB.$tbStates.ST = $dbnameHRD.$tbHRD.COL_STATE  "
            . "where $dbnameWEB.$tbStates.sCountry  like '%$Country%' "
            . "and col_state is null "
            . "group by 1,2";
    }

    //echo $query;
    $id_lookup = $db->query($query);
    $data = "<table border='0' align='center'><tbody><tr><th>State</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
    foreach ($id_lookup as $row): 
        {  
            //$fileName = $row['File'];
            $data .=  "<td>" . $row['State'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;   
            unset($row); // break the reference with the last element
        }
    endforeach;
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
//echo $query;
$phpfile = __FILE__;
footer($phpfile);
