<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$pageTitle = 'My Clubs';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$user = currentUser();
$clubs = $pdo->query('SELECT c.*, m.status AS membership_status FROM memberships m JOIN clubs c ON c.id = m.club_id WHERE m.user_id = ' . (int)$user['id'] . ' ORDER BY m.joined_at DESC')->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/student_sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">My Clubs</h2>
            <div class="row g-4">
                <?php foreach ($clubs as $club): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h5><?= e($club['name']) ?></h5>
                                <p class="text-muted"><?= e($club['category']) ?></p>
                                <span class="badge bg-success"><?= e($club['membership_status']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
