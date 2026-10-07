<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$pageTitle = 'Student Dashboard';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$user = currentUser();
$clubsJoined = $pdo->query('SELECT COUNT(*) FROM memberships WHERE user_id = ' . (int)$user['id'])->fetchColumn();
$eventsRegistered = $pdo->query('SELECT COUNT(*) FROM event_registrations WHERE user_id = ' . (int)$user['id'])->fetchColumn();
$clubs = $pdo->query('SELECT c.* FROM memberships m JOIN clubs c ON c.id = m.club_id WHERE m.user_id = ' . (int)$user['id'] . ' ORDER BY m.joined_at DESC LIMIT 3')->fetchAll();
$events = $pdo->query('SELECT e.*, c.name AS club_name FROM event_registrations er JOIN events e ON e.id = er.event_id JOIN clubs c ON c.id = e.club_id WHERE er.user_id = ' . (int)$user['id'] . ' ORDER BY e.event_date ASC LIMIT 3')->fetchAll();
$announcements = $pdo->query('SELECT * FROM announcements WHERE status = "published" ORDER BY created_at DESC LIMIT 3')->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/student_sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Student Dashboard</h2>
            <div class="row g-4">
                <div class="col-md-4"><div class="card stat-card bg-primary text-white"><div class="card-body"><h6>Student Name</h6><h4><?= e($user['name']) ?></h4></div></div></div>
                <div class="col-md-4"><div class="card stat-card bg-success text-white"><div class="card-body"><h6>Student ID</h6><h4><?= e($user['student_id']) ?></h4></div></div></div>
                <div class="col-md-4"><div class="card stat-card bg-warning text-dark"><div class="card-body"><h6>Department / Year</h6><h4><?= e($user['department']) ?> / <?= e($user['year']) ?></h4></div></div></div>
            </div>
            <div class="row g-4 mt-1">
                <div class="col-md-6"><div class="card shadow-sm border-0"><div class="card-body"><h5>Clubs Joined</h5><h2><?= e($clubsJoined) ?></h2></div></div></div>
                <div class="col-md-6"><div class="card shadow-sm border-0"><div class="card-body"><h5>Events Registered</h5><h2><?= e($eventsRegistered) ?></h2></div></div></div>
            </div>
            <div class="row g-4 mt-2">
                <div class="col-lg-4"><div class="card shadow-sm border-0 h-100"><div class="card-header bg-white"><h5>My Clubs</h5></div><div class="card-body"><?php foreach ($clubs as $club): ?><p class="mb-2"><?= e($club['name']) ?></p><?php endforeach; ?></div></div></div>
                <div class="col-lg-4"><div class="card shadow-sm border-0 h-100"><div class="card-header bg-white"><h5>Upcoming Events</h5></div><div class="card-body"><?php foreach ($events as $event): ?><p class="mb-2"><?= e($event['title']) ?> (<?= e($event['event_date']) ?>)</p><?php endforeach; ?></div></div></div>
                <div class="col-lg-4"><div class="card shadow-sm border-0 h-100"><div class="card-header bg-white"><h5>Announcements</h5></div><div class="card-body"><?php foreach ($announcements as $a): ?><p class="mb-2"><?= e($a['title']) ?></p><?php endforeach; ?></div></div></div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
