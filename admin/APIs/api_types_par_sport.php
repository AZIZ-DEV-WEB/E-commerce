<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");
$sport = $_GET['sport'] ?? '';

if ($sport) {
  $stmt = $pdo->prepare("
    SELECT tp.id, tp.nom FROM sport_type_produit stp
    JOIN type_produit tp ON stp.type_produit_id = tp.id
    WHERE stp.sport = :sport ORDER BY tp.nom
  ");
  $stmt->execute(['sport' => $sport]);
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} else {
  echo json_encode([]);
}
?>