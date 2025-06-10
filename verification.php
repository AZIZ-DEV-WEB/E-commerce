<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $panier_id = $_POST['panier_id'] ?? null;

    if (!$nom || !$adresse || !$telephone || !$panier_id) {
        die("Toutes les informations sont requises.");
    }

    // Traitez les informations de livraison ici (par exemple, enregistrez-les dans la base de données)

    // Redirigez vers la page de paiement
    header("Location: paiement.php?panier_id=$panier_id");
    exit;
}
?>