<?php
// Define the directory you want to list
$dir = "ny/";

// Open the directory
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        echo "<h1>Files in Directory</h1>";
        echo "<ul>";
        
        // Loop through the files
        while (($file = readdir($dh)) !== false) {
            // Skip . and .. directories
            if ($file != "." && $file != "..") {
                // Display a download link for each file
                echo "<li><a href='download.php?file=" . urlencode($file) . "'>" . htmlentities($file) . "</a></li>";
            }
        }
        
        echo "</ul>";
        closedir($dh);
    } else {
        echo "Unable to open directory.";
    }
} else {
    echo "Directory does not exist.";
}
?>

