<?php
session_start();
require_once 'include/db.php';
require_once 'include/auth.php';

requireLogin();

$post_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($post_id <= 0) {
    die("Nieprawidłowe ID ogłoszenia.");
}

$stmt = $pdo->prepare("SELECT * FROM ads WHERE id = :id AND user_id = :user_id");
$stmt->execute([
    'id' => $post_id,
    'user_id' => $_SESSION['user_id']
]);
$post = $stmt->fetch();

if (!$post) {
    die("Nie znaleziono ogłoszenia lub nie masz uprawnień do jego usunięcia.");
}

$deleteStmt = $pdo->prepare("DELETE FROM ads WHERE id = :id");
$deleteStmt->execute(['id' => $post_id]);

$imagePaths = [
    "users/posts/{$post_id}.png",
    "users/posts/{$post_id}.jpg",
    "users/posts/{$post_id}.jpeg"
];

foreach ($imagePaths as $path) {
    if (file_exists($path)) {
        unlink($path);
    }
}

header("Location: panel");
exit;
