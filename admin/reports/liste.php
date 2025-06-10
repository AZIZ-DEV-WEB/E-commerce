
<!DOCTYPE html>
<?php
session_start();
require_once '../../inc/functions.php';
require_once '../../inc/data.php';

$conn = connect();

// Appel de la fonction
$total_admins = getTotalAdmins();
$total_users = getTotalUsers();
$total_sales = getTotalSales();
$total_orders=getTotalOrders();
$taux_conversion = getConversionRate();


?>

<html lang="en">
<!-- [Head] start -->

<head>
  <title>Home | Mantis Bootstrap 5 Admin Template</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon"> <!-- [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="../../css/tabler-icons.min.css" >
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="../../css/feather.css" >
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="../../css/fontawesome.css" >
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="../../css/material.css" >
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="../../css/style.css" id="main-style-link" >
<link rel="stylesheet" href="../../css/style-preset.css" >
<link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/dashboard/">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


<!-- Bootstrap core CSS -->
<link href="../../css/bootstrap.min.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="../../css/dashboard.css" rel="stylesheet">
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        h3 {
            color: #333;
            margin-bottom: 20px;
        }
        .table-section {
            margin: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
   .kpis {
            text-align: center;
            display: flex;
            justify-content: space-around;
            margin: 20px;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        .card {
            background: white;
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
            border-radius: 8px;
            width: 220px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;

        }
      .graphiques {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
  padding: 20px;
}

.graphiques > div {
  flex: 1 1 45%; /* minimum 45% largeur pour 2 par ligne */
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  padding: 15px;
  box-sizing: border-box;
  max-width: 48%;
}

.graphiques canvas {
  width: 100% !important;
  height: auto !important;
}

@media screen and (max-width: 768px) {
  .graphiques > div {
    flex: 1 1 100%; /* un seul par ligne */
    max-width: 100%;
  }
}



</style>

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Vibe Sport</a>
      <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="../../deconnexion.php">Deconnexion</a>
        </li>
      </ul>
    </nav>
<?php include_once '../template/navigation.php'; ?>
  <!-- [ Pre-loader ] start -->
<div class="loader-bg">
  <div class="loader-track">
    <div class="loader-fill"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->
 <!-- [ Sidebar Menu ] start -->

<!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
<header class="pc-header">
  <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
<div class="me-auto pc-mob-drp">
  <ul class="list-unstyled">
    <!-- ======= Menu collapse Icon ===== -->
    <li class="pc-h-item pc-sidebar-collapse">
      <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
    <li class="pc-h-item pc-sidebar-popup">
      <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
    <li class="dropdown pc-h-item d-inline-flex d-md-none">
      <a
        class="pc-head-link dropdown-toggle arrow-none m-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false"
      >
        <i class="ti ti-search"></i>
      </a>
      <div class="dropdown-menu pc-h-dropdown drp-search">
        <form class="px-3">
          <div class="form-group mb-0 d-flex align-items-center">
            <i data-feather="search"></i>
            <input type="search" class="form-control border-0 shadow-none" placeholder="Search here. . .">
          </div>
        </form>
      </div>
    </li>
    <li class="pc-h-item d-none d-md-inline-flex">
      <form class="header-search">
        <i data-feather="search" class="icon-search"></i>
        <input type="search" class="form-control" placeholder="Search here. . .">
      </form>
    </li>
  </ul>
</div>
<!-- [Mobile Media Block end] -->
<div class="ms-auto">
  <ul class="list-unstyled">
    <li class="dropdown pc-h-item">
      <a
        class="pc-head-link dropdown-toggle arrow-none me-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false"
      >
        <i class="ti ti-mail"></i>
      </a>
      <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
        <div class="dropdown-header d-flex align-items-center justify-content-between">
          <h5 class="m-0">Message</h5>
          <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-x text-danger"></i></a>
        </div>
        <div class="dropdown-divider"></div>
        <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative" style="max-height: calc(100vh - 215px)">
          <div class="list-group list-group-flush w-100">
            <a class="list-group-item list-group-item-action">
              <div class="d-flex">
                <div class="flex-shrink-0">
                  <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
                </div>
                <div class="flex-grow-1 ms-1">
                  <span class="float-end text-muted">3:00 AM</span>
                  <p class="text-body mb-1">It's <b>Cristina danny's</b> birthday today.</p>
                  <span class="text-muted">2 min ago</span>
                </div>
              </div>
            </a>
            <a class="list-group-item list-group-item-action">
              <div class="d-flex">
                <div class="flex-shrink-0">
                  <img src="../assets/images/user/avatar-1.jpg" alt="user-image" class="user-avtar">
                </div>
                <div class="flex-grow-1 ms-1">
                  <span class="float-end text-muted">6:00 PM</span>
                  <p class="text-body mb-1"><b>Aida Burg</b> commented your post.</p>
                  <span class="text-muted">5 August</span>
                </div>
              </div>
            </a>
            <a class="list-group-item list-group-item-action">
              <div class="d-flex">
                <div class="flex-shrink-0">
                  <img src="../assets/images/user/avatar-3.jpg" alt="user-image" class="user-avtar">
                </div>
                <div class="flex-grow-1 ms-1">
                  <span class="float-end text-muted">2:45 PM</span>
                  <p class="text-body mb-1"><b>There was a failure to your setup.</b></p>
                  <span class="text-muted">7 hours ago</span>
                </div>
              </div>
            </a>
            <a class="list-group-item list-group-item-action">
              <div class="d-flex">
                <div class="flex-shrink-0">
                  <img src="../assets/images/user/avatar-4.jpg" alt="user-image" class="user-avtar">
                </div>
                <div class="flex-grow-1 ms-1">
                  <span class="float-end text-muted">9:10 PM</span>
                  <p class="text-body mb-1"><b>Cristina Danny </b> invited to join <b> Meeting.</b></p>
                  <span class="text-muted">Daily scrum meeting time</span>
                </div>
              </div>
            </a>
          </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="text-center py-2">
          <a href="#!" class="link-primary">View all</a>
        </div>
      </div>
    </li>
    <li class="dropdown pc-h-item header-user-profile">
      <a
        class="pc-head-link dropdown-toggle arrow-none me-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        data-bs-auto-close="outside"
        aria-expanded="false"
      >
        <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
        <span>Stebin Ben</span>
      </a>
      <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
        <div class="dropdown-header">
          <div class="d-flex mb-1">
            <div class="flex-shrink-0">
              <img src="../assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar wid-35">
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-1">Stebin Ben</h6>
              <span>UI/UX Designer</span>
            </div>
            <a href="#!" class="pc-head-link bg-transparent"><i class="ti ti-power text-danger"></i></a>
          </div>
        </div>
        <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button
              class="nav-link active"
              id="drp-t1"
              data-bs-toggle="tab"
              data-bs-target="#drp-tab-1"
              type="button"
              role="tab"
              aria-controls="drp-tab-1"
              aria-selected="true"
              ><i class="ti ti-user"></i> Profile</button
            >
          </li>
          <li class="nav-item" role="presentation">
            <button
              class="nav-link"
              id="drp-t2"
              data-bs-toggle="tab"
              data-bs-target="#drp-tab-2"
              type="button"
              role="tab"
              aria-controls="drp-tab-2"
              aria-selected="false"
              ><i class="ti ti-settings"></i> Setting</button
            >
          </li>
        </ul>
        <div class="tab-content" id="mysrpTabContent">
          <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel" aria-labelledby="drp-t1" tabindex="0">
            <a href="#!" class="dropdown-item">
              <i class="ti ti-edit-circle"></i>
              <span>Edit Profile</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-user"></i>
              <span>View Profile</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-clipboard-list"></i>
              <span>Social Profile</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-wallet"></i>
              <span>Billing</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-power"></i>
              <span>Logout</span>
            </a>
          </div>
          <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2" tabindex="0">
            <a href="#!" class="dropdown-item">
              <i class="ti ti-help"></i>
              <span>Support</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-user"></i>
              <span>Account Settings</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-lock"></i>
              <span>Privacy Center</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-messages"></i>
              <span>Feedback</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ti ti-list"></i>
              <span>History</span>
            </a>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>
 </div>
</header>
<!-- [ Header ] end -->



  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
 
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <div class="row">


       <section class="kpis"></section>
        <div class="card">
            <h3>💰 Revenus</h3>
            <p><?php echo number_format($total_sales, 0, '', ' '); // Ex : 1 055?> TND</p>
        </div>
        <div class="card">
            <h3>👥 Clients</h3>
            <p><?php echo number_format($total_users); ?></p>
        </div>
        <div class="card">
            <h3>🧑‍💼 Admins</h3>
            <p><?php echo number_format($total_admins); ?></p>
        </div>
        <div class="card">
            <h3>📦 Orders</h3>
            <p><?php echo number_format($total_orders); ?></p>
        </div>
  
       <div class="conversion-section" style="margin-top: 40px;">
  <h3 style="text-align:center;">📊 Taux de conversion mensuel</h3>
  <table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th>Mois</th>
        <th>Total paniers Passés</th>
        <th>Paniers payés</th>
        <th>Taux de conversion (%)</th>
      </tr>
    </thead>
    <tbody id="conversionBody"></tbody>
  </table>
</div>

       </section>




     <section class="graphiques">
      
      <div id="bar-chart-container">
        <h3 style="text-align:center; font-family:Arial; color:#333;">📊 Ventes par sport</h3>
        <canvas id="barChart"></canvas>
      </div>

      <div id="status-chart-container">
        <h3 style="text-align:center; font-family:Arial; color:#333;">🧾 Commandes par statut</h3>
        <canvas id="statusChart"></canvas>></canvas>
      </div>
      <div id="genre-sport-chart-container">
        <h3 style="text-align:center; font-family:Arial; color:#333;">🏆 Ventes par sport et genre</h3>
        <canvas id="genreSportChart"></canvas>
      </div>
      <div class="chart-card">
  <h3 style="text-align:center;">📈 Chiffre d'affaires mensuel</h3>
  <canvas id="caChart" width="600" height="300"></canvas>
</div>


</section>


<!-- debut script js pour line chart evolution ca par mois -->
<script>
function chargerChiffreAffaires() {
  fetch('../../inc/api_chiffre_affaires.php')
    .then(res => res.json())
    .then(data => {
      const labels = Array.from({ length: 12 }, (_, i) =>
        new Date(2024, i).toLocaleString('fr-FR', { month: 'long' })
      );

      const moisAvecData = data.map(d => Number(d.mois));
      const valeurs = labels.map((_, index) => {
        const match = data.find(d => Number(d.mois) === index + 1);
        return match ? parseFloat(match.chiffre_affaires) : 0;
      });

      new Chart(document.getElementById('caChart'), {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: "Chiffre d'affaires (TND)",
            data: valeurs,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            tension: 0.3,
            fill: true,
            pointBackgroundColor: '#4e73df'
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Montant (TND)'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Mois'
              }
            }
          },
          plugins: {
            legend: {
              display: true,
              position: 'top'
            }
          }
        }
      });
    })
    .catch(err => console.error("Erreur chargement chiffre d'affaires :", err));
}

