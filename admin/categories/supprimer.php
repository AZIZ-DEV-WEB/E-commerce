<?php  
$idcategorie=$_GET['id'];
$nomcategorie = $_GET['nom'] ?? 'inconnu'; // Utilisez une valeur par défaut si `nom` n'est pas défini
 include_once '../../inc/functions.php';
$conn=connect();
$requette="DELETE FROM categorie WHERE id=$idcategorie";
$resultat=$conn->query($requette);
if($resultat){
    header("Location: liste.php?deleted=ok");
    exit;
}
?>