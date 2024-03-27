<?php
// select_awards.php
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
session_start();

$title = "Admin Area Test Folders";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$fbase = __DIR__ . "/";

$myCall = $_SESSION['username'];
$debug = 'false';

include 'backend/header.php';
include_once '../config.php';
require_once 'backend/db.class.php';
require_once 'backend/java.php';
require_once 'backend/querybuilder.php';
include 'backend/filecheck.php';

error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    if (isset($_POST['submit'])) {
        echo "<div class='centered-content'>";
        $symlinkPath = $fbase.'cards/';
        $fileName = 'file.txt';
        writeAndDeleteFile($symlinkPath, $fileName);
        $symlinkPath = $fbase. 'Awards/';
        $fileName = 'file.txt';
        writeAndDeleteFile($symlinkPath, $fileName);
        $symlinkPath = $fbase.'cards-out/';
        $fileName = 'file.txt';
        writeAndDeleteFile($symlinkPath, $fileName);
        echo "</div>";
    }
}
?>


<div class='centered-content'>

    <div class="auto-style1"> Folder Checker
        <span class="auto-style3"><br></span>
    </div>

    <br><br>
    <form enctype="multipart/form-data" class="Txt_upload" method='POST' action='test_folders.php'>
        <input type='submit' name='submit' value="Start Test">
        <br>
        <br>
        <?php
        $handle = fopen(__DIR__ . "/logs/adminareawebcards.log", "a+");

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if ($line == "") {
                    //file blank
                } else {
                    echo "<div class='error'>Error: <br>";
                    echo $line . "</div>"; // process the line read.
                }
            }
        } else {
            // error opening the file.
        }
echo "</div>";

include 'backend/footer.php';

if ($debug == "true") {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
}

