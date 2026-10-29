<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require_once 'config/database.php';
$pdo = getDbConnection();
$students = [];
$courses = [];
$search = '';
$filterCourse = '';
$sort = 'DESC';
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT DISTINCT course FROM students ORDER BY course");
        $courses = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $sql = "SELECT * FROM students WHERE 1=1";
        $params = [];
        if (isset($_GET['search']) && $_GET['search'] !== '') {
            $search = trim($_GET['search']);
            $sql .= " AND name LIKE :search";
            $params['search'] = "%" . $search . "%";
        }
        if (isset($_GET['course']) && $_GET['course'] !== '') {
            $filterCourse = $_GET['course'];
            $sql .= " AND course = :course";
            $params['course'] = $filterCourse;
        }
        $sort = isset($_GET['sort']) && $_GET['sort'] === 'ASC' ? 'ASC' : 'DESC';
        $sql .= " ORDER BY id " . $sort;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $students = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Students query error: " . $e->getMessage());
    }
}
?>
<?php include 'includes/header.php'; ?>
<main class="py-4">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h1 class="h3 mb-0">Manage Students</h1>
                        <p class="text-muted mb-0">View, search, and filter student records</p>
                    </div>
                    <a href="add-student.php" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Add Student
                    </a>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="students.php" id="filterForm" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search by Name</label>
                                <input type="text" name="search" class="form-control" placeholder="Enter student name" value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Filter by Course</label>
                                <select name="course" class="form-select">
                                    <option value="">All Courses</option>
                                    <?php foreach ($courses as $course): ?>
                                    <option value="<?php echo htmlspecialchars($course); ?>" <?php echo ($filterCourse === $course) ? 'selected' : ''; ?>><?php echo htmlspecialchars($course); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Sort by ID</label>
                                <select name="sort" class="form-select">
                                    <option value="Z-A" <?php echo ($sort === 'DESC') ? 'selected' : ''; ?>>Descending (Newest)</option>
                                    <option value="A-Z" <?php echo ($sort === 'ASC') ? 'selected' : ''; ?>>Ascending (Oldest)</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i>Filter</button>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <a href="students.php" class="btn btn-outline-secondary w-100">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <span class="badge bg-secondary"><i class="fas fa-list me-1"></i><?php echo count($students); ?> student(s) found</span>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <?php if (empty($students)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                            <h4>No students found</h4>
                            <p class="text-muted">Try adjusting your search or filter criteria.</p>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="studentsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Course</th>
                                        <th>Enrolment Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td>#<?php echo $student['id']; ?></td>
                                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                        <td><span class="badge bg-<?php echo $student['gender'] === 'Male' ? 'info' : ($student['gender'] === 'Female' ? 'success' : 'secondary'); ?>"><?php echo htmlspecialchars($student['gender']); ?></span></td>
                                        <td><?php echo htmlspecialchars($student['course']); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($student['enrolment_date'])); ?></td>
                                        <td class="text-end">
                                            <a href="edit-student.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                            <button class="btn btn-sm btn-danger" onclick="deleteStudent(<?php echo $student['id']; ?>, '<?php echo htmlspecialchars($student['name'], ENT_QUOTES); ?>')"><i class="fas fa-trash"></i></button>
                                        </td>
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
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<script>
    function deleteStudent(id, name) {
        if (confirm("Are you sure you want to delete student: " + name + "?")) {
            window.location.href = 'delete-student.php?id=' + id;
        }
    }
</script>