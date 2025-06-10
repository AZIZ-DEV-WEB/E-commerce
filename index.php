<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include_once 'inc/functions.php';

if (!isset($_SESSION['visited_index'])) {
    $_SESSION['visited_index'] = true;

    $conn = connect();
    $stmt = $conn->prepare("INSERT INTO conversion_stats (page_name, action) VALUES (:page, :action)");
    $stmt->execute([
        'page' => 'index.php',
        'action' => 'visit'
    ]);
}

// Récupérer toutes les catégories
$categories = getAllCategories();

// Vérifier si une catégorie est sélectionnée
$selected_category = isset($_POST['category']) ? $_POST['category'] : null;


?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="css/chatbot.css">

    <style>
  #map {
      height: 500px;
      width: 80%;
      margin: 0 auto;
      border-radius: 10px;
      text-align: center;
      border-radius: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      margin-top: 20px;
  }
  .section-title {
    text-align: center;
    margin: 40px auto 20px;
    font-size: 2.5rem;
    font-weight: bold;
    color: #2c3e50;
    position: relative;
    padding-bottom: 10px;
}

.section-title::after {
    content: "";
    display: block;
    width: 80px;
    height: 4px;
    background-color: #e67e22;
    margin: 8px auto 0;
    border-radius: 2px;
}



    </style>
  </head>
<body>
<?php include 'inc/navbar.php'; ?>


<h1 class="section-title">Nos catégories</h1>
<div class="category-section">
  <div class="category-card">
    <a href="femme.php">
      <img src="images/female.jpg" alt="Femme">
      <div class="category-text">
        <p>ALLER À LA MODE</p>
        <h2>FEMME</h2>
      </div>
    </a>
  </div>
  <div class="category-card">
    <a href="homme.php">
      <img src="images/man.jpg" alt="Homme">
      <div class="category-text">
        <p>ALLER À LA MODE</p>
        <h2>HOMME</h2>
      </div>
    </a>
  </div>
  <div class="category-card">
    <a href="kids.php">
      <img src="images/kid.jpg" alt="BSK Teen">
      <div class="category-text">
        <p>ALLER À LA MODE</p>
        <h2>Kids</h2>
      </div>
    </a>
  </div>
</div>

<section>
<h1 class="section-title">Notre localisation</h1>
<div id="map" style="height: 400px; width: 80%;  border-radius: 10px;"></div>
</section>

<!-- Bouton flottant -->
<!-- Bouton flottant avec animation intégrée -->
<div class="chatbot-float">
  <button class="chatbot-toggle" id="chatbot-toggle">💬</button>
  <div class="vibebot-popup" id="vibebot-popup">
    <div class="vibebot-character">🤖</div>
    <div class="vibebot-message">Demandez à VibeBot !</div>
  </div>
</div>


<!-- Chatbot Container -->
<div class="chatbot-container" id="chatbot">
  <div class="chatbot-header">VibeBot</div>
  <div class="chatbot-messages" id="chatbot-messages"></div>

</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const popup = document.getElementById('vibebot-popup');

    // Fonction pour afficher puis masquer le popup
    function showPopupLoop() {
      popup.classList.add('show');

      // Masquer après 2.5 secondes
      setTimeout(() => {
        popup.classList.remove('show');
      }, 2500); // mi-temps d'affichage

      // Rappel de la fonction toutes les 5 secondes
      setTimeout(showPopupLoop, 5000);
    }

    // Lancer la boucle initiale après 2 secondes
    setTimeout(showPopupLoop, 2000);
  });
</script>



<!-- Script JS -->
<script>
  const toggleBtn = document.getElementById('chatbot-toggle');
  const chatbot = document.getElementById('chatbot');
  const messages = document.getElementById('chatbot-messages');
