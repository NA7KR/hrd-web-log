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
<!DOCTYPE html>
<html lang="en">
    <head>
        <link type="text/css" rel="stylesheet" href="Admin/css/style.css" >
        <title>
            <?php
            include_once (__DIR__ . '/../config.php');
            echo $myCall . " Ham Radio LogBook</title>" . PHP_EOL;
            include ("java.php");
            //include_once("analyticstracking.php");
            require("backend.php");
            include "counter.php";
            // make/updates database views
            //MakeViews();
            ?>
            <meta name="keywords" content="Ham Radio NA7KR">
            <meta name="description" content="<?php echo $myCall ?> Ham Radio LogBook"> 
            <meta http-equiv="content-type" content="text/html;charset=UTF-8"> 
            <meta name="revisit-after" content="1 days">
            <META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
            <meta name="viewport" content="width=device-width">
	<!-- Matomo -->	
	<script type="text/javascript">
  		var _paq = window._paq || [];
  		/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  		_paq.push(['trackPageView']);
  		_paq.push(['enableLinkTracking']);
  		(function() {
    			var u="//counter.na7kr.us/";
    			_paq.push(['setTrackerUrl', u+'matomo.php']);
    			_paq.push(['setSiteId', '3']);
    			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    			g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  		})();
	</script>
	<!-- End Matomo Code -->

            </head>

            <body  class="background1">

                <div class="auto-style1"> Hello welcome my log book at reads from Ham Radio Deluxe log.. <span class="auto-style3"><br>
                    </span><span class="auto-style4">My Call is <?php echo qrzcom_interface($myCall) ?></span><br>
                </div>
                <br><br>
                <?php
                //echo safe("this%20is\na&nbsb;'test';");
                if (isset($_POST['Submit1'])) {
                    $LOG  = htmlspecialchars($_POST["Log"]);
                    $data = '<form name ="form1" method ="post" action ="index.php">' . PHP_EOL;
                    $data .= '<input type="hidden" name="Log" value=' . $LOG . '>' . PHP_EOL;
                    $data .= '<input type="hidden" name="Submit" value= "true">' . PHP_EOL;
                    $data .= '<input type="hidden" name="1st" value= "true">' . PHP_EOL;
                    echo $data;
                    $data = "";
                    include buildfiles($LOG);
                } else {
                    echo select();
                    ?>
                    <div>
						<p class="p,center"> Only band and modes in your log shows</p>
                        <p class="p.center"> This is a addon and not any way a part of HRD.</p>
                        <div>
							<P class="p.center"><img  alt="HRD" src="HRD_logo.jpg"></p>
						</div>
                        <p class="p.center"> Please contact 
							<a href="mailto:support@na7kr.us">
								<span class="auto-style2">support@na7kr.us</span>
							</a> and not HRD.
						</p>
					</div>

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
                    echo \OptionList(false, false, false, false, false, false) . \PHP_EOL;
                    $phpfile = __FILE__;
                    footer($phpfile);
                }
                ?>   				
                </html>
