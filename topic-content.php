<?php
$topicId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

if ($topicId === 1):
?>
<h4><i class="fas fa-code me-2 text-primary"></i>Topic 1: Difference between == and === in PHP</h4>
<p><strong>Explanation:</strong> <code>==</code> is loose equality (compares values after type coercion). <code>===</code> is strict equality (compares both value and type).</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>$a == $b  // loose comparison<br>$a === $b // strict comparison</code></div>
<p><strong>Practical Example:</strong> Comparing student IDs from form input (string) with database values (integer).</p>
<pre><code>&lt;?php
$studentId = 5;
$formId = "5";

var_dump($studentId == $formId);   // bool(true) - loose, type juggling
var_dump($studentId === $formId);  // bool(false) - strict, different types
$anotherId = 5;
var_dump($studentId === $anotherId); // bool(true)
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>bool(true)<br>bool(false)<br>bool(true)</div>
<p><em>File used: add-student.php, edit-student.php (input validation)</em></p>
<?php endif; ?>

<?php if ($topicId === 2): ?>
<h4><i class="fas fa-layer-group me-2 text-primary"></i>Topic 2: Multidimensional Array</h4>
<p><strong>Explanation:</strong> An array containing one or more arrays. Stores related student data in structured format.</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>$students = [['name' => 'John', 'course' => 'CS'], ...];</code></div>
<pre><code>&lt;?php
$students = [
    ["name" => "Alice Johnson", "email" => "alice@example.com", "course" => "Computer Science"],
    ["name" => "Bob Wilson", "email" => "bob@example.com", "course" => "Information Technology"],
    ["name" => "Charlie Brown", "email" => "charlie@example.com", "course" => "Business Administration"]
];

foreach ($students as $student) {
    echo $student["name"] . " - " . $student["course"] . "\n";
}
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>Alice Johnson - Computer Science<br>Bob Wilson - Information Technology<br>Charlie Brown - Business Administration</div>
<p><em>File used: students.php, dashboard.php</em></p>
<?php endif; ?>

<?php if ($topicId === 3): ?>
<h4><i class="fas fa-file-import me-2 text-primary"></i>Topic 3: Difference between include() and require()</h4>
<p><strong>Explanation:</strong> <code>include()</code> warns if missing but continues. <code>require()</code> produces fatal error and stops.</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>include("config.php");   // Warning + Continue<br>require("config.php");  // Fatal Error + Stop</code></div>
<p><strong>Practical Example:</strong> config/database.php uses require() (critical). Header/footer use include().</p>
<pre><code>&lt;?php
require_once 'config/database.php';
include 'includes/header.php';
include 'includes/footer.php';
?&gt;</pre>
<div class="alert alert-info"><strong>Key Difference:</strong><br>include() = Warning + Continue<br>require() = Fatal Error + Stop</div>
<p><em>File used: All pages</em></p>
<?php endif; ?>

