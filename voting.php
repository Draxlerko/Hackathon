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
$results = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedOption = $_POST['vote'] ?? null;

    if ($selectedOption) {
        $message = "Ďakujeme za váš hlas, $voter! Hlasovali ste za možnosť: $selectedOption.";
        
       
        $percentYes = rand(40, 60); 
        $percentNo = 100 - $percentYes; 
        $results = [
            "Áno" => $percentYes,
            "Nie" => $percentNo
        ];
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
            margin: 20px 0;
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
        .progress-bar-container {
            margin: 20px 0;
            position: relative;
        }
        .progress-bar-container:not(:last-child)::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #ccc;
        }
        .progress-bar {
            height: 20px;
            background-color: rgb(51, 110, 117);
            border-radius: 5px;
            text-align: center;
            color: white;
            line-height: 20px;
            padding: 5px;
            font-size: 14px;
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
            color: green;
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
            <?php if ($results): ?>
                <?php foreach ($results as $option => $percent): ?>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?php echo $percent; ?>%;"><?php echo $percent; ?>%</div>
                        <p><strong><?php echo htmlspecialchars($option); ?></strong></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <button onclick="location.href='votingmenu.php'">Späť</button>
        <?php else: ?>
            <h1><?php echo htmlspecialchars($title); ?></h1>
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