<?php
header('Content-Type: application/json');

$pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");

$categorie = $_GET['categorie'] ?? '';
$sport = $_GET['sport'] ?? '';
$type = $_GET['type_produit'] ?? '';

$query = "SELECT 
    p.id, p.nom, p.description, p.image, p.prix, 
    p.categorie, p.quantite_stock, p.sport, 
    p.type_produit_id, p.createur, p.date_creation, p.offre_speciale, 
    tp.nom AS type_produit_nom
FROM produit p
LEFT JOIN type_produit tp ON p.type_produit_id = tp.id
WHERE 1=1";


$params = [];

if ($categorie) {
    $query .= " AND p.categorie = :categorie";
    $params['categorie'] = $categorie;
}
if ($sport) {
    $query .= " AND p.sport = :sport";
    $params['sport'] = $sport;
}
if ($type) {
    $query .= " AND p.type_produit_id = :type";
    $params['type'] = $type;
}


$stmt = $pdo->prepare($query);
$stmt->execute($params);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($produits);
?>
