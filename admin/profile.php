<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Admin Profile';
include __DIR__ . '/../includes/header.php';
$user = currentUser();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container py-4">
            <h2 class="mb-4">Admin Profile</h2>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <p><strong>Name:</strong> <?= e($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= e($user['email']) ?></p>
                    <p><strong>Role:</strong> <?= e($user['role']) ?></p>
                    <p><strong>Status:</strong> <?= e($user['status']) ?></p>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
