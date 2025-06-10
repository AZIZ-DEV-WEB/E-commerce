<?php
session_start();
include 'inc/functions.php';

if (!isset($_SESSION['visiteur_id']) || !isset($_POST['produit_id'])) {
    header("Location: login.php");
    exit;
}

$visiteur_id = intval($_SESSION['visiteur_id']);
$produit_id = intval($_POST['produit_id']);

try {
    $conn = connect(); // PDO

    $stmt = $conn->prepare("INSERT IGNORE INTO favoris (visiteur_id, produit_id) VALUES (:visiteur_id, :produit_id)");
    $stmt->execute([
        'visiteur_id' => $visiteur_id,
        'produit_id' => $produit_id
    ]);

    header("Location: produit.php?id=" . $produit_id);
    exit;
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
