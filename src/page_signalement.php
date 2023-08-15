<?php

session_start();

if($_SESSION['isAdmin'] == true){
require_once 'db.php';
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// récupérer les posts signalés
$sql = "SELECT * FROM POST WHERE signalement = 1";
$result = mysqli_query($conn, $sql);

// afficher les posts dans un tableau HTML
if (mysqli_num_rows($result) > 0) {
    echo '<table>';
    echo '<thead><tr><th>ID post</th><th>Pseudo posteur</th><th>Message</th><th>Date de publication</th><th>Pseudo signaleur</th></tr></thead>';
    echo '<tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
echo '<tr>';
echo '<td>' . $row['id_post'] . '</td>';
echo '<td>' . $row['pseudo_posteur'] . '</td>';
echo '<td>' . $row['message'] . '</td>';
echo '<td>' . $row['heure_date_publication'] . '</td>';
echo '<td>' . $row['pseudo_signaleur'] . '</td>';
echo '<td>';

echo '<form method="post" action="page_signalement.php">';
echo '<input type="hidden" name="id_post" value="' . $row['id_post'] . '">';
echo '<button type="submit" name="enlever_signalement">Enlever le signalement</button>';
echo '</form>';

echo '<form method="post" action="page_signalement.php">';
echo '<input type="hidden" name="id_post" value="' . $row['id_post'] . '">';
echo '<button type="submit" name="delete_post">Supprimer le post</button>';
echo '</form>';

echo '</td>';
echo '</tr>';

if (isset($_POST['enlever_signalement'])) {
    $id_post_a_signaler = mysqli_real_escape_string($conn, $_POST['id_post']);
    $sql = "UPDATE POST SET signalement = 0 WHERE id_post = '$id_post_a_signaler'";
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $_SERVER['REQUEST_URI']);
    } else {
        echo "Erreur lors du signalement : " . mysqli_error($conn);
    }
    
    }

if (isset($_POST['delete_post'])) {
    $id_post_to_delete = mysqli_real_escape_string($conn, $_POST['id_post']);
    $sql = "DELETE FROM POST WHERE id_post = '$id_post_to_delete'";
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $_SERVER['REQUEST_URI']);
    } else {
        echo "Erreur lors de la suppression du post : " . mysqli_error($conn);
    }
    
    }


}
    echo '</tbody>';
    echo '</table>';
} else {
    echo 'Aucun post signalé.';
}
}
?>
