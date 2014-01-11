<?php
/*************************************************************************
* 			NA7KR Log Program 
**************************************************************************

**************************************************************************
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
*
**************************************************************************/

#################################################
# QRZ.com callsign db.class.php 
#################################################
function qrzcom_interface($callsign) 
{
    $lookup  = "<a href='http://www.qrz.com/db/$callsign'>$callsign</a>";
    return ($lookup);
}

#################################################
# QRZ.com callsign db.class.php 
#################################################
function qsl_worked()
{
    include (__DIR__ . '/../config.php');
    require_once('db.class.php');
    $db = new Db();
    $id_lookup = $db->query("SELECT DISTINCT `COL_CALL` FROM NA7KR.TABLE_HRD_CONTACTS_V01 WHERE 1");
    foreach ($id_lookup as $row):
    $QSLWORKED .=$row['COL_CALL'] . ',';
        if ($debug == "true") {
            echo $QSLWORKED;
        }
    endforeach;
    return ($QSLWORKED);
    
} 

#################################################
# read DB for oprion view
#################################################
function select() {
    include (__DIR__ . '/../config.php');
    require_once('db.class.php');
    $data ='<FORM name ="form1" method ="post" action ="index.php">'. PHP_EOL;
    $db = new Db();
    $id_lookup = $db->query("SELECT Select_TXT, Select_Name FROM $dbnameWEB.$tbSelect ORDER BY `Select_TXT` ");
    foreach ($id_lookup as $row):

        if ($row['Select_Name'] == "callsign_db.class.php") {
            $data .='<input type="radio" value=' . $row['Select_Name'] . ' name="Log" > ' . $row['Select_TXT']. PHP_EOL;
        } else {
            $data .='<input type="radio" value=' . $row['Select_Name'] . ' name="Log"  > ' . $row['Select_TXT']. PHP_EOL;
        }
    endforeach;
    $data .='<br><div><P style="text-align: center">'. PHP_EOL;
    $data .= '<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></p></div></FORM><BR>'. PHP_EOL;
    return $data;
}


#################################################
# build include files
#################################################
function buildfiles($Key) {
    include (__DIR__ . '/../config.php');
    require_once('db.class.php');
    $db = new Db();
        $id_lookup = $db->row("SELECT Select_File FROM $dbnameWEB.$tbSelect WHERE Select_Name = '$Key'");
    return $id_lookup['Select_File'];
}

#################################################
# SQL safe
#################################################
function safe($value) {
    return mysql_real_escape_string($value);
}

#################################################
# Make Grid
# $i mod 2 is checking to see if the row number is odd or even odd rows are colored differently than even rows to create a datagrid look and feel
#################################################
function grid_style($i) {
    if (($i % 2) != 0) {
        $style = "<tr bgcolor='#5e5eff'>";
        return $style;
    } else {
        $style = "<tr bgcolor='#FFC600'>";
        return $style;
    }
}

#################################################
# 
# 
#################################################
function footer($i) {    
?>
<div class="c1">
<span class="auto-style5">
    <?php    
        date_default_timezone_set('America/Los_Angeles');
        echo "Was last modified: " . date("F d Y H:i", filemtime($i));
        require_once("backend.php");
    ?>
</span>
</div>
<br><br>

<div style="display:none;"><?php echo qsl_worked(); ?></div>
<?php 
}
?>