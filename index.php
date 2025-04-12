<?php
session_start();

$isLoggedIn = isset($_SESSION['user']); // Skontroluje, či je používateľ prihlásený

include 'header.php';
include 'navbar.php';

// Pripojenie k databáze
$conn = new mysqli("localhost", "root", "", "prvy_proof");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Načítanie udalostí z databázy
$sql = "SELECT * FROM news";
$result = $conn->query($sql);

// Načítanie údajov o vývoze odpadu z tabuľky "odpad"
$odpad_query = "SELECT datum_vyvozu, druh_odpadu FROM odpad WHERE id_obec = 1"; // Zmeňte id_obec podľa potreby
$odpad_result = $conn->query($odpad_query);

$odpad_data = [];
if ($odpad_result->num_rows > 0) {
    while ($row = $odpad_result->fetch_assoc()) {
        $odpad_data[$row['datum_vyvozu']] = $row['druh_odpadu'];
    }
}

// Funkcia na získanie názvu mesiaca
function getMonthName($month)
{
    $months = [
        1 => 'Január',
        2 => 'Február',
        3 => 'Marec',
        4 => 'Apríl',
        5 => 'Máj',
        6 => 'Jún',
        7 => 'Júl',
        8 => 'August',
        9 => 'September',
        10 => 'Október',
        11 => 'November',
        12 => 'December'
    ];

    return $months[$month] ?? 'Neznámy mesiac'; // Vráti predvolený text, ak kľúč neexistuje
}

// Aktuálny mesiac a rok
$currentMonth = isset($_GET['month']) ? intval($_GET['month']) : intval(date('m'));
$currentYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Oprava rozsahu mesiacov
if ($currentMonth < 1) {
    $currentMonth = 12;
    $currentYear--;
} elseif ($currentMonth > 12) {
    $currentMonth = 1;
    $currentYear++;
}

// Počet dní v mesiaci
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
?>


