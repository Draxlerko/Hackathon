<?php
// Statické údaje o platbe
$name = "Ján Novák";
$amount = 50.00; // Suma na zaplatenie v EUR
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