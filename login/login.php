<?php
ob_start(); // Start output buffering
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        header("Location: user_menu.php"); // presmeruj na hlavné menu
        exit();
    } else {
        $error = "Nesprávne meno alebo priezvisko!";
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
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #3A59D1, #3D90D7);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: #fff;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin-bottom: 1rem;
            color: #3A59D1;
        }
        .login-container label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
            color: #555;
        }
        .login-container input {
            width: calc(100% - 20px);
            padding: 0.8rem 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .login-container button {
            background: #3A59D1;
            color: #fff;
            border: none;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .login-container button:hover {
            background: #2f47aa;
            transform: translateY(-3px);
        }
        .error {
            color: red;
            font-weight: bold;
            margin-top: 1rem;
        }
        .settings-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }
        .settings-icon svg {
            width: 100%;
            height: 100%;
            fill: #fff;
            transition: transform 0.3s ease;
        }
        .settings-icon:hover svg {
            transform: rotate(90deg);
        }
    </style>
</head>
<body>
    <!-- Wrench ikonka -->
    <a href="../cabinet/admin_login.php" class="settings-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M22.7 19.3l-4.2-4.2c.5-1 .8-2.1.8-3.1 0-3.9-3.1-7-7-7-1.1 0-2.1.3-3.1.8L4.7 1.3C4.3.9 3.7.9 3.3 1.3l-2 2c-.4.4-.4 1 0 1.4l5.5 5.5c-.5 1-.8 2.1-.8 3.1 0 3.9 3.1 7 7 7 1.1 0 2.1-.3 3.1-.8l4.2 4.2c.4.4 1 .4 1.4 0l2-2c.4-.4.4-1 0-1.4zM9 12c0-1.7 1.3-3 3-3s3 1.3 3 3-1.3 3-3 3-3-1.3-3-3z"/>
        </svg>
    </a>

    <div class="login-container">
        <h2>Prihlásenie</h2>
        <form method="post" action="login.php">
            <label for="meno">Meno:</label>
            <input type="text" id="meno" name="meno" required>
            <label for="priezvisko">Priezvisko:</label>
            <input type="text" id="priezvisko" name="priezvisko" required>
            <button type="submit">Prihlásiť sa</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
