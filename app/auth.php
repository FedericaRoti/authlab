<?php

#The user logged in the current session 
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}
#Check if the user is logged in, if not redirect to login page
function require_auth(): void {
    if (!current_user()) {
        $_SESSION['flash_error'] = 'Devi effettuare il login.';
        header('Location: /login.php');
        exit;
    }
}
#Saved the user data in the session 
function login_user(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'    => $user['id'],
        'email' => $user['email'],
        'name'  => $user['name'] ?? null,
    ];
}
#Logout the user by clearing the session and destroying it
function logout_user(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
