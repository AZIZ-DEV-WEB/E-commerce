<?php
header('Content-Type: application/json');
require_once 'data.php';

$genre = isset($_GET['genre']) ? $_GET['genre'] : 'Homme';

echo json_encode(getProduitsParCategorie($genre));
