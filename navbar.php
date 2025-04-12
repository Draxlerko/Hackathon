<div class="navbar">
    <div class="navbar-left" style="padding-left: 6%;">
        <img src="assets/icon/erb.png" alt="erb" class="navbar-logo">
        <span class="navbar-text">Čierne</span>
    </div>
    <div class="navbar-right" style="padding-right:6%">
        <a href="index.php" class="navbar-link active">Domov</a>
        <a href="#" class="navbar-link">O nás</a>
        <a href="#" class="navbar-link">Úradné tlačivá</a>
        <a href="feedback.php" class="navbar-link">Spätná väzba</a>
        <a href="paygate.php" class="navbar-link">Platobná brána</a>
        <a href="login/login.php" class="navbar-link">
            <img src="assets/icon/user.png" alt="User" class="navbar-logo">Profil
        </a>
    </div>
</div>

<style>
    /* Štýly pre hlavičku */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #508C9B; /* Tmavomodré pozadie */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Jemný tieň */
    }

    .navbar-left {
        display: flex;
        align-items: center;
    }

    .navbar-logo {
        height: 40px; /* Výška loga */
        margin-right: 10px; /* Medzera medzi logom a textom */
    }

    .navbar-text {
        font-size: 20px;
        font-weight: bold;
        color: #EEEEEE; /* Svetlý text */
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 15px; /* Medzera medzi odkazmi */
    }

    .navbar-link {
        text-decoration: none;
        font-size: 16px;
        color: #EEEEEE; /* Svetlý text */
        padding: 5px 10px;
        border-radius: 5px;
        transition: color 0.3s ease, border-bottom 0.3s ease;
    }

    .navbar-link:hover {
        color: #134B70; /* Svetlomodrá farba pri hover */
        text-decoration: underline; /* Podčiarknutie pri hover */
    }

    .navbar-link.active {
        border-bottom: 2px solid #508C9B; /* Zvýraznenie aktívnej sekcie */
    }

    .navbar-link img {
        height: 20px; /* Veľkosť ikony používateľa */
        margin-left: 5px; /* Medzera medzi textom a ikonou */
    }
</style>