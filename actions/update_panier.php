<?php
session_start();
include_once '../inc/functions.php';

if (!isset($_SESSION['visiteur_id'])) {
    die("Non autorisé.");
}

$conn = connect();

// Vérifier la présence des quantités
if (!isset($_POST['quantites']) || !is_array($_POST['quantites'])) {
    die("Aucune donnée à traiter.");
}

$panier_id = null;

foreach ($_POST['quantites'] as $commande_id => $nouvelle_quantite) {
    // Valider les données
    if (!filter_var($nouvelle_quantite, FILTER_VALIDATE_INT) || $nouvelle_quantite < 1) {
        continue; // Ignorer les valeurs invalides
    }

    // Récupérer la commande
    $stmt = $conn->prepare("SELECT * FROM commande WHERE id = :id");
    $stmt->execute([':id' => $commande_id]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$commande) {
        continue;
    }

    $panier_id = $commande['panier']; // On enregistre le panier une seule fois
    $produit_id = $commande['produit'];

    // Récupérer le prix du produit
    $stmt = $conn->prepare("SELECT prix FROM produit WHERE id = :id");
    $stmt->execute([':id' => $produit_id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produit) {
        continue;
    }

    $prix_unitaire = $produit['prix'];
    $nouveau_total_commande = $prix_unitaire * $nouvelle_quantite;

    // Mise à jour de la commande
    $stmt = $conn->prepare("
        UPDATE commande 
        SET quantite = :quantite, total = :total, date_modification = NOW() 
        WHERE id = :id
    ");
    $stmt->execute([
        ':quantite' => $nouvelle_quantite,
        ':total' => $nouveau_total_commande,
        ':id' => $commande_id
    ]);
}

// Recalculer le total du panier uniquement s'il y a eu des mises à jour
if ($panier_id !== null) {
    $stmt = $conn->prepare("SELECT SUM(total) AS total_panier FROM commande WHERE panier = :panier_id");
    $stmt->execute([':panier_id' => $panier_id]);
    $total_panier = $stmt->fetch(PDO::FETCH_ASSOC)['total_panier'];

    // Mise à jour du total du panier
    $stmt = $conn->prepare("UPDATE panier SET total = :total, date_modification = NOW() WHERE id = :id");
    $stmt->execute([
        ':total' => $total_panier,
        ':id' => $panier_id
    ]);
}

header('Location: ../panier.php');
exit;
?>
