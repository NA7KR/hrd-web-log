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

if (!isset($_SESSION['username'])) {
    header("Location: login.php");

    exit;
}
$base = __DIR__ . "/";
$myCall = $_SESSION['username'];
$debug = 'false';
include ('backend/header.php');
include_once ( '../config.php');
//include ( 'counter.php');
require_once(  'backend/db.class.php');
require_once(  'backend/java.php');
require_once(  'backend/querybuilder.php');
include(  'backend/filecheck.php');
$Log = "";
error_reporting(E_ALL );

?>

   
    
    <body onload= "lookup('hidden');
            qsl('hidden');
            awards('hidden');
            desawards('hidden')" span class="background">
        <div class="auto-style1"> Hello welcome Admin Page..... 
            <span class="auto-style3"><br></span>
            <span class="auto-style4">My Call is <?php echo $myCall ?></span><br>
        </div>	
    <html> 


        <br><br>
        <form enctype="multipart/form-data" class="Txt_upload" method='POST'  action='dashboard.php'>
            <input type="radio" value='1' name="Log"  onclick="lookup('hidden');
                    qsl('hidden');
                    awards('visible');
                    desawards('visible')" > Upload Awards<br>
            <input type="radio" value='2' name="Log"  onclick="lookup('hidden');
                    qsl('visible');
                    awards('visible');
                    desawards('hidden')" > Upload Paper<br>
            <input type="radio" value='3' name="Log"  onclick="lookup('hidden');
                    qsl('visible');
                    awards('visible');
                    desawards('hidden')" > Upload Paper Back<br>
             <input type="radio" value='5' name="Log"  onclick="lookup('hidden');
                    qsl('visible');
                    awards('visible');
                    desawards('hidden')" > Upload Email<br>
            <input type="radio" value='6' name="Log"  onclick="lookup('hidden');
                    qsl('visible');
                    awards('visible');
                    desawards('hidden')" > Upload Email Back<br>
            <input type="radio" value='4' name="Log"  onclick="lookup('visible');
                    qsl('hidden');
                    awards('hidden');
                    desawards('hidden')"  > Look contact number<br>
             <input type="radio" value='7' name="Log"  onclick="lookup('visible');
                    qsl('hidden');
                    awards('hidden');
                    desawards('hidden')"  > Test folders<br>
            
            <br>
            <table width="30%" border="0" cellpadding="0" cellspacing="0"  >
                <tr valign="top">
                    <td style="border-width : 0px;"><span id="qsl" style="visibility:hidden"><label>Enter Contact Number :</label></span></td>
                    <td style="border-width : 0px;"><span id="qsl1" style="visibility:hidden"><input type="text"   name="contactno" ></span></td>
                </tr>

                <tr valign="top">
                    <td style="border-width : 0px;"><span id="lookup" style="visibility:hidden"><label>Enter Callsign :</label></span></td>
                    <td style="border-width : 0px;"><span id="lookup1" style="visibility:hidden"><input type="text"   name="callsign" ></span></td>
                </tr>

                <tr valign="top">
                    <td style="border-width : 0px;"><span id="desawards" style="visibility:hidden"><label>Enter Description :</label></span></td>
                    <td style="border-width : 0px;"><span id="desawards1" style="visibility:hidden"><input type="text"   name="awardsdes" ></span></td>
                </tr>

                <tr valign="top">
                    <td style="border-width : 0px;"><span id="awards" style="visibility:hidden"><label>Select file :</label></span></td>
                    <td style="border-width : 0px;"><span id="awards1" style="visibility:hidden"><input type='file'  name='file' ></span></td>
                </tr>
            </table>
            <br>
            <input type='submit' name='submit' value="upload here">
            <br>
            <br>
            <?php {
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
            }
            ?>
        </form>
    </body>
</html>

<?php


