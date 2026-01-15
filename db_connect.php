<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "notification_db"; // LE NOM EXACT QUE NOUS VENONS DE CRÉER

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}
?>