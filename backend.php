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

#################################################
# QRZ.com callsign lookup
#################################################

function qrzcom_interface($callsign) {
    $lookup = "<a href='http://www.qrz.com/db/$callsign'>$callsign</a>";
    return ($lookup);
}

#################################################
# QRZ.com callsign lookup
#################################################

function qsl_worked() {
    include (__DIR__ . '/../config.php');
    require_once('lookup.class.php');
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
    require_once('lookup.class.php');
    $data = '<form name ="form1" method ="post" action ="index.php">' . PHP_EOL;
    $db = new Db();
    $id_lookup = $db->query("SELECT Select_TXT, Select_Name FROM $dbnameWEB.$tbSelect ORDER BY `Select_TXT` ");
    foreach ($id_lookup as $row):

        if ($row['Select_Name'] == "callsign_lookup") {
            $data .='<input type="radio" value=' . $row['Select_Name'] . ' name="Log" > ' . $row['Select_TXT'] . PHP_EOL;
        } else {
            $data .='<input type="radio" value=' . $row['Select_Name'] . ' name="Log"  > ' . $row['Select_TXT'] . PHP_EOL;
        }
    endforeach;
    $data .='<br><div><P style="text-align: center">' . PHP_EOL;
    $data .= '<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></p></div></FORM><BR>' . PHP_EOL;
    return $data;
}

#################################################
# build include files
#################################################

function buildfiles($Key) {
    include (__DIR__ . '/../config.php');
    require_once('lookup.class.php');
    $db = new Db();
    $id_lookup = $db->row("SELECT Select_File FROM $dbnameWEB.$tbSelect WHERE Select_Name = :f", array("f" => $Key));
    return $id_lookup['Select_File'];
}

#################################################
# SQL safe 
#################################################

