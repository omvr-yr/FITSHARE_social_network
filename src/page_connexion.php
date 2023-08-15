<?php

session_start();

require_once 'db.php';

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_destroy();
    header('Location: page_connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['isAdmin'] = false;
    $login = $_POST['login'];
    $password = $_POST['password'];

    require_once 'db.php';
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    $sql = "SELECT * FROM utilisateurs WHERE pseudo = '".$login."'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $hashed_password = $row['mdp'];
        $status = $row['status'];

        if (password_verify($password, $hashed_password)){

            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $login;

            if ($status === 'admin') {
                $_SESSION['isAdmin'] = true;
            }

            // Redirection vers la page d'accueil
            header('Location: page_accueil.php');
            exit();
        } else {
            $_SESSION['mdpNonCorrespondant'] = true;
            $_SESSION['error_message'] = "Nom d'utilisateur ou mot de passe incorrect.";
        }

    } else {
        $_SESSION['mdpNonCorrespondant'] = true;
        $_SESSION['error_message'] = "Nom d'utilisateur ou mot de passe incorrect.";
    }

    $conn->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="page_connexion.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitShare - Connexion</title>
</head>
<body>
    <div class="container">
        <img id="logo" src="logo_2.png" alt="Logo">
        <h1>Connexion</h1>
                <form id="loginForm" action="page_connexion.php" method="post">
            <input type="text" id="login" name="login" placeholder="Pseudo" required><br><br>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required><br><br>
            <input type="submit" value="Se connecter">
            <p>Pas encore inscrit ? <a href="page_inscription.php">Créez un compte</a></p>
        </form>
    </div>
    <?php
    if (isset($_SESSION['error_message'])) {
        echo "<h5>Erreur : " . $_SESSION['error_message'] . "</h5>";
        echo '<img src="error.png" alt="error-img" class="error-img">';
    }
    ?>
</body>
</html>