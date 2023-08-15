<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: page_connexion.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>FitShare - Accueil</title>
  <link rel="stylesheet" href="page_accueil.css">

</head>
<body>
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

  <main>
    <h2>Fil d'actualité</h2>

    <?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db.php';
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: page_connexion.php');
    exit;
}

// Récupérer les derniers posts des abonnements de l'utilisateur connecté
$pseudo = $_SESSION['username'];
$sql = "SELECT POST.* FROM POST INNER JOIN relations ON POST.pseudo_posteur = relations.abonnes WHERE relations.abonnement = '$pseudo' ORDER BY POST.heure_date_publication DESC LIMIT 20";
$resultat = mysqli_query($conn, $sql);

// Afficher les posts dans des rectangles
if (mysqli_num_rows($resultat) > 0) {
    // Commencer la liste des posts
    echo '<ul class="post-list">';
    
    // Afficher chaque post sous forme d'élément de liste
    // Afficher chaque post sous forme d'élément de liste
    while ($row = mysqli_fetch_assoc($resultat)) {

    // Récupérer les informations du post
    $nom_posteur = $row['pseudo_posteur'];
    $id_post = $row['id_post'];
    $message = $row['message'];
    $heure_date_publication = date('d/m H:i', strtotime($row['heure_date_publication']));

    // Afficher le post dans un rectangle
    echo '<li class="post-rectangle">';
    echo "<p class='post-nom'><a href='page_utilisateur.php?PSEUDO=$nom_posteur'>$nom_posteur</a><br></p>";
    echo "<p class='post-text'>$message </p>";
    echo "<p class='post-subtitle'>$heure_date_publication</p>";

    require_once 'db.php';
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Vérifier si l'utilisateur a déjà liké le post
    $sql_check = "SELECT * FROM likes WHERE id_post = '$id_post' AND pseudo_likeur = '$pseudo'";
    $result_check = mysqli_query($conn, $sql_check);
    
    if(mysqli_num_rows($result_check) > 0) {
      ?>
        <form method='post'>
        <input type='hidden' name='id_post' value='<?php echo $id_post; ?>'>
        <button type='submit' name='supprimer_like' class='like-button' style='border:none; background:none; padding:0;'>
        <img src='dislike.png' alt='Liker' style='width:30px; height:30px;'>
        </button>
      </form>
  <?php
    } else {
      ?>
      <form method='post'>
        <input type='hidden' name='id_post' value='<?php echo $id_post; ?>'>
        <button type='submit' name='ajouter_like' class='like-button' style='border:none; background:none; padding:0;'>
        <img src='like.png' alt='Liker' style='width:30px; height:30px;'>
        </button>
      </form>

    <?php

    }

    echo "<form method='post'>";
    echo "<input type='hidden' name='id_post' value='$id_post'>";
    echo "<button type='submit' name='signaler' class='signaler-button'>Signaler</button>";
    echo "</form>";
}

    // Fermer la liste des posts
    echo '</ul>';
} else {
    echo '<p>Aucun post à afficher.</p>';
}

require_once 'db.php';
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (isset($_POST['ajouter_like'])) {
    $id_post_a_liker = mysqli_real_escape_string($conn, $_POST['id_post']);
    $pseudo_likeur = mysqli_real_escape_string($conn, $_SESSION['username']);
    
    // Ajouter une ligne à la table "likes"
    $sql = "INSERT INTO likes (id_post, pseudo_likeur) VALUES ('$id_post_a_liker', '$pseudo_likeur')";
    $sql_update = "UPDATE post SET nb_like = nb_like + 1 WHERE id_post = '$id_post_a_liker'";
        mysqli_query($conn, $sql_update);

    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $_SERVER['REQUEST_URI']);
    } else {
        echo "Erreur lors du like : " . mysqli_error($conn);
    }
}

if (isset($_POST['supprimer_like'])) {
    $id_post_a_liker = mysqli_real_escape_string($conn, $_POST['id_post']);
    $pseudo_likeur = mysqli_real_escape_string($conn, $_SESSION['username']);
    
    // Supprimer la ligne correspondante dans la table "likes"
    $sql = "DELETE FROM likes WHERE id_post = '$id_post_a_liker' AND pseudo_likeur = '$pseudo_likeur'";
    $sql_update = "UPDATE post SET nb_like = nb_like - 1 WHERE id_post = '$id_post_a_liker'";
        mysqli_query($conn, $sql_update);
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $_SERVER['REQUEST_URI']);
    } else {
        echo "Erreur lors de la suppression du like : " . mysqli_error($conn);
    }
}

if (isset($_POST['signaler'])) {
    $id_post_a_signaler = mysqli_real_escape_string($conn, $_POST['id_post']);
    $signaleur = mysqli_real_escape_string($conn, $_SESSION['username']);
    $sql = "UPDATE POST SET signalement = 1, pseudo_signaleur = '$signaleur' WHERE id_post = '$id_post_a_signaler'";
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $_SERVER['REQUEST_URI']);
    } else {
        echo "Erreur lors du signalement : " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>




  </main>
  
</body>
</html>