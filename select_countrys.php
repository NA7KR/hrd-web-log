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
    require_once('db.class.php.class.php');
    require_once("backend.php");
    $db = new Db();
    $find = '.jpg';
    $i = 0; //style counter
    $counter = 0;
    $filePath = "/Awards";
    $id_db.class.php = $db->query("SELECT COL_COUNTRY as 'Countrys Worked' FROM $dbnameHRD.$tbHRD WHERE 1 group by 1");
    //$id_db.class.php = $db->query("SELECT COL_COUNTRY as 'Countrys Worked' FROM $tbHRD WHERE 1 and COL_BAND LIKE $BAND and COL_MODE LIKE $MODE  group by 1");
    $data = "<table border='0' align='center'><tbody><tr><th>Country</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
    foreach ($id_db.class.php as $row): 
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