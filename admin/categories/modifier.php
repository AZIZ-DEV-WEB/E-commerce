<?php
include_once '../../inc/functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    // Vérification si une autre catégorie avec le même nom existe
    $conn=connect();
    $sql_check = "SELECT COUNT(*) FROM categorie WHERE nom = :nom AND id != :id";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([':nom' => $nom, ':id' => $id]);
    $existingCategoryCount = $stmt_check->fetchColumn();

    if ($existingCategoryCount > 0) {
        // Redirection avec un paramètre d'erreur
        header("Location: liste.php?erreur=nom_existe");
        exit;
    }

    modifierCategorie($id, $nom, $description);

    header('Location: liste.php?update=ok');
    exit;
}
?>
