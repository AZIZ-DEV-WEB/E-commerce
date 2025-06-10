<?php
include_once '../../inc/functions.php';
$conn = connect(); // Appel de la fonction connect()

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idstock = $_POST['idstock'] ?? null;
    $nouvelle_quantite_stock = $_POST['nouvelle_quantite_stock'] ?? null;

 

    try {
        // Mise à jour de la quantité
        $requette = "UPDATE produit SET quantite_stock = :nouvelle_quantite_stock WHERE id = :idstock";
        $stmt = $conn->prepare($requette);
        $stmt->bindParam(':idstock', $idstock, PDO::PARAM_INT);
        $stmt->bindParam(':nouvelle_quantite_stock', $nouvelle_quantite_stock, PDO::PARAM_INT);
        $stmt->execute();

        // Redirection vers la liste des produits avec un message de succès
        header('Location: liste.php?update=ok');
        exit;
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour : " . $e->getMessage());
    }
}
?>
