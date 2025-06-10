<?php
session_start();
require_once '../inc/functions.php';

if (!isset($_SESSION['visiteur_id'])) {
    header('Location: ../../connexion.php');
    exit;
}

$conn = connect();
$visiteur_id = $_SESSION['visiteur_id'];

// Vérifiez si un panier actif existe pour le visiteur
$stmt = $conn->prepare("SELECT id FROM panier WHERE visiteur_id = :visiteur_id AND statut = 'actif' LIMIT 1");
$stmt->execute([':visiteur_id' => $visiteur_id]);
$panier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$panier) {
    echo "<div class='alert alert-warning'>Aucun panier actif à valider.</div>";
    exit;
}

$panier_id = $panier['id'];

try {
    // ➕ Met à jour le statut en 'en_attente'
    $stmt = $conn->prepare("UPDATE panier SET statut = 'enattente' WHERE id = :panier_id");
    $stmt->execute([':panier_id' => $panier_id]);

    // Redirection vers la page checkout
    header("Location: ../checkout.php?panier_id=$panier_id");
    exit;
} catch (PDOException $e) {
    die("Erreur lors de la validation du panier : " . $e->getMessage());
}
?>
