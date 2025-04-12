<!-- filepath: c:\xampp\htdocs\hackathon\Hackathon\navbar.php -->
<div class="navbar" style="background-color:#919190">
    <div class="navbar-left" style="padding-left: 6%;">
        <img src="assets\icon\erb.png" alt="erb" class="navbar-logo">
        <span class="navbar-text text-light">Čierne</span>
    </div>
    <div class="navbar-right">
        <a href="index.php" class="navbar-link text-light" style="font-weight: 700;">Domov</a>
        <a href="about.php" class="navbar-link text-light" style="font-weight: 700;">O nás</a>
        <a href="contact.php" class="navbar-link text-light" style="font-weight: 700;">Úradné tlačivá</a>
        <a href="services.php" class="navbar-link text-light" style="font-weight: 700;">Podnety & Ankety</a>
        <a href="cabinet/admin_login.php" class="navbar-link text-light" style="font-weight: 700;"><img src="assets/icon/user.png" alt="User" class="navbar-logo">Profil</a>
    </div>
</div>

<style>
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #f8f9fa; /* Svetlosivá farba pozadia */
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
        color: #333;
    }

    .navbar-right {
        display: flex;
        align-items: center; /* Zarovná všetky tlačidlá vertikálne do stredu */
        gap: 15px; /* Medzera medzi tlačidlami */
    }

    .navbar-link {
        text-decoration: none;
        font-size: 16px;
        color: white; /* Farba textu */
        padding: 5px 10px;
        border-radius: 5px;
        transition: background-color 0.3s ease, color 0.3s ease;
        display: inline-flex; /* Zabezpečí správne zarovnanie obsahu */
        align-items: center; /* Vertikálne zarovnanie textu */
    }

    .navbar-link img {
        height: 20px; /* Veľkosť ikony používateľa */
        margin-left: 5px; /* Medzera medzi textom a ikonou */
    }

    .navbar-link:hover {
        background-color: #3A59D1; /* Modré pozadie pri hover */
        color: white; /* Biela farba textu pri hover */
    }

    .navbar-user {
        position: absolute;
        top: 10px;
        right: 20px;
    }

    .user-icon {
        font-size: 30px;
        text-decoration: none;
        color: black;
        cursor: pointer;
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .user-icon:hover {
        transform: scale(1.1);
        color: #3A59D1; /* Change color on hover */
    }
</style>