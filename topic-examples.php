<?php

requireLogin();
require_once '../config/database.php';

$pdo = getDbConnection();
$topicId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
?>
<?php include '../includes/header.php'; ?>

<main class="py-4">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav>
                    <ol class="breadcrumb bg-transparent p-0 mb-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Topic Examples</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-3">Learning Topic Examples</h1>
                <p class="text-muted">Study each topic to understand core PHP and web development concepts.</p>
            </div>
        </div>

        <!-- Topic Navigation -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row row-cols-2 row-md-4 row-lg-8 g-2">
                            <?php 
                            $topics = [
                                1 => '== vs === Comparison',
                                2 => 'Multidimensional Array',
                                3 => 'include() vs require()',
                                4 => 'Remove Duplicates',
                                5 => 'Insert User PDO',
                                6 => 'Fetch Users LIKE A%',
                                7 => 'Public/Private/Protected',
                                8 => 'Inheritance & Overriding',
                                9 => 'var/let/const JS',
                                10 => 'Email Regex Validation',
                                11 => 'Change .title Text JS',
                                12 => 'jQuery Form Submit',
                                13 => 'AJAX POST JSON',
                                14 => 'Merge Arrays',
                                15 => 'Set/Get Cookie',
                                16 => 'Replace Word String'
                            ];
                            foreach ($topics as $id => $title):
                                $active = $topicId === $id ? 'bg-primary text-white' : 'text-muted';
                            ?>
                            <a href="topic-examples.php?id=<?php echo $id; ?>" class="col btn btn-<?php echo $active; ?> w-100 text-start small">
                                Topic <?php echo $id; ?>: <?php echo htmlspecialchars($title); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Topic Content -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm topic-demo">
                    <div class="card-body">
                        <?php
                        // Topic content will be loaded below based on $topicId
                        include 'topic-content.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    // Topic 9: Difference between var, let, and const in JavaScript
    // Demonstrated directly in this page script
    
    // var: function-scoped, hoisted, can be redeclared
    function demoVar() {
        if (true) {
            var message = "I am var - function scoped";
        }
        console.log(message); // Accessible here due to hoisting/function scope
    }
    
    // let: block-scoped, cannot be redeclared in same scope
    function demoLet() {
        if (true) {
            let count = 10;
            console.log(count); // 10
        }
        // console.log(count); // ReferenceError: count is not defined
    }
    
    // const: block-scoped, cannot be reassigned
    function demoConst() {
        const PI = 3.14159;
        // PI = 3.14; // TypeError: Assignment to constant variable
    }
    
    // Run demos
    demoVar();
    demoLet();
    demoConst();
</script>