<?php
header('Content-Type: application/json');
require_once 'data.php';

$annee = $_GET['annee'] ?? null;
$mois = $_GET['mois'] ?? null;
$SPORT = $_GET['sport'] ?? null;
$gender = $_GET['genre'] ?? null;

echo json_encode(getVentesMensuelles($annee, $mois, $SPORT, $gender));
?>
