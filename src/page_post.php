<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>FitShare - Créer un post</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="page_post_story.css">
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
    <h2 id="page-title" >Créer un post</h2>

    <?php

    if (!isset($_SESSION['username'])) {
    header("Location: page_connexion.php");
    exit();
}

require_once 'fonctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $pseudo_posteur = $_SESSION['username'];

    addPOST($pseudo_posteur, $message);

    $url = "page_utilisateur.php?PSEUDO=" . urlencode($pseudo_posteur);

    header("Location: $url");
    exit();
}
?>


    <form method="POST" enctype="multipart/form-data">
    <label for="message">Message</label><br>
    <textarea id="message" name="message" required></textarea><br>
    <input type="submit" value="Publier">
    </form>

</body>
</html>