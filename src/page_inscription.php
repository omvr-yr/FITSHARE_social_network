<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
// lancer une session 
session_start();
$_SESSION['isAdmin'] = false;

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Vérification des champs obligatoires
    if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['confirm_mdp']) && isset($_POST['status']) && isset($_POST['photo'])) {
        
        $adminPassword = isset($_POST['adminPassword']) ? $_POST['adminPassword'] : "";
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];
        $confirm_mdp = $_POST['confirm_mdp'];
        $status = $_POST['status'];
        $photo = $_POST['photo'];

        if ($mdp !== $confirm_mdp) {
            $_SESSION['mdpNonCorrespondant'] = true;
            $_SESSION['error_message'] = 'Les mots de passe ne correspondent pas';
            header('Location: page_inscription.php');
            exit();
        }

        if (strlen($mdp) < 6 || strlen($mdp) > 20) {
            $_SESSION['mdpLongueurInvalide'] = true;
            $_SESSION['error_message'] = 'La longueur du mot de passe doit être comprise entre 6 et 20 caractères';
            header('Location: page_inscription.php');
            exit();
        }
        
        require_once 'db.php';

        if ($status === 'admin') {
            if ($adminPassword !== ADMIN_PASSWORD) {
                $_SESSION['mdpAdminIncorrect'] = true;
                $_SESSION['error_message'] = 'Le mot de passe administrateur est incorrect';
                header('Location: page_inscription.php');
                exit();
            }
        }


        require_once 'db.php';
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        // Vérification si le pseudo est déjà utilisé
        $sql = "SELECT * FROM utilisateurs WHERE pseudo = '".$pseudo."'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $_SESSION['pseudoDejaUtilise'] = true;
            $_SESSION['error_message'] = 'Le pseudo "'.$pseudo.'" est déjà utilisé';
            header('Location: page_inscription.php');
            exit();
        } 

        else {

            require_once 'db.php';
            $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            // Insertion des données dans la base de données
            $hashedPassword = hashPassword($mdp);
            $hashedAdminPassword =  hashPassword($adminPassword);         
            $sql = "INSERT INTO utilisateurs (nom, prenom, pseudo, mdp, status, photo)
                VALUES ('$nom', '$prenom', '$pseudo', '$hashedPassword', '$status', '$photo')";

            if ($conn->query($sql) === TRUE) {
            unset($_SESSION['error_message']);
            header('Location: page_connexion.php');
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            $conn->close();
            exit();
        }


        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <div class="rectangle"></div>
    <meta charset="UTF-8">
    <title>FitShare - Inscription</title>
    <link rel="stylesheet" href="page_inscription.css">
</head>
    <span style="font-family: 'Microsoft Sans Serif', sans-serif;">FitShare</span>

<body>
    <form method="post" action="page_inscription.php">

    <input type="text" id="nom" name="nom" placeholder="Nom" required><br><br>

    <input type="text" id="prenom" name="prenom" placeholder="Prénom" required><br><br>

    <input type="text" id="pseudo" name="pseudo" placeholder="Pseudo" required><br><br>

    <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required><br><br>

    <input type="password" id="confirm_mdp" name="confirm_mdp" placeholder="Confirmez le mot de passe" required><br><br>

    <input type="password" id="adminPassword" name="adminPassword" placeholder="Mot de passe admin">

    <h2>Inscrivez-vous pour partager vos performances avec vos amis.</h2>
    <h3>En vous inscrivant, vous confirmez avoir<br> pris connaissance de nos conditions <br>générales d'utilisation.</h3>

    <label for="photo"></label>
    <select id="photo" name="photo" required>
        <option value="0">-- Photo de profil -- </option>
        </option>
        <option value="1">Photo 1</option>
        <option value="2">Photo 2</option>
        <option value="3">Photo 3</option>
        <option value="4">Photo 4</option>
        <option value="5">Photo 5</option>
        <option value="6">Photo 6</option>
        <option value="7">Photo 7</option>
        <option value="8">Photo 8</option>
    </select>

    <label for="status"></label>
    <select id="status" name="status" required>
        <option value="etudiant">-- Choisissez votre statut -- </option>
        </option>
        <option value="etu">Etudiant</option>
        <option value="admin">Administrateur</option>
    </select>
    <br><br>

    <input type="submit" value="S'inscrire" class="btn-inscrire">
</form>
<img src="logo_2.png" alt="Logo 2" class="logo2">
<img src="apercu1.png" alt="apercu" class="apercu">
<?php
    if (isset($_SESSION['error_message'])) {
        echo "<h5>Erreur : " . $_SESSION['error_message'] . "</h5>";
        echo '<img src="error.png" alt="error-img" class="error-img">';
    }
    ?>
    </body>
</html>
