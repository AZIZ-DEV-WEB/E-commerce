<?php  
$idproduit=$_GET['id'];
$nomproduit = $_GET['delete-nom'] ?? 'inconnu'; // Utilisez une valeur par défaut si `nom` n'est pas défini
 include_once '../../inc/functions.php';
$conn=connect();
$requette="DELETE FROM produit WHERE id=$idproduit";
$resultat=$conn->query($requette);
if($resultat){
    header("Location: liste.php?deleted=ok&nom=" . urlencode($nomproduit));
    exit;
}
?>