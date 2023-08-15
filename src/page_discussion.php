<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="page_discussion.css">
    <title>FitShare - Discussion</title>
</head>
<header>
        <nav>
            <a href="page_recherche.php"><img src="loupe.png" alt="Recherche"></a>
            <a href="page_messages.php"><img src="mess.png" alt="Messages"></a>
            <a href="page_accueil.php" style="text-decoration: none;"><span style="font-family: 'Microsoft Sans Serif', sans-serif;">FitShare</span></a>
            <a href="<?php echo isset($_SESSION['username']) ? 'page_utilisateur.php?PSEUDO=' . urlencode($_SESSION['username']) : 'page_connexion.php'; ?>"><img src="profil.png" alt="Profil"></a>
            <a href="page_connexion.php?logout=true"><img src="deconnexion2.png" alt="Déconnexion"></a>
        </nav>

        <img src="logo_2.png" alt="logo-top" class="logo-top">
    </header>

<body>
<h2 id="page-title" >Discussion avec <?php echo $_GET['PSEUDO'] ?></h2>
<form method="POST" enctype="multipart/form-data">
    <textarea id="message" name="message" required placeholder=" Saisissez votre message ici..."></textarea><br>
    <input type="submit" value="Publier">
</form>
</body>
<br>
<div class="box">
    </div>
<img src="saviez.png" alt="saviez" style="width: 180px; position: absolute; position: fixed; top: 155px; left: 890px;">
<h6 id="texte">Saviez-vous que le joueur de football<br> brésilien Pelé a marqué son premier <br>but en équipe nationale du Brésil à <br>l'âge de seulement 16 ans ? C'était en <br>1957, lors d'un match contre<br> l'Argentine. Il est également le seul<br>  joueur à avoir remporté trois fois la <br>Coupe du monde de football avec son <br>équipe nationale en 1958, 1962 et 1970.</h6>
</html>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Démarrer la session pour récupérer les informations de l'utilisateur

if (!isset($_SESSION['username'])) {
    header("Location: page_connexion.php");
    exit();
}

require_once 'fonctions.php';
// Inclure le fichier de configuration de la base de données
require_once 'db.php';
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Echapper les caractères spéciaux dans le pseudo de l'utilisateur
$pseudo_destinataire = mysqli_real_escape_string($conn, $_GET['PSEUDO']);
$pseudo_expediteur = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];

    envoyer_message($pseudo_expediteur, $pseudo_destinataire, $message);

    $url = "page_discussion.php?PSEUDO=" . urlencode($pseudo_destinataire);

    header("Location: $url");
    exit();

    }
// Construire la requête SQL pour récupérer tous les messages de l'utilisateur en ordre décroissant
$sql = "SELECT * FROM message WHERE (pseudo_expediteur = '$pseudo_expediteur' AND pseudo_destinataire = '$pseudo_destinataire') OR (pseudo_expediteur = '$pseudo_destinataire' AND pseudo_destinataire = '$pseudo_expediteur') ORDER BY heure_date_publication ASC";
// Exécuter la requête SQL
$resultat = mysqli_query($conn, $sql);

// Vérifier si l'utilisateur a des message
if (mysqli_num_rows($resultat) > 0) {
    // Commencer la liste des messages
    echo '<ul class="post-list">';
    
    // Afficher chaque post sous forme d'élément de liste
    while ($row = mysqli_fetch_assoc($resultat)) {
        // Récupérer les informations du message
        $message = $row['message'];
        $heure_date_publication = date('d/m H:i', strtotime($row['heure_date_publication']));
        $pseudo_envoie = $row['pseudo_expediteur'];

        // Afficher le p dans un élément de liste
        echo '<li class="post-item">';
        if ($row['pseudo_expediteur'] == $pseudo_expediteur) {
            echo "<p class='post-nom' id='envoyeur'>$pseudo_envoie</p>";
        }
        else {
            echo "<p class='post-nom' id='receveur'>$pseudo_envoie</p>";
        }
        echo "<p class='post-text'>$message</p>";
        echo "<p class='post-subtitle'>$heure_date_publication</p>";
        echo '</li>';
    }

      
    // Fermer la liste des messages
    echo '</ul>';
} else {
    // Afficher un message si l'utilisateur n'a pas encore de message
    echo "<p>Commencer une discussion avec $pseudo_destinataire</p>";

}

?>