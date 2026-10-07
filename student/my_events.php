<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$pageTitle = 'My Events';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$user = currentUser();
$events = $pdo->query('SELECT e.*, c.name AS club_name FROM event_registrations er JOIN events e ON e.id = er.event_id JOIN clubs c ON c.id = e.club_id WHERE er.user_id = ' . (int)$user['id'] . ' ORDER BY e.event_date ASC')->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/student_sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">My Events</h2>
            <div class="row g-4">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2"><?= e($event['club_name']) ?></span>
                                <h5><?= e($event['title']) ?></h5>
                                <p><?= e($event['event_date']) ?> at <?= e($event['event_time']) ?></p>
                                <p><?= e($event['venue']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
