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
    $SUBMIT = \filter_input(\INPUT_POST, 'Submit', \FILTER_SANITIZE_STRING);
    $BAND = \filter_input(\INPUT_POST, 'Band', \FILTER_SANITIZE_STRING);
    $MODE = \filter_input(\INPUT_POST, 'Mode', \FILTER_SANITIZE_STRING);
    $INPUT = \filter_input(\INPUT_POST, 'optionlist', \FILTER_SANITIZE_STRING);
    include_once buildfiles($LOG);
    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
};
$query = "SELECT $dbnameWEB.tb_zones.zones as `ITU Zone to Work` \n"
    . "FROM $dbnameWEB.tb_zones left outer join $dbnameHRD.$tbHRD on $dbnameWEB.tb_zones.zones = $dbnameHRD.$tbHRD.COL_ITUZ  \n"
    . "where COL_ITUZ is null __REPLACE__ ";

if ($SUBMIT == "true") {
    if ($INPUT == "input_band") {
        $BAND = safe("%" . $BAND . "%");
        $query = str_replace("__REPLACE__", " and COL_BAND like $BAND ", $query);
    } elseif ($INPUT == "input_mode") {
        $MODE = safe("%" . $MODE . "%");
        $MODE = str_replace("USB", "SSB", $MODE);
        $MODE = str_replace("LSB", "SSB", $MODE);
        $query = str_replace("__REPLACE__", "and COL_MODE like $MODE ", $query);
    } elseif ($INPUT == "input_none") {
        $query = str_replace("__REPLACE__", " ", $query);
    }
    $id_lookup = $db->query($query);
   
    
    $data = "<table border='0' align='center'><tbody><tr><th>ITU Zone to Work</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
    foreach ($id_lookup as $row): 
        {  
            $fileName = $row['File'];
            $data .=  "<td>" . $row['ITU Zone to Work'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;   
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table><br><br>" . PHP_EOL;
   $data .=OptionList(false, false, false, false, false, false) . PHP_EOL; 
} else {
    $data = '<table width=500 class="center2">' . PHP_EOL;
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
    //$data .= $query. PHP_EOL;
    echo $data;
    $phpfile = __FILE__ ;
    footer($phpfile);