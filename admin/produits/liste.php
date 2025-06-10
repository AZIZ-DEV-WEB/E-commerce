<!doctype html>

<?php
session_start();
// Inclusion de la page functions.php
include_once '../../inc/functions.php';
$conn=connect();
// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['admin_nom'])) {
  // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
  header('Location: ../connexion.php');
  exit;
}






$stmt = $conn->prepare("SELECT nom FROM categorie");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les données sport-catégorie et sport-type_produit
$sportParCategorie = getAllCategorieSports(); // SELECT * FROM categorie_sport
$typeParSport = getAllSportTypeProduit(); // SELECT * FROM sport_type_produit
$types = getAllTypesProduits();

?>








<script>
  const sportParCategorie = <?= json_encode($sportParCategorie) ?>;
  const typeParSport = <?= json_encode($typeParSport) ?>;
  const allTypes = <?= json_encode($types) ?>;
</script>


<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">

  <title>Liste des Produits</title>



  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

  <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/dashboard/">

  <!-- Bootstrap core CSS -->
  <link href="../../css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../css/dashboard.css" rel="stylesheet">
  <!-- Remix Icon CDN -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="css/fontawesome.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../../css/table&filter.css">

  <!-- jQuery (nécessaire pour Bootstrap 4) -->
</head>

<body>
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>


    <script>
      // Disparaît automatiquement après 3 secondes
      setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
          alert.classList.remove('show');
          alert.classList.add('fade');
          setTimeout(() => alert.remove(), 500);
        }
      }, 3000);

      // Supprimer ?success=1 de l'URL
      if (window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('success');
        window.history.replaceState({}, document.title, url.toString());
      }
    </script>
  <?php endif; ?>
  <?php if (isset($_GET['deleted']) && $_GET['deleted'] == "ok"): ?>
    <script>
      // Disparaît automatiquement après 3 secondes
      setTimeout(function() {
        const alert = document.getElementById('deleteAlert');
        if (alert) {
          alert.classList.remove('show');
          alert.classList.add('fade');
          setTimeout(() => alert.remove(), 500);
        }
      }, 3000);
      // Supprimer ?delete=1 de l'URL
      if (window.history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('deleted');
        window.history.replaceState({}, document.title, url.toString());
      }
    </script>
  <?php endif; ?>

  <?php if (isset($_GET['update']) && $_GET['update'] == "ok"): ?>
    <script>
      // Disparaît automatiquement après 3 secondes
      setTimeout(function() {
        const alert = document.getElementById('updateAlert');
        if (alert) {
          alert.classList.remove('show');
          alert.classList.add('fade');
          setTimeout(() => alert.remove(), 500);
        }
      }, 3000);
      // Supprimer ?update=ok de l'URL sans recharger la page
      if (window.history.replaceState) {
        const new_url = new URL(window.location);
        new_url.searchParams.delete('update');
        window.history.replaceState({}, document.title, new_url.pathname + new_url.search);
      }
    </script>
  <?php endif; ?>


  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Vibe Sport</a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
           <a class="nav-link" href="../../admin/deconnexion.php">Deconnexion</a>
      </li>
    </ul>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <?php include_once '../template/navigation.php'; ?>


      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
           <h1 class="page-title">Liste de Produits</h1>
          <div>
            <a class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Ajouter</a>
          </div>

        </div>

        <!-- Affichage de la liste des produits  -->
         <!-- 🔍 Filtres produits -->