function safe($value) {
	$db = new Db();
	return $db->quote($value);
//    return mysql_real_escape_string($value);
	//return $value;
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
# Footer
# 
#################################################

function footer($i) {
    ?>
    <div class="c1">
        <span class="auto-style5">
            <?php
            date_default_timezone_set('America/Los_Angeles');
            echo "Was last modified: " . date("F d Y H:i", filemtime($i));
            ?>
        </span>
    </div>
    <br><br>

    <div style="display:none;"><?php echo qsl_worked(); ?></div>
    <?php
}

#################################################
# BAND
# 
#################################################

function band() {
    include (__DIR__ . '/../config.php');
    require_once('lookup.class.php');
    $db = new Db();
    $id_lookup = $db->query("SELECT COL_BAND FROM $dbnameHRD.$tbHRD GROUP BY `COL_BAND` ");
    $select .='<span id="Band">' . PHP_EOL;
    foreach ($id_lookup as $row): {
            $select .='<input type="radio" value=' . $row['COL_BAND'] . ' checked name="Band">' . $row['COL_BAND'] . PHP_EOL;
        }
    endforeach;
    $select .='</span>'. PHP_EOL;
    return($select);
}

#################################################
# MODE
# 
#################################################

function mode() {
    include (__DIR__ . '/../config.php');
    require_once('lookup.class.php');
    $db = new Db();
    $id_lookup = $db->query("SELECT COL_MODE FROM $dbnameHRD.$tbHRD GROUP BY `COL_MODE` ");
    $select .='<span id="Mode">' . PHP_EOL;
    foreach ($id_lookup as $row): {
            $result = $row['COL_MODE'];
            if ($result === "LSB") {
                $result = "";
            } elseif ($result === "USB") {
                $result = "";
            } else {
                $select .='<input type="radio" value=' . $result . ' checked name="Mode">' . $result . PHP_EOL;
            }
        }
    endforeach;
    $select .='</span>'. PHP_EOL;
    return($select);
}

#################################################
# Country
#  
#################################################

function country($i) {
    $result = count($CountrysArray);
    if ($result >= 2) {
        $select .="<BR><select id='mySelect' name='Country'>";
        foreach ($CountrysArray as $CountryArray) {
            if (isset($user_input)) {
                $selected = $user_input == $CountryArray['value'] ? ' selected="selected"' : '';
            } else {
                $select .= "<option value=\"$CountryArray\"$selected>$CountryArray</option>";
            }
        }
    } else {
        foreach ($CountrysArray as $CountryArray) {
            $select .= $CountryArray;
        }
    }

    return($i);
}

#################################################
# Make Views
#################################################

function MakeViews()
{
    include (__DIR__ . '/../config.php');
    require_once('lookup.class.php');
    $db = new Db();
    $id_lookup = $db->query("create or replace view $dbnameHRD.bands  as select col_band from $dbnameHRD.$tbHRD group by 1");
    $id_lookup = $db->query("create or replace view $dbnameHRD.modes as select col_mode from $dbnameHRD.$tbHRD group by 1");
    $id_lookup = $db->query("create or replace view $dbnameHRD.towork as  \n"
    . "select col_mode, col_band, STATE,ST, COUNTRY, sCOUNTRY  \n"
    . "FROM $dbnameWEB.$tbStates, $dbnameHRD.modes, $dbnameHRD.bands  \n"
    . "WHERE concat( col_mode,'-', col_band,'-', ST,'-', COUNTRY ) NOT IN ( \n"
    . "SELECT concat( COL_MODE,'-', COL_BAND,'-', COL_STATE,'-', COL_COUNTRY )AS the_key  \n"
    . "FROM  $dbnameHRD.$tbHRD   \n"
    . "where COL_STATE is not null)  \n");

    $id_lookup = $db->query("create or replace view $dbnameHRD.zone_to_work as  \n"
    . "SELECT col_mode, col_band, zones \n"
    . "FROM $dbnameWEB.tb_zones, $dbnameHRD.modes, $dbnameHRD.bands \n"
    . "WHERE concat( col_mode,'-', col_band,'-', Zones ) NOT IN (\n"
    . "SELECT concat( COL_MODE,'-', COL_BAND,'-', COL_ITUZ )AS the_key \n"
    . "FROM $dbnameHRD.$tbHRD \n"
    . "where COL_ITUZ is not null) \n");
	
}

#################################################
# Band Mode Call Options
#################################################

function OptionList(){
  $data ="Query Type:<br>" . PHP_EOL;
  $data .= "<span> <input type=\"radio\" name=\"optionlist\" value=\"input1\" id=\"input1\">Band</span>" . PHP_EOL;
  $data .= "<span> <input type=\"radio\" name=\"optionlist\" value=\"input2\" id=\"input2\">Mode</span>" . PHP_EOL;
  $data .= "<span> <input type=\"radio\" name=\"optionlist\" value=\"input3\" id=\"input3\">Call</span>" . PHP_EOL;
  $data .= "<span> <input type=\"radio\" name=\"optionlist\" value=\"input4\" id=\"input4\">State</span>" . PHP_EOL;
  $data .= "<span> <input type=\"radio\" name=\"optionlist\" value=\"input5\" id=\"input5\">Country</span>" . PHP_EOL;
  $data .= "<span> <input type=\"radio\" name=\"optionlist\" value=\"input6\" id=\"input6\"checked>None</span>" . PHP_EOL;
  $data .='<br>' . PHP_EOL;
  Return $data;
}  
  
#################################################
# QTY Options
#################################################

function OptionQty(){
  $data ='<select name="Qty"><option>' . PHP_EOL;
  $data .='50</option><option>' . PHP_EOL;
  $data .='100</option><option>' . PHP_EOL;
  $data .='250</option><option>' . PHP_EOL;
  $data .='500</option><option>' . PHP_EOL;
  $data .='1000</option><option>' . PHP_EOL;
  $data .='All</option>' . PHP_EOL;
  $data .='</select><br>' . PHP_EOL;
  Return $data;
}     

#################################################
# Country Options
#################################################

function OptionCountry(){
    include (__DIR__ . '/../config.php');
    require_once('db.class.php');
    require_once("backend.php");
    $db = new Db();    
    $id_lookup = $db->query("SELECT COL_COUNTRY as 'Countrys Worked' FROM $dbnameHRD.$tbHRD WHERE 1 group by 1");
    $data ='<span id="Country">' . PHP_EOL;
    $data .='<select name="Country">' . PHP_EOL;
    foreach ($id_lookup as $row): 
        {  
           $Name = $row['Countrys Worked'];
           $data .='<option>' . $Name . '</option>' . PHP_EOL;
           unset($row); // break the reference with the last element
        }
   endforeach;
   $data .='</select>' . PHP_EOL;
   $data .='</span>' . PHP_EOL;
   Return $data;
}

#################################################
# State Options
#################################################

function OptionState(){
    include (__DIR__ . '/../config.php');
    require_once('db.class.php');
    require_once("backend.php");
    $db = new Db();    
    //$id_lookup = $db->query("SELECT COL_STATE as 'State Worked' FROM $dbnameHRD.$tbHRD WHERE 1 group by 1");
    $SQL = "SELECT distinct COL_STATE as 'State Worked', COL_COUNTRY, 
        case when State is null then COL_STATE else State end as 'State'
        FROM NA7KR.TABLE_HRD_CONTACTS_V01 
        left outer JOIN HRD_Web.tb_States_Countries 
        on COL_STATE = 
        HRD_Web.tb_States_Countries.ST 
        Where COL_STATE is not null
        order by 2,1";

     $id_lookup = $db->query($SQL);
    
    $data ='<span id="State">' . PHP_EOL;
    $data .='<select name="State">' . PHP_EOL;
    foreach ($id_lookup as $row): 
        {  
            $Name = $row['State'];
            $Value = $row['State Worked']; 
            $data .= '<option value=' . $Value .' >' . $Name . '</option>' . PHP_EOL;
            unset($row); // break the reference with the last element
        }
   endforeach;
   $data .='</select>' . PHP_EOL;
   $data .='</span>' . PHP_EOL;
   Return $data;
}