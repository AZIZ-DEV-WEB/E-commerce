<?php
session_start();
include_once '../../inc/functions.php';
$conn = connect();

$idvisiteur = $_GET['id'] ?? null;

if (!filter_var($idvisiteur, FILTER_VALIDATE_INT)) {
    die("ID invalide.");
}

// Récupérer les informations du visiteur
$stmt = $conn->prepare("SELECT nom, prenom FROM visiteurs WHERE id = :idvisiteur");
$stmt->bindValue(':idvisiteur', $idvisiteur, PDO::PARAM_INT);
$stmt->execute();
$visiteur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$visiteur) {
    die("Visiteur introuvable.");
}


try {
    // Mettre à jour l'état du visiteur
    $requette = $conn->prepare("UPDATE visiteurs SET etat = 1 WHERE id = :idvisiteur");
    $requette->bindValue(':idvisiteur', $idvisiteur, PDO::PARAM_INT);
    $requette->execute();

    // Redirection avec le nom et le prénom dans l'URL
    header("Location: liste.php?validation=ok&nom=" . urlencode($visiteur['nom']) . "&prenom=" . urlencode($visiteur['prenom']));
    exit;
} catch (PDOException $e) {
    die("Erreur lors de la validation : " . $e->getMessage());
}
?>