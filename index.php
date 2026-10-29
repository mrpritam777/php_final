<?php

session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}