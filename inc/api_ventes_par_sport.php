<?php
header('Content-Type: application/json');
require_once 'data.php';

// Appeler la fonction et renvoyer les données
echo json_encode(getVentesParSport());
