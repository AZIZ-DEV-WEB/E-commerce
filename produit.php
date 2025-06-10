<?php
// Inclusion des fonctions (par exemple pour la connexion à la base de données)
include 'inc/functions.php';

// Démarrage de la session pour accéder à $_SESSION
session_start();

// Connexion à la base de données
$conn = connect();
$visiteur_id = isset($_SESSION['visiteur_id']) ? $_SESSION['visiteur_id'] : null;
$visiteur_nom = isset($_SESSION['visiteur_nom']) ? $_SESSION['visiteur_nom'] : 'Client Anonyme';
$visiteur_prenom = isset($_SESSION['visiteur_prenom']) ? $_SESSION['visiteur_prenom'] : '';


// Vérification si un ID de produit est passé dans l'URL et s'il est bien numérique
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id']; // Conversion en entier
    // Préparation de la requête SQL pour récupérer les infos du produit
    $stmt = $conn->prepare("SELECT * FROM produit WHERE id = ?");
    $stmt->execute([$id]);
    $produit = $stmt->fetch(); // Récupère une seule ligne

    // Si aucun produit n'est trouvé
    if (!$produit) {
        echo "Produit introuvable.";
        exit;
    }
} else {
    echo "ID invalide.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre de la page affichant le nom du produit -->
    <title><?= htmlspecialchars($produit['nom']); ?></title>

    <!-- Feuilles de style -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Style CSS personnalisé -->
    <style>
        /* Conteneur principal pour afficher les détails du produit */
        .produit-detail-container {
            display: flex;
            gap: 40px;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 40px auto;
            max-width: 1100px;
        }

        /* Image du produit */
        .image-section {
            width: 100%; /* La section occupe toute la largeur disponible */
            max-width: 400px; /* Largeur maximale de la section */
            height: 400px; /* Hauteur fixe pour la section */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f9f9f9; /* Couleur de fond pour encadrer l'image */
            border-radius: 10px; /* Coins arrondis */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre légère */
            overflow: hidden; /* Cache les parties débordantes de l'image */
        }

        .image-section img {
            width: 100%; /* L'image occupe toute la largeur de la section */
            height: 100%; /* L'image occupe toute la hauteur de la section */
            object-fit: cover; /* Ajuste l'image pour qu'elle remplisse l'espace sans déformation */
            border-radius: 10px; /* Coins arrondis pour l'image */
        }

        /* Section d'informations sur le produit */
        .info-section {
            flex: 1;
        }

        .info-section h1 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #222;
        }

        .info-section p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        /* Style du prix */
        .product-price {
            font-size: 24px;
            font-weight: bold;
            color: #e67e22;
        }

        /* Section des actions (boutons) */
        .actions {
            margin-top: 20px;
            display: flex;
            flex-direction: column; /* Aligne les boutons verticalement */
            align-items: left;
            gap: 10px; /* Espacement entre les boutons */
        }


        /* Bouton "Ajouter au panier" */
        .actions .btn-panier {
            background-color: #000; /* Couleur noire */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 50px; /* Forme arrondie */
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%; /* Prend toute la largeur disponible */
            max-width: 300px; /* Largeur maximale */
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .actions .btn-panier:hover {
            background-color: #333; /* Couleur légèrement plus claire au survol */
            transform: translateY(-2px); /* Légère élévation au survol */
        }

        /* Bouton "Ajouter aux favoris" */
        .actions .btn-favoris {
            background-color: transparent; /* Fond transparent */
            color: #000; /* Couleur noire pour le texte */
            padding: 12px 20px;
            border: 2px solid #ddd; /* Bordure grise */
            border-radius: 50px; /* Forme arrondie */
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%; /* Prend toute la largeur disponible */
            max-width: 300px; /* Largeur maximale */
            text-align: center;
            transition: border-color 0.3s ease, transform 0.2s ease;
        }

        .actions .btn-favoris:hover {
            border-color: #000; /* Bordure noire au survol */
            transform: translateY(-2px); /* Légère élévation au survol */
        }

        /* Formulaire d’avis */
        .avis-form {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .avis-form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        .avis-form input[type="text"],
        .avis-form textarea,
        .avis-form select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .avis-form button {
            margin-top: 20px;
            background-color: #27ae60;
            border: none;
            color: white;
            padding: 10px 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        .avis-form button:hover {
            background-color: #1e8449;
        }

        /* Titre des avis */
        #avis {
            font-size: 22px;
            margin: 40px 0 20px;
            text-align: center;
            color: #333;
        }

        /* Conteneur des avis */
        .avis-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); /* Deux colonnes minimum */
            gap: 20px; /* Espacement entre les avis */
            margin-top: 30px;
            justify-content: center;
            
        }

        /* Carte d’un avis */
        .avis-card {
            background: #fff;
            padding: 20px;
            border-left: 4px solid #3498db;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-bottom: 1px solid grey; /* Ligne de séparation */
        }

        .avis-card:hover {
            transform: translateY(-5px); /* Légère élévation au survol */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Ombre plus prononcée au survol */
        }

        .avis-card strong {
            font-size: 16px;
            color: #2c3e50;
        }

        .avis-card .stars {
            color: #f1c40f;
            font-size: 18px;
        }

        .avis-card p {
            margin: 10px 0;
            color: #555;
        }

        .avis-card small {
            color: #999;
            font-size: 12px;
        }

       /* Style de l'input quantité */
