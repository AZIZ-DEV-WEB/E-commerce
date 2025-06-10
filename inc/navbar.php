<?php
require_once 'inc/functions.php';

$nombre_articles = 0;

if (isset($_SESSION['visiteur_id'])) {
    $conn = connect();
    $visiteur_id = $_SESSION['visiteur_id'];

    // Récupérer le panier actif
    $stmt = $conn->prepare("SELECT id FROM panier WHERE visiteur_id = :visiteur_id AND statut = 'actif' LIMIT 1");
    $stmt->execute([':visiteur_id' => $visiteur_id]);
    $panier = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($panier) {
        $panier_id = $panier['id'];

        // Compter le nombre total d'articles dans le panier
        $stmt = $conn->prepare("SELECT SUM(quantite) AS total FROM commande WHERE panier = :panier_id");
        $stmt->execute([':panier_id' => $panier_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_articles = $result['total'] ?? 0;
    }
}
?>

<head>
  <link rel="stylesheet" href="../css/navbar.css"> 
</head>


<div id="header">
    <nav class="top-navbar">
 
  <div class="navbar-right">
    <ul class="navbar-right-list">
    <?php if (!isset($_SESSION['visiteur_id'])): ?>
      <li> <a href="connexion.php">Sign In</a></li>
            <?php endif; ?>  
    <?php if (isset($_SESSION['visiteur_id'])): ?>
      <li><a href="deconnexion.php">Sign Out</a></li>
    <?php endif; ?>
    </ul>
  </div>
</nav>
    </div>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container-fluid navbar-sections">
    <!-- Logo -->
    <div class="navbar-left">
    <a href="index.php"><img src="images/vibesport.png" alt="" class="logo"></a>
  </div>

    
    <!-- Toggle button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse center-elements" id="navbarContent">
  <ul class="navbar-nav mx-auto">

    <!-- HOMME -->
    <li class="nav-item dropdown">
      <a class="nav-link" href="homme.php">Homme</a>
      <!-- <ul class="dropdown-menu animate-slide">
        
   
      </ul> -->
    </li>

    <!-- FEMME -->
    <li class="nav-item dropdown">
      <a class="nav-link" href="femme.php">Femme</a>
      <!-- <ul class="dropdown-menu animate-slide">
        <li><a class="dropdown-item" href="#"><i class="bi bi-gender-female me-2"></i>Brassières</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi bi-body-text me-2"></i>Leggings</a></li>
      </ul> -->
    </li>

    <!-- ENFANT -->
    <li class="nav-item dropdown">
      <a class="nav-link" href="kids.php">Enfant</a>
      <!-- <ul class="dropdown-menu animate-slide">
        <li><a class="dropdown-item" href="#"><i class="bi bi-emoji-smile me-2"></i>Garçons</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi bi-emoji-laughing me-2"></i>Filles</a></li>
      </ul> -->
    </li>



  </ul>
</div>


      <!-- Panier -->
      <div class="nav-item position-relative ms-3">
        <a class="nav-link" href="panier.php">
          <i class="bi bi-cart me-2"></i> Panier
        <?php if ($nombre_articles > 0): ?>
  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    <?= $nombre_articles ?>
  </span>
<?php endif; ?>


        </a>
      </div>



      <!-- Favoris -->

      <div class="nav-item position-relative ms-3">
    <a class="nav-link" href="mes_favoris.php">
      <i class="bi bi-heart me-2"></i> Mes Favoris
    </a>
  </div>

      <!-- Right: Avatar & Panier -->
    <div class="right-elements d-flex align-items-center">
      <!-- Avatar -->
      <div class="nav-item position-relative ms-3">
        <a class="nav-link" href="profile.php">
           <?php if (isset($_SESSION['visiteur_id'])): ?>
            <i class="bi bi-person-circle me-2"></i>
          <?= htmlspecialchars($_SESSION['visiteur_prenom']); ?>
        <?php endif; ?>
        </a>
      </div>



    </div>

  </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>

