<?php
// java.php
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

    function disablefield()
    {
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
     function lookup(visiState)
        {
            document.getElementById('lookup').style.visibility = visiState;
            document.getElementById('lookup1').style.visibility = visiState;
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
