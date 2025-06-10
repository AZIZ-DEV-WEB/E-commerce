<?php
include_once '../../inc/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $categorie = $_POST['categorie'];
    $quantite = $_POST['quantite'];
    $sport = $_POST['sport'];
    $type_produit_id = $_POST['type_produit_id'];
    $createur = $_POST['createur'];
    $offre_speciale = $_POST['offre_speciale'];


    $conn = connect(); // ta fonction qui retourne un objet PDO

    // Gestion de l’image (si une nouvelle image est uploadée)
    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $image_path = '../../images/' . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    } else {
        $image_name = $_POST['ancienne_image'];
    }

    try {
        $sql = "UPDATE produit SET 
                    nom = :nom, 
                    description = :description, 
                    prix = :prix, 
                    image = :image, 
                    categorie = :categorie, 
                    quantite_stock = :quantite, 
                    sport = :sport, 
                    type_produit_id = :type_produit_id, 
                    createur = :createur,
                    offre_speciale = :offre_speciale
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':prix' => $prix,
            ':image' => $image_name,
            ':categorie' => $categorie,
            ':quantite' => $quantite,
            ':sport' => $sport,
            ':type_produit_id' => $type_produit_id,
            ':createur' => $createur,
            ':offre_speciale' => $offre_speciale,
            ':id' => $id
        ]);

        header("Location: liste.php?update=ok&nom=" . urlencode($nom));
        exit;

    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}
?>
