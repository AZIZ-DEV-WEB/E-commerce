<?php
session_start();
require_once 'inc/functions.php';

if (!isset($_SESSION['visiteur_id'])) {
    header('Location: connexion.php');
    exit;
}

$conn = connect();






$visiteur_id = $_SESSION['visiteur_id'];
$panier_id = $_GET['panier_id'] ?? null;

if (!$panier_id) {
    echo "<div class='alert alert-warning'>Aucun panier sélectionné pour le paiement.</div>";
    exit;
}

// Récupérer les commandes du panier
$stmt = $conn->prepare("
    SELECT c.id, p.nom AS produit, c.quantite, c.total 
    FROM commande c
    JOIN produit p ON c.produit = p.id
    WHERE c.panier = :panier_id
");
$stmt->execute([':panier_id' => $panier_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_panier = array_reduce($commandes, function ($carry, $item) {
    return $carry + $item['total'];
}, 0);

// Vérifier que le visiteur existe
$stmt = $conn->prepare("SELECT code_carte FROM visiteurs WHERE id = :visiteur_id");
$stmt->execute([':visiteur_id' => $visiteur_id]);
$visiteur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$visiteur) {
    echo "<div class='alert alert-danger'>Erreur : Visiteur introuvable.</div>";
    exit;
}

$code_carte_hash = $visiteur['code_carte'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Paiement</h1>
    <h3>Résumé de votre panier</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= htmlspecialchars($commande['produit']) ?></td>
                    <td><?= htmlspecialchars($commande['quantite']) ?></td>
                    <td><?= htmlspecialchars($commande['total']) ?> DT</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h4>Total à payer : <strong><?= htmlspecialchars($total_panier) ?> DT</strong></h4>

    <h3>Choisissez votre méthode de paiement</h3>
    <form action="finaliser_paiement.php" method="POST" id="paiementForm">
        <input type="hidden" name="panier_id" value="<?= htmlspecialchars($panier_id) ?>">

        <div class="mb-3">
            <label class="form-label">Méthode de paiement</label>
            <div class="d-flex flex-column">
                <?php
                $methods = [
                    'card' => 'Carte bancaire',
                    'cod' => 'Paiement à la livraison'
                ];
           foreach ($methods as $key => $label): ?>
    <label class="form-check-label d-flex align-items-center mb-3" style="gap: 10px;">
        <input type="radio" class="form-check-input me-2" name="method" value="<?= $key ?>" required>
        <img src="images/<?= $key ?>.png" alt="<?= $label ?>" style="width: 30px; height: auto;">
        <span><?= $label ?></span>
    </label>
<?php endforeach; ?>

            </div>
        </div>

        <div class="mb-3" id="codeCarteDiv" style="display: none;">
            <label for="code_carte" class="form-label">Code de carte (8 chiffres)</label>
            <input type="password" class="form-control" id="code_carte" name="code_carte" maxlength="8" pattern="\d{8}">
            <div id="codeCarteError" class="text-danger mt-2" style="display: none;">Le code de carte doit contenir exactement 8 chiffres.</div>
        </div>

        <button type="submit" class="btn btn-success">Payer</button>
    </form>

 
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const methodInputs = document.querySelectorAll('input[name="method"]');
    const codeCarteDiv = document.getElementById("codeCarteDiv");
    const codeCarteInput = document.getElementById("code_carte");
    const codeCarteError = document.getElementById("codeCarteError");

    methodInputs.forEach(input => {
        input.addEventListener("change", function () {
            if (this.value !== "cod") {
                codeCarteDiv.style.display = "block";
                codeCarteInput.setAttribute("required", "required");
            } else {
                codeCarteDiv.style.display = "none";
                codeCarteInput.removeAttribute("required");
                codeCarteError.style.display = "none";
            }
        });
    });

    document.getElementById('paiementForm').addEventListener('submit', function(event) {
        if (codeCarteDiv.style.display === "block") {
            const code = codeCarteInput.value.trim();
            if (!/^\d{8}$/.test(code)) {
                event.preventDefault();
                codeCarteError.style.display = 'block';
            } else {
                codeCarteError.style.display = 'none';
            }
        }
    });
});
</script>
</body>
</html>
