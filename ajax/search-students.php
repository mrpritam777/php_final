<?php


header('Content-Type: application/json');

session_start();
require_once '../config/database.php';

$response = ['success' => false, 'students' => []];

$search = trim($_POST['search'] ?? '');

if ($search !== '') {
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT id, name, course FROM students WHERE name LIKE :search LIMIT 10");
            $stmt->execute(['search' => "%" . $search . "%"]);
            $response['students'] = $stmt->fetchAll();
            $response['success'] = true;
        } catch (PDOException $e) {
            error_log("Search error: " . $e->getMessage());
        }
    }
}

echo json_encode($response);