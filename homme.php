<!DOCTYPE html>
<?php
session_start();
require_once 'inc/functions.php';

$conn = connect();
// Nom de la catégorie ciblée
$categorie = 'Homme';
$derniersProduitsHomme = getDerniersProduits('Homme');
$produitSpeciales= getProduitsOffreSpeciale('Homme');
$topProduits = getTopProduitsVendus('Homme');

// Requête pour récupérer les sports associés à cette catégorie
$stmt = $conn->prepare("
    SELECT DISTINCT sport 
    FROM categorie_sport 
    WHERE categorie_nom = :categorie
    ORDER BY sport ASC
");
$stmt->execute(['categorie' => $categorie]);
$sportsAssocies = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Déterminer le sport sélectionné dans l'URL
$sportSelectionne = isset($_GET['sport']) ? $_GET['sport'] : null;




// Appeler la fonction globale pour récupérer les produits filtrés
?>




<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catégorie Homme</title>
    <!-- Lien vers le CDN Bootstrap 5 -->
     <!-- CSS noUiSlider -->
<link href="https://cdn.jsdelivr.net/npm/nouislider@15.6.1/dist/nouislider.min.css" rel="stylesheet">
<link rel="stylesheet" href="images/style.css">

<!-- JS noUiSlider -->
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.6.1/dist/nouislider.min.js"></script>
<script src="images/index.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/product-card.css">
    <link rel="stylesheet" href="css/homme.css">


</head>
<body>
   
<header>
<?php include_once 'inc/navbar.php'; ?>
</header>
<nav class="navbar mt-5">
    <ul class="nav-center">
        <?php foreach ($sportsAssocies as $sport): ?>
            <li class="<?= ($sportSelectionne === $sport) ? 'active' : ''; ?>">
                <a href="produits.php?sport=<?= urlencode($sport); ?>&categorie=<?= urlencode($categorie); ?>">
                    <?= htmlspecialchars($sport); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<div class="banner-slider-wrapper">
  <button class="arrow left" onclick="prevSlide()">&#10094;</button>
  
  <div class="banner-slider" id="bannerSlider">
    <div class="slide">Our New Products!</div>
    <div class="slide">Discover the VibeSport Style</div>
    <div class="slide">Exclusive Offers for You</div>
    <div class="slide">Shop Now – Men's Collection</div>
  </div>

  <button class="arrow right" onclick="nextSlide()">&#10095;</button>
</div>





 

<section class="category-banner-slider section text-center mt-5 mb-5">
  <div class="slider-container">
    

  <div class="banner-slider-sports" id="bannerImageSlider">
    <div class="slidefemme">
        <video autoplay muted loop playsinline controls poster="poster.jpg">
            <source src="https://brand.assets.adidas.com/video/upload/f_auto:video,q_auto/if_w_gt_1920,w_1920/global_aclubs_home_arsenal_football_ss25_launch_onsite_mh_d_223b7a4ab3.mp4" 
                    type="video/mp4" media="(min-width: 960px)">
            <source src="https://brand.assets.adidas.com/video/upload/f_auto:video,q_auto/if_w_gt_960,w_960/global_aclubs_home_arsenal_football_ss25_launch_onsite_mh_t_c909bb3d95.mp4" 
                    type="video/mp4" media="(min-width: 768px)">
            <source src="https://brand.assets.adidas.com/video/upload/f_auto:video,q_auto/if_w_gt_768,w_768/global_aclubs_home_arsenal_football_ss25_launch_onsite_mh_m_b5239a07a0.mp4" 
                    type="video/mp4" media="(max-width: 767px)">
            Votre navigateur ne prend pas en charge les vidéos HTML5.
        </video>
    </div>
</div>

   
  </div>

</section>






<section class="product-section" id="latest-products">
  <h2 class="section-title">Derniers Produits</h2>
  <div class="product-grid">
    <?php foreach ($derniersProduitsHomme as $produit): ?>
      <a style="text-decoration: none;" href="produit.php?id=<?= $produit['id']; ?>" >
      <div class="product-card">
        <div class="image-wrapper">
          <span class="badge">Nouveau</span>
          <img src="<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom']); ?>" class="product-image">
        </div>
        <div class="product-info">
          <p class="product-name"><?= htmlspecialchars($produit['nom']); ?></p>
          <p class="product-price"><?= number_format($produit['prix'], 2); ?> €</p>
        </div>
      </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="product-section" id="best-sellers">
  <h2 class="section-title">Meilleures Ventes</h2>
  <div class="product-grid">
    <?php foreach ($topProduits as $produit): ?>
      <a style="text-decoration: none;" href="produit.php?id=<?= $produit['id']; ?>" >
      <div class="product-card">
        <div class="image-wrapper">
          <span class="badge">Top Vente</span>
          <img src="<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom']); ?>" class="product-image">
        </div>
        <div class="product-info">
          <p class="product-name"><?= htmlspecialchars($produit['nom']); ?></p>
          <p class="product-price"><?= htmlspecialchars($produit['prix']); ?> €</p>
          <button class="product-btn">Voir Détails</button>
        </div>
      </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>


<section class="product-section" id="special-offers">
  <h2 class="section-title">Offres Spéciales</h2>
  <div class="product-grid">
    <!-- Card -->
     <?php foreach($produitSpeciales as $produit):?>
      <a style="text-decoration: none;" href="produit.php?id=<?= $produit['id']; ?>" >
      <div class="product-card">
        <div class="image-wrapper">
          <span class="badge">Promo</span>
          <img src="<?= htmlspecialchars($produit['image']); ?>" alt="<?= htmlspecialchars($produit['nom']); ?>" class="product-image">
        </div>
        <div class="product-info">
          <p class="product-name"><?= htmlspecialchars($produit['nom']); ?></p>
          <p class="product-price"><?= number_format($produit['prix'], 2); ?> €</p>
          <button type="submit" class="product-btn">Voir Détails</button>
        </div>
      </div>
      </a>
    <?php endforeach; ?>
    

    <!-- Répéter pour d'autres produits -->
  </div>
</section>










<!-- Pied de page -->
 <footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p>&copy; 2025 Boutique en ligne. Tous droits réservés.</p>
        <p>
            <a href="contact.html" class="text-white">Contact</a> | 
            <a href="about.html" class="text-white">À propos</a>
        </p>
    </div>
</footer> 


<script>
  const priceSlider = document.getElementById('price-slider');
  const minInput = document.getElementById('min_price');
  const maxInput = document.getElementById('max_price');
  const minLabel = document.getElementById('min-label');
  const maxLabel = document.getElementById('max-label');

  const minVal = parseInt(minInput.value) || 0;
  const maxVal = parseInt(maxInput.value) || 100000;

  noUiSlider.create(priceSlider, {
    start: [minVal, maxVal],
    connect: true,
    step: 1000,
    range: {
      'min': 0,
      'max': 100000
    },
    format: {
      to: value => Math.round(value),
      from: value => Number(value)
    }
  });

  priceSlider.noUiSlider.on('update', function (values, handle) {
    const min = values[0];
    const max = values[1];

    minInput.value = min;
    maxInput.value = max;
    minLabel.textContent = `${min} DT`;
    maxLabel.textContent = `${max} DT`;
  });
</script>


<!-- Lien vers le CDN Bootstrap JS et dépendances -->
<script>
    let lastScroll = 0;
    const nav1 = document.getElementById('header');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        if (currentScroll > lastScroll) {
            // Scroll vers le bas → cacher nav1
            nav1.style.top = '-100px'; // ou hauteur de nav1
        } else {
            // Scroll vers le haut → réafficher nav1
            nav1.style.top = '0';
        }

        lastScroll = currentScroll;
    });
</script>

<script>
  let currentIndex = 0;
  const slides = document.querySelectorAll('.slide');
  const slider = document.getElementById('bannerSlider');

  function showSlide(index) {
    if (index >= slides.length) currentIndex = 0;
    else if (index < 0) currentIndex = slides.length - 1;
    else currentIndex = index;
    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
  }

  function nextSlide() {
    showSlide(currentIndex + 1);
  }

  function prevSlide() {
    showSlide(currentIndex - 1);
  }

  // Auto-slide every 5 seconds
  setInterval(nextSlide, 3000);
</script>

<script>
  let bannerIndex = 0;
  const banners = document.querySelectorAll('.banner-slider-sports .slidehomme');
  const bannerSlider = document.querySelector('.banner-slider-sports');

  function showBanner(index) {
    if (index >= banners.length) bannerIndex = 0;
    else if (index < 0) bannerIndex = banners.length - 1;
    else bannerIndex = index;

    bannerSlider.style.transform = `translateX(-${bannerIndex * 100}%)`;
  }

  function nextBanner() {
    showBanner(bannerIndex + 1);
  }

  function prevBanner() {
    showBanner(bannerIndex - 1);
  }

  // Auto-slide toutes les 5 secondes
  setInterval(nextBanner, 5000);
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

