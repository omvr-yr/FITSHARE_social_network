<?php
require_once 'db.php';
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$sql = "DELETE FROM POST WHERE is_story = 1 AND TIMESTAMPDIFF(MINUTE, heure_date_publication, NOW()) >= 5";
mysqli_query($conn, $sql);
?>
