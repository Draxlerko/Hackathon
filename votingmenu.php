<?php

$votings = [
    [
        "id" => 1,
        "title" => "Hlasovanie o nových chodníkoch",
        "image" => "images/chodniky.jpg"
    ],
    [
        "id" => 2,
        "title" => "Hlasovanie o výsadbe stromov",
        "image" => "images/stromy.jpg"
    ],
    [
        "id" => 3,
        "title" => "Hlasovanie o novom ihrisku",
        "image" => "images/ihrisko.jpg"
    ],
    [
        "id" => 4,
        "title" => "Hlasovanie o osvetlení",
        "image" => "images/osvetlenie.jpg"
    ]
];
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .menu-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .voting-box {
            width: 200px;
            height: 250px;
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .voting-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .voting-box img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }
        .voting-box h3 {
            margin: 10px 0 0;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <?php foreach ($votings as $voting): ?>
            <div class="voting-box" onclick="location.href='voting.php?id=<?php echo $voting['id']; ?>'">
                <img src="<?php echo htmlspecialchars($voting['image']); ?>" alt="Obrázok">
                <h3><?php echo htmlspecialchars($voting['title']); ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>