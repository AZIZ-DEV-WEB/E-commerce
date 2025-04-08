<!DOCTYPE html>
<html lang="en">
<?php 
include_once 'inc/functions.php';
$categories = getAllCategories(); 
$showRegistrationAlert = false;

if (!empty($_POST)) {
    if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['mp']) && !empty($_POST['telephone'])) {
        $user = AddVisiteur($_POST);
        if ($user) {
            $showRegistrationAlert = true;
        } else {
            echo "<script>alert('Erreur lors de l\'inscription.');</script>";
        }
    } else {
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'inc/navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Formulaire d'inscription</h2>
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Numéro de téléphone</label>
            <input type="tel" class="form-control" id="telephone" name="telephone">
            <div class="form-text">Exemple : +216 22 178 962</div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="mp" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
    </form>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="successModalLabel">Inscription réussie</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Votre compte a été créé avec succès !
      </div>
      <div class="modal-footer">
        <a href="login.php" class="btn btn-primary">Se connecter</a>
      </div>
    </div>
  </div>
</div>

<?php include 'inc/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($showRegistrationAlert): ?>
<script>
  const myModal = new bootstrap.Modal(document.getElementById('successModal'));
  window.addEventListener('load', () => {
    myModal.show();
  });
</script>
<?php endif; ?>

</body>
</html>
