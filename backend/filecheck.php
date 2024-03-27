<?php

function writeAndDeleteFile($Path, $fileName) {
    // Define the file path relative to the symbolic link
    $fullPath = $Path . $fileName;

    // Check if subfolders are accessible
    $subfolderAccessible = checkSubfolderAccess($Path);

    // If subfolders are not accessible, return an error message
    if (!$subfolderAccessible) {
        echo "<span style=\"color: red;\">Subfolders are not accessible for path: $Path</span><br>\n";
        return;
    }

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

function checkSubfolderAccess($path) {
    // Check if subfolders are accessible
    $subfolders = glob($path . '/*', GLOB_ONLYDIR);
    $subfoldersNotAccessible = [];
    foreach ($subfolders as $subfolder) {
        if (!is_readable($subfolder) || !is_writable($subfolder)) {
            $subfoldersNotAccessible[] = $subfolder;
        }
    }
    if (!empty($subfoldersNotAccessible)) {
        echo "<span style=\"color: red;\">Subfolder not accessible:</span><br>\n";
        foreach ($subfoldersNotAccessible as $subfolder) {
            echo "<span style=\"color: red;\">$subfolder</span><br>\n";
        }
        return false;
    }
    return true;
}
