<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Bezpečné prihlásenie používateľa
    $meno = $_POST['meno'];
    $priezvisko = $_POST['priezvisko'];

    $sql = "SELECT * FROM obcan WHERE meno = ? AND priezvisko = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $meno, $priezvisko);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $result->fetch_assoc(); // ulož prihláseného používateľa do session
        header("Location: podnet_form.php"); // presmeruj na formulár na zadanie podnetu
    } else {
        echo "<p class='error'>Nesprávne meno alebo priezvisko!</p>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prihlásenie</title>
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
        .login-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin-bottom: 1rem;
            color: #333;
        }
        .login-container label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
            color: #555;
        }
        .login-container input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .login-container button {
            background: #74ebd5;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .login-container button:hover {
            background: #9face6;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Prihlásenie</h2>
        <form method="post" action="login.php">
            <label for="meno">Meno:</label>
            <input type="text" id="meno" name="meno" required>
            <label for="priezvisko">Priezvisko:</label>
            <input type="text" id="priezvisko" name="priezvisko" required>
            <button type="submit">Prihlásiť sa</button>
        </form>
    </div>
</body>
</html>
