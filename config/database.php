<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'student_enrolment');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 *
 * @return PDO|null PDO connection object or null on failure
 */
function getDbConnection()
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false, // Use real prepared statements
            ];

            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log error but do NOT expose sensitive details to the end user
            error_log("Database connection error: " . $e->getMessage());
            $pdo = null;
        }
    }

    return $pdo;
}