document.addEventListener('DOMContentLoaded', () => {
  chargerChiffreAffaires();
});
</script>
  <!-- -------------------------------------------------------------- -->


<!-- -------debut script js pour line chart------------------------------ -->


<script>
  const ctx = document.getElementById('barChart').getContext('2d');

  fetch('../../inc/api_ventes_par_sport.php')
    .then(res => res.json())
    .then(data => {
      const labels = data.map(item => item.sport);
      const values = data.map(item => item.total_ventes);
   


      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Ventes par sport (TND)',
            data: values,
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: { display: true, text: 'Montant total des ventes (TND)' }

              
            },
            x: {
              title: { display: true, text: 'Sport' }
            }
          }
        }
      });
    })
    .catch(error => {
      console.error("Erreur lors du chargement :", error);
    });
</script>
         
          <!-- ------ fin Script js pour bar chart -------------------------- -->

          <!-- ------------------debut Script pour pie chart------------------- -->
<script>
  // Récupération du contexte du canvas
  const pieCtx = document.getElementById('statusChart').getContext('2d');

  // Appel à l'API pour récupérer les données de statut de commandes
  fetch('../../inc/api_commandes_par_statut.php')
    .then(response => response.json())
    .then(data => {
      // Extraire les libellés et les valeurs depuis le tableau JSON
      const labels = data.map(item => item.statut);
      const values = data.map(item => item.total);

      // Définir des couleurs pour chaque segment
      const colors = ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc'];

      // Création du graphique en pie chart
      new Chart(pieCtx, {
        type: 'pie',
        data: {
          labels: labels,
          datasets: [{
            label: 'Commandes par statut',
            data: values,
            backgroundColor: colors,
            hoverOffset: 12
          }]
        },
        options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: 'Répartition des commandes par statut',
              font: { size: 18 }
            },
            legend: {
              display: true,
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || '';
                  const value = context.parsed || 0;
                  return `${label} : ${value} commande(s)`;
                }
              }
            }
          }
        }
      });
    })
    .catch(error => {
      console.error("Erreur de chargement des données :", error);
    });
