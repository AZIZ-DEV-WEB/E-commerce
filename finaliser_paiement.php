<?php
session_start();
require_once 'inc/functions.php';
$conn = connect();





$visiteur_id = $_SESSION['visiteur_id'] ?? null;
$panier_id = $_POST['panier_id'] ?? null;
$method = $_POST['method'] ?? null;
$code_carte = $_POST['code_carte'] ?? null;

if (!$visiteur_id || !$panier_id || !$method) {
    echo "<div class='alert alert-danger'>Tous les champs sont requis.</div>";
    // 🔁 En cas d'erreur, on peut remettre le statut à 'actif' si besoin
    $conn->prepare("UPDATE panier SET statut = 'actif' WHERE id = :id AND visiteur_id = :vid")
        ->execute([':id' => $panier_id, ':vid' => $visiteur_id]);
    exit;
}

if ($method !== 'cod') {
    $stmt = $conn->prepare("SELECT code_carte FROM visiteurs WHERE id = :visiteur_id");
    $stmt->execute([':visiteur_id' => $visiteur_id]);
    $visiteur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$visiteur || !password_verify($code_carte, $visiteur['code_carte'])) {
        echo "<div class='alert alert-danger'>Le code de carte est incorrect.</div>";
        // ❌ Paiement échoué → statut = 'annule'
        $conn->prepare("UPDATE panier SET statut = 'annule' WHERE id = :id AND visiteur_id = :vid")
            ->execute([':id' => $panier_id, ':vid' => $visiteur_id]);
        exit;
    }
}

// ✅ Paiement réussi → statut = 'livré'
$stmt = $conn->prepare("UPDATE panier SET statut = 'livré' WHERE id = :panier_id AND visiteur_id = :visiteur_id");
$stmt->execute([
    ':panier_id' => $panier_id,
    ':visiteur_id' => $visiteur_id
]);

header('Location: success.php?panier_id=' . $panier_id);
exit;
?>
