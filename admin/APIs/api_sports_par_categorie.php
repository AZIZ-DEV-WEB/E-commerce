<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");
$categorie = $_GET['categorie'] ?? '';

if ($categorie) {
  $stmt = $pdo->prepare("SELECT DISTINCT sport FROM categorie_sport WHERE categorie_nom = :cat ORDER BY sport");
  $stmt->execute(['cat' => $categorie]);
  echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
} else {
  echo json_encode([]);
}
?>
