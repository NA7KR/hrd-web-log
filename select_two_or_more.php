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
$counter = 0;
$query = "00";
$id_lookup = $db->query($query);
    $data = "<table border='0' align='center'><tbody><tr><th>Country</th></tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($id_lookup as $row): {
            $fileName = $row['File'];
            $data .= "<td>" . $row['Countrys Worked'] . "</td>" . PHP_EOL . grid_style($i) . PHP_EOL;
            $counter++;
            $i++;
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table>" . PHP_EOL;
    $data .= "<p style='text-align: center'><BR> Counter " . $counter . "</p><BR>" . PHP_EOL;
    $data .=OptionList(false, false, false, false, false, false) . PHP_EOL; 
echo $data;
$phpfile = __FILE__;
footer($phpfile);
