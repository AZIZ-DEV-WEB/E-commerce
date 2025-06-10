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

// Appel de la fonction pour récupérer toutes les catégories
$categories = getAllCategories();
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">

    <title>Liste des Categories</title>
  

    <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/dashboard/">

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/dashboard.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">VibeSport</a>
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
            <h1 class="h2">Liste des Categories</h1>
            <div>
        <a  class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Ajouter</a>
      </div>

          </div>
            
                <!-- Affichage de la liste des catégories  --> 
      
          <div>
        

        <?php if(isset($_GET['delete']) && $_GET['delete']=="ok")
                    {
                      print '          <div class="alert alert-success">
                          categorie supprimé avec succés
                        </div>';
                    }?>

<?php if (isset($_GET['ajout']) && $_GET['ajout'] == "ok" && isset($_GET['nom'])): ?>
  <div id="success-alert" class="alert alert-success text-center">
            Catégorie  "<?php echo htmlspecialchars($_GET['nom']); ?>" ajoutée avec succès.
          </div>
        <?php endif; ?>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == "ok"): ?>
  <div id="deleteAlert" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
    La catégorie a été <strong>supprimée</strong> avec succès.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php endif; ?>

<?php if (isset($_GET['update']) && $_GET['update'] == "ok"): ?>
  <div id="success-alert"class="alert alert-success text-center">
    Catégorie mise à jour avec succès.
  </div>
<?php endif; ?>

<?php if (isset($_GET['erreur']) && $_GET['erreur'] == "nom_existe"): ?>
  <div class="alert alert-danger text-center">
    Le nom de la catégorie existe déjà. Veuillez en choisir un autre.
  </div>
<?php endif; ?>



                    <table class="table">
                <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($categories)): ?>
                  <?php foreach ($categories as $index => $categorie): ?>
                    <tr>
                      <th scope="row"><?php echo $index + 1; ?></th>
                      <td><?php echo htmlspecialchars($categorie['nom']); ?></td>
                      <td><?php echo htmlspecialchars($categorie['description']); ?></td>
                      <td>
                      <a
                        data-toggle="modal"
                        data-target="#editModal"
                        data-id="<?php echo $categorie['id']; ?>"
                        data-nom="<?php echo htmlspecialchars($categorie['nom']); ?>"
                        data-description="<?php echo htmlspecialchars($categorie['description']); ?>"
                        class="btn btn-primary btn-edit"
                          >
                   Modifier
                    </a>

                        <button class="btn btn-danger btn-delete" data-id="<?php echo $categorie['id']; ?>" data-nom="<?php echo htmlspecialchars($categorie['nom']); ?>" data-toggle="modal" data-target="#deleteModal">
  Supprimer
</button>
                        </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center">Aucune catégorie trouvée.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
                </table>
      </div>




        </main>
      </div>
    </div>


<!-- Modal Ajout  -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajout Categorie</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
  <form method="POST" action="ajout.php">
    <div class="form-group">
      <label for="nom">Nom de la catégorie</label>
      <input type="text" class="form-control" id="nom" name="nom" required>
    </div>
    <div class="form-group">
      <label for="description">Description</label>
      <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
      <button type="submit" class="btn btn-primary">Ajouter la catégorie</button>
    </div>
  </form>
</div>

      
    </div>
  </div>
</div>

<!--   -->

<!-- Modal Modif  -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modifier Categorie</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
  <form method="POST" action="modifier.php">
    <div class="form-group">
    <input type="hidden" id="edit-id" name="id">
      <label for="nom">Nom de la catégorie</label>
      <input type="text" class="form-control" id="edit-nom" name="nom" required>
    </div>
    <div class="form-group">
      <label for="description">Description</label>
      <textarea class="form-control" id="edit-description" name="description" rows="3" required></textarea>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
      <button type="submit" class="btn btn-primary">Modifier la catégorie</button>
    </div>
  </form>
</div>

      
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

<script>
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
</script>

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

<script>
  $(document).ready(function () {
    $('.btn-delete').click(function () {
      var id = $(this).data('id');
      var nom = $(this).data('nom');
      $('#delete-id').val(id);
      $('#delete-nom').text(nom);
    });
  });
</script>


<script>
  $(document).ready(function () {
    $('#editModal').on('show.bs.modal', function (event) {
      const button = $(event.relatedTarget);
      const id = button.data('id');
      const nom = button.data('nom');
      const description = button.data('description');

      // Remplir le formulaire
      $('#edit-id').val(id);
      $('#edit-nom').val(nom);
      $('#edit-description').val(description);
    });
  });
</script>


  </body>
</html>