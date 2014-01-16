<script type="text/javascript">
    window.onload = function() {
            document.getElementById('input1').onchange = disablefield;
            document.getElementById('input2').onchange = disablefield;
            document.getElementById('input3').onchange = disablefield;
            document.getElementById('input1').checked = false;
            document.getElementById('input2').checked = false;
            document.getElementById('input3').checked = false;
            document.getElementById('Call').disabled = true;
            var radios = document.form1.Band;
            for (var i = 0; i < radios.length; i++) {
                    radios[i].disabled = true;
            }
            var radios = document.form1.Mode;
            for (var i = 0; i < radios.length; i++) {
                    radios[i].disabled = true;
            }

    }

    function disablefield()
    {
            if (document.getElementById('input1').checked == true ){
                    document.getElementById('Call').disabled = true;
                    var radios = document.form1.Band;
                    for (var i = 0; i < radios.length; i++) {
                            radios[i].disabled = false;
                    }
                    var radios = document.form1.Mode;
                    for (var i = 0; i < radios.length; i++) {
                            radios[i].disabled = true;
                    }


            }
            else if ( document.getElementById('input2').checked == true ){
                    document.getElementById('Call').disabled = true;
                    var radios = document.form1.Band;
                    for (var i = 0; i < radios.length; i++) {
                            radios[i].disabled = true;
                    }
                    var radios = document.form1.Mode;
                    for (var i = 0; i < radios.length; i++) {
                            radios[i].disabled = false;
                    }
            }

            else if (document.getElementById('input3').checked == true ){
                    document.getElementById('Call').disabled = false;
                    var radios = document.form1.Band;
                    for (var i = 0; i < radios.length; i++) {
                            radios[i].disabled = true;
                    }
                    var radios = document.form1.Mode;
                    for (var i = 0; i < radios.length; i++) {
                            radios[i].disabled = true;
                    }
            }
    <?php
    include_once (__DIR__ . '/../config.php');
    echo $google;
    ?>
}
</script>