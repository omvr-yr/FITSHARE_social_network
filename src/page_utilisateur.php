<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="page_utilisateur.css">
    <title>FitShare - Page de profil</title>
</head>

<body>
    <header>
        <nav>
            <a href="page_recherche.php"><img src="loupe.png" alt="Recherche"></a>
            <a href="page_messages.php"><img src="mess.png" alt="Messages"></a>
            <a href="page_accueil.php" style="text-decoration: none;"><span id="titre-logo" style="font-family: 'Microsoft Sans Serif', sans-serif;">FitShare</span></a>
            <a href="<?php echo isset($_SESSION['username']) ? 'page_utilisateur.php?PSEUDO=' . urlencode($_SESSION['username']) : 'page_connexion.php'; ?>"><img src="profil.png" alt="Profil"></a>
            <a href="page_connexion.php?logout=true"><img src="deconnexion2.png" alt="Déconnexion"></a>
        </nav>

        <img src="logo_2.png" alt="logo-top" class="logo-top">
    </header>
    <main>
    <hr>
        <?php

            // Récupérer le nombre d'abonnements
            require_once 'db.php';
            $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $stmt = $conn->prepare('SELECT COUNT(*) FROM relations WHERE abonnement = ?');
            $stmt->bind_param('s', $_GET['PSEUDO']);
            $stmt->execute();
            $stmt->bind_result($nb_abonnements);
            $stmt->fetch();
            $stmt->close();

            $stmt = $conn->prepare('SELECT photo FROM utilisateurs WHERE pseudo = ?');
            $stmt->bind_param('s', $_GET['PSEUDO']);
            $stmt->execute();
            $stmt->bind_result($photo);
            $stmt->fetch();
            $stmt->close();

            $stmt = $conn->prepare('SELECT COUNT(*) FROM POST WHERE pseudo_posteur = ?');
            $stmt->bind_param('s', $_GET['PSEUDO']);
            $stmt->execute();
            $stmt->bind_result($nb_posts);
            $stmt->fetch();
            $stmt->close();

            require_once 'db.php';
            $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $stmt = $conn->prepare('SELECT COUNT(*) FROM relations WHERE abonnes = ?');
            $stmt->bind_param('s', $_GET['PSEUDO']);
            $stmt->execute();
            $stmt->bind_result($nb_abonnes);
            $stmt->fetch();
            $stmt->close();

            // Afficher les résultats
            echo '<span id="abonnements">Abonnements <br><br>  ' . $nb_abonnements . ' </span>';
            echo '<span id="abonnes">Abonnés <br> <br>' . $nb_abonnes . ' </span>';
            echo '<span id="nb-posts">Publications <br><br>  '. $nb_posts . ' </span>';
            echo '<img src="' . $photo . '.png" class="photo-de-profil">';

            $stmt = $conn->prepare('SELECT nom FROM utilisateurs WHERE pseudo = ?');
            $stmt->bind_param('s', $_GET['PSEUDO']);
            $stmt->execute();
            $stmt->bind_result($nom);
            $stmt->fetch();
            $stmt->close();
            ?>
            <p id="nom"><?php echo $nom; ?></p>
            <?php
            $stmt = $conn->prepare('SELECT prenom FROM utilisateurs WHERE pseudo = ?');
            $stmt->bind_param('s', $_GET['PSEUDO']);
            $stmt->execute();
            $stmt->bind_result($prenom);
            $stmt->fetch();
            $stmt->close();
            ?>
            <p id="prenom"><?php echo $prenom; ?></p>

        <?php

        if (isset($_SESSION['username'])) {

            if ($_GET['PSEUDO'] === $_SESSION['username']) {
                // Sa propre page
                ?>
                <h5 id="page-title">Bienvenue sur ton profil</h5>
                <br><br><br><br><br><br>
                <a href="page_post.php" class="button" id="publier-perf">Publier une Performance</a>
                <a href="page_story.php" class="button" id="publier-story">Publier une Story</a>
                <a href="page_postuser.php?PSEUDO=<?php echo urlencode($_GET['PSEUDO']); ?>"class="button" id="afficher-post">Afficher les posts</a>

                <div class="box">
                </div>
            <?php

            $pseudo_posteur = $_GET['PSEUDO']; // le pseudo de l'utilisateur dont on veut récupérer le post le plus aimé
            $sql = "SELECT id_post, message, nb_like FROM POST WHERE pseudo_posteur='$pseudo_posteur' ORDER BY nb_like DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0)
                $row = mysqli_fetch_assoc($result);
                $id_post = $row["id_post"];
            ?>
            <br><br>
            <p id="text-post"><?php echo $row['message'] ?></p>
            <p id="post-like">Nombre de likes  &nbsp; <?php echo $row['nb_like'] ?></p>
            <p id="post">Post le plus liké </p>

                <?php
            } else {
    // Connecté mais page d'un autre utilisateur
    ?>

    <h5 id="page-title">Vous êtes sur le profil de <?php echo $_GET['PSEUDO']; ?></h5>
    
    <?php
    require_once 'db.php';
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $stmt = $conn->prepare('SELECT COUNT(*) FROM relations WHERE abonnement = ? AND abonnes = ?');
    $stmt->bind_param('ss', $_SESSION['username'], $_GET['PSEUDO']);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    ?>
    <div class="box">
    </div>
    <?php

    $pseudo_posteur = $_GET['PSEUDO']; // le pseudo de l'utilisateur dont on veut récupérer le post le plus aimé
    $sql = "SELECT id_post, message, nb_like FROM POST WHERE pseudo_posteur='$pseudo_posteur' ORDER BY nb_like DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0)
        $row = mysqli_fetch_assoc($result);
        $id_post = $row["id_post"];
    ?>
    <p id="post">Post le plus liké </p><br><br>
    <p id="text-post"><?php echo $row['message'] ?></p>
    <p id="post-like">Nombre de likes  &nbsp; <?php echo $row['nb_like'] ?></p>
    <?php

    if ($count > 0) {
        // L'utilisateur suit déjà l'utilisateur en cours
        ?>
        <form method="post">
            <input type="hidden" name="action" value="ne_plus_suivre">
            <button type="submit" class="button" id="suivre">Ne plus suivre</button>
            <a href="page_discussion.php?PSEUDO=<?php echo urlencode($_GET['PSEUDO']); ?>" class="button" id="message">Ecrire un message</a>
            <a href="page_postuser.php?PSEUDO=<?php echo urlencode($_GET['PSEUDO']); ?>" class="button" id="afficher-post">Afficher les posts</a>
        </form>
        <?php
        if (isset($_POST['action']) && $_POST['action'] === 'ne_plus_suivre') {
            // Supprimer la ligne correspondant à la relation entre l'utilisateur en cours et l'utilisateur suivi
            $stmt = $conn->prepare('DELETE FROM relations WHERE abonnement = ? AND abonnes = ?');
            $stmt->bind_param('ss', $_SESSION['username'], $_GET['PSEUDO']);
            $stmt->execute();
            $stmt->close();
            // Rafraîchir la page pour afficher le bouton "Suivre"
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    } else {
        // L'utilisateur ne suit pas encore l'utilisateur en cours
        ?>
        <form method="post">
            <button type="submit" class="button" id="suivre">Suivre</button>
            <a href="page_discussion.php?PSEUDO=<?php echo urlencode($_GET['PSEUDO']); ?>" class="button" id="message">Ecrire un message</a>
            <a href="page_postuser.php?PSEUDO=<?php echo urlencode($_GET['PSEUDO']); ?>" class="button" id="afficher-post">Afficher les posts</a>
            <input type="hidden" name="action" value="suivre">
        </form>
        <?php
        if (isset($_POST['action']) && $_POST['action'] === 'suivre') {
            // Ajouter une ligne dans la table "relations" avec l'ID de l'utilisateur en cours et l'ID de l'utilisateur suivi
            $stmt = $conn->prepare('INSERT INTO relations (abonnement, abonnes) VALUES (?, ?)');
            $stmt->bind_param('ss', $_SESSION['username'], $_GET['PSEUDO']);
            $stmt->execute();
            $stmt->close();
            // Rafraîchir la page pour afficher le bouton "Ne plus suivre"
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }
    $conn->close();
}





        } else {
            // Pas connecté 
            ?>
            <h5 id="page-title">Vous êtes sur la page de <?php echo $_GET['PSEUDO']; ?></h5>
            <?php
        }

        ?>

        <?php
        if ($_SESSION['isAdmin'] == true){
            ?>
            <a href="page_signalement.php" class="button" id="signalement">Afficher les signalements</a>
            <?php
            }
        ?>

    </main>
</body>
</html>
