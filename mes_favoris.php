<?php
session_start();
include 'inc/functions.php';
$conn = connect();



if (!isset($_SESSION['visiteur_id'])) {
    header("Location: connexion.php");
    exit;
}

$visiteur_id = intval($_SESSION['visiteur_id']);

if (isset($_GET['supprimer'])) {
    $produit_id = intval($_GET['supprimer']);

    $deleteSql = "DELETE FROM favoris WHERE visiteur_id = :visiteur_id AND produit_id = :produit_id";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->execute([
        'visiteur_id' => $visiteur_id,
        'produit_id' => $produit_id
    ]);

    header("Location: mes_favoris.php");
    exit;
}


$sql = "
    SELECT p.*, f.date_ajout
    FROM favoris f
    JOIN produit p ON f.produit_id = p.id
    WHERE f.visiteur_id = :visiteur_id
    ORDER BY f.date_ajout DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute(['visiteur_id' => $visiteur_id]);
$favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Favoris</title>
  <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
  <link rel="stylesheet" href="cssglob.css">
</head>
<body>
  <?php include 'inc/navbar.php'; ?>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center mb-4">Mes Favoris</h1>
      </div>
    </div>
  </div>
  <div class="favoris-container">
    <?php if (count($favoris) > 0): ?>
      <?php foreach ($favoris as $produit): ?>
        <div class="product-card">
          <div class="image-wrapper">
            <img src="<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" class="product-image">
          </div>
          <div class="product-info">
            <h3><?= htmlspecialchars($produit['nom']) ?></h3>
            <p><?= number_format($produit['prix'], 2) ?> €</p>
            <a href="produit.php?id=<?= $produit['id'] ?>" class="btn">Voir Détails</a>
            <a href="mes_favoris.php?supprimer=<?= $produit['id'] ?>" class="btn delete-btn" onclick="return confirm('Supprimer ce favori ?')">Supprimer</a>

          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Vous n'avez aucun produit en favoris.</p>
    <?php endif; ?>
  </div>
</body>
</html>
