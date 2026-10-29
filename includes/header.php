<?php
/**
 * includes/header.php - Header with Sidebar Navigation
 * 
 * Topic 3 Reference: This file is loaded using include() for reusable header/footer.
 * include() produces a warning if missing but continues execution.
 * require() is used for critical files like database.php.
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrolment Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css?v=2.0" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-graduation-cap"></i> <span>Student Hub</span></h3>
                <strong>SH</strong>
            </div>

            <ul class="list-unstyled components">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="students.php" class="nav-link"><i class="fas fa-users me-2"></i>Manage Students</a>
                </li>
                <li class="nav-item">
                    <a href="add-student.php" class="nav-link"><i class="fas fa-user-plus me-2"></i>Add Student</a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link"><i class="fas fa-user-shield me-2"></i>Admin Users</a>
                </li>
            </ul>

            <div class="sidebar-footer mt-auto p-3 border-top">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-circle fa-lg me-2"></i>
                    <div>
                        <small class="text-white d-block"><?php echo htmlspecialchars(getCurrentUserName()); ?></small>
                        <small class="text-white-50 d-block">Administrator</small>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="logout.php" class="btn btn-danger btn-sm w-100"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-bars"></i>
                        <span class="sr-only">Toggle Sidebar</span>
                    </button>

                    <div class="d-flex flex-grow-1">
                        <h4 class="navbar-brand mb-0 flex-grow-1">
                            Student Enrolment System
                        </h4>
                    </div>

                    <div class="dropdown text-end">
                        <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fa-lg me-1"></i> Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                            <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="students.php"><i class="fas fa-users me-2"></i>Manage Students</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </nav>