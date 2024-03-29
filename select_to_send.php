<?php
// select_to_send.php
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

  
$title = "Cards to Send";

include_once("../config.php");
require_once('backend/db.class.php');
require_once("backend/backend.php");
require_once("backend/querybuilder.php");
include('backend/header.php');

// Create a new instance of the Db class





try {
    $query = getSelect_tosend();
    try 
    {
        $results = $db->select($query);
    } 
    catch (Exception $e) 
    {
        echo "Query: " . $query ."<br>";
        echo "Error executing query: " . $e->getMessage();
    }

    $data = "<div class='centered-content'>" . PHP_EOL;
    $data .= "<table class='custom-table' border='0'><tbody>";
    $data .= "<tr><th>Call</th><th>Country</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;

    foreach ($results as $row) 
    {  
        $data .=  "<tr><td>" . $row[DB_COL_CALL] . "</td>";
        $data .=  "<td>" . $row['COL_COUNTRY'] . "</td></tr>" . PHP_EOL;
    }

    $data .= "</tbody></table><br><br></div>" . PHP_EOL;
    
    echo $data;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

include('backend/footer.php');