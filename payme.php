<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hackathon";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Chyba pripojenia: " . $conn->connect_error);
}

// Načítanie údajov z databázy
$sql = "SELECT name, amount FROM payments WHERE id = 1"; // Príklad: načítanie údajov pre konkrétnu platbu
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Načítanie údajov
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $amount = $row['amount'];
} else {
    $name = "Neznáme meno";
    $amount = 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payme Platba</title>
</head>
<body>
    <h1>Platobná stránka</h1>
    <p><strong>Meno:</strong> <?php echo htmlspecialchars($name); ?></p>
    <p><strong>Suma na zaplatenie:</strong> <?php echo htmlspecialchars($amount); ?> €</p>
    <form action="process_payment.php" method="POST">
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
        <button type="submit">Zaplať</button>
    </form>
</body>
</html>