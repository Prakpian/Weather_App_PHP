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

    $geocode = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error: Invalid API response.");
    }

    if (!isset($geocode['results'][0])) {
        die("Error: unknown city");
    }

    $latitude = $geocode['results'][0]['latitude'];
    $longitude = $geocode['results'][0]['longitude'];

    $weatherDataURL = "https://api.open-meteo.com/v1/forecast?latitude=" . urlencode($latitude) . "&longitude=" . urlencode($longitude) . "&current=temperature_2m";
    $weatherResponse = file_get_contents($weatherDataURL);
    $weatherData = json_decode($weatherResponse, true);
    $weatherTemperature = $weatherData["current"]["temperature_2m"];

    echo "<h2>$city</h2><br/><p>$weatherTemperature °C</p>";

} else {
    die("Error: This script only accepts POST requests.");
}