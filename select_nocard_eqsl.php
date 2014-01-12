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
if (isset($_POST['Submit1'])) {
    $LOG = safe($_POST['Log']);
    $QTY = safe($_POST['Qty']);
    $BAND = safe($_POST['Band']);
    $MODE = safe($_POST['Mode']);
    $SUBMIT = safe($_POST['Submit']);
    $CALL_SEARCH = safe($_POST['Call_Search']);
    include_once buildfiles($LOG);
    $data = '<FORM name ="form1" method ="post" action ="index.php">' . PHP_EOL;
    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
    $data .= '<input type="hidden" name="Band" value=' .  $BAND . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Mode" value=' .  $MODE . '>' . PHP_EOL;
   
     if ($BAND == "_Any_Band_")
            {
                $BAND ="%";
            }
            if ($MODE == "_Any_Mode_")
            {
                $MODE ="%";
            }
    
}
$query = "SELECT $dbnameWEB.$tbStates.State as `State`, $dbnameWEB.$tbStates.ST as `State` FROM $dbnameWEB.$tbStates "
. "left outer join $dbnameHRD.$tbHRD on $dbnameWEB.$tbStates.Country = $dbnameHRD.$tbHRD.COL_COUNTRY AND "
. "$dbnameWEB.$tbStates.ST = $dbnameHRD.$tbHRD.COL_STATE where ( $dbnameWEB.$tbStates.sCountry  = '%$Country%' ) "
. "and col_state is not null and COL_EQSL_QSL_RCVD not in ( 'Y' ) AND col_state not in "
. "(select col_state from $dbnameHRD.$tbHRD where col_state is not null and COL_EQSL_QSL_RCVD <> "
. "'N' and COL_EQSL_QSL_RCVD <> 'R'and COL_BAND LIKE '%$BAND%' and COL_MODE LIKE '%$MODE%') group by 1,2";
if ($SUBMIT == "true") {
$id_lookup = $db->query ("$query");

$data = "<table border='0' align='center'><tbody><tr><th>State</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
foreach ($id_lookup as $row): 
    {  
        $fileName = $row['File'];
        $data .=  "<td>" . $row['State'] . "</td>" . grid_style($i) . PHP_EOL;
        $i++;   
        unset($row); // break the reference with the last element
    }
endforeach;
}
 else {
$data .= band() . PHP_EOL;
$data .= mode() . PHP_EOL;
$data .='<br><div><P style="text-align: center"><Input type = "Submit" Name = "Submit1" VALUE = "Submit"></p></div></FORM><BR>' . PHP_EOL;
}
$data .= "</table><br><br>" . PHP_EOL;
 //$data .= " Band" . $BAND . "<BR>";
 //$data .= " Mode" . $MODE . "<BR>";
 //$data .="<br>" .$query . "<br>";
echo $data;
$phpfile = __FILE__ ;
footer($phpfile);