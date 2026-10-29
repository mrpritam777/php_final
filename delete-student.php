<?php
require_once __DIR__ . '/includes/auth.php';

requireLogin();
require_once 'config/database.php';

$pdo = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    
    // Simple CSRF check using session
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
        exit;
    }

    if ($pdo) {
        try {
            $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            if ($stmt->rowCount() > 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Student deleted successfully!']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Student not found.']);
            }
        } catch (PDOException $e) {
            error_log("Delete error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to delete student.']);
        }
        exit;
    }
}

// If accessed via GET, redirect to students page
header("Location: students.php");
exit;