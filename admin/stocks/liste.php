<!doctype html>


<?php
session_start();
// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['admin_nom'])) {
    // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: connexion.php');
    exit;
}
// Inclusion de la page functions.php
include_once '../../inc/functions.php';
// Connexion à la base de données
$conn=connect();

// Appel de la fonction pour récupérer toutes les catégories

$stocks = getAllStocks();
//recuperer les categories
$stmt = $conn->prepare("SELECT nom FROM categorie");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Stock de Produits</title>
  

    <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/dashboard/">
    <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/dashboard.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="css/fontawesome.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/table&filter.css">

  </head>

  <body>
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
           <h1 class="page-title">Stock de Produits</h1>

  

          </div>
            
                <!-- Affichage des Stock de Produits --> 
                          <!-- 🔍 Filtres produits -->
<div class="filter-container">
  <form id="filtre-form" onsubmit="return false;">
    <!-- Categorie -->
    
    <select id="categorie" name="categorie">
      <option value="">-- Choisir une catégorie --</option>
      <?php foreach ($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat['nom']) ?>"><?= htmlspecialchars($cat['nom']) ?></option>
      <?php endforeach; ?>
    </select>

    <!-- Sport (rempli dynamiquement selon la catégorie) -->
    
    <select id="sport" name="sport">
      <option value="">-- Choisir un sport --</option>
    </select>

    <!-- Type de produit (rempli dynamiquement selon le sport) -->
   
    <select id="type_produit" name="type_produit">
      <option value="">-- Choisir un type de produit --</option>
    </select>
  </form>
</div>

      <div>

<?php if (isset($_GET['update']) && $_GET['update'] == "ok"): ?>
  <div id="success-alert"class="alert alert-success text-center">
    Stock mise à jour avec succès.
  </div>
<?php endif; ?>
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
           <td>${produit.categorie}</td>
          <td>${produit.sport}</td>
          <td>${produit.type_produit_nom}</td>
          <td>${produit.nom}</td>         
          <td>${produit.quantite_stock}</td>
       
         
          <td>
            <a
              data-toggle="modal"
              data-target="#editModal"
              data-id="${produit.id}"
              data-nom="${produit.nom}"
              data-image="${produit.image}"
              data-categorie="${produit.categorie}"
              data-quantite="${produit.quantite_stock}"
              data-sport="${produit.sport}"
              data-type_produit_id="${produit.type_produit_id}"
              class="btn btn-primary btn-edit"
            >
              <i class="ri-edit-line">Modifier</i>
            </a>
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







<table class="table table">
             <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Categorie</th>
                    <th scope="col">Sport</th>
                    <th scope="col">Type de Produit</th>
                    <th scope="col">Produit</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="produits-table-body">
                    <?php if (!empty($stocks)): ?>
                    
                    <?php
                         foreach ($stocks as $index => $stock): ?>
                        <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td><?php echo htmlspecialchars($stock['categorie']); ?></td>
                        <td><?php echo htmlspecialchars($stock['sport']); ?></td>
                        <td><?php echo htmlspecialchars($stock['type_produit']); ?></td>
                        <td><?php echo htmlspecialchars($stock['nom']); ?></td>  
                        <td><?php echo htmlspecialchars($stock['quantite_stock']); ?></td>
                        <td>
                      <a
                        data-toggle="modal"
                        data-target="#editModal"
                        data-id="<?php echo $stock['id']; ?>"
                        data-categorie="<?php echo htmlspecialchars($stock['categorie']); ?>"
                        data-sport="<?php echo htmlspecialchars($stock['sport']); ?>"
                        data-type="<?php echo htmlspecialchars($stock['type_produit']); ?>"
                        data-nom="<?php echo htmlspecialchars($stock['nom']); ?>"
                        data-image="<?php echo htmlspecialchars($stock['image']); ?>"
                        data-quantite="<?php echo htmlspecialchars($stock['quantite_stock']); ?>"
                        class="btn btn-primary btn-edit"> Modifier</a>

                    </td>
                    </tr>

       
               
                      
                


                        </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center">Aucun stock trouvée.</td>
                  </tr>
                <?php endif; ?>
            
              </tbody>
                </table>
      </div>




        </main>
      </div>
    </div>




<!-- Modal Modif  -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modifier Stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
  <form method="POST" action="modifier.php">
    <input type="hidden" id="edit-id" name="idstock" value="">
    <div class="form-group">
  <label for="edit-categorie">Catégorie</label>
  <input type="text" class="form-control" id="edit-categorie" name="categorie" readonly>
</div>
<div class="form-group">
  <label for="edit-sport">Sport</label>
  <input type="text" class="form-control" id="edit-sport" name="sport" readonly>
</div>
<div class="form-group">
  <label for="edit-type">Type de Produit</label>
  <input type="text" class="form-control" id="edit-type" name="type_produit" readonly>
</div>
<div class="form-group">
  <label for="edit-nom">Nom</label>
  <input type="text" class="form-control" id="edit-nom" name="nom" readonly>
</div>
  <div class="form-group">
  <label for="edit-image">Image</label>
  <input type="text" class="form-control" id="edit-image" name="image" readonly>
  </div>

    <div class="form-group">
      <label for="quantite">Quantité</label>
      <input type="number" step="1" class="form-control" id="edit-quantite" name="nouvelle_quantite_stock" required>
      </div>
 
 

    

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
      <button type="submit" class="btn btn-primary">Modifier le Stock</button>
    </div>
  </form>
</div>

      
    </div>
  </div>
</div>

    



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5hb7x1l5e1b5e1b5e1b5e1b5e1b5e1b5e1b5e1b5e1b5e1" crossorigin="anonymous"></script>

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
  // Masquer automatiquement le message de succès après 2 secondes
  document.addEventListener("DOMContentLoaded", function () {
    const alertBox = document.getElementById("success-alert");
    if (alertBox) {
      setTimeout(() => {
        alertBox.style.display = "none";
      }, 2000);
    }
  });
</script>

<!-- <script>
  document.addEventListener("DOMContentLoaded", function () {
    const deleteAlert = document.getElementById('deleteAlert');
    if (deleteAlert) {
      deleteAlert.style.display = 'block';
      setTimeout(() => {
        deleteAlert.classList.remove('show');
        deleteAlert.classList.add('hide');
      }, 3000); // 3 secondes
    }
  });
</script> -->




<script>
  $(document).ready(function () {
    $('#editModal').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const id = button.data('id');
      const categorie = button.data('categorie');
      const sport = button.data('sport');
      const type = button.data('type');
      const nom = button.data('nom');
      const image = button.data('image');
      const quantite = button.data('quantite');

      // Remplir le formulaire
      $('#edit-id').val(id);
      $('#edit-nom').val(nom);
      $('#edit-image').val(image);
      $('#edit-quantite').val(quantite);
      $('#edit-categorie').val(categorie);
      $('#edit-sport').val(sport);
      $('#edit-type').val(type);  
    });
  });
</script>


  </body>
</html>