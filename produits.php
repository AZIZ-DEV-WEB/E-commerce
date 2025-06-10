<?php
include 'inc/functions.php'; // fichier contenant la fonction getProduitsFiltres()
$conn = connect(); // ta fonction qui retourne un objet PDO

$sport = $_GET['sport'] ?? null;
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : null;
$typeProduit = isset($_GET['type_produit']) ? $_GET['type_produit'] : null;
$prix_min = isset($_GET['prix_min']) && $_GET['prix_min'] !== '' ? (float)$_GET['prix_min'] : null;
$prix_max = isset($_GET['prix_max']) && $_GET['prix_max'] !== '' ? (float)$_GET['prix_max'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : null;

$produitsFiltres = getProduitsFiltres($sport, $categorie, $typeProduit, $prix_min, $prix_max, $search);

$sportsAssocies = [];

if ($categorie) {
    // Requête pour récupérer les sports associés à la catégorie
    $stmt = $conn->prepare("
        SELECT DISTINCT sport
        FROM categorie_sport
        WHERE categorie_nom = :categorie
    ");
    $stmt->execute(['categorie' => $categorie]);
    $sportsAssocies = $stmt->fetchAll(PDO::FETCH_COLUMN);
}


// Préparer la requête pour récupérer les types de produits associés au sport sélectionné
$stmt = $conn->prepare("
    SELECT tp.id, tp.nom 
    FROM type_produit tp
    INNER JOIN sport_type_produit stp ON tp.id = stp.type_produit_id
    WHERE stp.sport = :sport
");

// Exécuter la requête avec le paramètre de sport
$stmt->execute(['sport' => $sport]);

// Récupérer les résultats
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/nouislider@15.6.1/dist/nouislider.min.css" rel="stylesheet">

<!-- JS noUiSlider -->
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.6.1/dist/nouislider.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/product-card.css">
<style>
    body {
        background-color: #f8f9fa;
    }
/* Container card */
.sticky-sidebar {
    background: linear-gradient(145deg, #ffffff, #f3f3f3);
    border: none;
    border-radius: 1rem;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.sticky-sidebar:hover {
    transform: translateY(-5px);
}

/* Card body + spacing */
.sticky-sidebar .card-body {
    padding: 1.5rem;
}

/* Title */
.sticky-sidebar .card-title {
    font-weight: 700;
    font-size: 1.25rem;
    margin-bottom: 1rem;
    border-left: 4px solid #0d6efd;
    padding-left: 0.75rem;
}

/* Form labels */
.sticky-sidebar .form-label {
    font-weight: 600;
    color: #444;
    margin-bottom: 0.5rem;
}

/* Inputs + Selects */
.sticky-sidebar input[type="text"],
.sticky-sidebar input[type="number"],
.sticky-sidebar select {
    border-radius: 0.5rem;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
}

.sticky-sidebar input:focus,
.sticky-sidebar select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Flex input pricing */
.d-flex.align-items-center input {
    flex: 1;
}

/* Button design */
.sticky-sidebar .btn-primary {
    background: linear-gradient(135deg, #0d6efd, #0a58ca);
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.sticky-sidebar .btn-primary:hover {
    background: linear-gradient(135deg, #0a58ca, #084298);
    transform: scale(1.02);
}

/* Responsive breakpoints */
@media (max-width: 768px) {
    .sticky-sidebar {
        position: static;
        margin-top: 1rem;
        border-radius: 0.75rem;
    }

    .sticky-sidebar .card-body {
        padding: 1rem;
    }

    .sticky-sidebar .card-title {
        font-size: 1.1rem;
    }
}



    .navbar {
        
        padding: 10px 0;
        border-radius: 5px;
        margin-bottom: 40px;
    }
    .nav-center {
        display: flex;
        justify-content: center;
        list-style-type: none;
        padding: 0;
        margin: 0;
        font-size: 18px;
    }
    .nav-center li {
        margin: 0 15px;
        padding: 10px 15px;
        background-color:DeepSkyBlue;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .nav-center a {
        color: black;
        text-decoration: none;
        font-weight: normal;
        display: block;
        text-align: center;
    }
    .nav-center li.active {
        background-color: #FF1493; /* Couleur de fond jaune pour l'élément actif */
        color: black; /* Couleur du texte */
        font-weight: bold; /* Texte en gras */
        border-radius: 5px; /* Coins arrondis */
    }
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-10px); /* Déplace la carte vers le haut */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Ajoute une ombre plus prononcée */
    }
</style>
</head>
<body>
    <header>
    <?php include_once 'inc/navbar.php'; ?>

    </header>
           
           <nav class="navbar mt-5">
    <ul class="nav-center">
        <?php foreach ($sportsAssocies as $sportItem): ?>
            <li class="<?= (isset($_GET['sport']) && $_GET['sport'] === $sportItem) ? 'active' : ''; ?>">
                <a href="produits.php?sport=<?= htmlspecialchars($sportItem); ?>&categorie=<?= htmlspecialchars($categorie); ?>">
                    <?= htmlspecialchars(ucfirst($sportItem)); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

       




<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar Filtre -->
        <aside class="col-md-3 mb-4">
            <div class="card h-100 sticky-sidebar">
                <div class="card-body">
                    <h5 class="card-title text-primary">Filtrer</h5>
                    <form method="GET" action="produits.php">
                        <input type="hidden" name="sport" value="<?= htmlspecialchars($sport); ?>">
                        <input type="hidden" name="categorie" value="<?= htmlspecialchars($categorie); ?>">

                        <div class="mb-3">
                            <label for="search" class="form-label">Rechercher par nom du Produit :</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Nom du produit" value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="type_produit" class="form-label">Catégorie de produit :</label>
                            <select name="type_produit" id="type_produit" class="form-select">
                                <option value="">Tous</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= $type['id']; ?>" <?= ($typeProduit == $type['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($type['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prix :</label>
                            <div class="d-flex align-items-center">
                            <input type="number" name="prix_min" class="form-control me-2" placeholder="Prix Min" min="0" step="5" value="<?= htmlspecialchars($_GET['prix_min'] ?? '0'); ?>">
                            <input type="number" name="prix_max" class="form-control ms-2" placeholder="Prix Max" min = "0" max="1000" step="5" value="<?= htmlspecialchars($_GET['prix_max'] ?? '1000'); ?>">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Appliquer le filtre</button>
                        </div>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Produits -->
        <section class="col-md-9">
        <h2 class="mb-4 text-center mt-2">
            <?php if (!empty($typeProduit)): ?>
                <?= htmlspecialchars($types[array_search($typeProduit, array_column($types, 'id'))]['nom']); ?> - <?= ucfirst(htmlspecialchars($sport)); ?>
            <?php else: ?>
                <?= ucfirst(htmlspecialchars($sport)); ?>
            <?php endif; ?>
        </h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($produitsFiltres as $produit): ?>
                    <a style="text-decoration: none;color: var(--gl-color-brand-p1);" href="produit.php?id=<?= $produit['id']; ?>">
                        <div class="product-card">
                            <div class="image-wrapper">

                            <img src="<?= htmlspecialchars($produit['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($produit['nom']) ?>">
                            <span class="badge">Promo</span>
                            </div>

                            <div class="product-info">
                                <p class="product-name"><?= htmlspecialchars($produit['nom']); ?></p>
                                <p class="product-price"><?= number_format($produit['prix'], 2); ?> DT</p>
                            </div>
                         
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>


<script>
    const minSlider = document.getElementById("minPrice");
    const maxSlider = document.getElementById("maxPrice");
    const minPriceValue = document.getElementById("minPriceValue");
    const maxPriceValue = document.getElementById("maxPriceValue");

    function updatePrices() {
        let min = parseInt(minSlider.value);
        let max = parseInt(maxSlider.value);

        if (min > max - 50) {
            min = max - 50;
            minSlider.value = min;
        }

        if (max < min + 50) {
            max = min + 50;
            maxSlider.value = max;
        }

        minPriceValue.textContent = min;
        maxPriceValue.textContent = max;
    }

    minSlider.addEventListener("input", updatePrices);
    maxSlider.addEventListener("input", updatePrices);
    updatePrices();
</script>


</body>
</html>
