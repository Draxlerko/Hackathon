<?php
$host = 'localhost'; // Adresa servera, kde je databáza
$user = 'root'; // Meno používateľa pre pripojenie k databáze (default je 'root' pre MySQL)
$pass = ''; // Heslo pre pripojenie k databáze (ak je prázdne, použije sa defaultná prázdna hodnota)
$dbname = 'prvy_proof'; // Názov databázy, do ktorej sa pripojujeme

// Vytvorenie pripojenia k databáze
$conn = new mysqli($host, $user, $pass, $dbname);

// Skontrolujeme, či došlo k chybe pri pripojení
if ($conn->connect_error) {
    die("Chyba pripojenia: " . $conn->connect_error); // Ak áno, vypíšeme chybu
}

// Nastavenie správnej znakovky pre databázu (pre slovenské znaky)
$conn->set_charset("utf8mb4");
?>
