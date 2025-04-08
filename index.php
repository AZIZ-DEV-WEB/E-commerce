<!DOCTYPE html>
<html lang="en">
<?php include_once 'inc/functions.php';
$categories=getAllCategories();
if(!empty($_POST)){
    $products=searchProduct($_POST['search']);
}else{
    $products=getAllProducts();
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


      <div class="row col-12 p-5">
     <?php foreach($products as $product){ 
      print '   <div class="col-3">
            
            <div class="card" >
                <img src="images/'.$product['image'].'" class="card-img-top" alt="...">
                <div class="card-body">
                  <h5 class="card-title">'.$product['nom'].'</h5>
                  <p class="card-text">'.$product['description'].'</p>
                  <a href="produit.php?id='.$product['id'].'" class="btn btn-primary">Afficher</a>
                </div>
              </div>

        </div>';
     }

       
        ?>    

      
  
</body>

<?php include 'inc/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</html>