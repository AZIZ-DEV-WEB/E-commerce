<!-- filepath: c:\Users\user\Desktop\E-commerce\inc\functions.php -->

<?php

function connect() {
    try {
        // Informations de connexion
        $host = 'localhost';
        $dbname = 'e-commerce';
        $username = 'root';
        $password = '';

        // Création de la connexion PDO
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    } catch (PDOException $e) {
        // Gestion des erreurs de connexion
        echo "Erreur de connexion : " . $e->getMessage();
        exit; // Arrête l'exécution en cas d'erreur critique
    }
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

function getAllProducts() {
    try {
        $conn = connect(); // Appel de la fonction connect()

        // Création de la requête SELECT
        $stmt = $conn->prepare("SELECT * FROM produit");

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $products = $stmt->fetchAll();

        return $products;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}

function searchProduct($keywords) {
    try {
        $conn = connect(); // Appel de la fonction connect()

        // Création de la requête SELECT avec une clause LIKE
        $stmt = $conn->prepare("SELECT * FROM produit WHERE nom LIKE :keywords");

        // Liaison des paramètres
        $stmt->bindValue(':keywords', '%' . $keywords . '%', PDO::PARAM_STR);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $products = $stmt->fetchAll();

        return $products;
    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}

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

        // 1. Préparer la requête INSERT
        $stmt = $conn->prepare("INSERT INTO visiteurs (nom, prenom, email, mp, telephone) 
                                VALUES (:nom, :prenom, :email, :mp, :telephone)");

        // 2. Lier les paramètres
        $stmt->bindValue(':nom', $data['nom'], PDO::PARAM_STR);
        $stmt->bindValue(':prenom', $data['prenom'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(':mp', $hashedPassword, PDO::PARAM_STR); // Utiliser le mot de passe hashé
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
            $_SESSION['visiteur_telephone'] = $user['telephone'];
            header('Location: profile.php');
            exit;
        } else {
            return "Mot de passe invalide";
        }
    } else {
        return "Email et mot de passe invalides";
    }
}



?>