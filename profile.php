<!DOCTYPE html>
<html lang="en">
<?php 
session_start();



?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'inc/navbar.php'; ?>
<h1>Bienvenue <span class="text-primary"><?php echo $_SESSION['visiteur_prenom'] . " " . $_SESSION['visiteur_nom']; ?></span></h1>
<a href="deconnexion.php"></a>
</body>

<?php include 'inc/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>



</body>
</html>
