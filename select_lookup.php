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
$FileNoGroup = 0;
$find = '.jpg';
$fileMutiply = 1000;


if (isset($_POST['Submit1'])) {
    $LOG = ($_POST['Log']);
    $QTY = ($_POST['Qty']);
    $SUBMIT = ($_POST['Submit']);
    $CALL_SEARCH = ($_POST['Call_Search']);
    $BAND = ($_POST['Band']);
    
    include_once buildfiles($LOG);
    $data = '<FORM name ="form1" method ="post" action ="index.php">' . PHP_EOL;
    $data .= '<input type="hidden" name="Log" value=' . $_POST['Log'] . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
    
}
//clean input
$CALL_SEARCH = safe("%".$CALL_SEARCH."%");


$query ="SELECT COL_CALL AS `Call`,COL_BAND AS Band, COL_State AS State, COL_Country AS Country, \n"
            . "$dbnameHRD.$tbHRD.COL_PRIMARY_KEY AS ID, COL_TIME_OFF AS Date, CASE COL_EQSL_QSL_RCVD When 'Y' Then 'Yes' end AS EQSL, \n"
            . "CASE COL_LOTW_QSL_RCVD  When 'V' Then 'Yes' end AS LOTW, CASE COL_QSL_RCVD When 'Y' Then 'Yes' end AS QSL, \n"
            . "COL_MODE AS `Mode`, $dbnameWEB.tb_Cards.COL_File_Path_E AS 'E QSL', $dbnameWEB.tb_Cards.COL_File_Path_F AS File, \n"
            . "$dbnameWEB.tb_Cards.COL_File_Path_B AS 'File Back' FROM $dbnameHRD.$tbHRD LEFT OUTER JOIN HRD_Web.tb_Cards \n"
            . "ON $dbnameHRD.$tbHRD.COL_PRIMARY_KEY = $dbnameWEB.tb_Cards.COL_PRIMARY_KEY where __REPLACE__ \n"
            . "ORDER BY $dbnameHRD.$tbHRD.`COL_PRIMARY_KEY` DESC";
if ($SUBMIT == "true") {
   
    if ($BAND <> "_Any_Band_" )
    {
            $BAND = safe("%".$BAND."%");
            $query = str_replace( "__REPLACE__", "COL_BAND like $BAND ", $query);
            //$id_lookup = $db->query("$query"); 
    }
    else 
        {
        $query = str_replace( "__REPLACE__", "COL_CALL like $CALL_SEARCH ", $query);
    }
    if ($QTY == "All")
    { 
            $id_lookup = $db->query("$query");
    }
    else
    {
            $query = str_replace( "DESC", "DESC Limit $QTY ", $query);
            $id_lookup = $db->query("$query");
    }
    

    $data = "<table border='0' align='center'><tbody><tr>"
            . "<th>Call</th>"
            . "<th>Band</th>" . "<th>State</th>" . "<th>Country</th>" 
            . "<th>ID</th>" . "<th>Date</th>" . "<th>EQSL</th>"
            . "<th>LOTW</th>" . "<th>QSL</th>" . "<th>Mode</th>"
            . "<th>E QSL</th>" . "<th>File</th>" . "<th>File Back</th>"
            . "</tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    foreach ($id_lookup as $row): {
            //
            $data .= "<td>" . qrzcom_interface($row['Call']) . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Band'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['State'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Country'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['ID'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Date'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['EQSL'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['LOTW'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['QSL'] . "</td>" . PHP_EOL;
            $data .= "<td>" . $row['Mode'] . "</td>" . PHP_EOL;
            $FileNoGroup = (($row[ID] / $fileMutiply) % $fileMutiply * $fileMutiply);
            $fileNoGroupHigh = $FileNoGroup + ($fileMutiply - 1);
            $filePath = "cards/" . $FileNoGroup . "-" . $fileNoGroupHigh;
            $pathToThumbs = $filePath . '/thumbs/';
            $fileName = $row['E QSL'];
            $pos = strpos($fileName, $find);
            if ($pos !== false) {
                $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>" . PHP_EOL;
            } else {
                $data .= "<td>" . $row['E QSL'] . "</td>" . PHP_EOL;
            }

            $fileName = $row['File'];
            $pos = strpos($fileName, $find);
            if ($pos !== false) {
                $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>" . PHP_EOL;
            } else {
                $data .= "<td>" . $row['File'] . "</td>" . PHP_EOL;
            }
            $fileName = $row['File Back'];
            $pos = strpos($fileName, $find);
            if ($pos !== false) {
                $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>" . grid_style($i) . PHP_EOL;
            } else {
                $data .= "<td>" . $row['File Back'] . "</td>" . grid_style($i) . PHP_EOL;
            }

            $i++;
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data = str_replace("USB", "SSB", $data);
    $data = str_replace("LSB", "SSB", $data);
    $data .= "</table><br><br>" . PHP_EOL;
} else {
    $data .="<br>" . PHP_EOL;
    $data .=band() . PHP_EOL;
    $data .="<br>" . PHP_EOL;
    $data .=mode() . PHP_EOL;
    $data .="<br>" . PHP_EOL;
    $data .='If you select Callsign lookup enter Callsign in the box' . PHP_EOL;
    $data .='<br><input id="mySelect2" type="lookup" name="Call_Search"><br>' . PHP_EOL;
    $data .='Select from drop down the amount of QLS would like to return' . PHP_EOL;
    $data .='<br><select id="mySelect3" name="Qty"><option>50</option><option>100</option><option>250</option><option>500</option><option>1000</option><option>All</option></select>' . PHP_EOL;
    $data .='<br><div><P style="text-align: center"><Input type = "Submit" Name = "Submit1" VALUE = "Submit"></p></div></FORM><BR>' . PHP_EOL;
}

echo $data;
$phpfile = __FILE__;
footer($phpfile);
?>