<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require_once 'config/database.php';
$pdo = getDbConnection();
$student = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_student') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => '', 'errors' => []];
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $course = trim($_POST['course'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $enrolment_date = $_POST['enrolment_date'] ?? '';
    if (empty($name)) { $response['errors']['name'] = "Name is required."; }
    if (empty($email)) { $response['errors']['email'] = "Email is required."; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $response['errors']['email'] = "Invalid email format."; }
    if (empty($phone)) { $response['errors']['phone'] = "Phone is required."; }
    if (empty($gender)) { $response['errors']['gender'] = "Gender is required."; }
    if (empty($course)) { $response['errors']['course'] = "Course is required."; }
    if (empty($address)) { $response['errors']['address'] = "Address is required."; }
    if (empty($enrolment_date)) { $response['errors']['enrolment_date'] = "Enrolment date is required."; }
    if (empty($response['errors']) && $pdo) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM students WHERE email = :email AND id != :id LIMIT 1");
            $stmt->execute(['email' => $email, 'id' => $id]);
            if ($stmt->fetch()) {
                $response['errors']['email'] = "Email already exists.";
            }
        } catch (PDOException $e) { error_log("Email check error: " . $e->getMessage()); }
    }
    if (empty($response['errors']) && $pdo) {
        try {
            $stmt = $pdo->prepare("UPDATE students SET name=:name, email=:email, phone=:phone, gender=:gender, course=:course, address=:address, enrolment_date=:enrolment_date WHERE id=:id");
            $stmt->execute([
                'name' => $name, 'email' => $email, 'phone' => $phone,
                'gender' => $gender, 'course' => $course, 'address' => $address,
                'enrolment_date' => $enrolment_date, 'id' => $id
            ]);
            $response['success'] = true;
            $response['message'] = "Student updated successfully!";
        } catch (PDOException $e) {
            error_log("Update error: " . $e->getMessage());
            $response['message'] = "Failed to update student.";
        }
    } elseif (empty($response['errors'])) {
        $response['message'] = "Database connection failed.";
    }
    echo json_encode($response);
    exit;
}

if (isset($_GET['id']) && $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => (int)$_GET['id']]);
        $student = $stmt->fetch();
        if (!$student) {
            header("Location: students.php?error=not_found");
            exit;
        }
    } catch (PDOException $e) {
        error_log("Edit load error: " . $e->getMessage());
        header("Location: students.php");
        exit;
    }
} else {
    header("Location: students.php");
    exit;
}
?>
<?php include 'includes/header.php'; ?>
<main class="py-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0">Edit Student</h1>
                        <p class="text-muted mb-0">Update student details for #<?php echo $student['id']; ?></p>
                    </div>
                    <a href="students.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Students</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><i class="fas fa-edit me-2"></i>Edit Student Record</h5>
                    </div>
                    <div class="card-body">
                        <form id="editStudentForm" novalidate>
                            <input type="hidden" name="action" value="update_student">
                            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo $student['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Course <span class="text-danger">*</span></label>
                                    <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($student['course']); ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Enrolment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="enrolment_date" class="form-control" value="<?php echo $student['enrolment_date']; ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($student['address']); ?></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="button" id="updateBtn" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Update Student
                                </button>
                                <a href="students.php" class="btn btn-outline-secondary px-4">Cancel</a>
                            </div>
                        </form>
                        <div id="editMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<script>
    $(document).ready(function() {
        $('#updateBtn').on('click', function() {
            var form = $('#editStudentForm')[0];
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            var email = $('input[name="email"]').val().trim();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showMessage('Please enter a valid email address.', 'danger');
                return;
            }
            var btn = $(this);
            var originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
            $.post('edit-student.php', $('#editStudentForm').serialize(), function(response) {
                btn.prop('disabled', false).html(originalText);
                if (response.success) {
                    showMessage(response.message, 'success');
                    setTimeout(function() {
                        window.location.href = 'students.php';
                    }, 1500);
                } else {
                    if (response.errors) {
                        var errorMsg = '';
                        $.each(response.errors, function(field, msg) {
                            errorMsg += msg + '<br>';
                        });
                        showMessage(errorMsg, 'danger');
                    } else {
                        showMessage(response.message || 'An error occurred.', 'danger');
                    }
                }
            }, 'json').fail(function() {
                btn.prop('disabled', false).html(originalText);
                showMessage('Server error. Please try again later.', 'danger');
            });
        });
        $('#editStudentForm input, #editStudentForm select, #editStudentForm textarea').on('input change', function() {
            $('#editMessage').html('');
        });
    });
    function showMessage(message, type) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        $('#editMessage').html('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
    }
</script>