<?php
session_start();

// Zapnutie zobrazovania chýb
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ak nie je používateľ prihlásený, presmeruj ho na prihlasovací formulár
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nazov = $_POST['nazov'] ?? null;
    $text = $_POST['text'] ?? null;
    $typ = $_POST['typ'] ?? null; // Pridanie typu
    $id_obcan = $_SESSION['user']['id'] ?? null;

    if (empty($nazov) || empty($text) || empty($typ)) {
        die("Chyba: Všetky polia musia byť vyplnené.");
    }

    $servername = "localhost";
    $username = "root"; // nastav meno svojho používateľa databázy
    $password = ""; // nastav heslo pre svoju databázu
    $dbname = "prvy_proof"; // názov tvojej databázy

    // Vytvorenie pripojenia
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Skontroluj pripojenie
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Pripojenie k databáze je úspešné.<br>";

    // Vloženie podnetu do databázy
    $sql = "INSERT INTO podnet (id_obcan, nazov, text, typ, datum) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $id_obcan, $nazov, $text, $typ);

    if ($stmt->execute()) {
        echo "Podnet bol úspešne zaregistrovaný!";
    } else {
        echo "Chyba pri registrácii podnetu: " . $stmt->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulár na zadanie podnetu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #9face6);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }
        .form-container label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
            color: #555;
        }
        .form-container input[type="text"],
        .form-container textarea,
        .form-container select {
            width: calc(100% - 2rem);
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .form-container textarea {
            resize: none;
            height: 100px;
        }
        .form-container button {
            background: #74ebd5;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
        }
        .form-container button:hover {
            background: #9face6;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Zadaj podnet</h2>
        <form method="post" action="podnet_form.php">
            <label for="nazov">Názov podnetu:</label>
            <input type="text" id="nazov" name="nazov" required>
            
            <label for="text">Text podnetu:</label>
            <textarea id="text" name="text" required></textarea>
            
            <label for="typ">Typ podnetu:</label>
            <select id="typ" name="typ" required>
                <option value="" disabled selected>Vyberte typ podnetu</option>
                <?php
                // Dynamické načítanie typov z databázy
                $conn = new mysqli("localhost", "root", "", "prvy_proof");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT DISTINCT typ FROM podnet WHERE typ IS NOT NULL";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $typ = htmlspecialchars($row['typ']);
                        echo "<option value='$typ'>$typ</option>";
                    }
                } else {
                    echo "<option value='' disabled>Žiadne typy podnetov neboli nájdené</option>";
                }

                $conn->close();
                ?>
            </select>
            
            <button type="submit">Odoslať podnet</button>
        </form>
    </div>
</body>
</html>
