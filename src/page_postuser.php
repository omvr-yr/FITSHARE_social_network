<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="page_postuser.css">
    <title>FitShare - Page de profil</title>
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

  <?php
  require_once 'page_postuser.php';
  ?>


</body>
</html>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de configuration de la base de données
require_once 'db.php';
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!isset($_SESSION['username'])) {
    header("Location: page_connexion.php");
    exit();
}

// Echapper les caractères spéciaux dans le pseudo de l'utilisateur
$pseudo = mysqli_real_escape_string($conn, $_GET['PSEUDO']);
    echo "<h2>Les posts de $pseudo</h2>";
// Construire la requête SQL pour récupérer tous les posts de l'utilisateur en ordre décroissant
$sql = "SELECT * FROM POST WHERE pseudo_posteur='$pseudo' ORDER BY heure_date_publication DESC";

// Exécuter la requête SQL
$resultat = mysqli_query($conn, $sql);

// Vérifier si l'utilisateur a des posts à afficher
if (mysqli_num_rows($resultat) > 0) {
    // Commencer la liste des posts
    echo '<ul class="post-list">';
    
    // Afficher chaque post sous forme d'élément de liste
    // Afficher chaque post sous forme d'élément de liste
    while ($row = mysqli_fetch_assoc($resultat)) {
    // Récupérer les informations du post
    $id_post = $row['id_post'];
    $message = $row['message'];
    $heure_date_publication = date('d/m H:i', strtotime($row['heure_date_publication']));

    // Afficher le post dans un rectangle
    echo '<li class="post-rectangle">';
    echo "<p class='post-text'>$message</p>";
    echo "<p class='post-subtitle'>$heure_date_publication</p>";

    if ($_SESSION['username'] == $pseudo || $_SESSION['isAdmin'] == true) {
    echo "<form method='post'>";
    echo "<input type='hidden' name='id_post' value='$id_post'>";
    ?>
    <button type='submit' name='delete_post' class='delete-button'>
        <img src="delete.png" alt="delete" width="20px">
    </button>
    <?php
    echo "</form>";

    echo '</li>';

    require_once 'db.php';
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if (isset($_POST['delete_post'])) {
        $id_post_to_delete = $_POST['id_post'];
        $sql = "DELETE FROM POST WHERE id_post = '$id_post_to_delete'";
        if (mysqli_query($conn, $sql)) {
            header('Location: ' . $_SERVER['REQUEST_URI']);
        } else {
            echo "Erreur lors de la suppression du post: " . mysqli_error($conn);
        }
    }

    }


    if ($_SESSION['username'] != $pseudo && $_SESSION['isAdmin'] == false){
    // Afficher le bouton "Signaler"
    echo "<form method='post'>";
    echo "<input type='hidden' name='id_post' value='$id_post'>";
    echo "<button type='submit' name='signaler_post' class='signaler-button'>Signaler</button>";
    echo "</form>";

    require_once 'db.php';
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if (isset($_POST['signaler_post'])) {
    $id_post_a_signaler = mysqli_real_escape_string($conn, $_POST['id_post']);
    $signaleur = mysqli_real_escape_string($conn, $_SESSION['username']);
    $sql = "UPDATE POST SET signalement = 1, pseudo_signaleur = '$signaleur' WHERE id_post = '$id_post_a_signaler'";
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $_SERVER['REQUEST_URI']);
    } else {
        echo "Erreur lors du signalement : " . mysqli_error($conn);
    }
    
    }


    }


}



    
    // Fermer la liste des posts
    echo '</ul>';
} else {
    // Afficher un message si l'utilisateur n'a pas encore publié de post
    echo '<p>Cet utilisateur n\'a pas encore publié de post.</p>';
}

?>