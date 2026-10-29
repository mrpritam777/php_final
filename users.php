<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();
require_once 'config/database.php';
$pdo = getDbConnection();
$users = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_user') {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => '', 'errors' => []];
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($name)) { $response['errors']['name'] = "Name is required."; }
    if (empty($email)) { $response['errors']['email'] = "Email is required."; }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $response['errors']['email'] = "Invalid email."; }
    if (empty($password)) { $response['errors']['password'] = "Password is required."; }
    elseif (strlen($password) < 6) { $response['errors']['password'] = "Password must be at least 6 characters."; }
    if (empty($response['errors']) && $pdo) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $response['errors']['email'] = "Email already registered.";
            }
        } catch (PDOException $e) { error_log("User email check: " . $e->getMessage()); }
    }
    if (empty($response['errors']) && $pdo) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashedPassword]);
            $response['success'] = true;
            $response['message'] = "User added successfully!";
        } catch (PDOException $e) {
            error_log("User insert error: " . $e->getMessage());
            $response['message'] = "Failed to add user.";
        }
    } elseif (empty($response['errors'])) {
        $response['message'] = "Database connection failed.";
    }
    echo json_encode($response);
    exit;
}

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Users fetch error: " . $e->getMessage());
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
                        <h1 class="h3 mb-0">Admin Users</h1>
                        <p class="text-muted mb-0">Manage system administrators</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-user-plus me-2"></i>Add User
                    </button>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="fas fa-search me-2"></i>Users Starting with "A" (Topic 6 Demo)</h5>
                        <span class="badge bg-info"><?php echo count(array_filter($users, fn($u) => strpos($u['name'], 'A') === 0)); ?> found</span>
                    </div>
                    <div class="card-body">
                        <?php 
                        $usersStartingWithA = [];
                        if ($pdo) {
                            try {
                                $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE :name ORDER BY id DESC");
                                $stmt->execute(['name' => 'A%']);
                                $usersStartingWithA = $stmt->fetchAll();
                            } catch (PDOException $e) {
                                error_log("Topic 6 query error: " . $e->getMessage());
                            }
                        }
                        ?>
                        <?php if (empty($usersStartingWithA)): ?>
                        <p class="text-muted">No users found whose name starts with "A".</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usersStartingWithA as $user): ?>
                                    <tr>
                                        <td>#<?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                        <div class="mt-2">
                            <a href="topic-examples.php?id=6" class="btn btn-sm btn-outline-primary">View Topic 6 Explanation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">All Admin Users</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($users)): ?>
                        <p class="text-muted">No users found.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>#<?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo date('M j, Y, g:i a', strtotime($user['created_at'])); ?></td>
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
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Admin User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password (min 6 chars)</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#addUserForm').on('submit', function(e) {
            e.preventDefault();
            var btn = $(this).find('button[type="submit"]');
            var originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Adding...');
            $.post('users.php', $(this).serialize(), function(response) {
                btn.prop('disabled', false).html(originalText);
                if (response.success) {
                    $('#addUserModal').modal('hide');
                    $('#addUserForm')[0].reset();
                    location.reload();
                } else {
                    alert(response.message || 'Failed to add user.');
                }
            }, 'json').fail(function() {
                btn.prop('disabled', false).html(originalText);
                alert('Server error.');
            });
        });
    });
</script>