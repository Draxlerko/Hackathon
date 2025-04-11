<?php


$id = $_GET['id'] ?? null;
$title = $_GET['title'] ?? null;
$voter = $_GET['voter'] ?? null;

if (!$id || !$title || !$voter) {
    die("Chyba: Neplatné údaje o hlasovaní.");
}


$options = ["Áno", "Nie"];


$message = "";
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedOption = $_POST['vote'] ?? null;

    if ($selectedOption) {
        $message = "Ďakujeme za váš hlas, $voter! Hlasovali ste za možnosť: $selectedOption v hlasovaní: $title.";
    } else {
        $error = "Musíte zvoliť jednu možnosť.";
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: rgb(37, 80, 86);
        }
        .voting-container {
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        .option-container {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .option-container input[type="radio"] {
            display: none;
        }
        .option-container label {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 10px 15px;
            border: 2px solid #ccc;
            border-radius: 5px;
            transition: all 0.3s ease;
            width: 100%;
            text-align: left;
        }
        .option-container input[type="radio"]:checked + label {
            background-color: rgb(51, 110, 117);
            color: white;
            border-color: rgb(51, 110, 117);
        }
        button {
            background-color: rgb(51, 110, 117);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        button:hover {
            background-color: rgb(37, 80, 86);
        }
        .message {
            margin-top: 20px;
            font-size: 18px;
            color: rgb(51, 110, 117);
        }
        .error {
            margin-top: 10px;
            font-size: 16px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="voting-container">
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php else: ?>
            <h1>Hlasovanie</h1>
            <p><strong><?php echo htmlspecialchars($title); ?></strong></p>
            <p><strong>Hlasujúci:</strong> <?php echo htmlspecialchars($voter); ?></p>
            
            <form action="" method="POST">
                <?php foreach ($options as $index => $option): ?>
                    <div class="option-container">
                        <input type="radio" id="option<?php echo $index; ?>" name="vote" value="<?php echo htmlspecialchars($option); ?>">
                        <label for="option<?php echo $index; ?>"><?php echo htmlspecialchars($option); ?></label>
                    </div>
                <?php endforeach; ?>
                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>">
                <input type="hidden" name="voter" value="<?php echo htmlspecialchars($voter); ?>">
                <button type="submit">Hlasovať</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>