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
$data = "";
$SUBMIT = "false";
$counter = 0;
if (isset($_POST['Submit1'])) {
	$LOG = htmlspecialchars($_POST["Log"]);
    if (isset($_POST['Submit'])) {
        $SUBMIT = htmlspecialchars($_POST["Submit"]);
    }
    if (isset($_POST['Band'])) {
        $BAND = htmlspecialchars($_POST["Band"]);
    }
    if (isset($_POST['Mode'])) {
        $MODE = htmlspecialchars($_POST["Mode"]);
    }
    if (isset($_POST['optionlist'])) {
        $INPUT = htmlspecialchars($_POST["optionlist"]);
    }
    include_once buildfiles($LOG);
    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
    $data .= '<input type="hidden" name="Submit" value="true">' . PHP_EOL;
};

$query = "SELECT HRD_Web.tb_cq_zones.zones as `CQ Zone to Work` \n"
	. " FROM HRD_Web.tb_cq_zones  \n"
	. " left outer join NA7KR.TABLE_HRD_CONTACTS_V01 on  \n"
	. " HRD_Web.tb_cq_zones.zones = NA7KR.TABLE_HRD_CONTACTS_V01.COL_CQZ   \n"
	. " where COL_CQZ is null \n"
	. " or HRD_Web.tb_cq_zones.zones not in \n"
	. " (SELECT COL_CQZ from NA7KR.TABLE_HRD_CONTACTS_V01 where COL_EQSL_QSL_RCVD __REPLACE__  = 'Y' group by 1) \n"
	. " group by 1";

if ($SUBMIT == "true") 
	{
		if ($INPUT == "input_band") 
		{
			$TEXT = $BAND;
			$BAND = safe("%" . $BAND . "%");
			$query ="SELECT $dbnameHRD.cq_zone_to_work.zones as `CQ Zone to Work`, COL_Mode as `Mode` \n"	
			. " FROM $dbnameHRD.cq_zone_to_work \n"	
			. "where COL_BAND like __REPLACE__ \n"	 
			. " order by col_band,col_mode,zones";

			$query = str_replace("__REPLACE__", " $BAND ", $query);
			$id_lookup = $db->query($query);
			$data = "<table border='0' align='center'><tbody><tr><th>CQ Zone to Work $TEXT</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
			foreach ($id_lookup as $row):
				{
					//$fileName = $row['File'];
					$data .=  "<td>" . $row['CQ Zone to Work'] . "</td><td>" . $row['Mode'] . "</td>" . grid_style($i) . PHP_EOL;
					$i++;
					unset($row); // break the reference with the last element
				}
			endforeach;
		}
		elseif ($INPUT == "input_mode") 
		{
			$TEXT = $MODE;
			$MODE = safe("%" . $MODE . "%");
			$query ="SELECT $dbnameHRD.cq_zone_to_work.zones as `CQ Zone to Work`, COL_Band as `Band`  \n"	
			. " FROM $dbnameHRD.cq_zone_to_work \n"
			. " where COL_MODE like __REPLACE__  \n"
			. " order by col_band,col_mode,zones";
			$query = str_replace("__REPLACE__", "$MODE ", $query);
			$id_lookup = $db->query($query);
			$data = "<table border='0' align='center'><tbody><tr><th>CQ Zone to Work $TEXT</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
			foreach ($id_lookup as $row):
				{
						//$fileName = $row['File'];
						$data .=  "<td>" . $row['CQ Zone to Work'] . "</td><td>" . $row['Band'] . "</td>" . grid_style($i) . PHP_EOL;
						$i++;
						unset($row); // break the reference with the last element
				}
			endforeach;
		}
		elseif ($INPUT == "input_none") {
			$query = str_replace("__REPLACE__", " ", $query);
			$id_lookup = $db->query($query);
			$data = "<table border='0' align='center'><tbody><tr><th>CQ Zone to Work</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
			foreach ($id_lookup as $row):
				{
					//$fileName = $row['File'];
					$data .=  "<td>" . $row['CQ Zone to Work'] . "</td>" . grid_style($i) . PHP_EOL;
					$i++;
					unset($row); // break the reference with the last element
				}
			endforeach;
		}
		$data .= "</table><br><br>" . PHP_EOL;
		$data .='<div class="c1">' . PHP_EOL;
		$data .='<span class="auto-style5">' . PHP_EOL;
		$data .= "Count " .$i;
		$data .=OptionList(false, false, false, false, false, false) . PHP_EOL;
	} 
	else 
	{
		$data = '<table width=600 class="center2">' . PHP_EOL;
		$data .='<tr><td>' . PHP_EOL;
		$data .=OptionList(true, true, false, false, false, true) . PHP_EOL;
		$data .=band() . PHP_EOL;
		$data .=mode() . PHP_EOL;
		$data .='</td></tr>' . PHP_EOL;
		$data .='</table>' . PHP_EOL;
		$data .='<div class="c1">' . PHP_EOL;
		$data .='<span class="auto-style5">' . PHP_EOL;
		$data .='none will return all<br>' . PHP_EOL;
		$data .='<Input type = "Submit" Name = "Submit1" VALUE = "Submit"></span></div></FORM><BR>' . PHP_EOL;
	}
  //$data .= $query. PHP_EOL;
    echo $data;
    $phpfile = __FILE__ ;
    footer($phpfile);
