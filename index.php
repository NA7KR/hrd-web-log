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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <link type="text/css" rel="stylesheet" href="Admin/css/style.css" >
        <title>
            <?php
            include_once (__DIR__ . '/../config.php');
            require("backend.php");
            include "counter.php";
            echo $myCall
            ?> 
            Ham Radio LogBook</title> 
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
            </span><span class="auto-style4">My Call is <?php echo qrzcom_interface($myCall) ?></span><br>
        </div>
        <br><br>
        <?php
        if (isset($_POST['Submit1'])) {
            $LOG = safe($_POST['Log']);
            $QTY = safe($_POST['Qty']);
            $BAND = safe($_POST['Band']);
            $MODE = safe($_POST['Mode']);
            $COUNTRY = safe($_POST['Country']);
            $CALL_SEARCH = safe($_POST['Call_Search']);
            if ($BAND == "_Any_Band_")
            {
                $BAND ="%";
            }
            if ($MODE == "_Any_Mode_")
            {
                $MODE ="%";
            }
          
            $CALL_SEARCH = safe($_POST['Call_Search']);
            include_once buildfiles($LOG);
            $data ='<FORM name ="form1" method ="post" action ="index.php">'. PHP_EOL;
            $data .= '<input type="hidden" name="Log" value=' .  $LOG . '>';
            echo $data;
        } else {
            echo select();
            ?>
            <div><p style="text-align: center"> Only band and modes in your log shows</p>
                <p style="text-align: center"> This is a addon and not any way a part of HRD.</p>
                <div><P style="text-align: center"><img  alt="HRD" src="HRD_logo.jpg"></p></div>
                <p style="text-align: center"> Please contact <a href="mailto:support@na7kr.us"><span class="auto-style2">support@na7kr.us</span></a> and not HRD.</p></div>

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
        <?php
        $phpfile = __FILE__ ;
        footer($phpfile);
        }
        
        ?>
       
</html>
   
       
