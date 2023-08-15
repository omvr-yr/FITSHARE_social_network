<?php
session_start();
require_once 'fonctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $utilisateurs = recherche($pseudo);
} else {
    $utilisateurs = array();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>FitShare - Recherche d'utilisateurs</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="page_recherche.css">
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
    <h2 id="page-title" >Recherche d'utilisateurs</h2>

    <form method="POST" class="center">
        <div class="search-container">
            <input type="text" id="pseudo" name="pseudo" required>
            <button type="submit" class="button-search">Rechercher</button>
        </div>
    </form>
    <br>

    <?php if (!empty($utilisateurs)) : ?>
        <ul>
        <?php foreach ($utilisateurs as $utilisateur) : ?>
        <li class="resultat-recherche">
        <a href="page_utilisateur.php?PSEUDO=<?php echo urlencode($utilisateur['pseudo']); ?>">
            <?php echo htmlentities($utilisateur['pseudo']); ?>
        </a>
         </li>
<?php endforeach; ?>

        </ul>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
        <p>Aucun utilisateur trouvé.</p>
    <?php endif; ?>

</body>
</html>
