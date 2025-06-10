<!DOCTYPE html>
<html lang="en">
<?php 
session_start();

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
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;          
            display: flex;                /* Ajout */
            width: 100%;
            margin: 0;                  /* Ajout */
            padding: 0;                 /* Ajout */
            justify-content: center;     /* Ajout */
            align-items: center;         /* Ajout */
            flex-direction: column; /* Ajout pour permettre un contenu centré verticalement */
            background-position: center;
            background: linear-gradient(135deg, #EF4C90, #16C4D9);
            background-attachment: fixed;
            background-size: cover;
        }


        .login-container {
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            margin-top: 5px;
            display: flex; /* Ajout pour centrer le contenu */
            flex-direction: column; /* Ajout pour centrer le contenu */
            align-items: center; /* Ajout pour centrer le contenu */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;

        }
        .login-container .form-control {
            border-radius: 5px;
        }
        .login-container .btn {
            width: 100%;
            background-color: #007bff;
            color: #fff;
        }
        .login-container .btn:hover {
            background-color: #0056b3;
        }
        .login-container .input-group-text {
            background-color: #007bff;
            color: #fff;
            border: none;
        }
        .login-container img {
            width: 150px;
            margin-bottom: 20px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 4px solid black; /* Ajout d'un contour noir */
        }
        .login-container .text-center a {
            color: #007bff;
            text-decoration: none;
        }
        .login-container .text-center a:hover {
            text-decoration: underline;
        }
    </style>
    
</head>

<body>


    <div class="login-container">
        <div class="text-center">
            <img src="images/vibesport.png" alt="Logo">
            <h5 class="mb-4">Connectez-vous à votre compte</h5>
        </div>
        <form action="connexion.php" method="post">
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Email" required>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="mp" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe" required>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </div>
        </form>
        <div class="text-center">
            <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
        </div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($error_message): ?>
    <script>
        var myModal = new bootstrap.Modal(document.getElementById('errorModal'));
        window.onload = function() {
            myModal.show();
        };
    </script>
    <?php endif; ?>
</body>
</html>