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


    $id_lookup = $db->query("SELECT tb_Cards.COL_PRIMARY_KEY as 'Log ID', $tbHRD.COL_CALL as 'Call',"
  . "tb_Cards.COL_File_Path_F as 'Card', tb_Cards.COL_File_Path_B as 'Back'"
  . "FROM HRD_Web.tb_Cards INNER JOIN $dbnameHRD.$tbHRD ON tb_Cards.COL_PRIMARY_KEY =" 
  . "$tbHRD.COL_PRIMARY_KEY WHERE tb_Cards.COL_File_Path_F <> ''");
  
    
    $data = "<table border='0' align='center'><tbody><tr>"
            . "<th>Log ID</th>" . "<th>Call</th>" . "<th>Card</th>" 
            . "<th>Back</th>" . "</tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($id_lookup as $row): {
            //
            $data .= "<td>" . $row['Log ID'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Call'] . "</td>" . PHP_EOL;
            $FileNoGroup = (($row['Log ID'] / $fileMutiply) % $fileMutiply * $fileMutiply);
            $fileNoGroupHigh = $FileNoGroup + ($fileMutiply - 1);
            $filePath = "cards/" . $FileNoGroup . "-" . $fileNoGroupHigh;
            $pathToThumbs = $filePath . '/thumbs/';
            $fileName = $row['Card'];
            $pos = strpos($fileName, $find);
            if ($pos !== false) {
                $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>" . PHP_EOL;
            } else {
                $data .= "<td>" . $row['Card'] . "</td>" . PHP_EOL;
            }

            $fileName = $row['Back'];
            $pos = strpos($fileName, $find);
            if ($pos !== false) {
                $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>" . grid_style($i) . PHP_EOL;
            } else {
                $data .= "<td>" . $row['Back'] . "</td>" . grid_style($i) . PHP_EOL;
            }
          
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