const questions = [

  {
    question: "Que vendez-vous ?",
    answer: "Nous proposons une large gamme d'équipements et articles de sport adaptés à toutes les catégories d'âges.",
    subQuestions: [
{
  question: "Quels sports proposez-vous ?",
  answer: "Nous proposons une variété de disciplines : football, basket-ball, tennis, natation, cyclisme et bien d'autres encore."
},

      {
        question: "Les produits sont-ils de marque ?",
        answer: "Oui, nous travaillons avec des marques reconnues comme Nike, Adidas, Puma, etc."
      }
    ]
  }
  ,
  {
    question: "Comment puis-je passer une commande ?",
    answer: "Vous pouvez passer une commande directement sur notre site web ou en visitant notre boutique.",
    subQuestions: [
      {
        question: "Quels modes de paiement acceptez-vous ?",
        answer: "Nous acceptons les cartes bancaires,  et les paiements en espèces à la livraison."
      },
      {
        question: "Puis-je retourner un produit ?",
        answer: "Oui, vous pouvez retourner un produit dans les 30 jours suivant l'achat, à condition qu'il soit en bon état."
      }
    ]
  },
  {
    question: "Avez-vous des promotions en cours ?",
    answer: "Oui, nous avons régulièrement des promotions. Consultez notre site pour les offres actuelles.",
    subQuestions: [
      {
        question: "Comment être informé des promotions ?",
        answer: "Inscrivez-vous à notre newsletter pour recevoir les dernières offres et promotions."
      }
    ]
  }
];




  // Toggle chatbot
  toggleBtn.addEventListener('click', () => {
    chatbot.classList.toggle('open');
    if (chatbot.classList.contains('open')) {
      showOptions();
    }
  });

  // Afficher les options de questions
  function showOptions() {
    messages.innerHTML = ''; // Nettoie l'historique
    questions.forEach(q => {
      const btn = document.createElement('button');
      btn.textContent = q.question;
      btn.className = 'question-button';
      btn.onclick = () => showAnswer(q);
      messages.appendChild(btn);
    });
  }

  // Afficher la réponse
// Afficher la réponse avec gestion des questions parentes
function showAnswer(q) {
  appendMessage(q.question, 'user');

  setTimeout(() => {
    appendMessage(q.answer, 'bot');

    // Nettoyer anciens boutons
    clearButtons();

    // Affiche les sous-questions s'il y en a
    if (q.subQuestions && q.subQuestions.length > 0) {
      const subTitle = document.createElement('div');
      subTitle.textContent = "Questions associées :";
      subTitle.className = "subquestion-title";
      messages.appendChild(subTitle);

      q.subQuestions.forEach(subQ => {
        const btn = document.createElement('button');
        btn.textContent = subQ.question;
        btn.className = 'question-button';
        btn.onclick = () => showAnswer(subQ); // récursif
        messages.appendChild(btn);
      });
    }

    // Réaffiche toujours les questions principales à la fin
    const mainTitle = document.createElement('div');
    mainTitle.textContent = "Questions principales :";
    mainTitle.className = "mainquestion-title";
    messages.appendChild(mainTitle);

    questions.forEach(mainQ => {
      const btn = document.createElement('button');
      btn.textContent = mainQ.question;
      btn.className = 'question-button';
      btn.onclick = () => showAnswer(mainQ);
      messages.appendChild(btn);
    });
  }, 500);
}

// Fonction utilitaire pour nettoyer boutons/éléments précédents
function clearButtons() {
  const buttons = messages.querySelectorAll('.question-button, .subquestion-title, .mainquestion-title');
  buttons.forEach(btn => btn.remove());
}




  // Affichage de message dans le fil
  function appendMessage(text, sender) {
    const msg = document.createElement('div');
    msg.className = 'message ' + sender;
    msg.textContent = text;
    messages.appendChild(msg);
    messages.scrollTop = messages.scrollHeight;
  }
</script>






<!-- CSS de Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-easybutton/2.4.0/easy-button.css" />

<!-- JS de Leaflet -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-easybutton/2.4.0/easy-button.js"></script>

<script>
  var map = L.map('map', {
      zoomControl: true,
      scrollWheelZoom: false
  }).setView([14.716677, -17.467686], 15);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  L.marker([36.8385474903323, 10.314263055727384]).addTo(map)
      .bindPopup('<b>Notre entreprise</b><br>Adresse : Tunis, Tunisie')
      .openPopup();

  L.control.scale().addTo(map);

  var homeButton = L.easyButton('fa-home', function() {
      map.setView([14.716677, -17.467686], 15);
  }, 'Revenir à la position initiale').addTo(map);

  window.addEventListener('resize', function() {
      map.invalidateSize();
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<?php include 'inc/footer.php'; ?>
</body>
</html>
