<?php

function addPOST($pseudo_posteur, $message){
    require_once 'db.php'; 
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $heure_date_publication = date('Y-m-d H:i:s'); // récupérer la date et l'heure actuelles
    $id_post = uniqid(); // générer un ID unique pour le post

    // échapper les caractères spéciaux dans les variables avant de les insérer dans la requête SQL
    $pseudo_posteur = mysqli_real_escape_string($conn, $pseudo_posteur);
    $message = mysqli_real_escape_string($conn, $message);
    // construire la requête SQL pour insérer les données dans la table POST
    $sql = "INSERT INTO POST (id_post, pseudo_posteur, message, heure_date_publication)
            VALUES ('$id_post', '$pseudo_posteur', '$message', '$heure_date_publication')";

    // exécuter la requête SQL
    mysqli_query($conn, $sql);
}

function addSTORY($pseudo_posteur, $message){
    require_once 'db.php'; 
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $heure_date_publication = date('Y-m-d H:i:s'); // récupérer la date et l'heure actuelles
    $id_post = uniqid(); // générer un ID unique pour le post

    // échapper les caractères spéciaux dans les variables avant de les insérer dans la requête SQL
    $pseudo_posteur = mysqli_real_escape_string($conn, $pseudo_posteur);
    $message = mysqli_real_escape_string($conn, $message);
    // construire la requête SQL pour insérer les données dans la table POST
    $sql = "INSERT INTO POST (id_post, pseudo_posteur, message, heure_date_publication, is_story)
            VALUES ('$id_post', '$pseudo_posteur', '$message', '$heure_date_publication', 1)";

    // exécuter la requête SQL
    mysqli_query($conn, $sql);
}

function recherche($pseudo){
    require_once 'db.php'; 
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $pseudo = mysqli_real_escape_string($conn, $pseudo);
    $sql = "SELECT * FROM UTILISATEURS WHERE pseudo LIKE '$pseudo%'";
    $resultat = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($resultat) > 0) {
        $utilisateurs = array();
        while ($row = mysqli_fetch_assoc($resultat)) {
            $utilisateurs[] = $row;
        }
        return $utilisateurs;
    }
}

function envoyer_message($pseudo_expediteur, $pseudo_destinataire, $message){
    require_once 'db.php'; 
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $heure_date_publication = date('Y-m-d H:i:s'); // récupérer la date et l'heure actuelles

    // échapper les caractères spéciaux dans les variables avant de les insérer dans la requête SQL
    $pseudo_expediteur = mysqli_real_escape_string($conn, $pseudo_expediteur);
    $pseudo_destinataire = mysqli_real_escape_string($conn, $pseudo_destinataire);
    $message = mysqli_real_escape_string($conn, $message);
    // construire la requête SQL pour insérer les données dans la table POST
    $sql = "INSERT INTO message (pseudo_expediteur, pseudo_destinataire, message, heure_date_publication)
            VALUES ('$pseudo_expediteur', '$pseudo_destinataire', '$message', '$heure_date_publication')";

    // exécuter la requête SQL
    mysqli_query($conn, $sql);
}


?>