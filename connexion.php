<!DOCTYPE html>
<html lang="en">
<?php 
include_once 'inc/functions.php';
$categories = getAllCategories(); 
$error_message = null;

if (!empty($_POST)) {
  $error_message = connectVisiteur($_POST);
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'inc/navbar.php'; ?>

<form class="p-5" action="connexion.php" method="post">
        <div class="mb-3 " >
          <label for="exampleInputEmail1" class="form-label">Email address</label>
          <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp">
          <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label">Password</label>
          <input type="password" name="mp" class="form-control" id="exampleInputPassword1">
        </div>

        <button type="submit" class="btn btn-primary">Connecter</button>
      </form>
      <?php if ($error_message): ?>
<!-- Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="errorModalLabel">Erreur de connexion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <?= htmlspecialchars($error_message) ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

    
</body>
<?php include 'inc/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<?php if ($error_message): ?>
<script>
  var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
  window.onload = function() {
    myModal.show();
  };
</script>
<?php endif; ?>


</html>