input[name="quantite_commander"] {
    width: 200px; /* Largeur fixe */
    justify-content: center; /* Centrer le texte */
    margin: 0 auto; /* Centrer horizontalement */
    margin-bottom: 20px; /* Espacement en bas */
    padding: 10px 20px ; /* Espacement interne */
    font-size: 1rem; /* Taille de la police */
    border: 1px solid #ccc; /* Bordure grise */
    border-radius: 40px; /* Coins arrondis */
    text-align: center; /* Centrer le texte */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Ombre légère */
    transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Transition pour les interactions */
}

/* Effet au survol */
input[name="quantite_commander"]:hover {
    border-color: #3498db; /* Bordure bleue au survol */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ombre plus prononcée */
}

/* Effet au focus */
input[name="quantite_commander"]:focus {
    border-color: #3498db; /* Bordure bleue au focus */
    outline: none; /* Supprime le contour par défaut */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Ombre plus prononcée */
}



    </style>
</head>

<body>
<?php include 'inc/navbar.php'; ?> <!-- Inclusion du menu de navigation -->


<!-- Affichage des détails du produit -->
<div class="produit-detail-container">
  <div class="image-section">
    <!-- Affichage de l'image -->
    <img src="<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom']); ?>" />
  </div>
  <div class="info-section">
    <!-- Nom du produit -->
    <h1><?= htmlspecialchars($produit['nom']); ?></h1>

    <!-- Prix du produit -->
    <p><?= number_format($produit['prix'], 2); ?> DT</p>

    <!-- Description du produit -->
    <p><?= nl2br(htmlspecialchars($produit['description'])); ?></p>
    <!-- Affichage des avis sur ce Produit -->

 <p><strong>Note moyenne :</strong> 
<?php
// Récupération de la note moyenne du produit
$stmtNote = $conn->prepare("SELECT ROUND(AVG(note), 1) AS moyenne FROM avis WHERE produit_id = ?");
$stmtNote->execute([$produit['id']]);
$noteMoyenne = $stmtNote->fetchColumn();

// Vérification de l'existence de note
if ($noteMoyenne !== null) {
    echo htmlspecialchars($noteMoyenne) . " ⭐";
} else {
    echo "Aucune note";
}
?>
</p>


    <!-- Bouton pour ajouter aux favoris -->
    <div class="actions">
<?php
// Récupération des IDs
$produit_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$visiteur_id = $_SESSION['visiteur_id'] ?? null;

// Vérifie si le produit est déjà en favoris
$deja_favori = false;
if ($visiteur_id) {
    $stmt = $conn->prepare("SELECT 1 FROM favoris WHERE visiteur_id = :visiteur_id AND produit_id = :produit_id");
    $stmt->execute(['visiteur_id' => $visiteur_id, 'produit_id' => $produit_id]);
    $deja_favori = $stmt->fetch() ? true : false;
}
?>

<?php if ($visiteur_id): ?>
  <?php if ($deja_favori): ?>
    <!-- Produit déjà ajouté -->
    <button type="submit"  disabled class="btn btn-favoris">❤️ Ajouter aux favoris</button>


  <?php else: ?>
    <!-- Formulaire pour ajouter aux favoris -->
    <form action="ajouter_favoris.php" method="POST">
      <input type="hidden" name="produit_id" value="<?= $produit_id ?>">
      <button type="submit" class="btn btn-favoris">❤️ Ajouter aux favoris</button>
    </form>
  <?php endif; ?>