<?php if ($topicId === 4): ?>
<h4><i class="fas fa-filter me-2 text-primary"></i>Topic 4: Remove Duplicates from Array</h4>
<p><strong>Explanation:</strong> Use <code>array_unique()</code> to remove duplicate values from: [1, 2, 2, 3, 4, 4, 5]</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>array_unique($array)</code></div>
<pre><code>&lt;?php
$numbers = [1, 2, 2, 3, 4, 4, 5];
$uniqueNumbers = array_unique($numbers);
$uniqueNumbers = array_values($uniqueNumbers);
print_r($uniqueNumbers);
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>Array ( [0]=>1 [1]=>2 [2]=>3 [3]=>4 [4]=>5 )</div>
<p><em>File used: topic-examples.php (educational demo)</em></p>
<?php endif; ?>
<?php if ($topicId === 5): ?>
<h4><i class="fas fa-user-plus me-2 text-info"></i>Topic 5: Insert User into users Table Using PDO</h4>
<p><strong>Explanation:</strong> PDO prepared statements securely insert a new user. Password hashed with <code>password_hash()</code>.</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");</code></div>
<pre><code>&lt;?php
require_once 'config/database.php';
$pdo = getDbConnection();
$hashedPassword = password_hash("securePass123", PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
$stmt->execute(['name' => 'John Admin', 'email' => 'john@example.com', 'password' => $hashedPassword]);
echo "User inserted. ID: " . $pdo->lastInsertId();
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>User inserted. ID: 2</div>
<p><em>File used: users.php</em></p>
<?php endif; ?>

<?php if ($topicId === 6): ?>
<h4><i class="fas fa-search me-2 text-info"></i>Topic 6: Fetch Users Starting with 'A' Ordered by ID DESC</h4>
<p><strong>Explanation:</strong> PDO prepared statement with LIKE clause fetches users whose names start with "A", ordered by ID descending.</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>$stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE :name ORDER BY id DESC");</code></div>
<pre><code>&lt;?php
$stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE :name ORDER BY id DESC");
$stmt->execute(['name' => 'A%']);
$users = $stmt->fetchAll();
foreach ($users as $user) {
    echo $user['name'] . " - " . $user['email'] . "\n";
}
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>Alice Johnson - alice@example.com<br>Andrew Smith - andrew@example.com<br>Amanda Lee - amanda@example.com<br>Arthur Morgan - arthur@example.com</div>
<p><em>File used: users.php</em></p>
<?php endif; ?>

<?php if ($topicId === 7): ?>
<h4><i class="fas fa-shield-halved me-2 text-success"></i>Topic 7: Public, Private, and Protected Properties</h4>
<p><strong>Explanation:</strong> Visibility: public (everywhere), private (class only), protected (class + subclasses).</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>class Person { public $name; private $email; protected $phone; }</code></div>
<pre><code>&lt;?php
class Person {
    public $name;
    private $email;
    protected $phone;
    public function __construct($n, $e, $p) { $this->name=$n; $this->email=$e; $this->phone=$p; }
    public function getEmail() { return $this->email; }
}
$person = new Person("Alice", "alice@example.com", "555-0100");
echo $person->name;        // Works: public
echo $person->getEmail();  // Works: public method accessing private
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>Alice<br>alice@example.com</div>
<p><em>File used: classes/Person.php, classes/Student.php</em></p>
<?php endif; ?>

<?php if ($topicId === 8): ?>
<h4><i class="fas fa-sitemap me-2 text-success"></i>Topic 8: Inheritance and Method Overriding</h4>
<p><strong>Explanation:</strong> Child class extends parent. Method overriding redefines parent method in child.</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>class Student extends Person { public function getDetails() { ... } }</code></div>
<pre><code>&lt;?php
class Person {
    public $name;
    public function __construct($name) { $this->name = $name; }
    public function getDetails() { return "Person: " . $this->name; }
}
class Student extends Person {
    public $course;
    public function __construct($name, $course) {
        parent::__construct($name);
        $this->course = $course;
    }
    public function getDetails() { return "Student: " . $this->name . ", Course: " . $this->course; }
}
$student = new Student("Alice", "Computer Science");
echo $student->getDetails();
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>Student: Alice, Course: Computer Science</div>
<p><em>File used: classes/Student.php</em></p>
<?php endif; ?>
<?php if ($topicId === 9): ?>
<h4><i class="fas fa-file-code me-2 text-warning"></i>Topic 9: Difference between var, let, and const in JavaScript</h4>
<p><strong>Explanation:</strong> Three ways to declare variables with different scoping rules.</p>
<table class="table table-sm">
    <thead><tr><th>Keyword</th><th>Scope</th><th>Redeclare</th><th>Reassign</th><th>Hoisting</th></tr></thead>
    <tbody>
        <tr><td><code>var</code></td><td>Function</td><td>Yes</td><td>Yes</td><td>Yes</td></tr>
        <tr><td><code>let</code></td><td>Block</td><td>No</td><td>Yes</td><td>No</td></tr>
        <tr><td><code>const</code></td><td>Block</td><td>No</td><td>No</td><td>No</td></tr>
    </tbody>
</table>
<pre><code>var message = "Hello";
var message = "Hello Again"; // Allowed
let count = 10;
count = 20; // Allowed
const PI = 3.14159;
// PI = 3.14; // Error
</code></pre>
<p><em>File used: topic-examples.js, form validation</em></p>
<?php endif; ?>

<?php if ($topicId === 10): ?>
<h4><i class="fas fa-envelope me-2 text-warning"></i>Topic 10: Validate Email Using JavaScript Regex</h4>
<p><strong>Explanation:</strong> Regex validates email client-side. PHP <code>filter_var()</code> validates server-side.</p>
<div class="bg-light p-3 rounded mb-3"><strong>Regex:</strong><br><code>/^[^\s@]+@[^\s@]+\.[^\s@]+$/</code></div>
<pre><code>&lt;script>
function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}
console.log(validateEmail("student@example.com"));  // true
console.log(validateEmail("invalid"));               // false
&lt;/script&gt;
&lt;?php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format.";
}
?&gt;</pre>
<p><em>File used: login.php, add-student.php, edit-student.php, users.php</em></p>
<?php endif; ?>

<?php if ($topicId === 11): ?>
<h4><i class="fas fa-edit me-2 text-warning"></i>Topic 11: Change Text of Element with Class .title</h4>
<p><strong>Explanation:</strong> Use <code>document.querySelector('.title')</code> and <code>textContent</code> to change text dynamically.</p>
<pre><code>&lt;h1 class="title">Original Title&lt;/h1>
&lt;script>
    document.querySelector('.title').textContent = "Updated via JavaScript";
