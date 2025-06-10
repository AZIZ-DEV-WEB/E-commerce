
<div class="text text-primary">
<a href="liste.php" class="btn btn-primary">Retourner vers la page précédente</a>
</div>
<?php
session_start();


// 1. Récupération des données du formulaire
$nom = $_POST['nom'] ?? '';
$description = $_POST['description'] ?? '';

// Vérifie si les champs sont remplis
if (empty($nom) || empty($description)) {
    die("Veuillez remplir tous les champs.");
}

// 2. Inclure les fonctions et se connecter à la base de données
include_once '../../inc/functions.php';
$conn = connect(); // Cette fonction retourne un objet PDO

// Vérification si la catégorie existe déjà
$sql_check = "SELECT COUNT(*) FROM categorie WHERE nom = :nom";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([':nom' => $nom]);
$existingCategoryCount = $stmt_check->fetchColumn();

if ($existingCategoryCount > 0) {
   // Redirection avec un paramètre d'erreur
   header("Location: liste.php?erreur=nom_existe");
   exit;}

// 3. Informations supplémentaires
$createur = $_SESSION['admin_id'];
$date_creation = date('Y-m-d H:i:s');

// 4. Préparer et exécuter la requête avec PDO
$sql = "INSERT INTO categorie (nom, description, createur, date_creation) VALUES (:nom, :description, :createur, :date_creation)";
$stmt = $conn->prepare($sql);

try {
    $stmt->execute([
        ':nom' => $nom,
        ':description' => $description,
        ':createur' => $createur,
        ':date_creation' => $date_creation
    ]);

    // Redirection en cas de succès
    header("Location: liste.php?ajout=ok&nom=" . urlencode($nom));
    exit;

} catch (PDOException $e) {
    die("Erreur lors de l'insertion : " . $e->getMessage());
}
?>
