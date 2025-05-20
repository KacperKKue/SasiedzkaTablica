<?php
require_once 'include/auth.php';

$avatarPath = 'images/avatar.png'; // domyślny awatar

if (isLoggedIn()) {
    $userId = $_SESSION["user_id"];
    $pngPath = "users/avatars/{$userId}.png";
    $jpgPath = "users/avatars/{$userId}.jpg";

    if (file_exists($pngPath)) {
        $avatarPath = $pngPath;
    } elseif (file_exists($jpgPath)) {
        $avatarPath = $jpgPath;
    }
}
?>

<div class="user-menu">
    <div class="avatar" id="avatar" style="background-image: url('<?php echo $avatarPath; ?>') !important;"></div>

    <div class="dropdown" id="userDropdown">
        <?php if (isLoggedIn()): ?>
            <a href="profil">Profil</a>
            <a href="panel">Panel</a>
            <a href="logout.php">Wyloguj</a>
        <?php else: ?>
            <a href="login">Zaloguj</a>
            <a href="rejestracja">Zarejestruj</a>
        <?php endif; ?>
    </div>
</div>
