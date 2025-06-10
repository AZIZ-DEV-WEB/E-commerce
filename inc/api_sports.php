<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");

try {
    $sql = "SELECT DISTINCT sport FROM produit WHERE sport IS NOT NULL AND sport <> '' ORDER BY sport ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $sports = $stmt->fetchAll(PDO::FETCH_COLUMN); // Renvoie un tableau simple
    echo json_encode($sports);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
