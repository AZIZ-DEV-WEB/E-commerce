<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include_once 'inc/functions.php';
$categories = getAllCategories();
$conn = connect();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php include 'inc/navbar.php'; ?>
    
<?php include 'inc/footer.php'; ?>
</body>
</html>