<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video File Viewer</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1e1e1e; /* Dark background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #f0f0f0; /* Light text color */
        }
        .container {
            width: 70%;
            height: 80%;
            background: black; /* Darker container */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            text-align: center;
        }
        h1 {
            color: #4e97ff; /* Lighter blue */
            margin-bottom: 20px;
            font-size: 28px;
        }
        form {
            margin-bottom: 30px;
        }
        .password-container {
            position: relative;
            display: inline-block;
        }
        input[type="number"] {
            padding: 14px;
            width: 80%;
            max-width: 300px;
            border: 2px solid #444; /* Dark border */
            border-radius: 5px;
            background-color: #333; /* Dark input background */
            color: #f0f0f0; /* Light text color */
            transition: border-color 0.3s;
        }
        input[type="number"]:focus {
            border-color: #4e97ff; /* Lighter blue on focus */
            outline: none;
        }
        input[type="submit"] {
            padding: 12px 20px;
            margin-top: 10px;
            cursor: pointer;
            background-color: #4e97ff; /* Lighter blue */
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #3a7ac5; /* Darker blue on hover */
            transform: translateY(-2px);
        }
        .video-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            overflow-y: auto;
            max-height: 60vh;
            padding: 20px 0;
        }
        .video-item {
            background-color: #3a3a3a; /* Dark video item background */
            border-radius: 10px;
            padding: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            text-align: left;
            width: 320px;
        }
        .video-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.7);
        }
        video {
            width: 100%;
            border-radius: 5px;
        }
        p {
            margin: 10px 0 0;
            color: #ccc; /* Slightly lighter text for video descriptions */
            font-size: 14px;
        }
        .remove-button {
            background-color: #ff4d4d; /* Red for remove button */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .remove-button:hover {
            background-color: #e60000; /* Darker red on hover */
            transform: translateY(-2px);
        }
        .error-message {
            color: #ff4d4d; /* Red for error messages */
            margin-top: 10px;
        }
        #reloadButton {
            margin: 20px 0;
            padding: 10px 15px;
            cursor: pointer;
            background-color: #4e97ff; /* Lighter blue */
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        #reloadButton:hover {
            background-color: #3a7ac5; /* Darker blue on hover */
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="container">
<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Directory to scan
$dir = __DIR__ . '/data/';

// Supported video extensions
$videoExtensions = ['mkv', 'mp4', 'avi', 'mov', 'wmv'];

// Store the MD5 hashed password
$storedPasswordHash = '7b157cfbc45cc2adc6352bd920d5dc6b';   
$foundVideo = false;

// Function to check the password
function checkPassword($password) {
    global $storedPasswordHash;
    return md5($password) === $storedPasswordHash;
}

// Check if the password is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedPassword = $_POST['password'] ?? '';
    
    if (checkPassword($submittedPassword)) {
        if (is_dir($dir) && $dh = opendir($dir)) {
            echo "<h1>Video Files</h1>";
            echo "<button id='reloadButton' onclick='reloadVideos()'>Reload Videos</button>";
            echo "<div class='video-gallery'>";
            while (($file = readdir($dh)) !== false) {
                $filePath = $dir . $file;
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

                if (in_array(strtolower($fileExtension), $videoExtensions) && is_file($filePath)) {
echo "<div class='video-item'>
        <video controls>
            <source src='data/$file' type='video/$fileExtension'>
            Your browser does not support the video tag.
        </video>
        <p>$file</p>
        <button class='remove-button' onclick='removeVideo(this)'>Remove</button>
      </div>";


                    $foundVideo = true;
                }
            }
            closedir($dh);

            if (!$foundVideo) {
                echo "<p>No video files found in this directory.</p>";
            }
            echo "</div>";
            echo "<button id='reloadButton' onclick='reloadVideos()'>Reload Videos</button>";
        } else {
            echo "<p>Error opening directory: $dir</p>";
        }
    } else {
        echo "<p class='error-message'>Incorrect password. Please try again.</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['password']) || !checkPassword($_POST['password'])) {
    echo '<h1>Enter Password:</h1>';
    echo 'https://telegram.dog/+3D0E2fRQr685YjJl?fbclid=PAZXh0bgNhZW0CMTEAAabggeIJODeXbzYoDnV-NjdK7pLl5c4tey06SyyyC7fsT5-QbrjS0FK5pHw_aem_-9Vi1dWlaVvz2g-uTPoK5g'
    echo '<form method="post" action="">
            <div class="password-container">
                <input type="number" id="password" name="password" autocomplete="off" required>
                <div class="blur-overlay"></div>
            </div>
            <input type="submit" value="Submit">
          </form>';
}
?>

</div>
 
<script>
    let videoItems = []; // Array to hold video item elements

    function removeVideo(button) {
        const videoItem = button.parentElement; // Get the parent video item
        const video = videoItem.querySelector('video'); // Get the video element

        if (video) {
            video.pause(); // Pause the video
            video.currentTime = 0; // Reset the video to the start
        }
        
        videoItem.style.display = 'none'; // Hide the video item
    }

    function reloadVideos() {
        videoItems.forEach(item => {
            item.style.display = 'block'; // Show all video items
        });
    }

    // Store video items when the page loads
    window.onload = function() {
        const videoGallery = document.querySelector('.video-gallery');
        videoItems = Array.from(videoGallery.children); // Store the video items
    };
</script>

</body>
</html>
