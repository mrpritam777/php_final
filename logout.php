<?php

session_start();
require_once 'includes/auth.php';

// Topic 15: Clear session cookie using setcookie
// Set expiration time in the past to delete the cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],     // Secure flag (true over HTTPS)
        $params["httponly"]    // HttpOnly flag (prevents JS access)
    );
}

logoutUser();
header("Location: login.php?logged_out=1");
exit;