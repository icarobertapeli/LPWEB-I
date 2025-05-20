<?php
$apiKey = "2f4b0955b9882844b0530bc1ebf253fd";
$tipo = $_GET["tipo"];
$query = trim($_GET["query"] ?? "");

if ($query) {
    $url = "https://api.themoviedb.org/3/search/$tipo?api_key=$apiKey&language=pt-BR&query=" . urlencode($query);
} else {
    $url = "https://api.themoviedb.org/3/$tipo/popular?api_key=$apiKey&language=pt-BR";
}

echo file_get_contents($url);
