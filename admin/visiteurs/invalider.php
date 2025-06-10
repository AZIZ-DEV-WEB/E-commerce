<?php
include_once '../../inc/functions.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn = connect();

    // Mettre à jour l'état de l'utilisateur
    $sql = "UPDATE visiteurs SET etat = 0 WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);

    // Rediriger avec un message de succès
    header('Location: liste.php?invalidation=ok');
    exit;
} else {
    // Rediriger avec un message d'erreur si l'ID est invalide
    header('Location: liste.php?erreur=id_invalide');
    exit;
}