&lt;/script&gt;</code></div>
<p><strong>Practical Example:</strong> Dashboard heading updates after page load.</p>
<pre><code>&lt;script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const title = document.getElementById('dashboardTitle');
            if (title) {
                title.innerHTML = '&lt;i class="fas fa-tachometer-alt me-2 text-success">&lt;/i>Dashboard Updated!';
            }
        }, 2000);
    });
&lt;/script&gt;</pre>
<p><em>File used: dashboard.php</em></p>
<?php endif; ?>

<?php if ($topicId === 12): ?>
<h4><i class="fas fa-paper-plane me-2 text-danger"></i>Topic 12: Submit Form Using jQuery on Button Click</h4>
<p><strong>Explanation:</strong> jQuery handles button click and submits form without page reload.</p>
<pre><code>&lt;script>
    $(document).ready(function() {
        $('#submitBtn').on('click', function(e) {
            e.preventDefault();
            $('#studentForm').submit();
        });
    });
&lt;/script&gt;</code></div>
<p><strong>Practical Example:</strong> add-student.php "Enrol Student" button triggers AJAX submission.</p>
<p><em>File used: add-student.php, edit-student.php</em></p>
<?php endif; ?>
<?php if ($topicId === 13): ?>
<h4><i class="fas fa-cloud-upload-alt me-2 text-danger"></i>Topic 13: AJAX POST Request with jQuery and JSON Response</h4>
<p><strong>Explanation:</strong> jQuery <code>$.post()</code> sends data to PHP backend and receives JSON response without page reload.</p>
<pre><code>&lt;script>
    $.post('add-student.php', $('#studentForm').serialize(), function(response) {
        if (response.success) {
            alert(response.message);
        } else {
            alert(response.message);
        }
    }, 'json');
&lt;/script&gt;</code></div>
<p><strong>PHP Backend:</strong> Receives POST, validates, inserts, returns JSON with success/errors.</p>
<p><em>File used: add-student.php, edit-student.php, users.php</em></p>
<?php endif; ?>

<?php if ($topicId === 14): ?>
<h4><i class="fas fa-object-group me-2 text-warning"></i>Topic 14: Merge Two Arrays</h4>
<p><strong>Explanation:</strong> Use <code>array_merge()</code> to combine two arrays into one.</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>array_merge($array1, $array2)</code></div>
<pre><code>&lt;?php
$courseList1 = ["Computer Science", "Information Technology"];
$courseList2 = ["Business Administration", "Data Science"];
$allCourses = array_merge($courseList1, $courseList2);
print_r($allCourses);
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>Array ( [0]=>Computer Science [1]=>Information Technology [2]=>Business Administration [3]=>Data Science )</div>
<p><em>File used: topic-examples.php (educational demo)</em></p>
<?php endif; ?>

<?php if ($topicId === 15): ?>
<h4><i class="fas fa-cookie-bite me-2 text-primary"></i>Topic 15: Set and Get a Cookie in PHP</h4>
<p><strong>Explanation:</strong> <code>setcookie()</code> stores data on browser. <code>$_COOKIE</code> retrieves it. Set before any output. Never store passwords.</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>setcookie("name", "value", time()+86400, "/", "", true, true);<br>echo $_COOKIE["name"];</code></div>
<pre><code>&lt;?php
setcookie("student_theme", "dark", time() + 86400, "/", "", true, true);
if (isset($_COOKIE["student_theme"])) {
    echo "Theme: " . htmlspecialchars($_COOKIE["student_theme"]);
}
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>Theme: dark</div>
<p><em>File used: topic-examples.php (demo), auth.php (session cookie)</em></p>
<?php endif; ?>

<?php if ($topicId === 16): ?>
<h4><i class="fas fa-pen me-2 text-primary"></i>Topic 16: Replace a Word in a String</h4>
<p><strong>Explanation:</strong> Use <code>str_replace()</code> to replace all occurrences of a search string.</p>
<div class="bg-light p-3 rounded mb-3"><strong>Syntax:</strong><br><code>str_replace($search, $replace, $subject)</code></div>
<pre><code>&lt;?php
$message = "You are enrolled in Web Development course.";
$newMessage = str_replace("Web Development", "Computer Science", $message);
echo $newMessage;
?&gt;</pre>
<div class="alert alert-info"><strong>Expected Output:</strong><br>You are enrolled in Computer Science course.</div>
<p><em>File used: topic-examples.php (demo), status messages throughout</em></p>
<?php endif; ?>