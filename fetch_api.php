<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $city = htmlspecialchars($city, ENT_QUOTES, 'UTF-8');

    if (empty($city)) {
        die("Error: City name cannot be empty.");
    }

    $url = "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($city) . "&count=1&language=en&format=json";

    $response = file_get_contents($url);

    if ($response === false) {
        die("Error: Failed to fetch data from the API.");
    }

    $result = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error: Invalid API response.");
    }

    header('Content-Type: application/json');
    echo json_encode($result, JSON_PRETTY_PRINT);
} else {
    die("Error: This script only accepts POST requests.");
}
?>