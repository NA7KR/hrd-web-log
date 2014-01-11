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
    $i = 0; //style counter
    $id_db.class.php = $db->query("SELECT $dbnameWEB.tb_zones.zones as `ITU Zone to Work` "
    . "FROM $dbnameWEB.tb_zones left outer join $dbnameHRD.$tbHRD on $dbnameWEB.tb_zones.zones"
    . " = $dbnameHRD.$tbHRD.COL_ITUZ  where COL_ITUZ is null");
    
    $data = "<table border='0' align='center'><tbody><tr><th>ITU Zone to Work</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
    foreach ($id_db.class.php as $row): 
        {  
            $fileName = $row['File'];
            $data .=  "<td>" . $row['ITU Zone to Work'] . "</td>" . grid_style($i) . PHP_EOL;
            $i++;   
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table><br><br>" . PHP_EOL;
    echo $data;
    $phpfile = __FILE__ ;
    footer($phpfile);