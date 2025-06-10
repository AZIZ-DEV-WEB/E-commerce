<?php
session_start();

if (!isset($_SESSION['visiteur_nom'], $_SESSION['visiteur_id'])) {
    header('Location: ../../connexion.php');
    exit;
}

include_once '../inc/functions.php';

$idproduit = $_POST['id'] ?? null;
$quantite_commander = $_POST['quantite_commander'] ?? null;

if (!$idproduit || !$quantite_commander || !filter_var($quantite_commander, FILTER_VALIDATE_INT)) {
    die("Données invalides.");
}

$conn = connect();

// Vérification du produit
$stmt = $conn->prepare("SELECT * FROM produit WHERE id = :idproduit");
$stmt->execute([':idproduit' => $idproduit]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable.");
}

// Vérification du stock
$stmt = $conn->prepare("SELECT * FROM produit WHERE id = :idproduit");
$stmt->execute([':idproduit' => $idproduit]);
$stock = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$stock || $stock['quantite_stock'] < $quantite_commander) {
    die("Stock insuffisant.");
}

// Calcul du total
$total = $produit['prix'] * $quantite_commander;
$date = date('Y-m-d');
$visiteur_id = $_SESSION['visiteur_id'];

// Vérification d’un panier actif existant
$stmt = $conn->prepare("SELECT id FROM panier WHERE visiteur_id = :visiteur_id AND statut = 'actif'");
$stmt->execute([':visiteur_id' => $visiteur_id]);
$panier = $stmt->fetch(PDO::FETCH_ASSOC);

if ($panier) {
    $panier_id = $panier['id'];
    $stmt = $conn->prepare("UPDATE panier SET total = total + :total, date_modification = :date WHERE id = :id");
    $stmt->execute([
        ':total' => $total,
        ':date' => $date,
        ':id' => $panier_id
    ]);
} else {
    // Création d’un nouveau panier
    $stmt = $conn->prepare("INSERT INTO panier (visiteur_id, total, date_creation, date_modification, statut) 
                            VALUES (:visiteur_id, :total, :date, :date, 'actif')");
    $stmt->execute([
        ':visiteur_id' => $visiteur_id,
        ':total' => $total,
        ':date' => $date
    ]);
    $panier_id = $conn->lastInsertId();
}

// Vérifiez si le produit existe déjà dans le panier
$stmt = $conn->prepare("SELECT id, quantite FROM commande WHERE produit = :produit AND panier = :panier");
$stmt->execute([
    ':produit' => $idproduit,
    ':panier' => $panier_id
]);
$commande_existante = $stmt->fetch(PDO::FETCH_ASSOC);

if ($commande_existante) {
    // Si le produit existe déjà, mettez à jour la quantité
    $nouvelle_quantite = $commande_existante['quantite'] + $quantite_commander;
    $stmt = $conn->prepare("UPDATE commande SET quantite = :nouvelle_quantite, total = :total, date_modification = :date_modification WHERE id = :id");
    $stmt->execute([
        ':nouvelle_quantite' => $nouvelle_quantite,
        ':total' => $produit['prix'] * $nouvelle_quantite,
        ':date_modification' => $date,
        ':id' => $commande_existante['id']
    ]);
} else {
    // Si le produit n'existe pas, insérez une nouvelle commande
    $stmt = $conn->prepare("INSERT INTO commande (produit, quantite, panier, total, date_creation, date_modification) 
                            VALUES (:produit, :nouvelle_quantite, :panier, :total, :date_creation, :date_modification)");
    $stmt->execute([
        ':produit' => $idproduit,
        ':nouvelle_quantite' => $quantite_commander,
        ':panier' => $panier_id,
        ':total' => $total,
        ':date_creation' => $date,
        ':date_modification' => $date
    ]);
}

// Mise à jour du stock
$stmt = $conn->prepare("UPDATE produit SET quantite_stock = quantite_stock - :quantite_commander WHERE id = :idproduit");
$stmt->execute([
    ':quantite_commander' => $quantite_commander,
    ':idproduit' => $idproduit
]);

// 1. Tracking de l'action "ajout au panier"
$stmt = $conn->prepare("INSERT INTO conversion_stats (page_name, action) VALUES (:page, :action)");
$stmt->execute([
    'page' => 'produit.php',
    'action' => 'add_to_cart'
]);


// Redirection vers le panier
header('Location: ../../index.php');
exit;
?>
