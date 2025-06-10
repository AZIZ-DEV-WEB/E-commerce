<?php
session_start();
require_once 'inc/functions.php';

if (!isset($_SESSION['visiteur_id'])) {
    header('Location: connexion.php');
    exit;
}

$conn = connect();
$visiteur_id = $_SESSION['visiteur_id'];
$panier_id = $_GET['panier_id'] ?? null;

if (!$panier_id) {
    echo "<div class='alert alert-warning'>Aucun panier sélectionné pour le checkout.</div>";
    exit;
}

// Récupérer les informations du visiteur
$stmt = $conn->prepare("SELECT nom,prenom,email, adresse, code_postal, ville, telephone FROM visiteurs WHERE id = :visiteur_id");
$stmt->execute([':visiteur_id' => $visiteur_id]);
$visiteur = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les commandes du panier
$stmt = $conn->prepare("
    SELECT c.id, p.nom AS produit, p.image, c.quantite, c.total 
    FROM commande c
    JOIN produit p ON c.produit = p.id
    WHERE c.panier = :panier_id
");
$stmt->execute([':panier_id' => $panier_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f9fafb;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .checkout-container {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      margin-top: 40px;
      padding: 20px;
    }
    .form-section, .cart-summary {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 6px 15px rgb(0 0 0 / 0.1);
      flex: 1 1 400px;
      min-width: 320px;
    }
    .form-section h2, .cart-summary h3 {
      margin-bottom: 25px;
      font-weight: 600;
      color: #333;
    }
    .delivery-options {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }
    .option {
      cursor: pointer;
      padding: 10px 20px;
      border-radius: 30px;
      background-color: #eaeaea;
      color: #555;
      font-weight: 500;
      transition: background-color 0.3s ease, color 0.3s ease;
      user-select: none;
    }
    .option.selected {
      background-color: #0d6efd;
      color: #fff;
      font-weight: 700;
    }
    form input[type="email"],
    form input[type="text"],
    form input[type="tel"],
    form textarea,
    form select {
      margin-bottom: 20px;
      border-radius: 8px;
      border: 1.5px solid #ddd;
      padding: 12px 15px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
      width: 100%;
      box-sizing: border-box;
    }
    form input[type="email"]:focus,
    form input[type="text"]:focus,
    form input[type="tel"]:focus,
    form textarea:focus,
    form select:focus {
      outline: none;
      border-color: #0d6efd;
      box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
    }
    .name-fields {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
    .name-fields input {
      flex: 1 1 48%;
    }
    .manual-address {
      color: #0d6efd;
      cursor: pointer;
      font-weight: 600;
      margin-bottom: 25px;
      user-select: none;
    }
    .cart-summary .modify-link {
      text-align: right;
      margin-bottom: 15px;
    }
    .cart-summary .modify-link a {
      color: #0d6efd;
      font-weight: 600;
      text-decoration: none;
    }
    .cart-summary .total-line {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
      font-size: 1.1rem;
      color: #555;
    }
    .cart-summary .total-line:last-child {
      font-weight: 700;
      font-size: 1.3rem;
      color: #222;
    }
    .small-text {
      font-size: 0.85rem;
      color: #888;
      margin-top: 15px;
    }
    .product {
      display: flex;
      margin-top: 25px;
      gap: 20px;
      border-top: 1px solid #eee;
      padding-top: 25px;
    }
    .product img {
      width: 100px;
      border-radius: 10px;
      object-fit: contain;
      box-shadow: 0 4px 8px rgb(0 0 0 / 0.1);
    }
    .product-details {
      font-size: 0.95rem;
      color: #333;
    }
    .product-details strong {
      font-weight: 600;
      color: #0d6efd;
    }
    button.btn-primary {
      background-color: #0d6efd;
      border: none;
      padding: 12px 25px;
      font-size: 1.1rem;
      border-radius: 8px;
      width: 100%;
      transition: background-color 0.3s ease;
      cursor: pointer;
    }
    button.btn-primary:hover {
      background-color: #0b5ed7;
    }
    @media (max-width: 768px) {
      .checkout-container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<div class="container checkout-container">
  <!-- Form Section -->
  <div class="form-section">
    <h2>Informations de livraison</h2>

    <div class="delivery-options">
      <div class="option selected" id="option-expedition">🚚 Expédition</div>
      <div class="option" id="option-retrait">📍 Retrait</div>
    </div>

    <form action="verification.php" method="POST" id="checkout-form">
      <input type="hidden" name="panier_id" value="<?php echo htmlspecialchars($panier_id); ?>">

      <div class="name-fields">
        <input type="text" placeholder="Prénom*" name="prenom" value="<?php echo htmlspecialchars($visiteur['prenom'] ?? ''); ?>" required>
        <input type="text" placeholder="Nom*" id="nom" name="nom" value="<?php echo htmlspecialchars($visiteur['nom'] ?? ''); ?>" required>
      </div>

      <input type="email" placeholder="E-mail*" name="email" value="<?php echo htmlspecialchars($visiteur['email'] ?? ''); ?>" required>

      <textarea placeholder="Adresse complète*" id="adresse" name="adresse" rows="3" required><?php echo htmlspecialchars($visiteur['adresse'] ?? ''); ?></textarea>

      <input type="text" placeholder="Code postal*" id="code_postal" name="code_postal" value="<?php echo htmlspecialchars($visiteur['code_postal'] ?? ''); ?>" required>

      <input type="text" placeholder="Ville*" id="ville" name="ville" value="<?php echo htmlspecialchars($visiteur['ville'] ?? ''); ?>" required>

      <input type="tel" placeholder="Numéro de téléphone*" id="telephone" name="telephone" value="<?php echo htmlspecialchars($visiteur['telephone'] ?? ''); ?>" required>

      <select class="form-select" id="mode_livraison" name="mode_livraison" required>
        <option value="Expédition" selected>Expédition</option>
        <option value="Retrait">Retrait</option>
      </select>

      <button type="submit" class="btn btn-primary mt-3">Passer à la vérification</button>
    </form>
  </div>

  <!-- Cart Summary -->
  <div class="cart-summary">
    <h3>Dans ton panier</h3>
    <div class="modify-link"><a href="panier.php">Modifier</a></div>

    <?php
    $total = 0;
    foreach ($commandes as $commande) {
        $total += (float)$commande['total'];
    }
    $frais_livraison = 0.00; // tu peux adapter ça dynamiquement si besoin
    ?>

    <div class="total-line">
      <span>Sous-total</span>
      <span><?= number_format($total, 2, ',', ' ') ?> DT</span>
    </div>
    <div class="total-line">
      <span>Frais d'expédition estimés</span>
      <span><?= number_format($frais_livraison, 2, ',', ' ') ?> DT</span>
    </div>
    <div class="total-line">
      <span>Total</span>
      <span><?= number_format($total + $frais_livraison, 2, ',', ' ') ?> DT</span>
    </div>

    <?php if (empty($commandes)): ?>
      <p class="small-text">Votre panier est vide.</p>
    <?php else: ?>
      <?php foreach ($commandes as $commande): ?>
        <div class="product">
          <img src="<?php echo htmlspecialchars($commande['image']); ?>" alt="<?php echo htmlspecialchars($commande['produit']); ?>">
          <div class="product-details">
            <strong><?php echo htmlspecialchars($commande['produit']); ?></strong><br>
            Quantité : <?php echo (int)$commande['quantite']; ?><br>
            Prix total : <?= number_format($commande['total'], 2, ',', ' ') ?> DT
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>



</body>
</html>
