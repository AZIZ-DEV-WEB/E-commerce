<!DOCTYPE html>
<html lang="en">
<?php include_once 'inc/functions.php';
$categories=getAllCategories();
if (isset($_GET['id'])) {
    $product = getProductById($_GET['id']);
} else {
    echo "<div class='alert alert-danger'>Aucun identifiant de produit fourni.</div>";
    exit;
}
?>
 
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <style>
  
 
</style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>
<body>
<?php include 'inc/navbar.php'; ?>
<div class="row justify-content-center p-5">
    <div class="card" style="width: 300px; height=150px">
        <img src="images/<?php echo $product['image']; ?>" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title"><?php echo $product['nom'] ?></h5>
            <p class="card-text"><?php echo $product['description'] ?></p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><?php echo $product['prix'] ?> DT</li>
            <li class="list-group-item"><?php echo $product['categorie_id'] ?></li>
        </ul>
    </div>
</div>






      
  
</body>

<?php include 'inc/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</html>