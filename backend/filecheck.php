<?php

// Function to check if a file exists in the main folder or its thumbs subfolder.
function check_file($folderPath, $fileName, $thumbs, $debug = false) {
    // Construct path to the main file
    $mainFilePath = $folderPath . DIRECTORY_SEPARATOR . $fileName;
    
    // Check if the file exists in the main folder
    if (file_exists($mainFilePath)) {
        return $mainFilePath;
    } else {
        // Determine the missing file path
        if (!$thumbs)
        {
            $missingFilePath =  "../images/Default.jpg";
        }
        else
        {
            $missingFilePath =  "../images/DefaultSmall.jpg";
        }
        
        // Send email notification
        send_email_notification($folderPath, $fileName, $missingFilePath);
        
        // Display debug message if enabled
        if ($debug) {
            echo "File $fileName is missing in $folderPath.<br>\n";
        } 
        return $missingFilePath;
    }
}


function showSymlinkSourceAndDest($path) {
    $source = '';
    $destination = '';
    
    // Check if the path exists and is a symbolic link
    if (file_exists($path) && is_link($path)) {
        $source = realpath($path); // Get the real path of the symbolic link
        $destination = readlink($path); // Get the destination of the symbolic link
    }
    
    return array('source' => $source, 'destination' => $destination);
}

function writeAndDeleteFile($Path, $fileName) {
    // Define the file path relative to the symbolic link
    $fullPath = $Path . $fileName;

    // Write data to the file
    $data = "Data to write to the file<br>\n";
    $result = @file_put_contents($fullPath, $data);

    if ($result !== false) {
        echo "Data written successfully to $fullPath<br>\n";

     
        // After testing, delete the file
        if (unlink($fullPath)) {
            echo "File deleted successfully.<br>\n";
        } else {
            echo "Error deleting file.<br>\n";
        }
    } else {
        // Handle the case where writing fails
        $symlinkInfo = showSymlinkSourceAndDest($Path);
        $source = $symlinkInfo['source'];
        $destination = $symlinkInfo['destination'];
        if ($source && $destination) {
            echo "Path '$fullPath' is a symbolic link.<br>\n";
            echo "Source: $source<br>\n";
            echo "Destination: $destination<br>\n";
        } else {
            // Check if the folder is a symlink
            $folderPath = dirname($fullPath); // Get the directory of the file
            $folderSymlinkInfo = showSymlinkSourceAndDest($folderPath);
            $folderSource = $folderSymlinkInfo['source'];
            $folderDestination = $folderSymlinkInfo['destination'];
            if ($folderSource && $folderDestination) {
                echo "Folder containing '$fullPath' is a symbolic link.<br>\n";
                echo "Source: $folderSource<br>\n";
                echo "Destination: $folderDestination<br>\n";
                echo "<span style=\"color: red;\">Error writing data to $folderSource<br></span>\n";
                echo "<span style=\"color: red;\">sudo chmod -R o+wx  $folderSource<br></span>\n";
            } else {
                echo "Path '$fullPath' is not a symbolic link.<br>\n";
            }
        }

        echo "<span style=\"color: red;\">Error writing data to $fullPath<br></span>\n";
        echo "<span style=\"color: red;\">sudo chmod -R o+w  $Path<br></span>\n";
        
    }
}