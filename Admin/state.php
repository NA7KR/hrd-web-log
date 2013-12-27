 <?php
    require("common.php");  
   
	$query = "SELECT  COUNT(*) as Count, 
		COL_CALL as CallSign, 
		COL_MODE as Mode 
		FROM (SELECT DISTINCT COL_CALL, COL_MODE,COL_BAND FROM NA7KR.TABLE_HRD_CONTACTS_V01) as A
		GROUP BY COL_CALL,COL_MODE 
		ORDER BY COUNT(*) DESC";


    try 
    {  
        $stmt = $db->prepare($query); 
        $stmt->execute();
			$arrValues = $stmt->fetchAll(PDO::FETCH_ASSOC);
			// open the table
			print "<table wdith=\"100%\">\n";
			 print "<tr>\n";
			// add the table headers
			foreach ($arrValues[0] as $key => $useless){
				 print "<th>$key</th>";
			 }
			 print "</tr>";
			// display data
			foreach ($arrValues as $row){
				 print "<tr>";
				 foreach ($row as $key => $val){
					 print "<td>$val</td>";
				 }
				 print "</tr>\n";
			 }
			// close the table
			print "</table>\n";
    } 
    catch(PDOException $ex) 
    {  
        die("Failed to run query: " . $ex->getMessage()); 
    }  
    
	
?>