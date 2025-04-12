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
    <title>Payme Platba</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color:rgb(4, 56, 55);
        }
        .payment-container {
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 1px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 450px;
        }
        button {
            background-color:rgb(76, 116, 175);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color:rgb(69, 134, 160);
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h1>Platobná stránka</h1>
        <p><strong>Meno:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p><strong>Suma na zaplatenie:</strong> <?php echo htmlspecialchars($amount); ?> FNT</p>
        <form action="process_payment.php" method="POST">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($amount); ?>">
            <button type="submit">Zaplať</button>
        </form>
    </div>
</body>
</html>