<style>
    .calendar-container {
        text-align: center;
        margin-top: 20px;
    }

    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        /* 7 stĺpcov pre dni v týždni */
        gap: 10px;
        margin: 20px 0;
    }

    .calendar-day {
        width: 50px; /* Zvýšenie veľkosti guličky */
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%; /* Gulička */
        background-color: white; /* Farba pozadia guličky */
        font-weight: bold;
        color: #333; /* Farba čísla */
        border: 4px solid transparent; /* Hrubší okraj, predvolene priehľadný */
        transition: all 0.3s ease; /* Plynulý prechod pri zmene */
    }

    .calendar-day.yellow {
        border-color: #FFD700; /* Zlatá farba pre plasty */
        color:black;
        background-color: white; /* Farba pozadia guličky */
    }

    .calendar-day.green {
        border-color: #008000; /* Zelená farba pre sklo */
        color:black;
        background-color: white; /* Farba pozadia guličky */
    }

    .calendar-day.blue {
        border-color: #0000FF; /* Modrá farba pre papier */
        color:black;
        background-color: white; /* Farba pozadia guličky */    
    }

    .calendar-day.brown {
        border-color: #8B4513; /* Hnedá farba pre bio */
        color:black;
        background-color: white; /* Farba pozadia guličky */
    }

    .calendar-day.black {
        border-color: #000000; /* Čierna farba pre komunálny odpad */
        color:black;
        background-color: white; /* Farba pozadia guličky */
    }

    .calendar-day:hover {
        transform: scale(1.1); /* Zvýraznenie pri hover */
        cursor: pointer;
    }

    .calendar-day.empty {
        background-color: transparent;
        border: none;
    }

    .calendar-navigation {
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
    }

    .nav-btn {
        text-decoration: none;
        background-color: #00bcd4;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .nav-btn:hover {
        background-color: #008c9e;
    }

    .calendar-legend {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .legend {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .legend.yellow {
        background-color: yellow;
    }

    .legend.green {
        background-color: green;
    }

    .legend.blue {
        background-color: blue;
    }

    .legend.brown {
        background-color: brown;
    }

    .legend.black {
        background-color: black;
    }

    .filter-btn {
        text-decoration: none;
        font-size: 16px;
        color: black;
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        background-color: #f0f0f0; /* Predvolená farba pozadia */
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sivý tieň pod tlačidlom */
    }

    .filter-btn:hover {
        transform: translateY(-2px); /* Mierne zdvihnutie pri hover */
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2); /* Zvýraznený tieň pri hover */
    }

    .filter-btn:active {
        transform: translateY(1px); /* Jemný stlačený efekt */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Zmenšený tieň pri kliknutí */
    }

    /* Štýly pre modálne okno */
    .modal {
        display: none; /* Skryté pred zobrazením */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5); /* Polopriehľadné pozadie */
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border-radius: 10px;
        width: 300px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    input[type="submit"] {
        background-color: #3A59D1;
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #2f47aa;
    }
</style><br>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div id="profileIcon">
                <img src="path/to/user-icon.png" alt="Profil" style="cursor: pointer;">
            </div>

            <?php if ($isLoggedIn): ?>
                <div class="profile-options">
                    <a href="profile.php">Upraviť profil</a>
                    <a href="login/logout.php">Odhlásiť sa</a>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-6">

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <h4>Nadchádzajúce udalosti v našej obci</h4>
            <div class="filter-buttons">
                <button class="filter-btn" data-category="vsetky">Všetky</button>
                <button class="filter-btn" data-category="oznam" style="background-color:#00bcd4;color:white;font-weight:600">Oznam</button>
                <button class="filter-btn" data-category="kultura" style="background-color:#e91e63;color:white;font-weight:600">Kultúra</button>
                <button class="filter-btn" data-category="sport" style="background-color:#FF7852;color:white;font-weight:600">Šport</button>
                <button class="filter-btn" data-category="zmena" style="background-color:#673ab7;color:white;font-weight:600">Zmena</button>
            </div>
            <div class="carousel-container">
                <button class="carousel-btn left-btn">&lt;</button>
                <div class="events-carousel">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $categoryClass = strtolower($row['typ']);
                            echo "
                            <div class='event-card $categoryClass'>
                                <h3>{$row['nazov']}</h3>
                                <p>{$row['text']}</p>
                                <p>{$row['datum_od']} - {$row['datum_do']}</p>
                                <span class='badge $categoryClass'>{$row['typ']}</span>
                            </div>
                            ";
                        }
                    } else {
                        echo "<p>Žiadne udalosti na zobrazenie.</p>";
                    }
                    ?>
                </div>
                <button class="carousel-btn right-btn">&gt;</button>
            </div>
        </div>
    </div><br><br>
    <div class="row">
        <div class="col-lg-6">
            <h4>Interaktívna mapa </h4>
        </div>
        <div class="col-lg-6"> <!-- mapa -->
            <div class="calendar-container" style="border: 1px solid #ccc; padding: 20px; border-radius: 10px;">
                <h4 style="text-align:left;">Kalendár <?= getMonthName($currentMonth) . " " . $currentYear ?></h4>
                <div class="calendar">
                    <?php
                    // Získanie prvého dňa v mesiaci
                    $firstDayOfMonth = date('N', strtotime("$currentYear-$currentMonth-01"));
                    $emptyDays = $firstDayOfMonth - 1; // Počet prázdnych dní pred prvým dňom mesiaca

                    // Prázdne dni pred prvým dňom mesiaca
                    for ($i = 0; $i < $emptyDays; $i++) {
                        echo '<div class="calendar-day empty"></div>';
                    }

                    // Dni v mesiaci
                    for ($day = 1; $day <= $daysInMonth; $day++):
                        $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                        $colorClass = '';
                        if (isset($odpad_data[$date])) {
                            switch ($odpad_data[$date]) {
                                case 'plasty': $colorClass = 'yellow'; break;
                                case 'sklo': $colorClass = 'green'; break;
                                case 'papier': $colorClass = 'blue'; break;
                                case 'bio': $colorClass = 'brown'; break;
                                case 'komunalny': $colorClass = 'black'; break;
                            }
                        }
                    ?>
                        <div class="calendar-day <?= $colorClass ?>">
                            <?= $day ?>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="calendar-navigation">
                    <?php
                    // Výpočet predchádzajúceho mesiaca a roka
                    $prevMonth = $currentMonth - 1;
                    $prevYear = $currentYear;
                    if ($prevMonth < 1) {
                        $prevMonth = 12;
                        $prevYear--;
                    }

                    // Výpočet nasledujúceho mesiaca a roka
                    $nextMonth = $currentMonth + 1;
                    $nextYear = $currentYear;
                    if ($nextMonth > 12) {
                        $nextMonth = 1;
                        $nextYear++;
                    }
                    ?>
                    <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="nav-btn">&lt; <?= getMonthName($prevMonth) . "/$prevYear" ?></a>
                    <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="nav-btn"><?= getMonthName($nextMonth) . "/$nextYear" ?> &gt;</a>
                </div>
                <div class="calendar-legend">
                    <p><span class="legend yellow"></span> Plasty</p>
                    <p><span class="legend green"></span> Sklo</p>
                    <p><span class="legend blue"></span> Papier</p>
                    <p><span class="legend brown"></span> Bio</p>
                    <p><span class="legend black"></span> Komunálny odpad</p>
                </div>
            </div>
        </div>
    </div><br><br><br>

    <!-- Modálne okno -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Prihlásenie</h2>
            <form action="login/login.php" method="post">
                <label for="username">Používateľské meno:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Heslo:</label>
                <input type="password" id="password" name="password" required>
                
                <input type="submit" value="Prihlásiť sa">
            </form>
        </div>
    </div>

</div>

<?php
$conn->close();
include 'footer.php';
?>