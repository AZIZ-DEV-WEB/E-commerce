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
$visiteurs = getAllVisitors();
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">

    <title>Liste des Visiteurs non approuvés</title>
  

    <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/dashboard/">

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/dashboard.css" rel="stylesheet">
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
            <h1 class="h2">Liste des Visiteurs </h1>


          </div>
            
                <!-- Affichage de la liste des visiteurs  -->
                  
                
          <div>
        

          <?php if (isset($_GET['validation']) && $_GET['validation'] == "ok" && isset($_GET['nom']) && isset($_GET['prenom'])): ?>
    <div id="success-alert" class="alert alert-success text-center">
        Visiteur "<?php echo htmlspecialchars($_GET['nom'] . ' ' . $_GET['prenom']); ?>" validé avec succès.
    </div>
<?php endif; ?>

<?php if (isset($_GET['invalidation']) && $_GET['invalidation'] == "ok"): ?>
    <div class="alert alert-success text-center">
        L'utilisateur a été invalidé avec succès.
    </div>
<?php elseif (isset($_GET['erreur']) && $_GET['erreur'] == "id_invalide"): ?>
    <div class="alert alert-danger text-center">
        ID utilisateur invalide.
    </div>
<?php endif; ?>


     <!-- liste des visiteurs non validés -->
           <table class="table table">
                <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">email</th>
                    <th scope="col">Nom et  Prenom</th>
                    <th scope="col">telephone</th>
                    <th scope="col">Etat</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
  
                <?php if (!empty($visiteurs)): ?>
                  
                  <?php
                     foreach ($visiteurs as $index => $visiteur): ?>
                    <tr>
                      <th scope="row"><?php echo $index + 1; ?></th>
                      <td><?php echo htmlspecialchars($visiteur['email']); ?></td>
                      <td><?php echo htmlspecialchars($visiteur['nom'] . ' ' . $visiteur['prenom']); ?></td>
                      <td><?php echo htmlspecialchars($visiteur['telephone']); ?></td>
                      <td><?php if($visiteur['etat']==1){
                                              echo '<span class="badge badge-success">Validé</span>';
                                              }else{
                                                echo '<span class="badge badge-danger">Non validé</span>';
                                              }
                                              // echo $_visiteur['etat'] == 1 ?
                                              
?>

                      </td>

                      
                      <td>
                        <?php if ($visiteur['etat'] == 1): ?>
                          <!-- <span class="badge badge-success">Validé</span> -->
                          <a href="invalider.php?id=<?php echo $visiteur['id']; ?>" class="btn btn-danger btn-sm">
                            Invalider
                        </a>
                        <?php else: ?>
                        <a href="valider.php?id=<?php echo $visiteur['id']; ?>" class="btn btn-success">
                          Valider
                    </a>
                        

                    <?php endif; ?>

                        </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center">Aucun visiteur trouvé.</td>
                  </tr>
                <?php endif; ?>
            
              </tbody>
                </table>
      </div>




        </main>
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










  </body>
</html>