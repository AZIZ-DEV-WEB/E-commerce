<?php
function getVentesParSport() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");
        $sql = "
           SELECT 
        pr.sport, 
        SUM(pr.prix * c.quantite) AS total_ventes
    FROM 
        commande c
    JOIN 
        panier pa ON c.panier = pa.id
    JOIN 
        produit pr ON c.produit = pr.id
    WHERE 
        pa.statut = 'payé'
    GROUP BY 
        pr.sport
    ORDER BY 
        total_ventes DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Fonction : Nombre de commandes par statut
function getCommandesParStatut() {
        $pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");
    try {
        $sql = "
            SELECT statut, COUNT(*) AS total
            FROM panier
            GROUP BY statut
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [['error' => $e->getMessage()]];
    }
}

// Fonction : Ventes par genre et sport (3 dimensions)
function getVentesParCategorieEtSport() {
        $pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");
    try {
        $sql = "
            SELECT cat.nom AS genre, p.sport, SUM(c.quantite * c.total) AS total_ventes
            FROM commande c
            JOIN produit p ON c.produit = p.id
            JOIN categorie cat ON p.categorie = cat.nom
            JOIN panier pa ON c.panier = pa.id
            WHERE pa.statut = 'payé'
            GROUP BY cat.nom, p.sport
            ORDER BY cat.nom, p.sport
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return [['error' => $e->getMessage()]];
    }
}



function getProduitsParCategorie($genre) {
        $pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");
    try {
        $sql = "
            SELECT 
                p.nom AS produit,
                p.sport,
                SUM(c.quantite) AS quantite_vendue,
                p.prix AS prix_unitaire,
                SUM(c.quantite * p.prix) AS total_ventes
            FROM commande c
            JOIN produit p ON c.produit = p.id
            JOIN categorie cat ON p.categorie = cat.nom
            JOIN panier pa ON c.panier = pa.id
            WHERE pa.statut = 'payé' AND cat.nom = :genre
            GROUP BY p.nom, p.sport, p.prix
            ORDER BY total_ventes DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['genre' => $genre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [['error' => $e->getMessage()]];
    }
}

function getVentesMensuelles($annee = null, $mois = null, $SPORT = null, $gender = null) {
         $pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");


    $annee = $annee ?? date('Y');
    $moisCondition = $mois ? "AND MONTH(p.date_creation) = :mois" : "";
    $sportCondition = $SPORT ? "AND pr.sport = :sport" : "";
    $genreCondition = $gender ? "AND cat.nom = :genre" : "";

    try {
        $sql = "
            SELECT 
                MONTH(p.date_creation) AS mois,
                pr.sport,
                cat.nom AS genre,
                SUM(c.total) AS total_ventes
            FROM panier p
            JOIN commande c ON c.panier = p.id
            JOIN produit pr ON c.produit = pr.id
            JOIN categorie cat ON pr.categorie = cat.nom
            WHERE p.statut = 'payé'
              AND YEAR(p.date_creation) = :annee
              $moisCondition
              $sportCondition
              $genreCondition
            GROUP BY mois, pr.sport, cat.nom
            ORDER BY mois ASC
        ";

        $stmt = $pdo->prepare($sql);
        $params = ['annee' => $annee];
        if ($mois) $params['mois'] = $mois;
        if ($SPORT) $params['sport'] = $SPORT;
        if ($gender) $params['genre'] = $gender;

        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return [['error' => $e->getMessage()]];
    }
}

function getChiffreAffairesParMois($annee = null) {
     $pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");
    $annee = $annee ?? date('Y');

    try {
        $sql = "
            SELECT 
                MONTH(p.date_creation) AS mois,
                SUM(c.total) AS chiffre_affaires
            FROM panier p
            JOIN commande c ON c.panier = p.id
            WHERE p.statut = 'payé'
              AND YEAR(p.date_creation) = :annee
            GROUP BY mois
            ORDER BY mois
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['annee' => $annee]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [['error' => $e->getMessage()]];
    }
}

function getTauxConversionMensuel($annee = null) {
    $pdo = new PDO("mysql:host=localhost;dbname=e-commerce", "root", "");
    $annee = $annee ?? date('Y');

    try {
        $sql = "
            SELECT 
                MONTH(date_creation) AS mois,
                COUNT(CASE WHEN statut in('enattente','actif','annule','payé') THEN 1 END) AS total_paniers,
                COUNT(CASE WHEN statut = 'payé' THEN 1 END) AS paniers_payes,
                ROUND(
                    (COUNT(CASE WHEN statut = 'payé' THEN 1 END) / COUNT(*)) * 100, 2
                ) AS taux_conversion
            FROM panier
            WHERE YEAR(date_creation) = :annee
            GROUP BY mois
            ORDER BY mois;
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['annee' => $annee]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        return [['error' => $e->getMessage()]];
    }
}






?>



