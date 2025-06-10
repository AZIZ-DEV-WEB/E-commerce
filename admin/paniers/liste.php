<?php
session_start();
require_once '../../inc/functions.php';

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../connexion.php');
    exit;
}

// Récupère tous les paniers et les états disponibles
$paniers = getAllpaniers();
$etats_disponibles = getEtatsPanier();

$message = null;

// Traitement du formulaire POST pour mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['panier_id'], $_POST['statut'])) {
    $panier_id = intval($_POST['panier_id']);
    $statut = trim($_POST['statut']);

    if (updatePanierEtat($panier_id, $statut)) {
        $message = "✅ Le panier #$panier_id a été mis à jour avec succès.";
    } else {
        $message = "❌ Erreur lors de la mise à jour du panier #$panier_id.";
    }

    // Rafraîchit les paniers après mise à jour
    $paniers = getAllpaniers();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des paniers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">

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
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
<div class="container-fluid">
                <div class="row">
                    <?php include_once '../template/navigation.php'; ?>
                    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="page-title">Liste des Paniers</h1>
                        </div>
                            
                                <!-- Affichage de la liste des catégories  --> 
                    
                        <div>
        

   


                 <table class="table table">
                        <thead >
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Client</th>
                                <th scope="col">Total</th>
                                <th scope="col">Date</th>
                                <th scope="col">État</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($paniers)): ?>
                                <?php foreach ($paniers as $panier): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($panier['panier_id']); ?></td>
                                        <td><?= htmlspecialchars($panier['visiteur_nom'] . ' ' . $panier['visteur_prenom']); ?></td>
                                        <td><?= number_format($panier['total'], 2); ?> DT</td>
                                        <td><?= htmlspecialchars($panier['date_creation']); ?></td>
                                        <td><?= htmlspecialchars($panier['statut'] ?? 'En attente'); ?></td>
                                        <td>
                                            <button 
                                                class="btn btn-success btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#etatModal" 
                                                data-panier-id="<?= $panier['panier_id']; ?>" 
                                                data-current-etat="<?= htmlspecialchars($panier['statut'] ?? 'En attente'); ?>">
                                                Traiter
                                            </button>
                                            <a href="details.php?panier_id=<?= $panier['panier_id']; ?>" class="btn btn-primary btn-sm">Détails</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Aucun panier trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
</div>




        </main>
      </div>
    </div>

   
    

   
</div>

<!-- Modale -->
<div class="modal fade" id="etatModal" tabindex="-1" aria-labelledby="etatModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="etatModalLabel">Changer l'état du panier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="panier_id" id="panier-id">
          <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select class="form-select" name="statut" id="statut">
                <?php foreach ($etats_disponibles as $etat_option): ?>
                    <option value="<?= htmlspecialchars($etat_option); ?>">
                        <?= htmlspecialchars($etat_option); ?>
                    </option>
                <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const etatModal = document.getElementById('etatModal');
    etatModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const panierId = button.getAttribute('data-panier-id');
        const currentEtat = button.getAttribute('data-current-etat');

        document.getElementById('panier-id').value = panierId;

        const select = document.getElementById('statut');
        Array.from(select.options).forEach(option => {
            option.selected = option.value === currentEtat;
        });
    });
});
</script>


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

</body>
</html>
