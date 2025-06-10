<?php
session_start();
require_once '../../inc/functions.php';

// Vérifiez si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

// Vérifiez si l'ID du panier est fourni
if (!isset($_GET['panier_id']) || !filter_var($_GET['panier_id'], FILTER_VALIDATE_INT)) {
    echo "<div class='alert alert-danger'>ID de panier invalide.</div>";
    exit;
}

$panier_id = $_GET['panier_id'];
$commandes = getAllCommandes($panier_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Détails du Panier #<?= htmlspecialchars($panier_id); ?></h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($commandes)): ?>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= htmlspecialchars($commande['produit_nom']); ?></td>
                        <td><?= htmlspecialchars($commande['quantite']); ?></td>
                        <td><?= number_format($commande['total'], 2); ?> DT</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">Aucune commande trouvée pour ce panier.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="liste.php" class="btn btn-secondary">Retour à la liste des paniers</a>
</div>
</body>
</html>