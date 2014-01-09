<?php
#################################################
# 
# 
#################################################
    include_once (__DIR__ . '/../config.php');
    require_once('Lookup.class.php');
    require_once("backend.php");
    $db = new Db();
    $find = '.jpg';
    $i = 0; //style counter
    $filePath = "/Awards";
    $id_lookup = $db->query("SELECT COL_Award as Award, COL_File as 'File' FROM $dbnameWEB.tb_awards WHERE 1");
    $data = "<table border='0' align='center'><tbody><tr><th>Award</th><th>File</th></tr><tr bgcolor='#5e5eff'>". PHP_EOL;
    foreach ($id_lookup as $row): 
        {  
            $fileName = $row['File'];
            $data .=  "<td>" . $row['Award'] . "</td>" . PHP_EOL;
            $data .= "<td>" . "<A HREF='$filePath/$fileName'><IMG SRC='$filePath/thumbs/$fileName' alt='$fileName'></A>" . "</td>". grid_style($i) . PHP_EOL;
            $i++;   
            unset($row); // break the reference with the last element
        }
    endforeach;
    $data .= "</table><br><br>" . PHP_EOL;
    echo $data;
    $phpfile = __FILE__ ;
    footer($phpfile);
    
  
?>