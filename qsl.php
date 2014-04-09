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
if ($argc != 2 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) {
    ?>

    This is a command line PHP script with one option.

    Usage:
    <?php echo $argv[0]; ?> <option>

    <option> Enter the QSL number. 
        With the --help, -help, -h,
        or -? options, you can get this help.

        <?php
    } else {
        include (__DIR__ . '/../config.php');
        require("db.class.php");
        $Key = $argv[1];
        $db = new Db();
        $id_lookup = $db->row("SELECT COL_File_Path_E FROM tb_Cards WHERE COL_PRIMARY_KEY = :f", array("f" => $Key));
        $qsl_lookup = $db->row("SELECT year(COL_TIME_OFF)as 'Year', month(COL_TIME_OFF)as 'Month', day(COL_TIME_OFF)as 'Day', hour(COL_TIME_OFF)as 'Hour', minute(COL_TIME_OFF) as 'Minute',COL_CALL as 'Call', COL_BAND as 'Band', COL_MODE as 'Mode' FROM NA7KR.TABLE_HRD_CONTACTS_V01  WHERE `COL_PRIMARY_KEY`   = $Key");
        echo $Key . "<br>";
        $Call_O = $qsl_lookup['COL_File_Path_E'];
        $Year = $qsl_lookup['Year'];
        $Month = $qsl_lookup['Month'];
        $Day = $qsl_lookup['Day'];
        $Hour = $qsl_lookup['Hour'];
        $Minute = $qsl_lookup['Minute'];
        $Band = $qsl_lookup['Band'];
        $Mode = $qsl_lookup['Mode'];
        $Call = $qsl_lookup['Call'];
        $Mode = str_replace("USB", "SSB", $Mode);
        $Mode = str_replace("LSB", "SSB", $Mode);
        $Call_R = str_replace("/", "-", $Call);
        $fileMutiply = 1000;
        $FileNoGroup = (($Key / $fileMutiply) % $fileMutiply * $fileMutiply);
        $fileNoGroupHigh = $FileNoGroup + ($fileMutiply - 1);
        $base = $base . 'cards/';
        $filePath = $base . $FileNoGroup . "-" . $fileNoGroupHigh;
        $pathToThumbs = $filePath . '/thumbs/';
        $Index = 'index.php';
        $HTaccess = '.htaccess';
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
            symlink($base . $Index, $filePath . "/" . $Index);
            symlink($base . $HTaccess, $filePath . "/" . $HTaccess);

            mkdir($pathToThumbs, 0777, true);
            symlink($base . $Index, $pathToThumbs . "/" . $Index);
            symlink($base . $HTaccess, $pathToThumbs . "/" . $HTaccess);
        }
        $FileName = "$filePath/E-$Key-$Call_R.jpg";
        if (file_exists($FileName)) {
            echo "The file $FileName exists \n";
        } else {
            echo "The file $FileName does not exist \n";
            include (__DIR__ . '/../config.php');
            $eqsl = "http://www.eqsl.cc/qslcard/GeteQSL.cfm?UserName=$myCall&Password=$EQSL&CallsignFrom=$Call&QSOBand=$Band&QSOMode=$Mode&QSOYear=$Year&QSOMonth=$Month&QSODay=$Day&QSOHour=$Hour&QSOMinute=$Minute";
            $str = file_get_contents($eqsl);
            $start1 = '<img src=';
            $end1 = ' alt="" />';

            $arr = array(
                "Error: You must specify the QSO Date/Time as QSOYear, QSOMonth, QSODay, QSOHour, and QSOMinute",
                "Error: No match on Username/Password for that QSO Date/Time",
                "Error: (n) overlapping accounts for that QSO Date/Time. User needs to correct that immediately",
                "Error: I cannot find that log entry",
                "Error: That QSO has been Rejected by (username)"
            );
            reset($arr);
            $error1 = 0;
            while (list(, $value) = each($arr)) {
                if (strpos($str, $value) !== false) {
                    $file = '/opt/error.txt'; // Open the file to get existing content 
                    $errormsg = file_get_contents($file); // Write the contents back to the file
                    $eqslp = str_replace("Password=$EQSL", "Password=PASSWORD", $eqsl);
                    $errormsg .= $FileName . " Error: " . $value . " Connection: " . $eqslp . "\r\n";
                    file_put_contents($file, $errormsg);
                    echo $value . "\n";
                    $error1 = 1;
                }
            }

            if ($error1 == 0) {
                $pic = getTexts($str, $start1, $end1);
                file_put_contents($FileName, file_get_contents("http://www.eqsl.cc/$pic"));
                chmod("$FileName", 0644);
                $FileName = "E-$Key-$Call_R.jpg";
                // open the directory
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
            }
        }
    }

    function getTexts($string, $start, $end) {
        $text = "";
        $posStart = strrpos($string, $start);
        $posEnd = strrpos($string, $end, $posStart);
        if ($posStart > 0 && $posEnd > 0) {
            $posStart = $posStart + strlen($start) + 2;
            $posEnd--;
            $text = substr($string, $posStart, $posEnd - $posStart);
        }
        return $text;
    }
    ?>
