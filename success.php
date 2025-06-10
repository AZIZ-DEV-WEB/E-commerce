<?php
session_start();
require_once 'inc/functions.php';
$conn = connect();
$visiteur_id = $_SESSION['visiteur_id'];

if (!isset($_SESSION['visiteur_id'])) {
    header('Location: connexion.php');
    exit;
}

$panier_id = $_GET['panier_id'] ?? null;

if (!$panier_id) {
    echo "<div class='alert alert-danger'>Aucun panier spécifié.</div>";
    exit;
}






// Récupérer les informations de l'utilisateur
$stmt = $conn->prepare("SELECT nom, email, adresse FROM visiteurs WHERE id = :visiteur_id");
$stmt->execute([':visiteur_id' => $visiteur_id]);
$visiteur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$visiteur) {
    echo "<div class='alert alert-danger'>Erreur : Utilisateur introuvable.</div>";
    exit;
}

// Récupérer les détails de la commande
$stmt = $conn->prepare("
    SELECT c.id, p.nom AS produit, c.quantite, c.total 
    FROM commande c
    JOIN produit p ON c.produit = p.id
    WHERE c.panier = :panier_id
");
$stmt->execute([':panier_id' => $panier_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$commandes) {
    echo "<div class='alert alert-danger'>Erreur : Aucune commande trouvée.</div>";
    exit;
}

// Calculer le total de la commande
$total_commande = 0;
foreach ($commandes as $commande) {
    $total_commande += $commande['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Succès</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h1>Merci pour votre commande, <?= htmlspecialchars($visiteur['nom']); ?> !</h1>
        <p>Votre Commande a été effectué avec succès.</p>
        <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
    </div>

    <!-- Section Facture -->
    <div class="card mt-4">
        <div class="card-header">
            <h2>Facture</h2>
        </div>
        <div class="card-body">
            <p><strong>Nom :</strong> <?= htmlspecialchars($visiteur['nom']); ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($visiteur['email']); ?></p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($visiteur['adresse']); ?></p>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td><?= htmlspecialchars($commande['produit']); ?></td>
                            <td><?= htmlspecialchars($commande['quantite']); ?></td>
                            <td><?= htmlspecialchars($commande['total']); ?> DT</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h4 class="text-end">Total : <?= htmlspecialchars($total_commande); ?> DT</h4>
        </div>
        <div class="card-footer text-center">
            <button onclick="window.print()" class="btn btn-secondary">Imprimer</button>
<a href="telecharger_facture.php?visiteur_id=<?= urlencode($visiteur_id); ?>&panier_id=<?= urlencode($panier_id); ?>" class="btn btn-success">Télécharger en PDF</a>
        </div>
    </div>
</div>
</body>
</html>