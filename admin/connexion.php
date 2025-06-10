<?php 
session_start();


// Inclusion des fonctions
include_once '../inc/functions.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mp = $_POST['mp'] ?? '';

    if (connectAdmin($email, $mp)) {
        header('Location: profile.php');
        exit;
    } else {
        echo '<div class="alert alert-danger text-center">Email ou mot de passe incorrect.</div>';
    }
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
// include '../inc/navbar.php'; // Inclusion de la barre de navigation ?>

<div class="container">
    <h1 class="text-center my-5">Connexion Administrateur</h1>
    <form class="p-5" action="connexion.php" method="post">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" required>
            <div id="emailHelp" class="form-text">Nous ne partagerons jamais votre email avec qui que ce soit.</div>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
            <input type="password" name="mp" class="form-control" id="exampleInputPassword1" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>

   

<?php include '../inc/footer.php'; // Inclusion du pied de page ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>