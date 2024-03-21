<?php
// backend/querybuilder.php
// New
/*
Copyright © 2024 NA7KR Kevin Roberts. All rights reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
$db = new Db();

function buildQuery($first, $QTY, $SUBMIT, $CALL_SEARCH, $BAND, $MODE, $STATE, $COUNTRY, $INPUT) {
    $query = "SELECT COL_CALL AS `Call`, \n"
        . "COL_BAND AS Band, \n"
        . "COL_State AS State, \n"
        . "COL_Country AS Country, \n"
        . DB_NAME . "." . DB_TABLE_HRD . ".COL_PRIMARY_KEY AS ID, \n"
        . " COL_TIME_OFF AS Date, \n"
        . "CASE COL_EQSL_QSL_RCVD When 'Y' Then 'Yes' end AS EQSL, \n"
        . "CASE COL_LOTW_QSL_RCVD  When 'V' Then 'Yes' end AS LOTW, \n"
        . "CASE COL_QSL_RCVD When 'Y' Then 'Yes' end AS QSL, \n"
        . "COL_MODE AS `Mode`," . DB_NAME . ".tb_Cards.COL_File_Path_E AS 'E QSL', \n"
        .  DB_NAME . ".tb_Cards.COL_File_Path_FE as FEmailCard, \n"
        .  DB_NAME . ".tb_Cards.COL_File_Path_BE as BEmailCard, \n"
        .  DB_NAME . ".tb_Cards.COL_File_Path_F AS File, \n"
        .  DB_NAME . ".tb_Cards.COL_File_Path_B AS 'File Back' \n"
        . "FROM " . DB_NAME . "." . DB_TABLE_HRD . " LEFT OUTER JOIN " . DB_NAME . ".tb_Cards \n"
        . "ON " . DB_NAME . "." . DB_TABLE_HRD . ".COL_PRIMARY_KEY = " . DB_NAME . ".tb_Cards.COL_PRIMARY_KEY  __REPLACE__ \n"
        . "ORDER BY " . DB_NAME . "." . DB_TABLE_HRD . ".`COL_PRIMARY_KEY` \n"
        . "DESC";
    // Build the WHERE clause based on form input
    // ...
    return $query;
}


// Function to set the query for retrieving the file path based on the select name and return it
function getSelect_Name() {
    // Construct the SQL query
    $query = "SELECT Select_File \n"
    . "FROM " . DB_TBSelect . "\n"
    . " WHERE Select_Name = :f";
    return $query;
}

function  getSelect_Award(){
    $query = "SELECT COL_Award AS Award, COL_File AS 'File' \n"
    . " FROM " . DB_NAME . ".tb_awards \n"
    . " ORDER BY `COL_Award` ASC";
    return $query;
}

function  getSelect_Fetch(){
    $query = "SELECT *, DATE_FORMAT(`COL_TIME_ON`, '%m/%d/%Y - %H:%i') AS `formatted_time` \n"
    . "FROM `TABLE_HRD_CONTACTS_V01` \n"
    . "WHERE `COL_CALL` = :call";
    return $query;
}
   
function getSelect_Pre($qs){
    $query = "SELECT *, DATE_FORMAT(`" . DB_COL_TIME_ON . "`, '%m/%d/%Y - %H:%i') AS `kCOL_TIME_ON` \n"
    . " FROM " . DB_TABLE_HRD . "\n"
    . " WHERE " . DB_COL_PRIMARY_KEY ." IN ($qs) \n"
    . " ORDER BY ". DB_COL_TIME_ON . " ASC"; 
    return $query;
}

function  getSelect_Home(){
    $query = "SELECT MIN(STR_TO_DATE(COL_TIME_ON, '%Y-%m-%d')) AS first_date,  \n"
    . " MAX(STR_TO_DATE(COL_TIME_ON, '%Y-%m-%d')) AS last_date  \n"
    . " FROM " . DB_TABLE_HRD . ";";
    return $query;
}

function getSelect_Countries(){
    $query = "SELECT COL_COUNTRY as 'Countries Worked' \n"
    . " FROM " . DB_NAME . "." . DB_TABLE_HRD . "\n"
    . " __REPLACE__ group by 1 order by COL_COUNTRY";
    return $query;
}

function getSelect_CQ_Zones(){
$query = "SELECT " . DB_NAME . ".tb_cq_zones.zones as `CQ Zone to Work` \n"
	. " FROM " . DB_NAME . ".tb_cq_zones  \n"
	. " left outer join " . DB_NAME . "." . DB_TABLE_HRD . " on  \n"
	. " " . DB_NAME . ".tb_cq_zones.zones =" . DB_NAME . "." . DB_TABLE_HRD . ".COL_CQZ   \n"
	. " where COL_CQZ is null \n"
	. " or " . DB_NAME . ".tb_cq_zones.zones not in \n"
	. " (SELECT COL_CQZ from " . DB_NAME . "." . DB_TABLE_HRD . " where COL_EQSL_QSL_RCVD __REPLACE__  = 'Y' group by 1) \n"
	. " group by 1";
    return $query;
}

function getSelect_CQ_ZonesBand(){
$query ="SELECT " . DB_NAME . ".cq_zone_to_work.zones as `CQ Zone to Work`, COL_Mode as `Mode` \n"	
			. " FROM " . DB_NAME . ".cq_zone_to_work \n"	
			. "where COL_BAND like '__REPLACE__' \n"	 
			. " order by col_band,col_mode,zones";
    return $query;
 }

function getSelect_AwardsCount(){
    $query =" SELECT COUNT(*) FROM " . DB_NAME . ".tb_awards";
    return $query;
 }

 function getSelect_Backend_Name(){
    $query = "SELECT `Select_TXT`, `Select_Name` FROM "  . DB_TBSelect . " ORDER BY `Select_TXT`";
    return $query;
}

function getSelect_Backend_Band(){
    $query = "SELECT COL_BAND FROM "  . DB_TABLE_HRD . " GROUP BY `COL_BAND` ";
    return $query;
}
function getSelect_Backend_Mode(){
    $query = "SELECT DISTINCT  CASE When COL_MODE Like '%USB%' or COL_MODE like '%LSB%' then 'SSB' else COL_MODE End as COL_MODE  FROM "  . DB_TABLE_HRD . " GROUP BY `COL_MODE` ";
    return $query;
}   

function getSelect_Backend_State(){
    $query = "SELECT distinct COL_STATE as 'State Worked',\n"
        . "COL_COUNTRY, \n"
        . "case when State is null then COL_STATE else State end as 'State' \n"
        . "FROM "  . DB_TABLE_HRD . " \n"
        . "left outer JOIN tb_States_Countries \n"
        . "on COL_STATE = tb_States_Countries.ST \n"
        . "Where COL_STATE is not null and tb_States_Countries.sCountry = 'USA' group by 1 \n"
        . "order by 2,1";
    return $query;
}   

function getSelect_Backend_Country(){
    $query = "SELECT COL_COUNTRY as 'Countrys Worked' FROM "  . DB_TABLE_HRD . " WHERE 1 group by 1";
    return $query;
}   

function getSelect_ZoneMode(){
$query ="SELECT " . DB_NAME . ".cq_zone_to_work.zones as `CQ Zone to Work`, COL_Band as `Band`  \n"	
			. " FROM " . DB_NAME . ".cq_zone_to_work \n"
			. " where COL_MODE like '__REPLACE__'  \n"
			. " order by col_band,col_mode,zones";

    return $query;
}   

function getSelect_ZoneNone($input){
    $query = str_replace("__REPLACE__", " ", $input);
return $query;
}   

function getSelect_full(){
$query  = "SELECT\n"
        . " DATE(`COL_TIME_OFF`) AS `Date`,\n"
        . " `COL_CALL` AS `CallSign`,\n"
        . " `COL_MODE` AS `Mode` , \n"
        . " `COL_BAND` AS `Band` , \n"
        . " `COL_GRIDSQUARE` AS  `Grid` ,\n"
        . " `COL_COUNTRY` AS `Country` , \n"
        . " `COL_STATE` AS `State` ,\n"
        . " `COL_QTH` AS `QTH` \n"
        . " FROM " . DB_TABLE_HRD . "\n"
        . "  __REPLACE__  ORDER BY `" . DB_TABLE_HRD . "`.`COL_PRIMARY_KEY`\n"
        . "DESC;";
    return $query;
}

function getSelect_ecard(){
$query = "SELECT tb_Cards.COL_PRIMARY_KEY as 'Log ID', \n"
        . DB_TABLE_HRD . ".COL_CALL as 'Call', \n"
        . "tb_Cards.COL_File_Path_FE as 'Card', \n"
        . "tb_Cards.COL_File_Path_BE as 'Back' \n"
        . "FROM tb_Cards INNER JOIN " . DB_NAME . "." . DB_TABLE_HRD . " ON tb_Cards.COL_PRIMARY_KEY =" . DB_TABLE_HRD . ".COL_PRIMARY_KEY \n"
        . "Where `COL_File_Path_FE` is not null";
    return $query;
}

function getSelect_zonesITU(){

$query =  "SELECT tb_zones.zones as `ITU Zone to Work` \n"
    . "FROM tb_zones \n"
    . " left outer join "  . DB_TABLE_HRD . " on tb_zones.zones = " . DB_TABLE_HRD . ".COL_ITUZ  \n"
    . "where COL_ITUZ is null ";
    return $query;
}

function getSelect_zonesITU_Band(){ 
$query = "SELECT " . DB_NAME . ".zone_to_work.zones as `ITU Zone to Work`,\n"
       . " COL_MODE as `Modes` \n"
       . " FROM " . DB_NAME . ".zone_to_work \n"
       . " WHERE (COL_BAND IS NOT NULL AND COL_BAND <> '') \n"
       . " AND COL_BAND LIKE '__REPLACE__' \n"
       . " ORDER BY col_band, col_mode, zones";
    return $query;
}


function getSelect_zonesITU_Mode(){
    $query ="SELECT " . DB_NAME . ".zone_to_work.zones as `ITU Zone to Work`,\n"
    . " COL_BAND as `Band` \n"
    . " FROM " . DB_NAME . ".zone_to_work \n"
    . " WHERE (COL_MODE IS NOT NULL AND TRIM(COL_MODE) <> '' AND COL_MODE NOT LIKE '% %') \n"
    . " AND COL_MODE LIKE '__REPLACE__' \n"
    . " ORDER BY col_band, col_mode, zones";
   return $query;   
}

function getSelect_nocard_eqsl($Country){
    $query = "SELECT " . DB_tbStates . ".State as `StateF`, \n"
         . DB_tbStates . ".ST as `State` \n"
        . " FROM " . DB_tbStates. "  left outer join " . DB_TABLE_HRD . " on " . DB_tbStates. ".Country = " . DB_TABLE_HRD . ".COL_COUNTRY \n"
        . " AND  " . DB_tbStates . ".ST = " . DB_TABLE_HRD . ".COL_STATE \n"
        . " where ( " . DB_tbStates . ".sCountry  like '%$Country%' ) \n"
        . " and col_state is not null \n"
        . " and COL_EQSL_QSL_RCVD not in ( 'Y' ) \n"
        . " AND col_state not in \n"
        . " (select col_state from " . DB_TABLE_HRD . " \n"
        . " where col_state is not null \n"
        . " and COL_EQSL_QSL_RCVD <> 'N' \n"
        . " and COL_EQSL_QSL_RCVD <> 'R' \n"
        . " __REPLACE__ ) \n"
        . " group by 1,2";
   return $query ;   
}

function getSelect_eqsl(){
$query = "SELECT tb_Cards.COL_PRIMARY_KEY as 'Log ID', \n"
        . "COL_CALL as 'Call', \n"
        . "tb_Cards.COL_File_Path_E as 'Card' \n"
        . "FROM tb_Cards INNER JOIN " . DB_TABLE_HRD . " ON  tb_Cards.COL_PRIMARY_KEY =". DB_TABLE_HRD .".COL_PRIMARY_KEY \n"
        . "WHERE tb_Cards.COL_File_Path_E <> '' __REPLACE__";
   return $query;   
}

function getSelect_lotw(){
$query = "SELECT " . DB_COL_CALL . " as 'Call', `COL_LOTW_QSL_RCVD` as 'Confirmed' FROM " . DB_TABLE_HRD . " Where `COL_LOTW_QSL_RCVD` = 'V' __REPLACE__";
   return $query;   
}

function getSelect_paper(){
$query = "SELECT tb_Cards.COL_PRIMARY_KEY AS 'Log ID', \n"
. "TABLE_HRD_CONTACTS_V01.COL_CALL AS 'Call', \n"
. "tb_Cards.COL_File_Path_F AS 'Card', \n"
. "tb_Cards.COL_File_Path_B AS 'Back' \n"
. "FROM tb_Cards \n"
. "INNER JOIN TABLE_HRD_CONTACTS_V01 ON tb_Cards.COL_PRIMARY_KEY = TABLE_HRD_CONTACTS_V01.COL_PRIMARY_KEY \n"
. "WHERE tb_Cards.COL_File_Path_F <> ''";
   return $query;   
}

function getSelect_qsl(){
    $query = "SELECT `KeyNumber` FROM `tb_to_download` WHERE `updated` = 0";
   return $query;   
}

function getSelect_qsl2(){
    $query = "SELECT YEAR(COL_TIME_OFF) AS `Year`, \n"
        . " MONTH(COL_TIME_OFF) AS `Month`, \n"
        . " DAY(COL_TIME_OFF) AS `Day`, \n"
        . " HOUR(COL_TIME_OFF) AS `Hour`, \n"
        . " MINUTE(COL_TIME_OFF) AS `Minute`,\n"
        . " COL_CALL AS `Call`,\n"
        . " COL_BAND AS `Band`, \n"
        . " COL_MODE AS `Mode` \n"
        . " FROM " . DB_TABLE_HRD . "  \n"
        . " WHERE " . DB_COL_PRIMARY_KEY . "  = :ikey";
    return $query; 
}

function getSelect_nocard_lotw($Country){
$query = "SELECT " . DB_tbStates . ".State as `StateF`, \n"
        . "" . DB_tbStates . ".ST as `State` \n"
        . "FROM " . DB_tbStates . " left outer join " . DB_TABLE_HRD . " on " . DB_tbStates . ".Country = " . DB_TABLE_HRD . ".COL_COUNTRY \n"
        . "AND  " . DB_tbStates . ".ST = " . DB_TABLE_HRD . ".COL_STATE \n"
        . "where ( " . DB_tbStates . ".sCountry like '%$Country%' ) \n"
        . "and col_state is not null \n"
        . "AND COL_LOTW_QSL_RCVD not in ( 'Y' ) \n"
        . "AND col_state not in (select col_state \n"
        . "from " . DB_TABLE_HRD . " \n"
        . "where col_state is not null \n"
        . "and COL_LOTW_QSL_RCVD <> 'N' \n"
        . "and COL_LOTW_QSL_RCVD <> 'R' \n"
        . "__REPLACE__ ) \n"
        . "group by 1,2";
    return $query;
}

function getSelect_two_or_more(){
$query = "Select count(*) as `Count`, \n"
        . " COL_CALL from(SELECT distinct COL_CALL, \n"
            . " COL_BAND, \n"
            . " COL_MODE, \n"
            . " count(*) \n"
            . " FROM NA7KR.`" . DB_TABLE_HRD . "` \n"
            . " __REPLACE__ \n"
            . " group by 1,2,3 ) as CALLSIGN \n"
        . " group by 2 HAVING Count > 1 \n"
        . " ORDER BY Count DESC";
    return $query;
}

function getSelect_towork_band($BAND){
$query = "select * from tb_States_Countries where st not in "
        . "( select col_state from NA7KR." . DB_TABLE_HRD . " where COL_BAND like '$BAND' and col_country = tb_States_Countries.country ) "
        . " and sCountry = 'USA' group by st ";
   return $query;
}

function getSelect_towork_mode_SSB($MODE){
    $query = "select * from tb_States_Countries where st not in "
    . "( select col_state from NA7KR." . DB_TABLE_HRD   
    . "where (COL_MODE like $MODE or COL_MODE like '%USB%' or COL_MODE like '%LSB%') and col_country = tb_States_Countries.country ) "
    . " and sCountry = 'USA' group by st ";
  return $query;
}

function getSelect_towork_mode($MODE){
    $query = "select * from tb_States_Countries where st not in "
    . "( select col_state from NA7KR." . DB_TABLE_HRD . " where  COL_MODE like '$MODE' and col_country = tb_States_Countries.country ) "
    . " and sCountry = 'USA' group by st ";
  return $query;
}

function getSelect_towork($Country){
$query ="SELECT " . DB_tbStates . ".State as `State` , "
    . DB_tbStates . ".ST as `State` , "
    . DB_tbStates . ".Country as `Country`"
    . " FROM " . DB_tbStates . " left outer join  " . DB_TABLE_HRD . " on " . DB_tbStates . ".Country  = " . DB_TABLE_HRD . ".COL_COUNTRY "
    . "AND " . DB_tbStates . ".ST = " . DB_TABLE_HRD . ".COL_STATE  "
    . "where " . DB_tbStates . ".sCountry  like '%$Country%' "
    . "and col_state is null "
    . "group by 1,2";
  return $query;
}

function getSelect_account_update($email){
$query ="SELECT 1 FROM users WHERE email = $email";
  return $query;
}

function getSelect_membership($email){
$query = "SELECT id, username, email FROM users";
  return $query;
}


function getSelect_welcome($Key){
$query ="SELECT * FROM `HRD_Web`.`tb_Cards` WHERE `tb_Cardsdb`." . DB_COL_PRIMARY_KEY . " = $Key ";
  return $query;
}


function getSelect_welcome_update($Key){
$query ="UPDATE `HRD_Web`.`tb_Cards` SET  `$tbside` = '$FileName' WHERE `tb_Cards`." . DB_COL_PRIMARY_KEY . " = $Key";
  return $query;
}


function getSelect_welcome_insert($Key,$FileName){
    $query = "INSERT INTO `HRD_Web`.`tb_Cards` (" . DB_COL_PRIMARY_KEY . ", `$tbside`) VALUES ( $Key, '$FileName')";
  return $query;
}

function getSelect_welcome2($Key){
    $query = "SELECT COL_CALL FROM " . DB_TABLE_HRD . " where COL_PRIMARY_KEY ='$Key'";
  return $query;
}

function getSelect_startstop(){
    $query = "SELECT `COL_OPTOUT`, T2.COL_CALL, T2.COL_EMAIL \n"
    . "FROM `TABLE_OPTOUT` as T1 INNER JOIN " . DB_TABLE_HRD . " as T2 ON T1.COL_CALL=T2.COL_CALL \n"
    . "WHERE T2.COL_CALL = :call AND T2.COL_EMAIL = :email";
    return $query;
}

function setSelect_OptOut(){
    $query = "UPDATE TABLE_OPTOUT SET COL_OPTOUT = ? WHERE " . DB_COL_CALL . " = ?";
    return $query;
}

function setSelect_First(){
    $query = "SELECT MIN(STR_TO_DATE(COL_TIME_ON, '%Y-%m-%d')) AS first_date, MAX(STR_TO_DATE(COL_TIME_ON, '%Y-%m-%d')) AS last_date FROM $db_Table;";
    return $query;
}

function get_Dashboard_call($callsign)
{
    $query = "SELECT * FROM " . DB_TABLE_HRD . " WHERE `COL_CALL` = '$callsign'";
    return $query;
}


function setSelect_Awards(){
    $query = "INSERT INTO `tb_awards` ( `COL_Award`, `COL_File`) VALUES ( ?, ?)";
    return $query;
}


function setSelect_QSL(){
    $query = "UPDATE `tb_to_download` SET  `updated` = '1' WHERE `tb_to_download`.`KeyNumber` = ?";
    return $query;
}

function setSelect_tosend(){
$query = "SELECT " . DB_COL_CALL . ", `COL_COUNTRY`
    FROM " . DB_NAME . "." . DB_TABLE_HRD . "
    RIGHT JOIN `NA7KR`.`vw_country` ON `COL_COUNTRY` = " . DB_NAME . ".`vw_country`.`COUNTRYS`
    WHERE " . DB_NAME . "." . DB_TABLE_HRD . ".`COL_LOTW_QSL_RCVD` <> 'V' 
    AND " . DB_NAME . "." . DB_TABLE_HRD . ".`COL_QSL_SENT` = 'N' AND col_country
    NOT IN (SELECT COL_COUNTRY FROM " . DB_NAME . "." . DB_TABLE_HRD . " WHERE COL_LOTW_QSL_RCVD = 'V')
    ORDER BY col_country ASC";
    return $query;
}			