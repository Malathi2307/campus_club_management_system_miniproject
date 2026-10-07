<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Student Details';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$id = (int)($_GET['id'] ?? 0);
$user = $pdo->prepare('SELECT * FROM users WHERE id = ? AND role = "student"');
$user->execute([$id]);
$student = $user->fetch();
if (!$student) {
    setFlash('danger', 'Student not found.');
    redirect(BASE_URL . '/admin/students.php');
}
$clubs = $pdo->prepare('SELECT c.*, m.status AS membership_status FROM memberships m JOIN clubs c ON c.id = m.club_id WHERE m.user_id = ?');
$clubs->execute([$id]);
$events = $pdo->prepare('SELECT e.*, er.status AS reg_status FROM event_registrations er JOIN events e ON e.id = er.event_id WHERE er.user_id = ?');
$events->execute([$id]);
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Student Details</h2>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6"><strong>Name:</strong> <?= e($student['name']) ?><br><strong>Student ID:</strong> <?= e($student['student_id']) ?><br><strong>Email:</strong> <?= e($student['email']) ?><br><strong>Phone:</strong> <?= e($student['phone']) ?></div>
                        <div class="col-md-6"><strong>Department:</strong> <?= e($student['department']) ?><br><strong>Year:</strong> <?= e($student['year']) ?><br><strong>Status:</strong> <?= e(ucfirst($student['status'])) ?><br><strong>Created:</strong> <?= e($student['created_at']) ?></div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0"><h5 class="mb-0">Joined Clubs</h5></div>
                        <div class="card-body">
                            <?php if ($clubs->rowCount() > 0): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($clubs->fetchAll() as $club): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><?= e($club['name']) ?></span>
                                            <span class="badge bg-info"><?= e($club['membership_status']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted mb-0">No club memberships found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0"><h5 class="mb-0">Event Registrations</h5></div>
                        <div class="card-body">
                            <?php if ($events->rowCount() > 0): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($events->fetchAll() as $event): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><?= e($event['title']) ?></span>
                                            <span class="badge bg-success"><?= e($event['reg_status']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted mb-0">No event registrations found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
