<?php
// Fichier : api_commandes_par_statut.php
header('Content-Type: application/json');
require_once 'data.php'; // Inclure la logique métier



echo json_encode(getCommandesParStatut());





?>

