<?php
error_reporting(E_ALL);
$i = 0;
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
$first =  htmlspecialchars($_POST["1st"]);
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
$FileNoGroup = 0;
$find = '.jpg';
$fileMutiply = 1000;
$first = "false";
$SUBMIT = "false";

if (isset($_POST['Submit1'])) 
{ 
    $first = htmlspecialchars($_POST["1st"]);
    $LOG = htmlspecialchars($_POST["Log"]);
    if (isset($_POST['Qty'])) {
        $QTY = htmlspecialchars($_POST["Qty"]);
    }
    if (isset($_POST['Submit'])) {
        $SUBMIT = htmlspecialchars($_POST["Submit"]);
    }
    if (isset($_POST['Call_Search'])) {
        $CALL_SEARCH = htmlspecialchars($_POST["Call_Search"]);
    }
    if (isset($_POST['Band'])) {
        $BAND = htmlspecialchars($_POST["Band"]);
    }
    if (isset($_POST['Mode'])) {
        $MODE = htmlspecialchars($_POST["Mode"]);
    }
    if (isset($_POST['State'])) {
        $STATE = htmlspecialchars($_POST["State"]);
    }
    if (isset($_POST['Country'])) {
        $COUNTRY = htmlspecialchars($_POST["Country"]);
    }
    if (isset($_POST['optionlist'])) {
        $INPUT = htmlspecialchars($_POST["optionlist"]);
    }
    //include_once buildfiles($LOG);
    //$data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    //$data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
}

$query = "SET sql_mode = \" \"";
$id_lookup = $db->query($query);
$query = "SELECT COL_CALL AS `Call`, \n"
        . "COL_BAND AS Band, \n"
        . "COL_State AS State, \n"
        . "COL_Country AS Country, \n"
        . "$dbnameHRD.$tbHRD.COL_PRIMARY_KEY AS ID, \n"
        . " COL_TIME_OFF AS Date, \n"
        . "CASE COL_EQSL_QSL_RCVD When 'Y' Then 'Yes' end AS EQSL, \n"
        . "CASE COL_LOTW_QSL_RCVD  When 'V' Then 'Yes' end AS LOTW, \n"
        . "CASE COL_QSL_RCVD When 'Y' Then 'Yes' end AS QSL, \n"
        . "COL_MODE AS `Mode`, $dbnameWEB.tb_Cards.COL_File_Path_E AS 'E QSL', \n"
        . "$dbnameWEB.tb_Cards.COL_File_Path_F AS File, \n"
        . "$dbnameWEB.tb_Cards.COL_File_Path_B AS 'File Back' \n"
        . "FROM $dbnameHRD.$tbHRD LEFT OUTER JOIN HRD_Web.tb_Cards \n"
        . "ON $dbnameHRD.$tbHRD.COL_PRIMARY_KEY = $dbnameWEB.tb_Cards.COL_PRIMARY_KEY  __REPLACE__ \n"
        . "ORDER BY $dbnameHRD.$tbHRD.`COL_PRIMARY_KEY` \n"
        . "DESC";

