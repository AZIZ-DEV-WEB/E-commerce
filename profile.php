<!DOCTYPE html>
<html lang="fr">
<?php 
session_start();
if (!isset($_SESSION['visiteur_nom'])) {
    header('location:connexion.php');
    exit;
}
include 'inc/functions.php';
// Connexion à la base de données
$conn=connect();
$visiteur_id = $_SESSION['visiteur_id'] ?? null;
$commandes = [];

if ($visiteur_id) {
    $stmt = $conn->prepare("SELECT * FROM panier WHERE visiteur_id = ? ORDER BY date_creation DESC");
    $stmt->execute([$visiteur_id]);
    $paniers = $stmt->fetchAll();
}
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Utilisateur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .profile-header {
      background: linear-gradient(to right, #0d6efd, #0dcaf0);
      color: white;
      padding: 40px 20px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    .profile-card {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
      margin-bottom: 40px;
    }

    .section-title {
      margin-bottom: 20px;
      font-weight: bold;
      color: #0d6efd;
    }
    .btn-sm {
      margin: 5px;
    }
    .orders-section {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }
    table th {
      background-color: #e9ecef;
    }
  </style>
</head>
<body>

<?php include 'inc/navbar.php'; ?>

<main class="container mt-5">

  <!-- Header -->
  <section class="profile-header text-center">
    <h2>Bienvenue, <?= htmlspecialchars($_SESSION['visiteur_prenom'] . " " . $_SESSION['visiteur_nom']); ?> !</h2>
    <p class="lead">Voici les informations liées à votre compte.</p>
  </section>

  <!-- Profil Utilisateur -->
  <section class="row justify-content-center">
    <div class="col-md-6 profile-card text-center">
      
      <h4><?= htmlspecialchars($_SESSION['visiteur_prenom'] . " " . $_SESSION['visiteur_nom']); ?></h4>
      <p>Email : <strong><?= htmlspecialchars($_SESSION['visiteur_email'] ?? 'non renseigné') ?></strong></p>
      <p>Téléphone : <strong><?= htmlspecialchars($_SESSION['visiteur_telephone'] ?? 'non renseigné') ?></strong></p>

      <div class="mt-3">
        <a href="modifier_profil.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil-square"></i> Modifier le profil</a>
        <a href="changer_mdp.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-lock"></i> Changer le mot de passe</a>
      </div>
    </div>
  </section>

  <!-- Commandes passées -->
  <section class="orders-section mt-4">
    <h5 class="section-title"><i class="bi bi-bag-check"></i> Vos commandes</h5>
    
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Numéro</th>
            <th>Date</th>
            <th>Montant</th>
            <th>Statut</th>
            <th>Détails</th>
          </tr>
        </thead>
        <tbody>
<?php foreach ($paniers as $panier): ?>

<?php
    $statut = $panier['statut'];
    $couleur = '';

    if ($statut === 'payé') {
        $couleur = 'success';
    } elseif ($statut === 'annule') {
        $couleur = 'danger';
    } elseif ($statut === 'enattente') {
        $couleur = 'primary';
    } else {
        $couleur = 'warning';
    }
?>

<tr>
    <td><?= $panier['id']; ?></td>
    <td><?= $panier['date_creation']; ?></td>
    <td><?= $panier['total']; ?> DT</td>
    <td><span class="badge bg-<?= $couleur ?>"><?= htmlspecialchars($statut); ?></span></td>
    <td>
        <!-- Bouton qui déclenche la modale -->
        <button 
            class="btn btn-sm btn-outline-info"
            data-bs-toggle="modal" 
            data-bs-target="#modalPanier<?= $panier['id']; ?>">
            Voir
        </button>
    </td>
</tr>

<!-- MODALE associée à ce panier -->
<div class="modal fade" id="modalPanier<?= $panier['id']; ?>" tabindex="-1" aria-labelledby="modalPanierLabel<?= $panier['id']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalPanierLabel<?= $panier['id']; ?>">Détails du panier #<?= $panier['id']; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
<ul class="list-group">
    <?php
    // Requête pour récupérer les produits commandés + quantités
    $stmt = $conn->prepare("
        SELECT p.nom, c.quantite,c.total
        FROM commande c
        JOIN produit p ON c.produit = p.id
        WHERE c.panier = ?
    ");
    $stmt->execute([$panier['id']]);
    $produits = $stmt->fetchAll();

    if (count($produits) > 0) {
        foreach ($produits as $produit) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo '<span><i class="bi bi-box-seam me-2"></i>' . htmlspecialchars($produit['nom']) . '</span>';
            echo '<span class="badge bg-primary rounded-pill">' . htmlspecialchars($produit['quantite']) . '</span>';
            echo '<span class="badge bg-secondary rounded-pill">' . htmlspecialchars($produit['total']) . ' DT</span>';
            echo '</li>';
        }
    } else {
        echo '<li class="list-group-item text-muted">Aucun article dans ce panier.</li>';
    }
    ?>
</ul>

        <!-- Tu peux ajouter ici les articles du panier si tu veux -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<?php endforeach; ?>


        </tbody>
      </table>
    </div>
  </section>

</main>

<?php include 'inc/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
