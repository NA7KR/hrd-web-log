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
$Country = "%";
$Band = "%";
$Mode = "%";
$db = new Db();
$i = 0; //style counter
$id_db.class.php = $db->query("SELECT $dbnameWEB.$tbStates.State as `State`, $dbnameWEB.$tbStates.ST as `State` FROM $dbnameWEB.$tbStates left "
. "outer join $dbnameHRD.$tbHRD on $dbnameWEB.$tbStates.Country = $dbnameHRD.$tbHRD.COL_COUNTRY AND "
. "$dbnameWEB.$tbStates.ST = $dbnameHRD.$tbHRD.COL_STATE where ( $dbnameWEB.$tbStates.sCountry  = $Country ) " 
. "and col_state is not null AND COL_LOTW_QSL_RCVD not in ( 'Y' ) AND col_state not in "
. "(select col_state from $dbnameHRD.$tbHRD where col_state is not null and COL_LOTW_QSL_RCVD <> "
. "'N' and COL_LOTW_QSL_RCVD <> 'R'and COL_BAND LIKE $Band and COL_MODE LIKE $Mode) group by 1,2");

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