if ($debug == "true") {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
}
if (isset($_FILES['file'])) {
    // Example usage
   
    $LOG = $_POST['Log'];
    if ($LOG == 1) {
        $filePath = $base . "Awards/";
        $FileName = $_FILES["file"]["name"];
        $AwardsDes = $_POST['awardsdes'];
    } elseif ($LOG == 2) {
        $side = "F";
        $tbside = "COL_File_Path_F";
    } elseif ($LOG == 3) {
        $side = "B";
        $tbside = "COL_File_Path_B";
    } elseif ($LOG == 5) {
        $side = "EF";
        $tbside = "COL_File_Path_FE";
    } elseif ($LOG == 6) {
        $side = "EB";
        $tbside = "COL_File_Path_BE";
    }
    if (($LOG == 2) || ($LOG == 3) || ($LOG == 5)|| ($LOG == 6))
    {
        $Key = $_POST['contactno'];
        $i = intval($Key);
        if ($i == $Key) 
        {
            $fileMutiply = 1000;
            $FileNoGroup = (($Key / $fileMutiply) % $fileMutiply * $fileMutiply);
            $fileNoGroupHigh = $FileNoGroup + ($fileMutiply - 1);
            $filePath = $base . "cards/" . $FileNoGroup . "-" . $fileNoGroupHigh . "/";
            if (!file_exists('$filePath')) 
            {
                mkdir($filePath, 0777, true);
            }
                              
            $query = getSelect_welcome2($Key);
                try {
                    $results = $db->row($query);
                    } 
                    catch (Exception $e) 
                    {
                    echo "Query: " . $query ."<br>";
                    echo "Error executing query: " . $e->getMessage();
                }

            

            $CallSign .=$results[$db_COL_CALL];
            $FileName = $side . "-" . $Key . "-" . $CallSign . ".jpg";
        } 
        else 
        {
            echo "<div class='error'> Please Enter Number </div>";
        }
    }
    if (($LOG == 1) || ($LOG == 2) || ($LOG == 3) || ($LOG == 5) || ($LOG == 6)) 
    {
       
        {
            $upload_exts = "";
            $file_exts = array("jpg", "bmp", "jpeg", "gif", "png", "JPG");
            $upload_exts_tp = explode(".", $_FILES["file"]["name"]);
            $upload_exts = end($upload_exts_tp);
            if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 2000000) && in_array($upload_exts, $file_exts)) 
            {
                if ($_FILES["file"]["error"] > 0) 
                {
                    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
                } 
                else 
                {
                    if (is_writable($filePath)) {
                        echo "The folder is writable.<br>";
                    } else {
                    
                        echo "Cannot save to: " . $filePath . "<br>";
                        exit;        
                    }
                    echo "Upload: " . $FileName . "<br>";
                    if (file_exists($filePath . $FileName)) 
                    {
                        echo "<div class='error'>" . $filePath . $FileName . " already exists. " . "</div>";
                    } 
                    else 
                    {
                        move_uploaded_file($_FILES["file"]["tmp_name"], $filePath . $FileName);
                        echo "<div class='sucess'>" . "Stored in: " . $filePath . $FileName . "</div>";
                        // open the directory
                        $pathToThumbs = $filePath . "/thumbs/";
                        $dir = opendir($pathToThumbs);

                        // load image and get image size
                        $img = imagecreatefromjpeg("{$filePath}/{$FileName}");
                        $width = imagesx($img);
                        $height = imagesy($img);

                        // calculate thumbnail size
                        $thumbWidth = 100;
                        $new_height = floor($height * ( $thumbWidth / $width ));

                        // create a new temporary image
                        $tmp_img = imagecreatetruecolor($thumbWidth, $new_height);

                        // copy and resize old image into new image
                        imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $thumbWidth, $new_height, $width, $height);

                        // save thumbnail into a file
                        imagejpeg($tmp_img, "{$pathToThumbs}{$FileName}");
                        chmod("{$pathToThumbs}{$FileName}", 0644);
                        // close the directory
                        closedir($dir);
                        echo "<br> Saved File <br>";
                        echo $LOG . "<br>";

                        if ($LOG == 1) 
                        {
                            try {
                                // Prepare SQL statement to update optout status
                                $query = setSelect_Awards();
                                $params = array($AwardsDes, $FileName);
                                $action = 'update'; // Action type
                                $tablename = DB_Table_optout; // Table name
                                // Execute the update query

                                try{
                                    $affectedRows = $db->executeSQL($query, $params,  $action, $tablename);
                                }
                                catch (Exception $e) 
                                {
                                    echo "Query: " . $query ."<br>";
                                    echo "Error executing query: " . $e->getMessage();
                                }

                                echo "Query: " . $query . "Prams: " . $pathToThumbs . " and " . $FileName ;
                                if ($affectedRows > 0) {
                                    // Update successful
                                    ?>
                                    <div class="container" style="display: flex; justify-content: center; align-items: center;">
                                    <?php
                                   
                                    $message = '<h1 style="color:Blue;">Added</h1>';
                                    
                                    echo $message . "</div>";
                                } else {
                                    // Update failed
                                   echo "Update failed ". $query;
                                }
                            } catch (Exception $e) {
                                // Handle exceptions
                                echo "Error: " . $e->getMessage();
                            }
                            $Key = "";


                        } 
                        elseif (($LOG == 2) || ($LOG == 3)|| ($LOG == 5)|| ($LOG == 6)) 
                        {
                            $query = getSelect_welcome($Key);

                            try {
                                    $results = $db->select($query);
                                    } catch (Exception $e) {
                                    echo "Query: " . $query ."<br>";
                                    echo "Error executing query: " . $e->getMessage();
                                }
                            echo $query . '<br>';
                            echo $$results[$db_COL_PRIMARY_KEY]  ."<br>";
                            if ($results[$db_COL_PRIMARY_KEY] <> "") 
                            {
                                $query = getSelect_welcome_update($Key);
                                echo "Query " . $query . "<br>";
                                
                                 try {
                                    $update = $db->query($query);
                                    } catch (Exception $e) {
                                    echo "Query: " . $query ."<br>";
                                    echo "Error executing query: " . $e->getMessage();
                                }
} 
                            else 
                            {
                                echo "<br>";
                                $query = getSelect_welcome_insert($Key,$FileName);
                                echo "Query " . $query . "<br>";
                                 try {
                                    $update = $db->query($query);
                                    } catch (Exception $e) {
                                    echo "Query: " . $query ."<br>";
                                    echo "Error executing query: " . $e->getMessage();
                                }
                            }
                        }
                        echo  "$Key<br>";
                    }
                }
            }
            else 
            {
                echo "<div class='error'>Invalid file $FileName</div>";
            }
        }
       
    }
}
$LOG = "";
$callsign = "";
$QSLWORKED = "";
if (isset($_POST['Log'])) 
{
    $LOG = htmlspecialchars($_POST["Log"]);
}
if (isset($_POST['callsign'])) 
{
    $callsign = htmlspecialchars($_POST["callsign"]);
}


