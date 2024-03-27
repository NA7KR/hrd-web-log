<?php 

include_once("../config.php");
include_once 'backend/db.class.php';
include_once 'backend/querybuilder.php';
include('counter.php');

// Define the indentation variable
$indentation = "\t"; // Use tab for indentation, you can change it as per your preference

if (!isset($_SESSION['username'])) {
    $isadmin = "False";
}
else {
    $isadmin = "True";
}


// Prepare and execute query
$query = getHeader();
try {
    $results = $db->select($query);
} catch (Exception $e) {
    echo "Query: " . $query ."<br>";
    echo "Error executing query: " . $e->getMessage();
}

// Assuming you have a way to determine the user's role, let's say it's stored in a session variable
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/style.css">

    <!-- Bootstrap JS (optional) -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/smoothqsl.js"></script>

    <?php
         //include 'backend/back.php';
    $java = isset($java) ? $java : false;

    if ($java) {
        include 'backend/java.php';
    }
?>
</head>
<body>
<header>
    <a href="#" class="logo"><?php echo $callsign; ?></a>
    <div class="menuToggle"></div>
    <nav>
        <ul>
            <?php 
            foreach ($results as $row) {
                if ($row['parent_id'] == 0) {
                    echo "<li>\n<a href='" . $row['item_link'] . "'>" . $row['item_text'];
                    $submenuQuery = getHeader_submenu();
                    $parent_id = $row['item_id'];
                    //$params = array(':parent_id' => $parent_id);
                    $params = [':parent_id' => $parent_id, ':isadmin' => $isadmin  ];
                    try {
                        $submenuItems = $db->select($submenuQuery,$params);
                    } catch (Exception $e) {
                        echo "Query: " . $submenuQuery ."<br>";
                        echo "Error executing query: " . $e->getMessage();
                        continue; // Skip to the next iteration if an error occurs
                    }
                  
                    if (!empty($submenuItems)) {
                        echo "<b>▼</b>";
                        echo "</a>\n<ul>\n";
                        foreach ($submenuItems as $submenu_row) {
                            echo "{$indentation}<li><a href='" . $submenu_row['item_link'] . "'>" . $submenu_row['item_text'];
                            $submenuQuery2 = getHeader_submenu();
                            $submenu_id = $submenu_row['item_id'];
                            //$params2 = array(':parent_id' => $submenu_id    );
                            $params2 = [':parent_id' => $submenu_id, ':isadmin' => $isadmin  ];
                        
                     
                            
                            
                            try {
                                $submenuItems2 = $db->select($submenuQuery2,$params2);
                            } catch (Exception $e) {
                                echo "Query: " . $submenuQuery2 ."<br>";
                                echo "Error executing query: " . $e->getMessage();
                                continue; // Skip to the next iteration if an error occurs
                            }
  
                            if (!empty($submenuItems2)) {
                                echo "<b>+</b></a><ul>\n";
                                foreach ($submenuItems2 as $submenu_row2) {
                                    echo "{$indentation}{$indentation}<li><a href='" . $submenu_row2['item_link'] . "'>" . $submenu_row2['item_text'] .  "</a></li>\n";
                                }
                                echo "{$indentation}</ul>\n";
                                echo "{$indentation}</li>\n";
                            } else {
                                echo "</a></li>\n";
                            }
                        }
                        echo "</ul>\n";
                    } else {
                        echo "</a>\n";
                    }
                    echo "</li>\n";
                }
            }

            // Check if the user is an admin and display admin features accordingly
            if ($user_role == 'admin') {
                echo "<li><a href='#'>Admin Feature</a></li>";
            }
            ?>
        </ul>
    </nav>
</header>
<main>
<br><br><br><br>
<h1><?php echo $title; ?></h1>
<div class=\"container\">

