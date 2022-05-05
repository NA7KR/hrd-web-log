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
            document.getElementById('input_band').onchange = disablefield;
            document.getElementById('input_mode').onchange = disablefield;
            document.getElementById('input_search').onchange = disablefield;
            document.getElementById('input_state').onchange = disablefield; 
            document.getElementById('input_country').onchange = disablefield;
            document.getElementById('input_none').onchange = disablefield;
            document.getElementById('input_band').checked = false;
            document.getElementById('input_mode').checked = false;
            document.getElementById('input_search').checked = false;
            document.getElementById('input_state').checked = false;
            document.getElementById('input_country').checked = false;
            document.getElementById('input_none').checked = true;
            document.getElementById('Band').style.display = "none"
            document.getElementById('Mode').style.display = "none"
            document.getElementById('Call').style.display = "none"
            document.getElementById('State').style.display = "none"
            document.getElementById('Country').style.display = "none"
    }

    function disablefield(){
            if (document.getElementById('input_band').checked == true ){
               document.getElementById('Band').style.display = "block"
                document.getElementById('Mode').style.display = "none"
                document.getElementById('Call').style.display = "none"
                document.getElementById('State').style.display = "none"
                document.getElementById('Country').style.display = "none"
            }
            else if ( document.getElementById('input_mode').checked == true ){
                document.getElementById('Band').style.display = "none"
                document.getElementById('Mode').style.display = "block"
                document.getElementById('Call').style.display = "none"
                document.getElementById('State').style.display = "none"
                document.getElementById('Country').style.display = "none"
            }

            else if (document.getElementById('input_search').checked == true ){
                document.getElementById('Band').style.display = "none"
                document.getElementById('Mode').style.display = "none"
                document.getElementById('Call').style.display = "block"
                document.getElementById('State').style.display = "none"
                document.getElementById('Country').style.display = "none"
            }
            else if (document.getElementById('input_state').checked == true ){
                document.getElementById('Band').style.display = "none"
                document.getElementById('Mode').style.display = "none"
                document.getElementById('Call').style.display = "none"
                document.getElementById('State').style.display = "block"
                document.getElementById('Country').style.display = "none"
            }
            else if (document.getElementById('input_country').checked == true ){
                document.getElementById('Band').style.display = "none"
                document.getElementById('Mode').style.display = "none"
                document.getElementById('Call').style.display = "none"
                document.getElementById('State').style.display = "none"
                document.getElementById('Country').style.display = "block"
            }
            else if (document.getElementById('input_none').checked == true ){
                document.getElementById('Band').style.display = "none"
                document.getElementById('Mode').style.display = "none"
                document.getElementById('Call').style.display = "none"
                document.getElementById('State').style.display = "none"
                document.getElementById('Country').style.display = "none"
            }
     }
    <?php
    include_once (__DIR__ . '/../config.php');
    echo $google;
    ?>
</script>
