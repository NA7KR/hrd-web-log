<?php
/* * *************************************************************************
 * 			NA7KR Log Program 
 * ************************************************************************* */

/* * *************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * ************************************************************************* */
session_start();
if (empty($_SESSION['user'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link type="text/css" rel="stylesheet" href="css/style.css" />
        <script type="text/javascript">

            function db.class.php(visiState)
            {
                document.getElementById('db.class.php').style.visibility = visiState;
                document.getElementById('db.class.php1').style.visibility = visiState;
            }
            function qsl(visiState)
            {
                document.getElementById('qsl').style.visibility = visiState;
                document.getElementById('qsl1').style.visibility = visiState;
            }
            function awards(visiState)
            {
                document.getElementById('awards').style.visibility = visiState;
                document.getElementById('awards1').style.visibility = visiState;
            }
            function desawards(visiState)
            {
                document.getElementById('desawards').style.visibility = visiState;
                document.getElementById('desawards1').style.visibility = visiState;
            }
        </script>
        <meta name="keywords" content="Ham Radio NA7KR">
        <meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
        <meta name="description" content="<?php echo $myCall ?> Ham Radio LogBook"> 
        <meta http-equiv="content-type" content="text/html;charset=UTF-8"> 
        <meta name="revisit-after" content="30 days">
        <META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
        <?php
        include_once (__DIR__ . '/../../config.php');
        include (__DIR__ . '/../../counter.php');
        require_once(__DIR__ . '/../db.class.php.class.php');
        if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) { // if request is not secure, redirect to secure url
            $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('Location: ' . $url);
            exit;
        }
        ?>
        <title><?php echo $myCall ?> Ham Radio LogBook Upload / config</title> 
    </head>

    <body onload= "db.class.php('hidden');
            qsl('hidden');
            awards('hidden');
            desawards('hidden')" span class="background1">
        <div class="auto-style1"> Hello welcome Admin Page..... 
            <span class="auto-style3"><br></span>
            <span class="auto-style4">My Call is <?php echo $myCall ?></span><br>
        </div>	
    <html> 
        <h2><a href="logout.php">Sign Out</a></h2>

        <br><br>
        <form enctype="multipart/form-data" class="Txt_upload" method='POST'  action='welcome.php'>
            <input type="radio" value='1' name="Log"  onclick="db.class.php('hidden');
                    qsl('hidden');
                    awards('visible');
                    desawards('visible')" > Upload Awards
            <input type="radio" value='2' name="Log"  onclick="db.class.php('hidden');
                    qsl('visible');
                    awards('visible');
                    desawards('hidden')" > Upload Paper
            <input type="radio" value='3' name="Log"  onclick="db.class.php('hidden');
                    qsl('visible');
                    awards('visible');
                    desawards('hidden')" > Upload Paper Back
            <input type="radio" value='4' name="Log"  onclick="db.class.php('visible');
                    qsl('hidden');
                    awards('hidden');
                    desawards('hidden')"  > Look contact number
            <br>
            <table width="30%" border="0" cellpadding="0" cellspacing="0"  >
                <tr valign="top">
                    <td style="border-width : 0px;"><span id="qsl" style="visibility:hidden"><label>Enter Contact Number :</label></span></td>
                    <td style="border-width : 0px;"><span id="qsl1" style="visibility:hidden"><input type="text"   name="contactno" ></span></td>
                </tr>

                <tr valign="top">
                    <td style="border-width : 0px;"><span id="db.class.php" style="visibility:hidden"><label>Enter Callsign :</label></span></td>
                    <td style="border-width : 0px;"><span id="db.class.php1" style="visibility:hidden"><input type="text"   name="callsign" ></span></td>
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
                $handle = fopen("/srv/error.txt", "r");
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
$db = new Db();
if ($debug == "true") {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
}
if (isset($_FILES['file'])) {
    $LOG = $_POST['Log'];
    if ($LOG == 1) {
        $filePath = "Awards/";
        $FileName = $_FILES["file"]["name"];
        $AwardsDes = $_POST['awardsdes'];
    } elseif ($LOG == 2) {
        $side = "F";
        $tbside = "COL_File_Path_F";
    } elseif ($LOG == 3) {
        $side = "B";
        $tbside = "COL_File_Path_B";
    }
    if (($LOG == 2) || ($LOG == 3)) {
        $Key = $_POST['contactno'];
        $i = intval($Key);
        if ("$i" == "$Key") {
            $fileMutiply = 1000;
            $FileNoGroup = (($Key / $fileMutiply) % $fileMutiply * $fileMutiply);
            $fileNoGroupHigh = $FileNoGroup + ($fileMutiply - 1);
            $filePath = "/srv/cards/" . $FileNoGroup . "-" . $fileNoGroupHigh . "/";
            if (!file_exists('$filePath')) {
                mkdir($filePath, 0777, true);
            }

            $Key = $_POST['username'];
            $id_db.class.php = $db->row("SELECT COL_CALL FROM $dbnameHRD.$tbHRD where COL_PRIMARY_KEY ='$Key'");
            $CallSign .=$id_db.class.php['COL_CALL'];
            $FileName = $side . "-" . $Key . "-" . $CallSign . ".jpg";
        } else {
            echo "<div class='error'> Please Enter Number </div>";
        }
    }
    if (($LOG == 1) || ($LOG == 2) || ($LOG == 3)) {
        $upload_exts = "";
        $file_exts = array("jpg", "bmp", "jpeg", "gif", "png");
        $upload_exts = end(explode(".", $_FILES["file"]["name"]));
        if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 2000000) && in_array($upload_exts, $file_exts)) {
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
            } else {
                echo "Upload: " . $FileName . "<br>";
                if (file_exists($filePath . $FileName)) {
                    echo "<div class='error'>" . $filePath . $FileName . " already exists. " . "</div>";
                } else {
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

                    if ($LOG == 1) {
                        $update = $db->query("INSERT INTO `HRD_Web`.`tb_awards` (`COL_PRIMARY_KEY`, `COL_Award`, `COL_File`) VALUES ('NULL', '$AwardsDes', '$FileName'");
                    } elseif (($LOG == 2) || ($LOG == 3)) {

                        $id_db.class.php = $db->row("SELECT * FROM `HRD_Web`.`tb_Cards` WHERE `tb_Cards`.`COL_PRIMARY_KEY` = $Key ");
                        if ($id_db.class.php['COL_PRIMARY_KEY'] <> "") {
                            $update = $db->query("UPDATE `HRD_Web`.`tb_Cards` SET  `$tbside` = '$FileName' WHERE `tb_Cards`.`COL_PRIMARY_KEY` = $Key");
                        } else {
                            $update = $db->query("INSERT INTO `HRD_Web`.`tb_Cards` (`COL_PRIMARY_KEY`, `$tbside`) VALUES ( $Key, $FileName)");
                        }
                    }
                }
            }
        } else {
            echo "<div class='error'>Invalid file $FileName</div>";
        }
    }
}

$LOG = $_POST['Log'];
$callsign = $_POST['callsign'];
echo "<br>";
if ($LOG == 4) {
    $id_db.class.php = $db->query("SELECT `COL_CALL` , `COL_BAND`,`COL_TIME_OFF`,`COL_PRIMARY_KEY`FROM $dbnameHRD.$tbHRD WHERE `COL_CALL` ='$callsign'");
    foreach ($id_db.class.php as $row) {
        $QSLWORKED .=$row['COL_CALL'] . ' Was worked on the ' . $row['COL_BAND'] . ' Band on ' . date("jS M Y", strtotime($row['COL_TIME_OFF'])) . ' Contact number is: ' . $row['COL_PRIMARY_KEY'] . '<br>';
    }
    echo $QSLWORKED;
}
?>
<a href="memberlist.php">Memberlist</a><br> 
<a href="edit_account.php">Edit Account</a><br> 
</body>
</html>