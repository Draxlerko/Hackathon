<?php
// Statické údaje o platbe
$name = "Filip Geletka";
$amount = 50.00; // Suma na zaplatenie v EUR
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platobná stránka</title>
    <!-- Napojenie na externý CSS súbor -->
    <link rel="stylesheet" href="payments.css">
</head>
<body>
    <div class="payment-container">
        <h1>Platobná stránka</h1>
        <div class="payment-details">
            <p><strong>Meno:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Suma na zaplatenie:</strong> <?php echo htmlspecialchars($amount); ?> FNT</p>
        </div>
        <form action="user_menu.php" method="POST">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
            <button type="submit" class="pay-button">Zaplať</button>
        </form>
    </div>
</body>
</html>