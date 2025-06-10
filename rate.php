<?php
include 'inc/functions.php';
$conn = connect();
session_start();
// Check if the user is logged in
$visiteur = isset($_SESSION['visiteur']) ? $_SESSION['visiteur'] : null;
$visiteur_nom = isset($_SESSION['visiteur_nom']) ? $_SESSION['visiteur_nom'] : null;
$visiteur_prenom = isset($_SESSION['visiteur_prenom']) ? $_SESSION['visiteur_prenom'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure produit_id is a valid integer and sanitize input
    $produit_id = isset($_POST['produit_id']) ? intval($_POST['produit_id']) : 0;
    
    // If no valid produit_id, redirect
    if ($produit_id <= 0) {
        header("Location: produit.php?erreur=1");
        exit;
    }
    
    // Default to 'Client Anonyme' if no name is provided
    $nom = !empty($_POST['nom']) ? trim($_POST['nom']) : ($visiteur_prenom ?? $visiteur_nom ?? 'Client Anonyme');

    // Get and validate the rating (note)
    $note = isset($_POST['note']) ? intval($_POST['note']) : 0;
    
    if ($note < 1 || $note > 5 || empty($_POST['message'])) {
        // Redirect to the product page with an error if validation fails
        header("Location: produit.php?id=$produit_id&erreur=1");
        exit;
    }

    // Sanitize the message input
    $message = trim($_POST['message']);
    
    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO avis (produit_id, nom, note, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$produit_id, $nom, $note, $message]);

    // Redirect to the product page with the new review
    header("Location: produit.php?id=$produit_id#avis");
    exit;
}
?>
