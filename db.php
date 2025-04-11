<?php
$host = 'localhost';
$user = 'root'; // alebo tvoje DB meno
$pass = '';     // alebo tvoje DB heslo
$dbname = 'prvy_proof';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Chyba pripojenia: " . $conn->connect_error);
}
?>
