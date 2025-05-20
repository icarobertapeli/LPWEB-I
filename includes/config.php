<?php
$pdo = new PDO("mysql:host=localhost;dbname=tmdb_crud", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
