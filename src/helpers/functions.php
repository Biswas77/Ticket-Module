<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function currentUserId() {
    return $_SESSION['user']['id'] ?? null;
}

function currentUserRole() {
    return $_SESSION['user']['role'] ?? 'user';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: auth_login.php");
        exit;
    }
}

function isAuthor($ticket) {
    return currentUserId() == $ticket['created_by'];
}

function isAssignee($ticket) {
    return currentUserId() == $ticket['assigned_to'];
}

function requireAuthor($ticket) {
    if (!isAuthor($ticket)) {
        echo "Access Denied: Only the author can perform this action.";
        exit;
    }
}

function requireAssignee($ticket) {
    if (!isAssignee($ticket)) {
        echo "Access Denied: Only the assigned user can update status.";
        exit;
    }
}
