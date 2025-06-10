<?php
session_start();
if (!isset($_SESSION['visiteur_nom'])) {
    header('Location: connexion.php');
    exit;
}

// Connexion à la base de données
include_once 'inc/functions.php';
$conn=connect();

$id = $_SESSION['visiteur_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ancien_mdp = $_POST['ancien_mdp'];
    $nouveau_mdp = $_POST['nouveau_mdp'];
    $confirmer_mdp = $_POST['confirmer_mdp'];

    // Vérifier l'ancien mot de passe
    $stmt = $conn->prepare("SELECT mp FROM visiteurs WHERE id = ?");
    $stmt->execute([$id]);
    $visiteur = $stmt->fetch();

    if ($visiteur && password_verify($ancien_mdp, $visiteur['mp'])) {
        if ($nouveau_mdp === $confirmer_mdp) {
            $hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE visiteurs SET mp = ? WHERE id = ?");
            $stmt->execute([$hash, $id]);
            $message = "Mot de passe changé avec succès.";
        } else {
            $error = "Les nouveaux mots de passe ne correspondent pas.";
        }
    } else {
        $error = "Ancien mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Changer le mot de passe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'inc/navbar.php'; ?>

<div class="container mt-5">
  <h2>Changer le mot de passe</h2>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php elseif (isset($message)): ?>
    <div class="alert alert-success"><?= $message ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="ancien_mdp" class="form-label">Ancien mot de passe</label>
      <input type="password" class="form-control" name="ancien_mdp" required>
    </div>
    <div class="mb-3">
      <label for="nouveau_mdp" class="form-label">Nouveau mot de passe</label>
      <input type="password" class="form-control" name="nouveau_mdp" required>
    </div>
    <div class="mb-3">
      <label for="confirmer_mdp" class="form-label">Confirmer le mot de passe</label>
      <input type="password" class="form-control" name="confirmer_mdp" required>
    </div>
    <button type="submit" class="btn btn-primary">Changer</button>
    <a href="profil.php" class="btn btn-secondary">Annuler</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
