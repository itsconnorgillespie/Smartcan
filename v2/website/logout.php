<?php

// Session start
session_start();

// Unset variables
$_SESSION = array();

// Clear cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Session destroy
session_destroy();

// Redirect
header("Location: login.php");