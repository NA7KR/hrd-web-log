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
    $find = '.jpg';
    $i = 0; //style counter
    $filePath = "/Awards";
    $data ="";
    $id_lookup = $db->query("SELECT COL_Award as Award, COL_File as 'File' FROM $dbnameWEB.tb_awards WHERE 1");
    $data = "<table border='0' align='center'><tbody><tr><th>Award</th><th>File</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
    foreach ($id_lookup as $row): 
        {  
            $fileName = $row['File'];
            $data .=  "<td>" . $row['Award'] . "</td>" . PHP_EOL;
            $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>". grid_style($i) . PHP_EOL;
            $i++;   
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table><br><br>" . PHP_EOL;
    echo $data;
    $data = "";
    $phpfile = __FILE__ ;
    footer($phpfile);
?>