</script>
<!-- -------------------fin script pie chart----------------------------------- -->

     <!-- ------------------debut Script pour bar chart------------------- -->
      <script>
  const genreSportCtx = document.getElementById('genreSportChart').getContext('2d');

  fetch('../../inc/api_ventes_par_genre_sport.php')
    .then(res => res.json())
    .then(data => {
      // Extraire tous les sports et genres uniques
      const sports = [...new Set(data.map(item => item.sport))];
      const genres = [...new Set(data.map(item => item.genre))];
          


      // Construire un dataset pour chaque genre
      const datasets = genres.map((genre, index) => {
        const genreData = sports.map(sport => {
          const match = data.find(item => item.genre === genre && item.sport === sport);
          return match ? parseFloat(match.total_ventes) : 0;
        });

        // Couleurs dynamiques par genre
        const colorPalette = ['#4e73df', '#1cc88a', '#f6c23e'];
        return {
          label: genre,
          data: genreData,
          backgroundColor: colorPalette[index % colorPalette.length]
        };
      });

      // Création du graphique groupé
      new Chart(genreSportCtx, {
        type: 'bar',
        data: {
          labels: sports,
          datasets: datasets
        },
        options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: 'Ventes par sport et genre',
              font: { size: 18 }
            },
            tooltip: {
              mode: 'index',
              intersect: false
            },
            legend: {
              position: 'top'
            }
          },
          scales: {
            x: {
              title: {
                display: true,
                text: 'Sport'
              },
              stacked: false
            },
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Montant total des ventes (TND)'
              }
            }
          }
        }
      });
    })
    .catch(err => {
      console.error("Erreur lors du chargement des données :", err);
    });
