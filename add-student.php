<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require_once 'config/database.php';
$pdo = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_student') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => '', 'errors' => []];
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
            $stmt = $pdo->prepare("SELECT id FROM students WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $response['errors']['email'] = "Email already exists.";
            }
        } catch (PDOException $e) { error_log("Email check error: " . $e->getMessage()); }
    }
    if (empty($response['errors']) && $pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO students (name, email, phone, gender, course, address, enrolment_date) VALUES (:name, :email, :phone, :gender, :course, :address, :enrolment_date)");
            $stmt->execute([
                'name' => $name, 'email' => $email, 'phone' => $phone,
                'gender' => $gender, 'course' => $course, 'address' => $address,
                'enrolment_date' => $enrolment_date
            ]);
            $response['success'] = true;
            $response['message'] = "Student added successfully!";
            $response['id'] = $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Insert error: " . $e->getMessage());
            $response['message'] = "Failed to add student. Please try again.";
        }
    } elseif (empty($response['errors'])) {
        $response['message'] = "Database connection failed.";
    }
    echo json_encode($response);
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
                        <h1 class="h3 mb-0">Add New Student</h1>
                        <p class="text-muted mb-0">Fill in the student enrolment form</p>
                    </div>
                    <a href="students.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Students</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><i class="fas fa-user-plus me-2"></i>Student Enrolment Form</h5>
                    </div>
                    <div class="card-body">
                        <form id="studentForm" novalidate>
                            <input type="hidden" name="action" value="add_student">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control" placeholder="555-0100" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Course <span class="text-danger">*</span></label>
                                    <input type="text" name="course" class="form-control" placeholder="Computer Science" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Enrolment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="enrolment_date" class="form-control" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="3" placeholder="123 Street, City" required></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="button" id="submitBtn" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Enrol Student
                                </button>
                                <button type="reset" class="btn btn-outline-secondary px-4">Reset</button>
                            </div>
                        </form>
                        <div id="formMessage" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title">Form Instructions</h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2"><i class="fas fa-info-circle me-2 text-primary"></i> All fields are required.</li>
                            <li class="mb-2"><i class="fas fa-info-circle me-2 text-primary"></i> Email must be unique.</li>
                            <li class="mb-2"><i class="fas fa-info-circle me-2 text-primary"></i> Form submits via AJAX without page reload.</li>
                            <li class="mb-2"><i class="fas fa-info-circle me-2 text-primary"></i> Validation runs on both client and server.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<script>
    // Topic 12: jQuery - Submit form when button is clicked
    // Topic 13: AJAX POST request with JSON response
    // Topic 10: JavaScript email validation with regex
    $(document).ready(function() {
        $('#submitBtn').on('click', function() {
            var form = $('#studentForm')[0];
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
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');

            $.post('add-student.php', $('#studentForm').serialize(), function(response) {
                btn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    showMessage(response.message, 'success');
                    $('#studentForm')[0].reset();
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

        $('#studentForm input, #studentForm select, #studentForm textarea').on('input change', function() {
            $('#formMessage').html('');
        });
    });

    function showMessage(message, type) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        $('#formMessage').html('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
    }
</script>