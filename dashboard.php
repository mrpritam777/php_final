<?php
require_once 'includes/auth.php';
requireLogin();
require_once 'config/database.php';
$pdo = getDbConnection();
$totalStudents = 0;
$totalCourses = 0;
$recentStudents = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM students");
        $totalStudents = (int)$stmt->fetchColumn();
        $stmt = $pdo->query("SELECT COUNT(DISTINCT course) FROM students");
        $totalCourses = (int)$stmt->fetchColumn();
        $stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC LIMIT 5");
        $recentStudents = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Dashboard query error: " . $e->getMessage());
    }
}
?>
<?php include 'includes/header.php'; ?>
<main class="py-4">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0" id="dashboardTitle"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard</h1>
                        <p class="text-muted mb-0">Welcome back, <?php echo htmlspecialchars(getCurrentUserName()); ?>!</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            <?php echo date('l, F j, Y'); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Total Students</h5>
                                <p class="card-text"><?php echo $totalStudents; ?> enrolled students</p>
                            </div>
                            <div class="align-self-end">
                                <i class="fas fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Total Courses</h5>
                                <p class="card-text"><?php echo $totalCourses; ?> courses offered</p>
                            </div>
                            <div class="align-self-end">
                                <i class="fas fa-book fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Male Students</h5>
                                <p class="card-text"><?php echo count(array_filter($recentStudents, fn($s) => $s['gender'] === 'Male')); ?></p>
                            </div>
                            <div class="align-self-end">
                                <i class="fas fa-mars fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card bg-warning text-white shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Female Students</h5>
                                <p class="card-text"><?php echo count(array_filter($recentStudents, fn($s) => $s['gender'] === 'Female')); ?></p>
                            </div>
                            <div class="align-self-end">
                                <i class="fas fa-venus fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Recent Enrolments</h5>
                        <a href="students.php" class="btn btn-sm btn-outline-primary">View All Students</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentStudents)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent enrolments found.</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Course</th>
                                        <th>Enrolment Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentStudents as $student): ?>
                                    <tr>
                                        <td>#<?php echo $student['id']; ?></td>
                                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td><span class="badge bg-light text-dark"><?php echo htmlspecialchars($student['course']); ?></span></td>
                                        <td><?php echo date('M j, Y', strtotime($student['enrolment_date'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Quick Actions</h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="add-student.php" class="btn btn-primary w-100">
                                    <i class="fas fa-user-plus me-2"></i>Add New Student
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="students.php" class="btn btn-success w-100">
                                    <i class="fas fa-users me-2"></i>View All Students
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="users.php" class="btn btn-info w-100">
                                    <i class="fas fa-user-shield me-2"></i>Manage Admin Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
<script>
    // Topic 11: Change text of element with class .title using JavaScript
    function updateDashboardTitle() {
        const h1Element = document.getElementById('dashboardTitle');
        if (h1Element) {
            setTimeout(function() {
                const originalText = h1Element.innerHTML;
                h1Element.innerHTML = '<i class="fas fa-tachometer-alt me-2 text-success"></i>Dashboard Updated!';
                h1Element.classList.add('text-success');
                setTimeout(function() {
                    h1Element.innerHTML = originalText;
                    h1Element.classList.remove('text-success');
                }, 3000);
            }, 2000);
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        updateDashboardTitle();
    });
</script>