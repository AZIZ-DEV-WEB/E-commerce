<?php
header('Content-Type: application/json');
require_once 'data.php';

$annee = $_GET['annee'] ?? date('Y');

echo json_encode(getChiffreAffairesParMois($annee));
