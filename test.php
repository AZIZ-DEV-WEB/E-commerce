<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Paiement</title>
  <link href="https://fonts.googleapis.com/css2?family=Helvetica&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: Helvetica, Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #fff;
    }

    .container {
      display: flex;
      justify-content: space-between;
      padding: 40px;
      max-width: 1200px;
      margin: auto;
    }

    .form-section {
      width: 60%;
    }

    .form-section h2 {
      margin-bottom: 20px;
    }

    .delivery-options {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }

    .option {
      flex: 1;
      border: 2px solid black;
      padding: 10px;
      border-radius: 10px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .option.selected {
      background-color: #f5f5f5;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"] {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    .name-fields {
      display: flex;
      gap: 20px;
    }

    .cart-summary {
      width: 35%;
      padding-left: 40px;
      border-left: 1px solid #eee;
    }

    .cart-summary h3 {
      margin-bottom: 10px;
    }

    .cart-summary .total-line {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
    }

    .cart-summary .product {
      display: flex;
      margin-top: 20px;
      gap: 15px;
    }

    .cart-summary .product img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border: 1px solid #ccc;
    }

    .product-details {
      font-size: 14px;
    }

    .small-text {
      font-size: 13px;
      color: #666;
    }

    .modify-link {
      font-size: 14px;
      text-align: right;
      display: block;
      margin-bottom: 10px;
    }

    .manual-address {
      font-size: 13px;
      text-decoration: underline;
      cursor: pointer;
      margin-top: -10px;
      margin-bottom: 15px;
    }

  </style>
</head>
<body>

  <div class="container">
    <!-- Form Section -->
    <div class="form-section">
      <h2>Options de livraison</h2>

      <div class="delivery-options">
        <div class="option selected">
          🚚 Expédition
        </div>
        <div class="option">
          📍 Retrait
        </div>
      </div>

      <form>
        <input type="email" placeholder="E-mail*" required>

        <div class="name-fields">
          <input type="text" placeholder="Prénom*" required>
          <input type="text" placeholder="Nom*" required>
        </div>

        <input type="text" placeholder="🔍 Commence à saisir l'adresse">
        <div class="manual-address">Saisir l'adresse manuellement</div>
        
        <input type="tel" placeholder="Numéro de téléphone*" required>
      </form>
    </div>

    <!-- Cart Summary -->
    <div class="cart-summary">
      <h3>Dans ton panier</h3>
      <div class="modify-link"><a href="#">Modifier</a></div>

      <div class="total-line">
        <span>Sous-total</span>
        <span>259,98 €</span>
      </div>
      <div class="total-line">
        <span>Frais d'expédition estimés</span>
        <span>0,00 €</span>
      </div>
      <div class="total-line" style="font-weight: bold;">
        <span>Total</span>
        <span>259,98 €</span>
      </div>

      <div style="margin-top: 15px;" class="small-text">
        Livraison d'ici le mer. 28 mai
      </div>

      <div class="product">
        <img src="https://static.nike.com/a/images/t_default/4b5f1c0c-2fd5-4a15-9e29-8e0d9ae0cd10/air-jordan-1-low-chaussure.png" alt="Produit">
        <div class="product-details">
            <?php foreach ($commandes as $commande): ?>
          <strong><?php echo htmlspecialchars($commande['produit']); ?></strong><br>
          Réf. article : 553558-136<br>
          Taille : 40.5<br>
          Couleur : Blanc/Blanc/Blanc<br>
          Quantité : <?php echo htmlspecialchars($commande['quantite']); ?><br>
          <strong><?php echo htmlspecialchars($commande['prix']); ?> €</strong>
        <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
