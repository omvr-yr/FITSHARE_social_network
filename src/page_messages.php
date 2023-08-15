<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>FitShare - Recherche d'utilisateurs</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="page_messages.css">
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
<div class="box">
    </div>
<h2 id="page-title" >Liste des discussions en cours</h2>
<br>
<img src="saviez.png" alt="saviez" style="width: 180px; position: absolute; top: 155px; left: 890px;">
<p id="texte">Savais-tu que l'acteur et bodybuilder <br>autrichien Arnold Schwarzenegger <br>avait commencé à s'entraîner avec <br>des haltères en fabriquant des poids<br> à partir de morceaux d'acier de<br> différentes formes et tailles qu'il<br> trouvait dans une usine de fonte <br>abandonnée ?</p>
</html>
<?php

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: page_connexion.php");
    exit();
}

// Inclure le fichier de configuration de la base de données
require_once 'db.php';
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Echapper les caractères spéciaux dans le pseudo de l'utilisateur
$pseudo = mysqli_real_escape_string($conn, $_SESSION['username']);

// Construire la requête SQL pour récupérer tous les utilisateurs avec qui l'utilisateur actuellement connecté a des discussions
$sql = "SELECT DISTINCT LEAST(pseudo_expediteur, pseudo_destinataire) AS pseudo1, GREATEST(pseudo_expediteur, pseudo_destinataire) AS pseudo2
        FROM message 
        WHERE pseudo_expediteur ='$pseudo' OR pseudo_destinataire ='$pseudo'";
// Exécuter la requête SQL
$resultat = mysqli_query($conn, $sql);

// Vérifier si l'utilisateur a des discussions
if (mysqli_num_rows($resultat) > 0) {
    // Commencer la liste des utilisateurs avec qui l'utilisateur a des discussions
    echo '<ul class="user-list">';
    
    // Afficher chaque utilisateur sous forme d'élément de liste
    while ($row = mysqli_fetch_assoc($resultat)) {
        // Récupérer le pseudo de l'utilisateur
        $pseudo_destinataire = $row['pseudo1'] == $pseudo ? $row['pseudo2'] : $row['pseudo1'];

        // Afficher le pseudo dans un élément de liste
        echo '<li class="user-item">';
        echo "<a href='page_discussion.php?PSEUDO=" . urlencode($pseudo_destinataire) . "'>$pseudo_destinataire</a>";
        echo '</li>';
    }
      
    // Fermer la liste des utilisateurs
    echo '</ul>';
} else {
    // Afficher un message si l'utilisateur n'a pas encore de discussion
    echo "<p id='pas-encore'>Vous n'avez pas encore de discussion avec d'autres utilisateurs</p>";
}

?>