if ($SUBMIT == "true") 
{
    $SUBMIT = "false";
    if ($INPUT == "input_band") 
    {
        $BAND = safe("%" . $BAND . "%");
        $query = str_replace("__REPLACE__", "where COL_BAND like $BAND ", $query);
    } 
    elseif ($INPUT == "input_mode") 
    {
        $MODE = safe("%" . $MODE . "%");
        $MODE = str_replace("USB", "SSB", $MODE);
        $MODE = str_replace("LSB", "SSB", $MODE);
        $query = str_replace("__REPLACE__", "where COL_MODE like $MODE or COL_MODE like 'USB' or COL_MODE like 'LSB' ", $query);
    } 
    elseif ($INPUT == "input_search") 
    {
        $CALL_SEARCH = safe("%" . $CALL_SEARCH . "%");
        $query = str_replace("__REPLACE__", "where COL_CALL like $CALL_SEARCH ", $query);
    } 
    elseif ($INPUT == "input_state") 
    {
        $STATE = safe("%" . $STATE . "%");
        $query = str_replace("__REPLACE__", "where COL_STATE like $STATE ", $query);
    } 
    elseif ($INPUT == "input_country") 
    {
        $COUNTRY = safe("%" . $COUNTRY . "%");
        $query = str_replace("__REPLACE__", "where COL_COUNTRY like $COUNTRY ", $query);
    } 
    elseif ($INPUT == "input_none") 
    {
        $query = str_replace("__REPLACE__", " ", $query);
    } 
    else 
    {
        $query = str_replace("__REPLACE__", " ", $query);
    }
//  echo $query;
    if ($QTY == "All") 
    {
//        $id_lookup = $db->query($query );
    } 
    else 
    {
        $query = str_replace("DESC", "DESC Limit $QTY ", $query);
        $id_lookup = $db->query($query);
    }
    $data .= "<table border='0' align='center'><tbody><tr>"
            . "<th>Call</th>"
            . "<th>Band</th>" . "<th>State</th>" . "<th>Country</th>"
            . "<th>ID</th>" . "<th>Date</th>" . "<th>EQSL</th>"
            . "<th>LOTW</th>" . "<th>QSL</th>" . "<th>Mode</th>"
            . "<th>E QSL</th>" . "<th>Paper Front</th>" . "<th>Paper Back</th>"
            . "</tr><tr bgcolor='#5e5eff'>" . PHP_EOL;
    
    foreach ($id_lookup as $row): 
        {
	        try
	            {
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
                    
                    $FileNoGroup = ((int)($row['ID'] / $fileMutiply) % $fileMutiply * $fileMutiply);
                    
                    $fileNoGroupHigh = $FileNoGroup + ($fileMutiply - 1);
                    
                    $filePath = "cards/" . $FileNoGroup . "-" . $fileNoGroupHigh;
                    $pathToThumbs = $filePath . '/thumbs/';
                    $FileName = null;
                    $fileName = $row['E QSL'];
                    if (isset($fileName))
                    {
                        $pos = strpos($fileName, $find);
                        if ($pos !== false) 
                        {
                            $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>" . PHP_EOL;
                        } 
                        else 
                        {
                            $data .= "<td>" . $row['E QSL'] . "</td>" . PHP_EOL;
                        }
                    }
                    else 
                        {
                            $data .= "<td>" . $row['E QSL'] . "</td>" . PHP_EOL;
                        }
                    $FileName = null;
                    $fileName = $row['File'];
                    if (isset($fileName)) 
                    {
                        $pos = strpos($fileName, $find);
                        if ($pos !== false) 
                        {
                            $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>" . PHP_EOL;
                        }
                        else
                        {
                            $data .= "<td>" . $row['File'] . "</td>" . PHP_EOL;
                        }
                    } 
                    else 
                    {
                        $data .= "<td>" . $row['File'] . "</td>" . PHP_EOL;
                    }
                    $FileName = null;
                    $fileName = $row['File Back'];
                    if (isset($fileName)) 
                    {
                        $pos = strpos($fileName, $find);
                        if ($pos !== false) 
                        {
                            $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>" . grid_style($i) . PHP_EOL;
                        }
                        else 
                    {
                        $data .= "<td>" . $row['File Back'] . "</td>" . grid_style($i) . PHP_EOL;
                    }
                    } 
                    else 
                    {
                        $data .= "<td>" . $row['File Back'] . "</td>" . grid_style($i) . PHP_EOL;
                    }

                    $i++; 
                    unset($row); // break the reference with the last element
                }
                catch(Error $e) 
                {
                    $trace = $e->getTrace();
                    echo $e->getMessage().' in '.$e->getFile().' on line '.$e->getLine().' called from '.$trace[0]['file'].' on line '.$trace[0]['line'];
                }
            }
            endforeach;
            $data = str_replace("USB", "SSB", $data); //do not put .=
            $data = str_replace("LSB", "SSB", $data);

            $data .= "</table><br><br>" . PHP_EOL;
            $data .= OptionList(false, false, false, false, false, false) . PHP_EOL;
        }    
        else 
        {
            $data .= '<table width=600 class="center2">' . PHP_EOL;
            $data .='<tr><td>' . PHP_EOL;
            $data .=OptionList(true, true, true, true, true, true) . PHP_EOL;
            $data .=band() . PHP_EOL;
            $data .=mode() . PHP_EOL;
            $data .=OptionState() . PHP_EOL;
            $data .=OptionCountry() . PHP_EOL;
            $data .='<span id="Call">' . PHP_EOL;
            $data .='Enter Callsign in the box' . PHP_EOL;
            $data .='<input  type="text" name="Call_Search"><br>' . PHP_EOL;
            $data .='</span>' . PHP_EOL;
            $data .='</td></tr>' . PHP_EOL;
            $data .='</table>' . PHP_EOL;
            $data .='<div class="c1">' . PHP_EOL;
            $data .='<span class="auto-style5">' . PHP_EOL;
            $data .='Select from drop down the amount of QLS would like to return<br>' . PHP_EOL;
            $data .=OptionQty() . PHP_EOL;
            $data .='<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><BR>' . PHP_EOL;
        }
//$data .= "Input 1 " . $QTY;
//$data .= "Query " . $query;

echo $data;
$phpfile = __FILE__;
footer($phpfile);
?>
