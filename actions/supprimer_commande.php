<?php
session_start();
require_once '../inc/functions.php';

if (!isset($_SESSION['visiteur_id'])) {
    header('Location: ../connexion.php');
    exit;
}

$conn = connect();
$commande_id = $_GET['id'] ?? null;

if (!$commande_id || !filter_var($commande_id, FILTER_VALIDATE_INT)) {
    die("ID de commande invalide.");
}

try {
    // Récupérez le panier associé à la commande
    $stmt = $conn->prepare("SELECT panier FROM commande WHERE id = :id");
    $stmt->execute([':id' => $commande_id]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$commande) {
        die("Commande introuvable.");
    }

    $panier_id = $commande['panier'];

    // Supprimez la commande
    $stmt = $conn->prepare("DELETE FROM commande WHERE id = :id");
    $stmt->execute([':id' => $commande_id]);

    // Recalculez le total du panier
    $stmt = $conn->prepare("SELECT SUM(total) AS total_panier FROM commande WHERE panier = :panier_id");
    $stmt->execute([':panier_id' => $panier_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $nouveau_total = $result['total_panier'] ?? 0;

    // Mettez à jour le total dans la table panier
    $stmt = $conn->prepare("UPDATE panier SET total = :total WHERE id = :panier_id");
    $stmt->execute([
        ':total' => $nouveau_total,
        ':panier_id' => $panier_id
    ]);

    // Vérifiez s’il reste des commandes dans ce panier
    $stmt = $conn->prepare("SELECT COUNT(*) FROM commande WHERE panier = :panier_id");
    $stmt->execute([':panier_id' => $panier_id]);
    $nb_commandes_restantes = $stmt->fetchColumn();

    if ($nb_commandes_restantes == 0) {
        // Si le panier est vide, annulez-le
        $stmt = $conn->prepare("UPDATE panier SET statut = 'annule' WHERE id = :panier_id");
        $stmt->execute([':panier_id' => $panier_id]);
    }

    // Redirigez vers la page du panier
    header('Location: ../panier.php');
    exit;
} catch (PDOException $e) {
    die("Erreur lors de la suppression de la commande : " . $e->getMessage());
}
