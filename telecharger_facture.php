<?php
ob_start(); // Démarre le tampon de sortie
session_start();

require_once 'inc/functions.php';
require('fpdf.php');

$conn = connect();

$visiteur_id = $_GET['visiteur_id'] ?? null;
$panier_id = $_GET['panier_id'] ?? null;

if (!$visiteur_id || !$panier_id) {
    die('Paramètres manquants.');
}

if (!class_exists('FPDF')) {
    die('La bibliothèque FPDF est absente.');
}

// Récupérer les infos du visiteur
$stmt = $conn->prepare("SELECT nom, email, adresse FROM visiteurs WHERE id = :visiteur_id");
$stmt->execute([':visiteur_id' => $visiteur_id]);
$visiteur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$visiteur) {
    die('Erreur : visiteur introuvable.');
}

// Récupérer les commandes de ce panier spécifique
$stmt = $conn->prepare("
    SELECT p.nom AS produit, c.quantite, c.total 
    FROM commande c
    JOIN produit p ON c.produit = p.id
    WHERE c.panier = :panier_id
");
$stmt->execute([':panier_id' => $panier_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($commandes)) {
    die('Aucune commande trouvée pour ce panier.');
}

// Calcul du total
$total_commande = array_sum(array_column($commandes, 'total'));

// Création PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Facture', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Nom : ' . $visiteur['nom'], 0, 1);
$pdf->Cell(0, 10, 'Email : ' . $visiteur['email'], 0, 1);
$pdf->Cell(0, 10, 'Adresse : ' . $visiteur['adresse'], 0, 1);
$pdf->Ln(10);

// Table des produits
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, 'Produit', 1);
$pdf->Cell(30, 10, 'Quantité', 1);
$pdf->Cell(30, 10, 'Total', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
foreach ($commandes as $commande) {
    $pdf->Cell(80, 10, $commande['produit'], 1);
    $pdf->Cell(30, 10, $commande['quantite'], 1);
    $pdf->Cell(30, 10, number_format($commande['total'], 2) . ' €', 1);
    $pdf->Ln();
}

// Total général
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(110, 10, 'Total', 1);
$pdf->Cell(30, 10, number_format($total_commande, 2) . ' €', 1);

ob_end_clean();
$pdf->Output('D', 'Facture-' . $visiteur_id . '-panier-' . $panier_id . '.pdf');
exit;
?>
