<?php
// Function to create a zip file
function zipFolder($folderPath, $zipFileName) {
    // Check if the folder exists
    if (!is_dir($folderPath)) {
        exit("Folder does not exist.");
    }

    // Initialize ZipArchive class
    $zip = new ZipArchive();
    
    // Open the zip file for writing (create if it doesn't exist)
    if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
        exit("Unable to create zip file.");
    }

    // Add the folder to the zip file
    $folderPath = realpath($folderPath);
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folderPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        // Skip directories (they will be added automatically)
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($folderPath) + 1);

            // Add the file to the zip archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Close the zip file
    $zip->close();
}

// Path to the folder you want to compress
$folderToZip = 'ny'; // Replace with your folder
$zipFileName = 'folder-download.zip';

// Create the zip file
zipFolder($folderToZip, $zipFileName);

// Serve the zip file for download
if (file_exists($zipFileName)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
    header('Content-Length: ' . filesize($zipFileName));
    readfile($zipFileName);

    // Optional: Delete the zip file after download
    unlink($zipFileName);
    exit;
} else {
    exit("Error creating the zip file.");
}
?>
