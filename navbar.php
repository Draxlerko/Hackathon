<!-- Add this inside your navbar -->
<div class="navbar-user">
    <a href="login/login.php" class="user-icon">👤</a>
</div>

<style>
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