<div class="filter-container">
  <form id="filtre-form" onsubmit="return false;">
    <!-- Categorie -->
    <label for="categorie">Catégorie :</label>
    <select id="categorie" name="categorie">
      <option value="">-- Choisir une catégorie --</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat['nom']) ?>"><?= htmlspecialchars($cat['nom']) ?></option>
      <?php endforeach; ?>
    </select>

    <!-- Sport (rempli dynamiquement selon la catégorie) -->
    <label for="sport">Sport :</label>
    <select id="sport" name="sport">
      <option value="">-- Choisir un sport --</option>
    </select>

    <!-- Type de produit (rempli dynamiquement selon le sport) -->
    <label for="type_produit">Type de produit :</label>
    <select id="type_produit" name="type_produit">
      <option value="">-- Choisir un type --</option>
    </select>
  </form>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
  const categorieSelect = document.getElementById('categorie');
  const sportSelect = document.getElementById('sport');
  const typeProduitSelect = document.getElementById('type_produit');

  // ⚙️ Quand on change de catégorie
  categorieSelect.addEventListener('change', function () {
    const categorie = this.value;

    // Réinitialiser les champs suivants
    sportSelect.innerHTML = '<option value="">-- Choisir un sport --</option>';
    typeProduitSelect.innerHTML = '<option value="">-- Choisir un type --</option>';

    if (categorie) {
      fetch(`../APIs/api_sports_par_categorie.php?categorie=${encodeURIComponent(categorie)}`)
        .then(response => response.json())
        .then(sports => {
          sports.forEach(sport => {
            const option = document.createElement('option');
            option.value = sport;
            option.textContent = sport;
            sportSelect.appendChild(option);
          });
        })
        .catch(error => console.error('Erreur lors de la récupération des sports:', error));
    }
  });

  // ⚙️ Quand on change de sport
  sportSelect.addEventListener('change', function () {
    const sport = this.value;

    typeProduitSelect.innerHTML = '<option value="">-- Choisir un type --</option>';

    if (sport) {
      fetch(`../APIs/api_types_par_sport.php?sport=${encodeURIComponent(sport)}`)
        .then(response => response.json())
        .then(types => {
          types.forEach(type => {
            const option = document.createElement('option');
            option.value = type.id;
            option.textContent = type.nom;
            typeProduitSelect.appendChild(option);
          });
        })
        .catch(error => console.error('Erreur lors de la récupération des types de produit:', error));
    }
  });
});
</script>

