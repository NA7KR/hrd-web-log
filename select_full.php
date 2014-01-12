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
    $counter = 0;
    $filePath = "/Awards";
    $id_lookup = $db->query("SELECT date(`COL_TIME_OFF`)AS`Date` ,`COL_CALL`AS`CallSign`,`COL_MODE`AS`Mode` ,"
        . " `COL_BAND`AS`Band` ,`COL_GRIDSQUARE`AS`Grid` ,`COL_COUNTRY`AS`Country` ,"
        . "`COL_STATE`AS`State` ,`COL_QTH`AS`QTH` FROM $dbnameHRD.$tbHRD WHERE COL_BAND LIKE '%$Band%' AND "
        . " COL_MODE LIKE '%$Mode%' ORDER BY $dbnameHRD.$tbHRD.`COL_PRIMARY_KEY` DESC");
    
    $data = "<table border='0' align='center'><tbody><tr>"
            . "<th>Date</th>"
            . "<th>CallSign</th>"
            . "<th>Mode</th>"
            . "<th>Band</th>"
            . "<th>Grid</th>"
            . "<th>Contury</th>"
            . "<th>State</th>"
            . "</tr><tr bgcolor='#5e5eff'>". PHP_EOL;
    foreach ($id_lookup as $row): 
        {  
            $fileName = $row['File'];
            $data .=  "<td>" . $row['Date'] . "</td>";
            $data .=  "<td>" . qrzcom_interface($row['CallSign']) . "</td>";
            $data .=  "<td>" . $row['Mode'] . "</td>";
            $data .=  "<td>" . $row['Band'] . "</td>";
            $data .=  "<td>" . $row['Grid'] . "</td>";
            $data .=  "<td>" . $row['Country'] . "</td>";
            $data .=  "<td>" . $row['State'] . "</td>" . grid_style($i) . PHP_EOL;
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