<?php
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
        /* Svetlé pozadie */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* Jemný tieň */
    }

    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        /* 7 stĺpcov pre dni v týždni */
        gap: 10px;
        margin: 20px 0;
    }

    .calendar-day {
        width: 50px;
        height: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        background-color: #FFFFFF;
        /* Biele pozadie pre dni */
        font-weight: bold;
        color: #201E43;
        /* Tmavý text */
        border: 2px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .calendar-day.today {
        border: 2px solid #508C9B;
        /* Zvýraznenie dnešného dňa */
    }

    .calendar-day .event-dot {
        width: 6px;
        height: 6px;
        background-color: #508C9B;
        /* Modrozelená bodka */
        border-radius: 50%;
        margin-top: 5px;
    }

    .calendar-day:hover {
        transform: scale(1.1);
        /* Zvýraznenie pri hover */
    }

    .calendar-day.empty {
        background-color: transparent;
        border: none;
        cursor: default;
    }

    .calendar-events {
        margin-top: 20px;
        text-align: left;
        background-color: #FFFFFF;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.33);
    }

    .calendar-events h5 {
        color: #134B70;
        font-weight: bold;
        margin-bottom: 10px;
    }

    #event-details {
        color: #201E43;
    }

    .vrsok{
        background-image: url();
        background-size: cover;
        background-repeat: no-repeat;
    }

</style><br>
<div class="container">
    <section class="vrsok">
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
    </section>
    <section class="spodok">
        <div class="row">
            <div class="col-lg-6">
                <div class="calendar-container">
                    <h4 style="text-align:center;">Interaktívna mapa </h4>
                    <img src="mapa_MSZ.png" alt="Mapa" style="width: 100%; height: auto; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                </div>
            </div>
            <div class="col-lg-6"> <!-- mapa -->
                <div class="calendar-container">
                    <h4>Kalendár <?= getMonthName($currentMonth) . " " . $currentYear ?></h4>
                    <div class="calendar">
                        <?php
                        // Získanie prvého dňa v mesiaci
                        $firstDayOfMonth = date('N', strtotime("$currentYear-$currentMonth-01"));
                        $emptyDays = $firstDayOfMonth - 1;

                        // Prázdne dni pred prvým dňom mesiaca
                        for ($i = 0; $i < $emptyDays; $i++) {
                            echo '<div class="calendar-day empty"></div>';
                        }

                        // Dni v mesiaci
                        for ($day = 1; $day <= $daysInMonth; $day++):
                            $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                            $hasEvent = isset($odpad_data[$date]) || isset($events[$date]); // Skontroluje, či sú udalosti
                            $isToday = ($date === date('Y-m-d')); // Skontroluje, či je dnešný deň
                        ?>
                            <div class="calendar-day <?= $isToday ? 'today' : '' ?>" data-date="<?= $date ?>">
                                <span><?= $day ?></span>
                                <?php if ($hasEvent): ?>
                                    <div class="event-dot"></div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="calendar-events">
                        <h5>Udalosti</h5>
                        <div id="event-details">Kliknite na deň pre zobrazenie udalostí.</div>
                    </div>
                </div>
            </div>
        </div><br><br><br>
    </section>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const calendarDays = document.querySelectorAll(".calendar-day");
        const eventDetails = document.getElementById("event-details");

        calendarDays.forEach(day => {
            day.addEventListener("click", function() {
                const date = this.getAttribute("data-date");

                // Simulácia udalostí (nahradiť skutočnými údajmi z PHP)
                const events = {

                };

                if (events[date]) {
                    eventDetails.innerHTML = events[date]
                        .map(event => `<p>${event}</p>`)
                        .join("");
                } else {
                    eventDetails.innerHTML = "Žiadne udalosti na tento deň.";
                }
            });
        });
    });
</script>
<?php
$conn->close();
include 'footer.php';
?>