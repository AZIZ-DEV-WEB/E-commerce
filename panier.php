<?php
session_start();
require_once 'inc/functions.php';

if (!isset($_SESSION['visiteur_id'])) {
    header('Location: connexion.php');
    exit;
}
$conn = connect();


$visiteur_id = $_SESSION['visiteur_id'];
$panier_id = null;

//if ($visiteur_id) {
//     $stmt = $conn->prepare("SELECT id FROM panier WHERE visiteur_id = :visiteur_id AND statut = 'actif' LIMIT 1");
//     $stmt->execute([':visiteur_id' => $visiteur_id]);
//     $panier = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($panier) {
//         $panier_id = $panier['id'];
//     }
// }

if ($visiteur_id) {
    // 1. Préparer la requête
    $stmt = $conn->prepare("SELECT * FROM panier WHERE visiteur_id = ? AND statut IN ('actif', 'enattente') LIMIT 1");
    
    // 2. Exécuter la requête avec le visiteur_id
    $stmt->execute([$visiteur_id]);
    
    // 3. Récupérer le panier si trouvé
    if ($panier = $stmt->fetch()) {
        $panier_id = $panier['id'];
        //echo "Panier ID : " . $panier_id;
    } else {
        //echo "Aucun panier trouvé.";
        $panier_id = null;
    }

} else {
    echo "Visiteur non connecté.";
    exit;
}


// Supposons que $panier est déjà défini et contient le panier actif
if (isset($panier) && isset($panier['id'])) {
    $panier_id = $panier['id'];

    // 2. Récupérer les commandes du panier
    $stmt = $conn->prepare("
        SELECT c.id AS commande_id, c.quantite, p.nom, p.prix, p.image
        FROM commande c
        JOIN produit p ON p.id = c.produit
        WHERE c.panier = ?
    ");

    $stmt->execute([$panier_id]);
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calcul du total du panier (si total stocké dans $panier)
    $total = $panier['total'] ?? 0;
} else {
    // Gestion du cas où aucun panier actif n'est défini
    $commandes = [];
    $total = 0;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .card:hover {
        border: 1px solid #ffc107;
        box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
        transition: 0.3s ease;
    }
</style>

</head>
<body>
<?php include 'inc/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">🛒 Mon Panier</h2>

  <?php if (!empty($commandes)): ?>
    <form action="actions/update_panier.php" method="post">

        <?php foreach ($commandes as $commande): ?>
            <div class="card mb-3 shadow-sm">
                <div class="row g-0">
                    <div class="col-md-4 text-center p-3">
                        <img src="<?= htmlspecialchars($commande['image']) ?>" class="img-fluid rounded" style="max-height: 200px;" alt="Produit">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($commande['nom']) ?></h5>
                            <p class="card-text fw-bold"><?= number_format($commande['prix'], 2) ?> DT</p>
                            
                            <div class="mb-2">
                                <label for="quantite_<?= $commande['commande_id'] ?>" class="form-label">Quantité :</label>
                                <input type="number" name="quantites[<?= $commande['commande_id'] ?>]" id="quantite_<?= $commande['commande_id'] ?>" value="<?= $commande['quantite'] ?>" min="1" class="form-control w-25 d-inline" required>
                            </div>

                            <p class="card-text">Sous-total : <span class="fw-bold"><?= number_format($commande['prix'] * $commande['quantite'], 2) ?> DT</span></p>

                            <a href="actions/supprimer_commande.php?id=<?= $commande['commande_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">❌ Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="d-flex justify-content-between align-items-center my-4">
            <button type="submit" class="btn btn-warning">🔄 Mettre à jour le panier</button>
            <div class="fw-bold fs-4">Total : <?= number_format($total, 2) ?> DT</div>
        </div>
    </form>

    <div class="text-end">

<?php if (isset($panier_id) && $panier_id !== null): ?>
    <a href="actions/valider_panier.php?panier_id=<?= urlencode($panier_id) ?>" class="btn btn-success btn-lg">
        ✅ Passer à la caisse
    </a>
    <?php endif; ?>

<?php else: ?>

    <div class="alert alert-warning text-center">
        🛒 Votre panier est vide. Ajoutez des produits pour continuer.
    </div>
<?php endif; ?>

</div>

<?php include 'inc/footer.php'; ?>
</body>
</html>


