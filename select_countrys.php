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
    $QTY = \filter_input(\INPUT_POST, 'Qty', \FILTER_SANITIZE_STRING);
    $SUBMIT = \filter_input(\INPUT_POST, 'Submit', \FILTER_SANITIZE_STRING);
    $CALL_SEARCH = \filter_input(\INPUT_POST, 'Call_Search', \FILTER_SANITIZE_STRING);
    $BAND =  \filter_input(\INPUT_POST, 'Band', \FILTER_SANITIZE_STRING);
    $MODE = \filter_input(\INPUT_POST, 'Mode', \FILTER_SANITIZE_STRING);
    $STATE = \filter_input(\INPUT_POST, 'State', \FILTER_SANITIZE_STRING);
    $COUNTRY = \filter_input(\INPUT_POST, 'Country', \FILTER_SANITIZE_STRING);
    $INPUT = \filter_input(\INPUT_POST, 'optionlist', \FILTER_SANITIZE_STRING);
    include_once buildfiles($LOG);
    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
 
}   

    $id_lookup = $db->query("SELECT COL_COUNTRY as 'Countrys Worked' FROM $dbnameHRD.$tbHRD WHERE 1 group by 1");
    //$id_lookup = $db->query("SELECT COL_COUNTRY as 'Countrys Worked' FROM $tbHRD WHERE 1 and COL_BAND LIKE $BAND and COL_MODE LIKE $MODE  group by 1");
    $data = "<table border='0' align='center'><tbody><tr><th>Country</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
    foreach ($id_lookup as $row): 
        {  
            $fileName = $row['File'];
            $data .=  "<td>" . $row['Countrys Worked'] . "</td>" . PHP_EOL . grid_style($i) . PHP_EOL;
            $counter++;
            $i++;   
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table>" . PHP_EOL;
    $data .= "<p style='text-align: center'><BR> Counter " . $counter . "</p><BR>". PHP_EOL;
    echo $data;
    $phpfile = __FILE__ ;
    footer($phpfile);