<?php else: ?>
  <!-- Message pour les visiteurs non connectés -->
  <p>🔒 Connectez-vous pour ajouter ce produit à vos favoris.</p>
<?php endif; ?>



<form action="actions/commander.php" method="POST">
      <input type="hidden" name="id" value="<?= $produit['id']; ?>">
      <input type="number" step="1" placeholder="quantité" name="quantite_commander" value="1" min="1" required>
      <button type="submit" class="btn btn-panier">Ajouter au panier</button>
    </form>
    </div>
    <!-- Fin de la section actions -->
  </div>
  <!-- Fin de la section info produit -->


   
    <p>Stock disponible : <span style="color: green; font-weight: bold;"><?= $produit['quantite_stock']; ?></span></p>

  </div><!-- Fin de la section commande -->
  </div><!-- Fin de la section produit detail -->
 

      <!-- Formulaire d'avis -->
      <h3 style="text-align: center; text-decoration-line: underline;">Laisser un avis</h3>
    <form action="rate.php" method="post" class="avis-form">
      <label for="note">Note :</label>
      <select name="note" id="note" required>
        <option value="1">⭐</option>
        <option value="2">⭐⭐</option>
        <option value="3">⭐⭐⭐</option>
        <option value="4">⭐⭐⭐⭐</option>
        <option value="5">⭐⭐⭐⭐⭐</option>
      </select>

      <label for="message">Message :</label>
      <textarea name="message" id="message" required></textarea>
      <input type="hidden" name="visiteur_id" value="<?= $visiteur_id; ?>">
      <input type="hidden" name="visiteur_nom" value="<?= $visiteur_prenom; ?>">
      <input type="hidden" name="visiteur_nom" value="<?= $visiteur_prenom; ?>">



      <!-- ID du produit à noter -->
      <input type="hidden" name="produit_id" value="<?= $produit['id']; ?>">
     
      <!-- Bouton d’envoi -->
      <button type="submit">Envoyer l'avis</button>
    </form>


<!-- Section pour afficher les avis clients -->
<!-- Affichage des avis clients -->
<h3 id="avis">Avis des clients</h3>
<div class="avis-container">
<?php
// Requête pour récupérer tous les avis liés au produit
$stmtAvis = $conn->prepare("SELECT * FROM avis WHERE produit_id = ? ORDER BY date DESC");
$stmtAvis->execute([$produit['id']]);
$avis = $stmtAvis->fetchAll();

// Boucle pour afficher chaque avis
if ($avis):
  foreach ($avis as $a):
?>
  <div class="avis-card">
    <strong><?= htmlspecialchars($a['nom']); ?></strong>
    <p class="stars"><?= str_repeat("⭐", $a['note']); ?></p>
    <p><?= nl2br(htmlspecialchars($a['message'])); ?></p>
    <small><?= date('d/m/Y H:i', strtotime($a['date'])); ?></small>
  </div>
<?php
  endforeach;
else:
  echo "<p>Aucun avis pour le moment.</p>";
endif;
?>


<!-- <section class ="other-similar-products-section">
  <div class="container">
    <h2>Produits similaires</h2>
    <div class="row">
      <?php
      // Récupérer les produits similaires (par exemple, de la même catégorie)
      $stmtSimilar = $conn->prepare("SELECT * FROM produit WHERE categorie_id = ? AND id != ? LIMIT 4");
      $stmtSimilar->execute([$produit['categorie_id'], $produit['id']]);
      $similarProducts = $stmtSimilar->fetchAll();

      foreach ($similarProducts as $similarProduct):
      ?>
        <div class="col-md-3 mb-4">
          <div class="card">
            <img src="<?= htmlspecialchars($similarProduct['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($similarProduct['nom']); ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($similarProduct['nom']); ?></h5>
              <p class="card-text"><?= number_format($similarProduct['prix'], 2); ?> DT</p>
              <a href="produit.php?id=<?= $similarProduct['id']; ?>" class="btn btn-primary">Voir le produit</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section> -->

<!-- Script JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

<!-- Pied de page -->
<?php include 'inc/footer.php'; ?>
</body>
</html>
