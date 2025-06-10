<?php
session_start();
if (!isset($_SESSION['visiteur_nom'])) {
    header('Location: connexion.php');
    exit;
}

// Connexion à la base de données
include_once 'inc/functions.php'; // 
$conn=connect();

$id = $_SESSION['visiteur_id']; // Assure-toi que tu stockes l'ID dans la session au moment de la connexion

// Récupérer les infos actuelles
$stmt = $conn->prepare("SELECT * FROM visiteurs WHERE id = ?");
$stmt->execute([$id]);
$visiteur = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    $stmt = $conn->prepare("UPDATE visiteurs SET prenom = ?, nom = ?, email = ?, telephone = ? WHERE id = ?");
    $stmt->execute([$prenom, $nom, $email, $telephone, $id]);

    // Mettre à jour la session
    $_SESSION['visiteur_prenom'] = $prenom;
    $_SESSION['visiteur_nom'] = $nom;
    $_SESSION['visiteur_email'] = $email;
    $_SESSION['visiteur_telephone'] = $telephone;

    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier le profil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'inc/navbar.php'; ?>

<div class="container mt-5">
  <h2 class="mb-4">Modifier mon profil</h2>
  <form method="POST">
    <div class="mb-3">
      <label for="prenom" class="form-label">Prénom</label>
      <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($visiteur['prenom']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="nom" class="form-label">Nom</label>
      <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($visiteur['nom']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($visiteur['email']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="telephone" class="form-label">Téléphone</label>
      <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($visiteur['telephone']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="profile.php" class="btn btn-secondary">Annuler</a>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
