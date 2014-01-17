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
?>
<script type="text/javascript">
     window.onload = function() {
            document.getElementById('input1').onchange = disablefield;
            document.getElementById('input2').onchange = disablefield;
            document.getElementById('input3').onchange = disablefield; 
            document.getElementById('input1').checked = false;
            document.getElementById('input2').checked = false;
            document.getElementById('input3').checked = false;
            document.getElementById('Band').style.display = "none"
            document.getElementById('Mode').style.display = "none"
            document.getElementById('Call').style.display = "none"
    }

    function disablefield(){
            if (document.getElementById('input1').checked == true ){
               document.getElementById('Band').style.display = "block"
                document.getElementById('Mode').style.display = "none"
                document.getElementById('Call').style.display = "none"
            }
            else if ( document.getElementById('input2').checked == true ){
                document.getElementById('Band').style.display = "none"
                document.getElementById('Mode').style.display = "block"
                document.getElementById('Call').style.display = "none"
            }

            else if (document.getElementById('input3').checked == true ){
                document.getElementById('Band').style.display = "none"
                document.getElementById('Mode').style.display = "none"
                document.getElementById('Call').style.display = "block"
            }
     }
    <?php
    include_once (__DIR__ . '/../config.php');
    echo $google;
    ?>
</script>