</script>
<!-- -------------------fin script bar chart----------------------------------- -->


<section class="table-section">
  <h3 style="text-align:center;">🛍️ Produits vendus par genre</h3>

  <div style="text-align:center; margin-bottom: 20px;">
    <label for="genreFilter">Filtrer par catégorie :</label>
    <select id="genreFilter">
      <option value="Homme">Homme</option>
      <option value="Femme">Femme</option>
      <option value="Kids">Kids</option>
    </select>
  </div>

  <table id="produitsParGenre" border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
    <thead>
      <tr>
        <th>Produit</th>
        <th>Sport</th>
        <th>Quantité vendue</th>
        <th>Prix unitaire (TND)</th>
        <th>Total ventes (TND)</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</section>

<!-- ---------debut script table section---------- -->
<script>
function chargerProduitsParGenre(genre) {
  fetch(`../../inc/api_tableau_genre.php?genre=${encodeURIComponent(genre)}`)
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector('#produitsParGenre tbody');
      tbody.innerHTML = ''; // Vider le tableau avant de remplir
       let totalGeneral = 0;
      data.forEach(item => {
        const totalVente = parseFloat(item.total_ventes);
        totalGeneral += totalVente;
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${item.produit}</td>
          <td>${item.sport}</td>
          <td>${item.quantite_vendue}</td>
          <td>${parseFloat(item.prix_unitaire).toFixed(2)}</td>
          <td>${parseFloat(item.total_ventes).toFixed(2)}</td>
        `;
        tbody.appendChild(row);
      });
      // Ajouter la ligne de total final
      const totalRow = document.createElement('tr');
      totalRow.style.fontWeight = 'bold';
      totalRow.style.backgroundColor = '#f2f2f2';
      totalRow.innerHTML = `
        <td colspan="4" style="text-align:right;">Total général :</td>
        <td>${totalGeneral.toFixed(2)}</td>
      `;
      tbody.appendChild(totalRow);
    })
    .catch(err => console.error("Erreur chargement tableau :", err));
}

// Initial load
document.addEventListener('DOMContentLoaded', () => {
  const select = document.getElementById('genreFilter');
  chargerProduitsParGenre(select.value);

  select.addEventListener('change', () => {
    chargerProduitsParGenre(select.value);
  });
});
</script>


<!-- ----------fin script table section-------- -->

<section>

<h3 style="text-align:center;">📆 Ventes par sport/mois</h3>

<div style="display:flex; justify-content: center; gap:15px; flex-wrap: wrap;">
  <select id="anneeFilter">
    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--) echo "<option value='$y'>$y</option>"; ?>
  </select>
  <select id="moisFilter">
    <option value="">Tous les mois</option>
    <?php foreach (range(1,12) as $m) echo "<option value='$m'>" . date('F', mktime(0,0,0,$m,1)) . "</option>"; ?>
  </select>
<select id="SPORTFilter">
  <option value="">Tous les sports</option>
</select>

  <select id="genderFilter">
    <option value="">Tous les genres</option>
    <option value="Homme">Homme</option>
    <option value="Femme">Femme</option>
    <option value="Kids">Kids</option>
  </select>
</div>

<table id="ventesParMois" border="1" cellpadding="10" style="width:100%; margin-top:20px;">
  <thead>
    <tr>
      <th>Mois</th>
      <th>Sport</th>
      <th>Genre</th>
      <th>Total ventes (TND)</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

</section>

<script>
function chargerOptionsSports() {
  fetch('../../inc/api_sports.php')
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById('SPORTFilter');
      data.forEach(sport => {
        const option = document.createElement('option');
        option.value = sport;
        option.textContent = sport.charAt(0).toUpperCase() + sport.slice(1); // capitalisation
        select.appendChild(option);
      });
    })
    .catch(err => console.error("Erreur chargement sports :", err));
}
</script>


<script>
function chargerVentes() {
  const annee = document.getElementById('anneeFilter').value;
  const mois = document.getElementById('moisFilter').value;
  const SPORT = document.getElementById('SPORTFilter').value;
  const gender = document.getElementById('genderFilter').value;

  const url = `../../inc/api_ventes_mensuelles.php?annee=${annee}&mois=${mois}&sport=${SPORT}&genre=${gender}`;

  fetch(url)
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector('#ventesParMois tbody');
      tbody.innerHTML = '';

      let total = 0;
data.forEach(item => {
  const row = document.createElement('tr');
  row.innerHTML = `
    <td>${new Date(2024, item.mois - 1).toLocaleString('fr-FR', { month: 'long' })}</td>
    <td>${item.sport}</td>
    <td>${item.genre}</td>
    <td>${parseFloat(item.total_ventes).toFixed(2)}</td>
  `;
  total += parseFloat(item.total_ventes);
  tbody.appendChild(row);
});


      const totalRow = document.createElement('tr');
      totalRow.style.fontWeight = 'bold';
      totalRow.innerHTML = `<td colspan="3" style="text-align:right;">Total :</td><td>${total.toFixed(2)}</td>`;
      tbody.appendChild(totalRow);
    })
    .catch(err => console.error("Erreur :", err));
}

document.addEventListener('DOMContentLoaded', () => {
  ['anneeFilter', 'moisFilter', 'SPORTFilter', 'genderFilter'].forEach(id => {
    document.getElementById(id).addEventListener('change', chargerVentes);
  });
  chargerOptionsSports(); // Charger les sports
  chargerVentes(); // Charger par défaut
});
</script>




<script>
function chargerTauxConversion() {
  fetch('../../inc/api_conversion.php')
    .then(res => res.json())
    .then(data => {
      console.log("Données reçues :", data); // 👈 debug

      const tbody = document.getElementById('conversionBody');
      tbody.innerHTML = '';

    data.forEach(row => {
      const moisNom = new Date(2024, row.mois - 1).toLocaleString('fr-FR', { month: 'long' });

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${moisNom}</td>
        <td>${row.total_paniers}</td>
        <td>${row.paniers_payes}</td>
        <td>${parseFloat(row.taux_conversion).toFixed(2)}%</td>
      `;
      tbody.appendChild(tr);
    });

    })
    .catch(err => {
      console.error("Erreur chargement taux de conversion :", err);
    });
}

document.addEventListener('DOMContentLoaded', () => {
  chargerTauxConversion();
});
</script>




  <!-- [Page Specific JS] start -->
  <script src="../../js/apexcharts.min.js"></script>
  <script src="../../js/pages/dashboard-default.js"></script>

  <script src="../../js/popper.min.js"></script>
  <script src="../../js/simplebar.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="../../js/fonts/custom-font.js"></script>
  <script src="../../js/pcoded.js"></script>
  <script src="../../js/feather.min.js"></script>




<!-- ---------------------------------------------- -->


  

  <script>layout_change('light');</script>
  
  
  
  
  <script>change_box_container('false');</script>
  
  
  
  <script>layout_rtl_change('false');</script>
  
  
  <script>preset_change("preset-1");</script>
  
  
  <script>font_change("Public-Sans");</script>
  
    

</body>
<!-- [Body] end -->

</html>