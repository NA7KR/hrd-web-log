<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
/***************************************************************************
*			NA7KR Log Program 
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
 ?>
<script type="text/javascript">
	function makeDisable()
	{
		var x=document.getElementById("mySelect2")
		x.disabled=true
		var x=document.getElementById("mySelect3")
		x.disabled=true
	}
	function makeEnable()
	{
		var x=document.getElementById("mySelect2")
		x.disabled=false
		var x=document.getElementById("mySelect3")
		x.disabled=false
	}
	function onLoad()
	{
		makeDisable();
	}
</script>

	<?php
		include_once "config.php";
		include_once("style.php");
		include "counter.php";
	?>
	<title><?php echo $myCall ?> Ham Radio LogBook</title> 
	<meta name="keywords" content="Ham Radio NA7KR">
	<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
	<meta name="description" content="<?php echo $myCall ?> Ham Radio LogBook"> 
	<meta http-equiv="content-type" content="text/html;charset=UTF-8"> 
	<meta name="revisit-after" content="1 days">
	<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
</head>

<body onload="onLoad();" class="background1">
<?php include_once("analyticstracking.php") ?>
    <div class="auto-style1"> Hello welcome my log book at reads from Ham Radio Deluxe log.. <span class="auto-style3"><br>
        </span><span class="auto-style4">My Call is <?php echo  qrzcom_interface($myCall) ?></span><br>
    </div>
<?php
    $i = 0;//style counter
    $style = "";//style setting
	$query ="";
	$post = "";
	$button = "";
	$counter = 0;
	$QSLWORKED = 0;
	$link = mysql_connect($dbhost, $dbuname, $dbpass) or die ('Cannot connect to the database: ' . mysql_error());
	mysql_select_db($dbnameHRD) or die ('Cannot connect to the database: ' . mysql_error());
	if (isset($_POST['Submit1']))
	{
		$LOG = safe($_POST['Log']);
		$BAND = safe($_POST['Band']);
		$MODE = safe($_POST['Mode']);
		$COUNTRY = safe($_POST['Country']);
		$QTY = safe($_POST['Qty']);
		$CALL_SEARCH = safe($_POST['Call_Search']);
		if ($BAND == "_Any_Band_")
		{
				$BAND ="%";
		}
		if ($MODE == "_Any_Mode_")
		{
			$MODE ="%";
		}
		if ($BAND == "%" &&  $MODE == "%" && $LOG == "towork")
			{
				if ($debug == "true")
				{
					Echo "<BR>1<BR>" . $LOG . "<BR><BR>";
				}
			    $sql = "SELECT `COL_CALL` , `COL_BAND`,`COL_TIME_OFF`,`COL_PRIMARY_KEY`FROM `TABLE_HRD_CONTACTS_V01` WHERE `COL_CALL` = \"KV4PY\"";
				$query1 = mysql_query($sql);
				while($info = mysql_fetch_array( $query1 ))
				{
			 		$query = $info[1];
				}
			}
		elseif ($BAND != "%" ||  $MODE != "%")
		{
			if ($LOG == "towork" and $MODE <> "SSB")
			{
				if ($debug == "true")
				{
					Echo "<BR>2<BR>" . $LOG . "<BR><BR>";
				}
				$query = "(SELECT `col_mode` as 'Mode', `col_band` as 'Band', `STATE` as 'State', `COUNTRY` as 'Country' \n"
				. "FROM `towork`  WHERE `col_mode` like '$MODE' AND `col_band` like '$BAND' AND `sCOUNTRY` = '_Country_')";
			}
			elseif ($LOG == "zones")
			{
				if ($debug == "true")
				{
					Echo "<BR>3<BR>" . $LOG . "<BR><BR>";
				}
				$query = "(SELECT `col_mode` as 'Mode', `col_band` as 'Band', `zones` as 'Zones' \n"
				. "FROM `zone_to_work` WHERE `col_mode` like '$MODE' AND `col_band` like '$BAND') ";
			}
			elseif ($MODE == "SSB")
			{
				if ($debug == "true")
					{
						Echo "<BR>6<BR>" . $LOG . "<BR><BR>";
					}
				$query = "SELECT $dbnameWEB.tb_Bands_lookup.Col_Band as 'Band', $dbnameWEB.tb_Bands_lookup.SSB_Mode as 'Mode', \n"
				. " $dbnameHRD.towork.STATE as 'State', $dbnameHRD.towork.Country as 'Country' \n"
				. " FROM $dbnameHRD.towork RIGHT JOIN $dbnameWEB.tb_Bands_lookup ON $dbnameHRD.towork.col_band = $dbnameWEB.tb_Bands_lookup.Col_Band \n"
				. " WHERE $dbnameHRD.towork.STATE IS NOT NULL and $dbnameHRD.towork.col_mode = $dbnameWEB.tb_Bands_lookup.SSB_Mode and \n"
				. " $dbnameHRD.towork.Col_Band like '_Band_' and sCOUNTRY like '_Country_'";
				//	$query1 = mysql_query($sql);
				//while($info = mysql_fetch_array( $query1 ))
				//{
			 	//	$query = $info[1];
				//}

			}
			else
			{
				if ($debug == "true")
				{
					Echo "<BR>4<BR>" . $LOG . "<BR><BR>";
				}
				$sql = "(SELECT Select_Name,Select_Query FROM $dbnameWEB.tb_Select where Select_Name = '$LOG')";
			$query1 = mysql_query($sql);
			while($info = mysql_fetch_array( $query1 ))
			{
		 		$query = $info[1];
			}
			}
		}
		else
		{
			if ($debug == "true")
				{
					Echo "<BR>5<BR>" . $LOG . "<BR>";
				}
			if ($LOG == "callsign_lookup")
			{
				$sql = "(SELECT Select_Name,Select_Query FROM $dbnameWEB.tb_Select where Select_Name = '$LOG')";
				$query1 = mysql_query($sql);
				while($info = mysql_fetch_array( $query1 ))
				{
					$query = $info[1];
				}
				if ($QTY == "All")
					{
						;// do no-think
					}
				else
				{
					$query = str_replace( "DESC", "DESC Limit $QTY ", $query);
				}
			}
			Else
			{
				$sql = "(SELECT Select_Name,Select_Query FROM $dbnameWEB.tb_Select where Select_Name = '$LOG')";
				$query1 = mysql_query($sql);
				while($info = mysql_fetch_array( $query1 ))
				{
					$query = $info[1];
				}
			}
		}
		$query = str_replace( "_Band_", $BAND, $query);
		$query = str_replace( "_Mode_", $MODE, $query);
		$query = str_replace( "_DB_",$dbnameHRD, $query);
		$query = str_replace( "_dbWEB_", $dbnameWEB, $query);
		$query = str_replace( "_tbST_", $tbStates, $query);
		$query = str_replace( "_tbSL_", $tbSelect, $query);
		$query = str_replace( "_tbHRD_", $tbHRD, $query);
		$query = str_replace( "_Country_", $COUNTRY, $query);
		$query = str_replace( "_CALL_SEARCH_", $CALL_SEARCH, $query);
		MakeViews();
		buildData($query);
	}
	if ($debug == "true")
	{
		echo "<BR><BR>". $query . "<BR><BR>";
 	}
	$sql = mysql_query("SELECT DISTINCT `COL_CALL` FROM `TABLE_HRD_CONTACTS_V01` WHERE 1");
	if(mysql_num_rows($sql))
	{
		while($rs=mysql_fetch_array($sql))
		{
			$QSLWORKED .=$rs[0] . ',' ;
		}
		if ($debug == "true")
		{
			Echo  $QSLWORKED;
		}
	}
	echo fbutton($button);

function  buildData($query)
{
    include "config.php";
	$SetCall = 0;
    $result = mysql_query($query);
     if ($debug == "true")
        {
                echo "<BR><BR>". $query . "<BR><BR>";
        }

    //return error message if query could not be made
    if (!$result)
	{
        echo "Could not successfully run query ($query) from DB: " . mysql_error();
        exit;
    }

    //write col names
    $x = 0;
    echo '<table border="0" align="center"><tbody><tr>';//open table
    while ($x < mysql_num_fields($result))
	{
        $meta = mysql_fetch_field($result, $x);
        $col_name = $meta->name;
		
		if ($x ==0 && $col_name =="Call")
		{
			$SetCall = 1;
		}
        echo '<td>' . $col_name . '</td>';
        $x++;
    }
    echo '</tr>';

    //write data to table
    while ($row = mysql_fetch_array($result, MYSQL_NUM))
	{
        $x = 0;//
	$FileNoGroup =0;
	$find = '.jpg';
	$fileMutiply = 1000;
        $style = grid_style($i);
        while ($x < mysql_num_fields($result)) {
            $data = $row[$x];
			$data = str_replace( "USB", "SSB", $data);
			$data = str_replace( "LSB", "SSB", $data);

			$FileNoGroup = (($row[4]/$fileMutiply) % $fileMutiply * $fileMutiply);
            $fileNoGroupHigh = $FileNoGroup + ($fileMutiply-1);
            $filePath="cards/". $FileNoGroup ."-".$fileNoGroupHigh;
			$pathToThumbs = $filePath . '/thumbs/';
			$Index =  'index.php';
			$HTaccess = '.htaccess';
			$base = '/srv/cards/';
			if (!file_exists($filePath)) 
			{
				mkdir($filePath, 0777, true);
				symlink($base . $Index, $filePath . "/" . $Index);
				symlink($base . $HTaccess, $filePath . "/" . $HTaccess );
	
				mkdir($pathToThumbs, 0777, true);
				symlink($base . $Index, $pathToThumbs . "/" . $Index);
				symlink($base . $HTaccess, $pathToThumbs . "/" . $HTaccess );
			}
			
			If ($row[0] <> NULL)
			{
				if ($SetCall == 1)
				{
					$fileName = $row[0];
					$data = str_replace( stristr("$fileName", $data),  qrzcom_interface($fileName), $data);
					$SetCall == 0;
				}
			}
			
			If ($row[1] <> NULL)
			{
				$fileName = $row[1];
				$pos =strpos($fileName,$find);
				if ($pos !== false)
                   	{
						$filePath ="/Awards";
						$jpgfile = "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>";
						$data = str_replace( "$fileName", "$jpgfile", $data);
					}

			}
			If ($row[2] <> NULL)
			{
				$fileName = $row[2];
				$pos =strpos($fileName,$find);
				if ($pos !== false)
				{
					$jpgfile = "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>";
					$data = str_replace( "$fileName", "$jpgfile", $data);
				}

			}
			If ($row[3] <> NULL)
			{
				$fileName = $row[3];
				$pos =strpos($fileName,$find);
				if ($pos !== false)
				{
					$jpgfile = "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>";
					$data = str_replace( "$fileName", "$jpgfile", $data);
				}
			}

			If ($row[10] <> NULL)
			{
				$fileName = $row[10];
                $pos =strpos($fileName,$find);

				if ($pos !== false)
				{
					$jpgfile = "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>";
					$data = str_replace( "$fileName", "$jpgfile", $data);
				}
			}
			If ($row[11] <> NULL)
			{
				$fileName = $row[11];
				$pos =strpos($fileName,$find);
				if ($pos !== false)
				{
					$jpgfile = "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>";
					$data = str_replace( "$fileName", "$jpgfile", $data);
				}
			}
			If ($row[12] <> NULL)
			{
				$fileName = $row[12];
				$pos =strpos($fileName,$find);
				if ($pos !== false)
				{
					$jpgfile = "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>";
					$data = str_replace( "$fileName", "$jpgfile", $data);
				}
			}
			echo '<td>' . $data . '</td>';
            $x++;
        }//end nested while
		echo '</tr>';
		$i++;
		$counter++;
    }
    echo '</tbody></table>';//close table
    echo '<BR> Counter ' . $counter . '<BR>';

    mysql_close($link);
}
#################################################
# QRZ.com callsign lookup 
#################################################
function qrzcom_interface($callsign) 
{
  $lookup =  "<a href='http://www.qrz.com/db/$callsign'>$callsign</a>";
  return ($lookup); 
}
#################################################
# Make Views
#################################################
function MakeViews()
{
	include "config.php";
	$sql = "create or replace view  $dbnameHRD.bands  as select  col_band from $dbnameHRD.$tbHRD group by 1";
	mysql_query($sql);
	
	$sql = "create or replace view $dbnameHRD.modes as select   col_mode from $dbnameHRD.$tbHRD group by 1";
	mysql_query($sql);
	
	$sql = "create or replace view $dbnameHRD.towork as  \n"
	. "select col_mode, col_band, STATE, COUNTRY, sCOUNTRY  \n"
	. "FROM $dbnameWEB.$tbStates, $dbnameHRD.modes, $dbnameHRD.bands  \n"
	. "WHERE concat( col_mode,'-', col_band,'-', ST,'-', COUNTRY ) NOT IN ( \n"
	. "SELECT concat( COL_MODE,'-', COL_BAND,'-', COL_STATE,'-', COL_COUNTRY )AS the_key  \n"
	. "FROM  $dbnameHRD.$tbHRD   \n"
	. "where COL_STATE is not null)  \n";
	mysql_query($sql);
	mysql_query($sql);

	$sql = "create or replace view $dbnameHRD.zone_to_work as  \n"
	. "SELECT col_mode, col_band, zones \n"
	. "FROM $dbnameWEB.tb_zones, $dbnameHRD.modes, $dbnameHRD.bands \n"
	. "WHERE concat( col_mode,'-', col_band,'-', Zones ) NOT IN (\n"
	. "SELECT concat( COL_MODE,'-', COL_BAND,'-', COL_ITUZ )AS the_key \n"
	. "FROM $dbnameHRD.$tbHRD \n"
	. "where COL_ITUZ is not null) \n";
	mysql_query($sql);
}
#################################################
# Make Grid
# $i mod 2 is checking to see if the row number is odd or even odd rows are colored differently than even rows to create a datagrid look and feel
#################################################
function grid_style($i)
{
    if (($i % 2) != 0)
    {
        $style = ' id="even"';
		echo "<tr bgcolor='#FFC600'>";
        return $style;
    }
    else
    {
        $style = ' id="odd"';
		echo "<tr bgcolor='#5e5eff'>";
        return $style;
    }
}
#################################################
# SQL safe
#################################################
function safe($value)
{
	return mysql_real_escape_string($value);
}

#################################################
# Button
#################################################
function fbutton($button)
{
	include "config.php";
	$link = "";
	$select = "";
	$CountryArray = "";
	$selected = "";
	$link = mysql_connect($dbhost, $dbuname, $dbpass) or die ('Cannot connect to the database: ' . mysql_error());
	mysql_select_db($dbnameHRD) or die ('Cannot connect to the database: ' . mysql_error());

	$sql=mysql_query("SELECT COL_BAND FROM $tbHRD GROUP BY `COL_BAND` ");
	$select .='<FORM name ="form1" method ="post" action ="log.php">';
	if(mysql_num_rows($sql))
	{
		while($rs=mysql_fetch_array($sql))
		{
			$select .='<input type="radio" value=' . $rs[0] . ' checked name="Band">' . $rs[0] ;
		}
	}
	$select .='<input type="radio" value= _Any_Band_  checked name="Band"> Any Band'  ;
	$select .='<BR>';

	$sql=mysql_query("SELECT COL_MODE FROM $tbHRD GROUP BY `COL_MODE` ");

	if(mysql_num_rows($sql))
	{
		while($rs=mysql_fetch_array($sql))
		{
			$result = $rs[0];
			if ($result === "LSB")
			{
				$result = "SSB";
			}
			else
			{
				if  ($result === "USB")
				{
					$result = "SSB";
				}
				$select .='<input type="radio" value=' . $result . ' checked name="Mode">' . $result ;
			}
		}
	}
	$select .='<input type="radio" value= _Any_Mode_  checked name="Mode"> Any Mode'  ;
	$select .='<BR>';

	$sql=mysql_query("SELECT Select_TXT, Select_Name FROM $dbnameWEB.$tbSelect ORDER BY `Select_TXT` ");
	if(mysql_num_rows($sql))
	{
		while($rs=mysql_fetch_array($sql))
		{
			if ($rs[1] == "callsign_lookup")
			{
				$select .='<input type="radio" value=' . $rs[1] . ' name="Log" onclick="makeEnable()" > ' . $rs[0] ;
			}
			else
			{
				$select .='<input type="radio" value=' . $rs[1] . ' name="Log" onclick="makeDisable()" > ' . $rs[0] ;
			}
		}
	}
	$result =  count($CountrysArray);
	if ($result >= 2)
	{
		$select .="<BR><select id='mySelect' name='Country'>";
		foreach ($CountrysArray as $CountryArray)
		{
				if (isset($user_input))
			{
					$selected = $user_input == $CountryArray['value'] ? ' selected="selected"' : '';
			}
				else
			{
				$select .= "<option value=\"$CountryArray\"$selected>$CountryArray</option>";
			}
		}
	}
	else
	{
		foreach ($CountrysArray as $CountryArray)
		{
			$select .= $CountryArray;
		}
	}
	$select .='</select><br>';
	$select .='If you select Callsign Lookup enter Callsign in the box';
	$select .='<br><input id="mySelect2" type="text" name="Call_Search"><br>';
	$select .='Select from drop down the amount of QLS would like to return';
	$select .='<br><select id="mySelect3" name="Qty"><option>50</option><option>100</option><option>All</option></select>';
	$select .='<br><div><P style="text-align: center"><Input type = "Submit" Name = "Submit1" VALUE = "Submit"></p></div></FORM><BR>' ;
	mysql_close($link);
	echo $select;

 }
?>
<div><p style="text-align: center"> Only band and modes in your log shows</p>
     <p style="text-align: center"> This is a addon and not any way a part of HRD.</p>
     <p style="text-align: center"> Please contact <a href="mailto:support@na7kr.us"><span class="auto-style2">support@na7kr.us</span></a> and not HRD.</p></div>
    <div><P style="text-align: center"><img  alt="HRD" src="HRD_logo.jpg"></p></div>
	<p>
    <div class="c1">
		<span class="auto-style5">
			<a href="http://validator.w3.org/check?uri=referer">
				<img class="c4"  src="http://www.w3.org/Icons/valid-html401" alt="Valid HTML 4.01 Transitional" height="31" width="88">
			</a>
		</span>
	</div>
	<br><br>
	<div class="c1">
		<span class="auto-style5">
			<a href="http://jigsaw.w3.org/css-validator/check/referer">
				<img class="c4" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!">
			</a>
		</span>
	</div>
	<br><br>
	
	<div style="display:none;"><?php echo $QSLWORKED ; ?></div>
</html>