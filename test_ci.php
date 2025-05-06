<?php
// This script will test if the controllers can be accessed through CodeIgniter

// Define the controller class name
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'theme_settings';

// Create a URL to the controller
$url = "http://localhost/multisms/{$controller}";

// Make a request to the controller
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Display the results
echo "Testing controller: {$controller}<br>";
echo "URL: {$url}<br>";
echo "HTTP Code: {$httpCode}<br>";
echo "Response:<br><pre>" . htmlspecialchars($response) . "</pre>";
?>