<?php
session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header("Location: login");
        exit;
    }
}

function requireNotLoggedIn(): void {
    if (isLoggedIn()) {
        header("Location: index");
        exit;
    }
}