<script>
function fetchProduits() {
  const cat = document.getElementById("categorie").value;
  const sport = document.getElementById("sport").value;
  const type = document.getElementById("type_produit").value;

  const params = new URLSearchParams();
  if (cat) params.append("categorie", cat);
  if (sport) params.append("sport", sport);
  if (type) params.append("type_produit", type);

  fetch("../APIs/api_filtrer_produits.php?" + params.toString())
    .then(res => res.json())
    .then(data => {
      const tableBody = document.getElementById("produits-table-body");
      tableBody.innerHTML = ""; // Vider le contenu actuel

      if (data.length === 0) {
        tableBody.innerHTML = `
          <tr>
            <td colspan="13" class="text-center">Aucun produit trouvé.</td>
          </tr>
        `;
        return;
      }

      data.forEach((produit, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
          <th scope="row">${index + 1}</th>
          <td>${produit.nom}</td>
          <td>${produit.description}</td>
          <td>${produit.image}</td>
          <td>${produit.prix}</td>
          <td>${produit.categorie}</td>
          <td>${produit.quantite_stock}</td>
          <td>${produit.sport}</td>
          <td>${produit.type_produit_nom}</td>
          <td>${produit.createur}</td>
          <td>${produit.date_creation}</td>
          <td>${produit.offre_speciale}</td>
          <td>
            <a
              data-toggle="modal"
              data-target="#editModal"
              data-id="${produit.id}"
              data-nom="${produit.nom}"
              data-description="${produit.description}"
              data-prix="${produit.prix}"
              data-image="${produit.image}"
              data-categorie="${produit.categorie}"
              data-quantite="${produit.quantite_stock}"
              data-sport="${produit.sport}"
              data-type_produit_id="${produit.type_produit_id}"
              data-createur="${produit.createur}"
              data-offre_speciale="${produit.offre_speciale}"
              class="btn btn-primary btn-edit"
            >
              <i class="ri-edit-line"></i>
            </a>
            <button class="btn btn-danger btn-delete" data-id="${produit.id}" data-nom="${produit.nom}" data-toggle="modal" data-target="#deleteModal">
              <i class="ri-delete-bin-6-line"></i>
            </button>
          </td>
        `;

        tableBody.appendChild(row);
      });
    });
}

// Rafraîchir les produits à chaque changement
document.getElementById("categorie").addEventListener("change", fetchProduits);
document.getElementById("sport").addEventListener("change", fetchProduits);
document.getElementById("type_produit").addEventListener("change", fetchProduits);

// Premier chargement si une valeur est déjà sélectionnée
document.addEventListener("DOMContentLoaded", fetchProduits);
</script>




        <div>


          <?php if (isset($_GET['delete']) && $_GET['delete'] == "ok") {
            print '          <div class="alert alert-success">
                          categorie supprimé avec succés
                        </div>';
          } ?>

          <?php if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_GET['nom'])): ?>
            <div id="success-alert" class="alert alert-success text-center">
              Produit "<?php echo htmlspecialchars($_GET['nom']); ?>" ajouté avec succès.
            </div>
          
          
          
            <?php endif; ?>

          <?php if (isset($_GET['deleted']) && $_GET['deleted'] == "ok" && isset($_GET['nom'])): ?>
            <div id="deleteAlert" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
              Le produit "<?php echo htmlspecialchars($_GET['nom']); ?>" a été <strong>supprimée</strong> avec succès.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php endif; ?>

          <?php if (isset($_GET['update']) && $_GET['update'] == "ok"): ?>
            <div id="updateAlert" class="alert alert-success text-center">
              Catégorie mise à jour avec succès.
            </div>
          <?php endif; ?>

          <?php if (isset($_GET['erreur']) && $_GET['erreur'] == "nom_existe"): ?>
            <div class="alert alert-danger text-center">
              Le nom de la catégorie existe déjà. Veuillez en choisir un autre.
            </div>
          <?php endif; ?>












         <table class="table table">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Nom</th>
                <th scope="col">Description</th>
                <th scope="col">Image</th>
                <th scope="col">Prix</th>
                <th scope="col">Categorie</th>
                <th scope="col">Quantité</th>
                <th scope="col">Sport</th>
                <th scope="col">Type Produit</th>
                <th scope="col">Createur</th>
                <th scope="col">Date de Creation</th>
                <th scope="col">Offre Spéciale</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody id="produits-table-body">
              <?php

              $conn = connect();
              // Requête pour récupérer les produits avec leurs catégories
              $sql =  "SELECT p.*, tp.nom AS type_produit_nom
        FROM produit p
        JOIN type_produit tp ON p.type_produit_id = tp.id";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
              ?>
              <?php if (!empty($produits)): ?>

                <?php
                foreach ($produits as $index => $produit): ?>
                  <tr>
                    <th scope="row"><?php echo $index + 1; ?></th>
                    <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                    <td><?php echo htmlspecialchars($produit['description']); ?></td>
                   <td><?php echo htmlspecialchars($produit['image']); ?></td>


                    <td><?php echo htmlspecialchars($produit['prix']); ?></td>
                    <td><?php echo htmlspecialchars($produit['categorie']); ?></td>
                    <td><?php echo htmlspecialchars($produit['quantite_stock']); ?></td>
                    <td><?php echo htmlspecialchars($produit['sport']); ?></td>
                    <td><?php echo htmlspecialchars($produit['type_produit_nom']); ?></td>

                    <td><?php echo htmlspecialchars($produit['createur']); ?></td>
                    <td><?php echo htmlspecialchars($produit['date_creation']); ?></td>
                    <td><?php echo htmlspecialchars($produit['offre_speciale']); ?></td>



                    <td>
                      <a
                        data-toggle="modal"
                        data-target="#editModal"
                        data-id="<?= $produit['id']; ?>"
                        data-nom="<?= htmlspecialchars($produit['nom']); ?>"
                        data-description="<?= htmlspecialchars($produit['description']); ?>"
                        data-prix="<?= htmlspecialchars($produit['prix']); ?>"
                        data-image="<?= htmlspecialchars($produit['image']); ?>"
                        data-categorie="<?= htmlspecialchars($produit['categorie']); ?>"
                        data-quantite="<?= htmlspecialchars($produit['quantite_stock']); ?>"
                        data-sport="<?= htmlspecialchars($produit['sport']); ?>"
                        data-type_produit_id="<?= htmlspecialchars($produit['type_produit_id']); ?>"
                        data-createur="<?= htmlspecialchars($produit['createur']); ?>"
                        data-offre_speciale="<?= htmlspecialchars($produit['offre_speciale']); ?>"
                        class="btn btn-primary btn-edit">
                        <i class="ri-edit-line"></i>
                      </a>


                      <button class="btn btn-danger btn-delete" data-id="<?php echo $produit['id']; ?>" data-nom="<?php echo htmlspecialchars($produit['nom']); ?>" data-toggle="modal" data-target="#deleteModal">
                        <i class="ri-delete-bin-6-line"></i>
                        <!-- pour l'icône de la corbeille -->
                      </button>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-center">Aucun Produit trouvée.</td>
                </tr>
              <?php endif; ?>

            </tbody>
          </table>
        </div>
    </div>
      </div>

      </main>
      </div>
  </div>







  <!-- Modal Ajout  -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ajout Produit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="ajout.php" enctype="multipart/form-data">
            <div class="form-group">
              <label for="nom">Nom du Produit</label>
              <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="form-group">
              <label for="nom">Prix</label>
              <input type="number" step="0.01" class="form-control" id="prix" name="prix" placeholder="Prix" required>
            </div>

            <div class="form-group">
              <input type="file" class="form-control" name="image" required>
            </div>
            <?php
            // Récupérer les catégories depuis la base de données
            $stmt = $conn->prepare("SELECT nom FROM categorie");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="form-group">
              <label for="categorie">Catégorie</label>
                <select name="categorie" id="categorie" class="form-control" required>
                  <?php foreach ($categories as $categorie): ?>
                    <option value="<?= htmlspecialchars($categorie['nom']) ?>"><?= $categorie['nom'] ?></option>
                  <?php endforeach; ?>
                </select>
            </div>

         

            <div class="form-group">
              <label for="quantite">Quantité</label>
              <input type="number" class="form-control" name="quantite" required>
            </div>
            <input type="hidden" name="createur" value="<?php echo $_SESSION['admin_id'] ?>" required>

         <div class="form-group">
              <label for="sportname">Sport</label>
              <select name="sportname" id="sportname" class="form-control" required>
                <option value="">Sélectionnez un sport</option>
                <?php foreach ($sportsAssocies as $sport): ?>
                  <option value="<?= htmlspecialchars($sport) ?>"><?= htmlspecialchars($sport) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            
            <!-- Type produit (sera filtré par JS) -->
            <div class="form-group">
                    <label for="type_produit" class="form-label">Catégorie de produit :</label>
                            <select name="type_produit" id="type_produit" class="form-control">
                                <option value="">Tous</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?= $type['id']; ?>" >
                                        <?= htmlspecialchars($type['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
             </div>




            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary">Ajouter le Produit</button>
            </div>
          </form>
        </div>


      </div>
    </div>
  </div>

  <!--   -->
  <!-- Modal Suppression -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="deleteForm" method="GET" action="supprimer.php">
        <input type="hidden" name="id" id="delete-id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmer la suppression</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Voulez-vous vraiment supprimer la catégorie <strong id="delete-nom"></strong> ?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-danger">Supprimer</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Modif  -->
  <!-- Modal Modif -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="modifier.php" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Modifier Produit</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <input type="hidden" id="edit-id" name="id">
            <input type="hidden" id="edit-ancienne-image" name="ancienne_image"> <!-- 👈 champ caché pour l'image existante -->

            <div class="form-group">
              <label>Nom du Produit</label>
              <input type="text" class="form-control" id="edit-nom" name="nom" required>
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" id="edit-description" name="description" rows="3" required></textarea>
            </div>

            <div class="form-group">
              <label>Prix</label>
              <input type="number" step="0.01" class="form-control" id="edit-prix" name="prix" required>
            </div>

            <div class="form-group">
              <label>Image actuelle</label><br>
              <img id="edit-apercu-image" src="" style="max-width: 100px; max-height: 100px;">
            </div>

            <div class="form-group">
              <label for="image">Changer l'image</label>
              <input type="file" class="form-control" id="edit-image" name="image">
            </div>
            <?php
              // Récupérer les catégories depuis la base de données
            $stmt = $conn->prepare("SELECT nom FROM categorie");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>?>
           


            <div class="form-group">
              <label>Catégorie</label>
              <select class="form-control" id="edit-categorie" name="categorie" required>
               <option value="">Sélectionner</option>
                  <?php foreach ($categories as $cat): ?>
                      <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                    <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Quantité</label>
              <input type="number" class="form-control" id="edit-quantite" name="quantite" required>
            </div>

            <div class="form-group">
              <label>Sport</label>
              <select class="form-control" id="edit-sport" name="sportname" required>
                <option value="">Sélectionner</option>
                <?php foreach ($sportsAssocies as $sport): ?>
                  <option value="<?= htmlspecialchars($sport) ?>"><?= htmlspecialchars($sport) ?></option>
                <?php endforeach; ?>                
              </select>
            </div>
         

            

            <div class="form-group">
              <label>Type de Produit</label>
              <select class="form-control" id="edit-type-produit" name="type_produit" required>
                <option value="">Sélectionner</option>
                  <?php foreach ($types as $type): ?>
                                    <option value="<?= $type['id']; ?>" >
                                        <?= htmlspecialchars($type['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Créateur</label>
              <input type="text" class="form-control" id="edit-createur" name="createur" required>
            </div>
          </div>

          <div class="form-group">
            <label>Offre Spéciale</label>
            <select class="form-control" id="edit-offre-speciale" name="offre_speciale" required>
              <option value="oui">Oui</option>
              <option value="non">Non</option>
            </select>
          </div>


          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Modifier</button>
          </div>
        </form>
      </div>
    </div>
  </div>







  <!-- Bootstrap core JavaScript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5hb7x1l5e1b5e1b5e1b5e1b5e1b5e1b5e1b5e1" crossorigin="anonymous"></script>

  <!-- Popper.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaHfN3Ij0x04k6zJr74NZxj0x" crossorigin="anonymous"></script>

  <!-- Bootstrap JavaScript -->
  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
  <script>
    feather.replace()
  </script>

  <!-- Graphs -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.1/dist/Chart.min.js"></script>

  <!-- jQuery (nécessaire pour Bootstrap 4) -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

  <!-- Popper.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaHfN3Ij0x04k6zJr74NZxj0x" crossorigin="anonymous"></script>

  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


  



  <script>
document.addEventListener('DOMContentLoaded', function () {
    const sportSelect = document.getElementById('sportname');
    const typeProduitSelect = document.getElementById('type_produit');

    sportSelect.addEventListener('change', function () {
        const selectedSport = sportSelect.value;

        // Vider la liste actuelle
        typeProduitSelect.innerHTML = '<option value="">Chargement...</option>';

        if (selectedSport) {
            fetch('../APIs/api_types_par_sport.php?sport=' + encodeURIComponent(selectedSport))
                .then(response => response.json())
                .then(data => {
                    // Nettoyer les options
                    typeProduitSelect.innerHTML = '<option value="">Tous</option>';

                    // Ajouter les nouveaux types
                    data.forEach(function (type) {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.nom;
                        typeProduitSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erreur chargement types produits:', error);
                    typeProduitSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            // Aucun sport sélectionné → reset de la liste
            typeProduitSelect.innerHTML = '<option value="">Tous</option>';
        }
    });
});
</script>

<script>
document.getElementById('categorie').addEventListener('change', function () {
    const selectedCategorie = this.value;
    const sportSelect = document.getElementById('sportname');

    sportSelect.innerHTML = '<option>Chargement...</option>';

    fetch('../APIs/api_sports_par_categorie.php?categorie=' + encodeURIComponent(selectedCategorie))
        .then(response => response.json())
        .then(data => {
            sportSelect.innerHTML = '<option value="">Sélectionnez un sport</option>';

            if (data.length > 0) {
                data.forEach(sport => {
                    const option = document.createElement('option');
                    option.value = sport;
                    option.textContent = sport;
                    sportSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Aucun sport trouvé';
                sportSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des sports :', error);
            sportSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        });
});
</script>


  <script>
    // Masquer automatiquement le message de succès après 2 secondes
    document.addEventListener("DOMContentLoaded", function() {
      const alertBox = document.getElementById("success-alert");
      if (alertBox) {
        setTimeout(() => {
          alertBox.style.display = "none";
        }, 2000);
      }
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const deleteAlert = document.getElementById('deleteAlert');
      if (deleteAlert) {
        deleteAlert.style.display = 'block';
        setTimeout(() => {
          deleteAlert.classList.remove('show');
          deleteAlert.classList.add('hide');
        }, 3000); // 3 secondes
      }
    });
  </script>


  <script>
    $(document).ready(function() {
      $('.btn-delete').click(function() {
        var id = $(this).data('id');
        var nom = $(this).data('nom');

        $('#delete-id').val(id);
        $('#delete-nom').text(nom);

      });
    });
  </script>


  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const editCategorie = document.getElementById('edit-categorie');
      const editSport = document.getElementById('edit-sport');
      const editType = document.getElementById('edit-type-produit');

      function resetSelect(select, placeholder = "Sélectionnez une option") {
        select.innerHTML = `<option value="">${placeholder}</option>`;
      }

      // Re-remplit le sport et le type si une catégorie est modifiée
      editCategorie.addEventListener('change', () => {
        const cat = editCategorie.value;
        resetSelect(editSport, "Sélectionnez un sport");
        resetSelect(editType, "Sélectionnez un type");

        const sports = sportParCategorie
          .filter(item => item.categorie_nom === cat)
          .map(item => item.sport);

        for (let sport of sports) {
          editSport.innerHTML += `<option value="${sport}">${sport.charAt(0).toUpperCase() + sport.slice(1)}</option>`;
        }
      });

      editSport.addEventListener('change', () => {
        const sport = editSport.value;
        resetSelect(editType, "Sélectionnez un type");

        const typesIds = typeParSport
          .filter(item => item.sport === sport)
          .map(item => parseInt(item.type_produit_id));

        for (let type of allTypes) {
          if (typesIds.includes(parseInt(type.id))) {
            editType.innerHTML += `<option value="${type.id}">${type.nom}</option>`;
          }
        }
      });

      // Lorsqu'on ouvre la modale, pré-remplir les champs
      $('#editModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const modal = $(this);

        const id = button.data('id');
        const nom = button.data('nom');
        const description = button.data('description');
        const prix = button.data('prix');
        const image = button.data('image');
        const categorie = button.data('categorie');
        const quantite = button.data('quantite');
        const sport = button.data('sport');
        const typeId = button.data('type_produit_id');
        const createur = button.data('createur');
        const offre = button.data('offre_speciale');

        modal.find('#edit-id').val(id);
        modal.find('#edit-nom').val(nom);
        modal.find('#edit-description').val(description);
        modal.find('#edit-prix').val(prix);
        modal.find('#edit-apercu-image').attr('src', image);
        modal.find('#edit-ancienne-image').val(image);
        modal.find('#edit-categorie').val(categorie);
        modal.find('#edit-quantite').val(quantite);
        modal.find('#edit-createur').val(createur);
        modal.find('#edit-offre-speciale').val(offre);

        // ➤ Remplir les sports
        const sports = sportParCategorie
          .filter(item => item.categorie_nom === categorie)
          .map(item => item.sport);

        resetSelect(editSport, "Sélectionnez un sport");
        for (let s of sports) {
          editSport.innerHTML += `<option value="${s}">${s.charAt(0).toUpperCase() + s.slice(1)}</option>`;
        }
        editSport.value = sport;

        // ➤ Remplir les types
        const typesIds = typeParSport
          .filter(item => item.sport === sport)
          .map(item => parseInt(item.type_produit_id));

        resetSelect(editType, "Sélectionnez un type");
        for (let type of allTypes) {
          if (typesIds.includes(parseInt(type.id))) {
            editType.innerHTML += `<option value="${type.id}">${type.nom}</option>`;
          }
        }
        editType.value = typeId;
      });
    });
  </script>


  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const categorieSelect = document.getElementById('categorie');
      const sportSelect = document.getElementById('sport');
      const typeSelect = document.getElementById('type_produit_id');

      // Réinitialisation
      function resetSelect(select, placeholder = "Sélectionnez une option") {
        select.innerHTML = `<option value="">${placeholder}</option>`;
      }

      // Remplir les sports selon la catégorie
      categorieSelect.addEventListener('change', () => {
        const cat = categorieSelect.value;
        resetSelect(sportSelect, "Sélectionnez un sport");
        resetSelect(typeSelect, "Sélectionnez un type");

        const sports = sportParCategorie
          .filter(item => item.categorie_nom === cat)
          .map(item => item.sport);

        for (let sport of sports) {
          sportSelect.innerHTML += `<option value="${sport}">${sport.charAt(0).toUpperCase() + sport.slice(1)}</option>`;
        }
      });

      // Remplir les types de produits selon le sport choisi
      sportSelect.addEventListener('change', () => {
        const sport = sportSelect.value;
        resetSelect(typeSelect, "Sélectionnez un type");

        const typesIds = typeParSport
          .filter(item => item.sport === sport)
          .map(item => parseInt(item.type_produit_id));

        for (let type of allTypes) {
          if (typesIds.includes(parseInt(type.id))) {
            typeSelect.innerHTML += `<option value="${type.id}">${type.nom}</option>`;
          }
        }
      });
    });
  </script>





</body>

</html>