echo "<br>";
if ($LOG == 4  ) 
{
   
    $query = get_Dashboard_call($callsign);
    try {
        $results = $db->select($query);
    } catch (Exception $e) {
        echo "Query: " . $query ."<br>";
        echo "Error executing query: " . $e->getMessage();
    }
    
    foreach ($results as $row) 
    {
        $QSLWORKED .=$row[$db_COL_CALL] . ' Was worked on the ' . $row[$db_COL_BAND] . ' Band on ' . date("jS M Y", strtotime($row['COL_TIME_OFF'])) . ' Contact number is: ' . $row[$db_COL_PRIMARY_KEY] . '<br>';
    }
    echo $QSLWORKED;
}
if ($LOG == 7  ) 
{
    $symlinkPath = '/var/www/Working/cards/';
    $fileName = 'file.txt';
    writeAndDeleteFile($symlinkPath, $fileName);
    $symlinkPath = '/var/www/Working/Awards/';
    $fileName = 'file.txt';
    writeAndDeleteFile($symlinkPath, $fileName);
    $symlinkPath = '/var/www/Working/cards-out/';
    $fileName = 'file.txt';
    writeAndDeleteFile($symlinkPath, $fileName);
}
?>
<a href="memberlist.php">Memberlist</a><br> 
<a href="edit_account.php">Edit Account</a><br> 
</body>
</html>

