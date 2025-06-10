<!-- filepath: c:\Users\user\Desktop\E-commerce\inc\functions.php -->

<?php





//session_start();
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = []; // panier vide par défaut
}

function isActivePage($path) {
    if (!function_exists('isActivePage')) {
        function isActivePage($path) {
            $currentPath = $_SERVER['REQUEST_URI'];
            return strpos($currentPath, $path) !== false ? 'active' : '';
        }
    }
    
}

function connect() {
    try {
        $host = 'localhost';
        $dbname = 'e-commerce';
        $username = 'root';
        $password = '';

        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }

    
}

function getAllCategorieSports() {
    $conn = connect();
    $stmt = $conn->query("SELECT * FROM categorie_sport");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getAllSportTypeProduit() {
    $conn = connect();
    $stmt = $conn->query("SELECT * FROM sport_type_produit");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//echo "Tous les mots de passe ont été hachés avec succès.";

function getConversionRate() {
    $conn = connect(); // Connexion à la base de données
    // Requête pour compter le nombre total de visites sur la page d'accueil
    $visitsStmt = $conn->prepare("SELECT COUNT(*) FROM conversion_stats WHERE page_name = 'index.php' AND action = 'visit'");
    $visitsStmt->execute();
    $total_visits = $visitsStmt->fetchColumn();

    $cartStmt = $conn->prepare("SELECT COUNT(*) FROM conversion_stats WHERE page_name = 'produit.php' AND action = 'add_to_cart'");
    $cartStmt->execute();
    $add_to_cart = $cartStmt->fetchColumn();

    if ($total_visits == 0) return 0;

    return ($add_to_cart / $total_visits) * 100;
}

function getAllCategories() {
    try {
        $conn = connect(); // Appel de la fonction connect()

        // Création de la requête SELECT
        $stmt = $conn->prepare("SELECT * FROM categorie");

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $categories = $stmt->fetchAll();

        return $categories;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}

// function getAllProducts() {
//     try {
//         $conn = connect(); // Appel de la fonction connect()

//         // Création de la requête SELECT
//         $stmt = $conn->prepare( "SELECT p.*, tp.nom AS type_produit_nom
//         FROM produit p
//         JOIN type_produit tp ON p.type_produit_id = tp.id");

//         // Exécution de la requête
//         $stmt->execute();

//         // Récupération des résultats
//         $produits = $stmt->fetchAll();

//         return $produits;
//     } catch (PDOException $e) {
//         // Gestion des erreurs
//         echo "Erreur : " . $e->getMessage();
//         return [];
//     }
// }
function getAllTypesProduits(){
    try {
        $conn = connect(); // Appel de la fonction connect()

        // Création de la requête SELECT
        $stmt = $conn->prepare("SELECT id, nom FROM type_produit

         ");

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $typesProduits = $stmt->fetchAll();

        return $typesProduits;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}
function getAllStocks($filters = []) {
  $pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");

  $sql = "
    SELECT 
      p.id, p.nom, p.quantite_stock, p.sport,
      tp.nom AS type_produit,
      c.nom AS categorie
    FROM produit p
    JOIN categorie c ON p.categorie = c.nom
    JOIN type_produit tp ON p.type_produit_id = tp.id
    WHERE 1 = 1
  ";

  $params = [];

  if (!empty($filters['categorie'])) {
    $sql .= " AND p.categorie = :categorie";
    $params['categorie'] = $filters['categorie'];
  }

  if (!empty($filters['sport'])) {
    $sql .= " AND p.sport = :sport";
    $params['sport'] = $filters['sport'];
  }

  if (!empty($filters['type_produit'])) {
    $sql .= " AND p.type_produit_id = :type_produit_id";
    $params['type_produit_id'] = $filters['type_produit'];
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// function getStockByProductId($productId) {
//     try {
//         $conn = connect();
//         $stmt = $conn->prepare("SELECT quantite_stock FROM produit WHERE id = :produit_id");
//         $stmt->execute([':produit_id' => $productId]);
//         $stock = $stmt->fetch(PDO::FETCH_ASSOC);

//         return $stock ? $stock['quantite'] : 0; // Retourne 0 si aucun stock trouvé
//     } catch (PDOException $e) {
//         echo "Erreur : " . $e->getMessage();
//         return 0;
//     }
// }

// function searchProduct($keywords) {
//     try {
//         $conn = connect(); // Appel de la fonction connect()

//         // Création de la requête SELECT avec une clause LIKE
//         $stmt = $conn->prepare("SELECT * FROM produit WHERE nom LIKE :keywords");

//         // Liaison des paramètres
//         $stmt->bindValue(':keywords', '%' . $keywords . '%', PDO::PARAM_STR);

//         // Exécution de la requête
//         $stmt->execute();

//         // Récupération des résultats
//         $products = $stmt->fetchAll();

//         return $products;
//     } catch (PDOException $e) {
//         // Gestion des erreurs
//         echo "Erreur : " . $e->getMessage();
//         return [];
//     }
// }

function getProductById($id) {
    try {
        $conn = connect(); // Connexion à la base de données

        // 1. Création de la requête SELECT
        $stmt = $conn->prepare("SELECT * FROM produit WHERE id = :id");

        // 2. Liaison des paramètres
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        // 3. Exécution de la requête
        $stmt->execute();

        // 4. Récupération du résultat
        $product = $stmt->fetch();

        return $product;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return null;
    }
}

function AddVisiteur($data) { 
    try {
        $conn = connect(); // Connexion à la base de données

        // Hachage sécurisé du mot de passe
        $hashedPassword = password_hash($data['mp'], PASSWORD_DEFAULT);
        $hashedCodeCarte = password_hash($data['code_carte'], PASSWORD_DEFAULT);

        // 1. Préparer la requête INSERT
        $stmt = $conn->prepare("INSERT INTO visiteurs (nom, prenom, email, mp, telephone, code_carte, adresse, code_postal, ville) 
                                VALUES (:nom, :prenom, :email, :mp, :telephone, :code_carte, :adresse, :code_postal, :ville)");

        // 2. Lier les paramètres
        $stmt->bindValue(':nom', $data['nom'], PDO::PARAM_STR);
        $stmt->bindValue(':prenom', $data['prenom'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(':mp', $hashedPassword, PDO::PARAM_STR); // Utiliser le mot de passe hashé
        $stmt->bindValue(':code_carte', $hashedCodeCarte, PDO::PARAM_STR); // Utiliser le code de carte hashé
        $stmt->bindValue(':adresse', $data['adresse'], PDO::PARAM_STR);
        $stmt->bindValue(':code_postal', $data['code_postal'], PDO::PARAM_STR);
        $stmt->bindValue(':ville', $data['ville'], PDO::PARAM_STR);
        $stmt->bindValue(':telephone', $data['telephone'], PDO::PARAM_STR);

        // 3. Exécuter la requête
        $stmt->execute();

        // 4. Retourner l'ID du nouvel enregistrement
        return $conn->lastInsertId(); 
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return null;
    }
}

function getAllVisitors(){
    try {
        $conn = connect(); // Appel de la fonction connect()

        // Création de la requête SELECT
        $stmt = $conn->prepare("SELECT * FROM visiteurs");

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $visiteurs = $stmt->fetchAll();

        return $visiteurs;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}

function connectVisiteur($data) {
    $conn = connect();

    $email = trim($data['email']);
    $password = trim($data['mp']);

    // Recherche de l'utilisateur par email
    $stmt = $conn->prepare("SELECT * FROM visiteurs WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérification sécurisée du mot de passe
        if (password_verify($password, $user['mp'])) {
            session_start();
            $_SESSION['visiteur_id'] = $user['id'];
            $_SESSION['visiteur_nom'] = $user['nom'];
            $_SESSION['visiteur_prenom'] = $user['prenom'];
            $_SESSION['visiteur_mp'] = $user['mp'];
            $_SESSION['visiteur_email'] = $user['email'];
            $_SESSION['visiteur_telephone'] = $user['telephone'];
            $_SESSION['visiteur_etat'] = $user['etat']; // Assurez-vous que cette clé est définie

            header('Location: index.php');
            exit;
        } else {
            return "Mot de passe invalide";
        }
    } else {
        return "Email et mot de passe invalides";
    }
}


function getTotalSales(){
    $conn = connect();
    // Requête pour compter le nombre total de ventes
    $requete = $conn->prepare("SELECT SUM(total) FROM panier WHERE statut ='payé'");
    $requete->execute();
    return $requete->fetchColumn();
}
function getTotalOrders(){
    $conn=connect();
    // Requête pour compter le nombre total de commandes
    $requete = $conn->prepare("SELECT COUNT(*) FROM panier where statut ='payé'");
    $requete->execute();
    return $requete->fetchColumn();
}
function newordersthismonth(){
    $conn = connect(); // Connexion à la base de données
    // Requête pour compter le nombre de commandes passées ce mois-ci
    $requete = $conn->prepare("SELECT COUNT(*) FROM commande WHERE MONTH(date_creation) = MONTH(CURRENT_DATE()) AND YEAR(date_creation) = YEAR(CURRENT_DATE())");
    $requete->execute();
    return $requete->fetchColumn();
}
function PreviousMonthOrders(){
    $conn = connect();
    //requete pour obtenir le nombre de commandes du mois precedent
    $requete = $conn->prepare("SELECT COUNT(*) FROM commande WHERE MONTH(CURRENT_DATE()) - MONTH(date_creation) = 1 AND YEAR(date_creation) = YEAR(CURRENT_DATE())");
    $requete->execute();
    return $requete->fetchColumn();
}

function newsalesthismonth(){
    $conn = connect(); // Connexion à la base de données
    // Requête pour compter le nombre de ventes ce mois-ci
    $requete = $conn->prepare("SELECT SUM(total) FROM commande WHERE MONTH(date_creation) = MONTH(CURRENT_DATE()) AND YEAR(date_creation) = YEAR(CURRENT_DATE())");
    $requete->execute();
    return $requete->fetchColumn();
}
function PreviousMonthSales(){
    $conn = connect();
    //requete pour obtenir le nombre de ventes du mois precedent
    $requete = $conn->prepare("SELECT SUM(total) FROM commande WHERE MONTH(CURRENT_DATE()) - MONTH(date_creation) = 1 AND YEAR(date_creation) = YEAR(CURRENT_DATE())");
    $requete->execute();
    return $requete->fetchColumn();
}
function calculerPourcentageEtBadge($valeurActuelle, $valeurPrecedente) {
    // Calcul du pourcentage
    if ($valeurPrecedente > 0) {
        $percentage = (($valeurActuelle - $valeurPrecedente) / $valeurPrecedente) * 100;
    } else {
        $percentage = 0;
    }

    // Choix de la classe CSS
    if ($percentage < 30) {
        $badge_class = "bg-light-danger border border-danger";
    } elseif ($percentage >= 30 && $percentage <= 70) {
        $badge_class = "bg-light-warning border border-warning";
    } else {
        $badge_class = "bg-light-success border border-success";
    }

    return [
        'percentage' => $percentage,
        'badge_class' => $badge_class,
    ];
}


function getTotalAdmins(){
       $conn = connect(); // Connexion à la base de données
    // Requête pour compter le nombre total de visiteurs
    $requete = $conn->prepare("SELECT COUNT(*) FROM admin");
    $requete->execute();
    return $requete->fetchColumn();
}
function getTotalUsers() {
    $conn = connect(); // Connexion à la base de données
    // Requête pour compter le nombre total de visiteurs
    $requete = $conn->prepare("SELECT COUNT(*) FROM visiteurs");
    $requete->execute();
    return $requete->fetchColumn();
}

function newusersthismonth() {
    $conn = connect(); // Connexion à la base de données
    // Requête pour compter le nombre de visiteurs inscrits ce mois-ci
    $requete = $conn->prepare("SELECT COUNT(*) FROM visiteurs WHERE MONTH(date_creation) = MONTH(CURRENT_DATE()) AND YEAR(date_creation) = YEAR(CURRENT_DATE())");
    $requete->execute();
    return $requete->fetchColumn();
}

function PreviousMonthUsers(){
    $conn = connect();
    //requete pour obtenir le nombre de visiteurs du mois precedent
    $requete = $conn->prepare("SELECT COUNT(*) FROM visiteurs WHERE MONTH(CURRENT_DATE()) - MONTH(date_creation) = 1 AND YEAR(date_creation) = YEAR(CURRENT_DATE())");
    $requete->execute();
    return $requete->fetchColumn();
}
function connectAdmin($email, $password) {
    $conn = connect();

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = :email");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification sans hashage
    if ($admin && $admin['mp'] === $password) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nom'] = $admin['nom'];
        $_SESSION['admin_email'] = $admin['email'];
        return true;
    }

    return false;
}

function modifierCategorie($id, $nom, $description) {
    $conn = connect(); // appel direct à la fonction de connexion
    $stmt = $conn->prepare("UPDATE categorie SET nom = ?, description = ? WHERE id = ?");
    $stmt->execute([$nom, $description, $id]);
}


function modifierProduit($id, $nom, $description, $image, $prix,$categorie, $quantite_stock,$sport_id ) {
    $conn = connect(); // appel direct à la fonction de connexion
    $stmt = $conn->prepare("UPDATE produit SET nom = ?, description = ?, prix = ?, image = ? WHERE id = ?");
    $stmt->execute([$nom, $description, $prix, $image, $id]);
}

function getAllpaniers() {
    $conn = connect();

    // Requête pour récupérer les paniers avec les informations des utilisateurs
    $sql = "
        SELECT 
            v.nom AS visiteur_nom,
            v.prenom AS visteur_prenom,
            p.total,
            p.statut,
            p.date_creation,
            p.id AS panier_id
        FROM panier p
        JOIN visiteurs v ON p.visiteur_id = v.id
        ORDER BY p.date_creation DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $paniers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $paniers;
}

function getAllCommandes($panier_id) {
    $conn = connect();

    // Requête pour récupérer les commandes d'un panier spécifique
    $sql = "
        SELECT 
            c.id AS commande_id,
            p.nom AS produit_nom,
            c.quantite,
            c.total
        FROM commande c
        JOIN produit p ON c.produit = p.id
        WHERE c.panier = :panier_id
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':panier_id' => $panier_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updatePanierEtat($panier_id, $statut) {
    $conn = connect();

    // Vérifie l'ancien statut
    $stmt = $conn->prepare("SELECT statut FROM panier WHERE id = :id");
    $stmt->execute([':id' => $panier_id]);
    $ancien_statut = $stmt->fetchColumn();

    // Si c’est déjà le même, pas besoin d'update
    if ($ancien_statut === $statut) {
        return true;
    }

    // Sinon, mettre à jour
    $sql = "UPDATE panier SET statut = :statut WHERE id = :panier_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':statut' => $statut,
        ':panier_id' => $panier_id
    ]);

    return $stmt->rowCount() > 0;
}


function getEtatsPanier() {
    $conn = connect();
    $sql = "SHOW COLUMNS FROM panier WHERE Field = 'statut'";
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Exemple de retour : enum('En attente','En livraison','Livré')
        $type = $row['Type'];
        preg_match("/^enum\((.*)\)$/", $type, $matches);
        if (isset($matches[1])) {
            $enum = str_getcsv($matches[1], ',', "'");
            return $enum;
        }
    }

    return [];
}

function getProductsByCategory($category_id) {
    $conn = connect();
    $sql = "SELECT * FROM produit WHERE categorie = :category_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':category_id' => $category_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

class SportEnum {
    const FOOTBALL = 'football';
    const BASKETBALL = 'basketball';
    const TENNIS = 'tennis';
    const NATATION = 'natation';
    const CYCLISME = 'cyclisme';
    const MUSCULATION = 'musculation';
    const FITNESS = 'fitness';
    const AUTRE = 'autre';

    public static function all() {
        return [
            self::FOOTBALL,
            self::BASKETBALL,
            self::TENNIS,
            self::NATATION,
            self::CYCLISME,
            self::MUSCULATION,
            self::FITNESS,
            self::AUTRE,
        ];
    }
}

function getDerniersProduits($categorie) {
    // Connexion à la base de données (à adapter selon ta config)
    $conn = connect(); // Appel de la fonction connect()
    // Requête SQL pour récupérer les 3 derniers produits de la catégorie donnée
    $sql = "SELECT * FROM produit 
            WHERE categorie = :categorie 
            ORDER BY date_creation DESC 
            LIMIT 3";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['categorie' => $categorie]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProduitsOffreSpeciale($categorie) {
    // Connexion à la base (en supposant que tu as une fonction conn())
    $conn = connect();

    // Préparation de la requête
    $sql = "SELECT * FROM produit 
            WHERE offre_speciale = 'oui' 
            AND categorie = :categorie 
            ORDER BY date_creation DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
    $stmt->execute();

    // Récupération des résultats
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $produits;
}


function getProduitsParCategorieEtSport($categorie, $sport) {
    $conn = connect(); // Assurez-vous que cette fonction retourne une connexion PDO
    $sql = "SELECT * FROM produit WHERE categorie = :categorie AND sport = :sport";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':categorie' => $categorie,
        ':sport' => $sport
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getProduitsFiltres($sport, $categorie = null, $typeProduit = null, $prix_min = null, $prix_max = null, $search = null)
{
    $conn = connect(); // Connexion à la base de données

    $sql = "
        SELECT p.*, tp.nom AS type_produit_nom
        FROM produit p
        LEFT JOIN type_produit tp ON p.type_produit_id = tp.id
        WHERE p.sport = :sport
    ";

    $params = ['sport' => $sport];

    if ($categorie) {
        $sql .= " AND p.categorie = :categorie";
        $params['categorie'] = $categorie;
    }

    if (!empty($typeProduit)) {
        $sql .= " AND p.type_produit_id = :type_produit_id";
        $params['type_produit_id'] = $typeProduit;
    }

    if ($prix_min !== null) {
        $sql .= " AND p.prix >= :prix_min";
        $params['prix_min'] = $prix_min;
    }

    if ($prix_max !== null) {
        $sql .= " AND p.prix <= :prix_max";
        $params['prix_max'] = $prix_max;
    }

    if ($search) {
        $sql .= " AND p.nom LIKE :search";
        $params['search'] = '%' . $search . '%';
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTopProduitsVendus($categorie) {
    $conn = connect(); // Connexion à la base de données

    // Requête SQL pour sélectionner les 3 produits les plus vendus dans une catégorie donnée
    $sql = "
        SELECT p.id, p.nom, p.image, p.prix, SUM(c.quantite) AS total_vendu
        FROM produit p
        JOIN commande c ON p.id = c.produit
        WHERE p.categorie = :categorie
        GROUP BY p.id, p.nom, p.image, p.prix
        ORDER BY total_vendu DESC
        LIMIT 3
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
    $stmt->execute();

    // Récupération des résultats
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>