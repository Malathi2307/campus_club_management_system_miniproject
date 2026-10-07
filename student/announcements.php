<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$pageTitle = 'Announcements';
include __DIR__ . '/../includes/header.php';
$announcements = getPDO()->query('SELECT * FROM announcements WHERE status = "published" ORDER BY created_at DESC')->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/student_sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Announcements</h2>
            <div class="row g-4">
                <?php foreach ($announcements as $item): ?>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h5><?= e($item['title']) ?></h5>
                                <p><?= e($item['message']) ?></p>
                                <small class="text-muted">Posted by <?= e($item['posted_by']) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
