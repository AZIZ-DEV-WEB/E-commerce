
<?php



var_dump($_POST);
var_dump($_FILES);



session_start();
include_once '../../inc/functions.php';
$conn = connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification des champs
    if (
        isset($_POST['nom'], $_POST['description'], $_POST['prix'], $_FILES['image'], $_POST['categorie'],
        $_POST['quantite'], $_POST['createur'], $_POST['sportname'], $_POST['type_produit'])
    ) {
        $nom = htmlspecialchars($_POST['nom']);
        $description = htmlspecialchars($_POST['description']);
        $prix = floatval($_POST['prix']);
        $categorie = $_POST['categorie'];
        $quantite = intval($_POST['quantite']);
        $createur = intval($_POST['createur']);
        $sport = intval($_POST['sportname']);
        $type_produit = intval($_POST['type_produit']);

        // Gestion de l'upload de l'image
        $image = $_FILES['image'];
        $imageName = basename($image['name']);
        $imageTmpPath = $image['tmp_name'];
        $uploadDir = '../../images/';
        $targetPath = $uploadDir . $imageName;

        // Créer le dossier uploads s’il n’existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Vérifier si l’image est bien uploadée
        if (move_uploaded_file($imageTmpPath, $targetPath)) {
            try {
                $conn = connect();
                $sql = "INSERT INTO produit (nom, description, prix, image, categorie, quantite_stock, sport, type_produit_id, createur, date_creation)
                        VALUES (:nom, :description, :prix, :image, :categorie, :quantite, :sport, :type_produit, :createur, NOW())";
                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':prix', $prix);
                $stmt->bindParam(':image', $imageName);
                $stmt->bindParam(':categorie', $categorie);
                $stmt->bindParam(':quantite', $quantite);
                $stmt->bindParam(':sport', $sport);
                $stmt->bindParam(':type_produit', $type_produit);
                $stmt->bindParam(':createur', $createur);

                $stmt->execute();

                // Rediriger avec message de succès
                header('Location: liste.php?success=1&nom=' . urlencode($nom));
                exit;
            } catch (PDOException $e) {
                echo "Erreur lors de l'ajout : " . $e->getMessage();
            }
        } else {
            echo "Erreur lors de l'upload de l'image.";
        }
    } else {
        echo "Tous les champs sont requis.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>
