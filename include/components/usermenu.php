<?php
require_once 'include/auth.php';
?>

<div class="user-menu">
    <div class="avatar" id="avatar">
        <style>
            <?php if (isLoggedIn()): ?>
                <?php if (file_exists('users/avatars/' . $_SESSION["user_id"] . '.png')): ?>
                    #avatar {
                        background-image: url('users/avatars/<?php echo $_SESSION["user_id"]; ?>.png') !important;
                    }
                <?php else: ?>
                    #avatar {
                        background-image: url('images/avatar.png') !important;
                    }
                <?php endif; ?>
            <?php else: ?>
                #avatar {
                    background-image: url('images/avatar.png') !important;
                }
            <?php endif; ?>
        </style>
    </div>
    <div class="dropdown" id="userDropdown">
        <?php if (isLoggedIn()): ?>
            <a href="profil">Profil</a>
            <a href="dodaj">Panel</a>
            <a href="logout.php">Wyloguj</a>
        <?php else: ?>
            <a href="login">Zaloguj</a>
            <a href="rejestracja">Zarejestruj</a>
        <?php endif; ?>
    </div>
</div>