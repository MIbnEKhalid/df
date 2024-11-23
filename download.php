<?php
// Define the folder containing the files
$dir = "ny/";

if (isset($_GET['file'])) {
    $file = basename($_GET['file']); // Get the file name from the query string
    $filepath = $dir . $file;        // Full path to the file

    // Check if the file exists
    if (file_exists($filepath)) {
        // Set headers to download the file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        
        // Read the file and output its content
        readfile($filepath);
        exit;
    } else {
        echo "File does not exist.";
    }
} else {
    echo "No file specified.";
}
?>

