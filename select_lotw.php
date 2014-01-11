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
$counter = 0;
$FileNoGroup = 0;
$find = '.jpg';
$fileMutiply = 1000;


    $id_lookup = $db->query("SELECT `COL_CALL` as 'Call', `COL_LOTW_QSL_RCVD` as 'Confirmed' FROM $dbnameHRD.$tbHRD Where `COL_LOTW_QSL_RCVD` = 'V'");
    
    $data = "<table border='0' align='center'><tbody><tr>"
            . "<th>Call</th>" . "<th>Confirmed</th>" 
            . "<th>Back</th>" . "</tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($id_lookup as $row): {
            //
            $data .= "<td>" . $row['Call'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Confirmed'] . "</td>" . grid_style($i) . PHP_EOL; 
            $counter++;
            $i++;
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table>" . PHP_EOL;
    $data .= "<p style='text-align: center'><BR> Counter " . $counter . "</p><BR>". PHP_EOL;

echo $data;
$phpfile = __FILE__;
footer($phpfile);
?>