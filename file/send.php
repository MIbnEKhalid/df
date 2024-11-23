<?php

/*
 * File Name: send.php
 * Description: This file is used to send the file to server.
 */

// Configurations
set_time_limit(0); // Set the time limit to 0 to avoid timeout error
define("UPLOAD_DIR", "uploads/"); // Change this to your file uploading directory

// Checking if the form is submitted
if (isset($_POST) || isset($_FILES)) {
    // If user pasted URL
    if (!empty($_POST["link"])) {
        // Remote uploading
        $remoteUpload = remoteUpload($_POST["link"]);
        // Show remote upload result (it can be the file size or false)
        if ($remoteUpload !== false) {
            echo "File uploaded successfully. Size: " . $remoteUpload . " bytes.";
        } else {
            echo "Error: Could not download the file from the provided URL.";
        }
    } else {
        // Normal uploading
        // Getting form inputs
        $file = $_FILES["file"];
        // Uploading file through a function that will return a message
        $uploadMessage = loadUpload($file);
        echo $uploadMessage;
    }
} else {
    // Showing index.html file if $_POST or $_FILES are not set
    include './index.html';
    exit;
}


// Local uploading function
function loadUpload($file)
{
    if ($file["error"] !== UPLOAD_ERR_OK) {
        return "Error: " . getUploadError($file["error"]);
    }

    $fileName = $file["name"]; // File name
    $fileTmp = $file["tmp_name"]; // Temporary file name
    // Adding current time stamps to the filename (So this should be unique)
    $newFileName = time() . "_" . $fileName;

    // Uploading file to the path
    if (move_uploaded_file($fileTmp, UPLOAD_DIR . $newFileName)) {
        return "File uploaded successfully: " . $newFileName;
    } else {
        return "Error: Could not move the uploaded file.";
    }
}

// Remote uploading function to upload file from URL
function remoteUpload($url)
{
    // Use basename() function to return the base name of the file
    $fileName = time() . '_' . basename($url);

    // Use file_get_contents() function to get the file
    $fileContent = file_get_contents($url);
    if ($fileContent === false) {
        return false; // Could not retrieve the file
    }

    // Save the file by using base name
    $downloadFile = file_put_contents(UPLOAD_DIR . $fileName, $fileContent);
    if ($downloadFile) {
        // If file is downloaded successfully, then return the file size
        return filesize(UPLOAD_DIR . $fileName);
    } else {
        return false; // If file is not downloaded
    }
}

// Function to get upload error messages
function getUploadError($errorCode)
{
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
            return "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
        case UPLOAD_ERR_FORM_SIZE:
            return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
        case UPLOAD_ERR_PARTIAL:
            return "The uploaded file was only partially uploaded.";
        case UPLOAD_ERR_NO_FILE:
            return "No file was uploaded.";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Missing a temporary folder.";
        case UPLOAD_ERR_CANT_WRITE:
            return "Failed to write file to disk.";
        case UPLOAD_ERR_EXTENSION:
            return "File upload stopped by extension.";
        default:
            return "Unknown error.";
    }
}
