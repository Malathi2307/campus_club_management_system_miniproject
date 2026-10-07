<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Reports';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$stats = [
    'students' => $pdo->query('SELECT COUNT(*) FROM users WHERE role = "student"')->fetchColumn(),
    'clubs' => $pdo->query('SELECT COUNT(*) FROM clubs')->fetchColumn(),
    'memberships' => $pdo->query('SELECT COUNT(*) FROM memberships')->fetchColumn(),
    'events' => $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn(),
    'registrations' => $pdo->query('SELECT COUNT(*) FROM event_registrations')->fetchColumn(),
];
$popularClubs = $pdo->query('SELECT c.name, COUNT(m.user_id) AS total FROM clubs c LEFT JOIN memberships m ON m.club_id = c.id GROUP BY c.id ORDER BY total DESC LIMIT 5')->fetchAll();
$popularEvents = $pdo->query('SELECT e.title, COUNT(er.user_id) AS total FROM events e LEFT JOIN event_registrations er ON er.event_id = e.id GROUP BY e.id ORDER BY total DESC LIMIT 5')->fetchAll();
$byDepartment = $pdo->query('SELECT department, COUNT(*) AS total FROM users WHERE role = "student" GROUP BY department ORDER BY total DESC')->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Reports</h2>
            <div class="row g-4">
                <div class="col-md-6 col-xl-3"><div class="card card-body text-center shadow-sm border-0"><h6>Total Students</h6><h3><?= e($stats['students']) ?></h3></div></div>
                <div class="col-md-6 col-xl-3"><div class="card card-body text-center shadow-sm border-0"><h6>Total Clubs</h6><h3><?= e($stats['clubs']) ?></h3></div></div>
                <div class="col-md-6 col-xl-3"><div class="card card-body text-center shadow-sm border-0"><h6>Total Memberships</h6><h3><?= e($stats['memberships']) ?></h3></div></div>
                <div class="col-md-6 col-xl-3"><div class="card card-body text-center shadow-sm border-0"><h6>Total Events</h6><h3><?= e($stats['events']) ?></h3></div></div>
                <div class="col-md-12"><div class="card card-body text-center shadow-sm border-0"><h6>Total Event Registrations</h6><h3><?= e($stats['registrations']) ?></h3></div></div>
            </div>
            <div class="row mt-4 g-4">
                <div class="col-lg-6"><div class="card shadow-sm border-0"><div class="card-header bg-white"><h5>Most Popular Clubs</h5></div><div class="card-body"><table class="table table-hover mb-0"><thead><tr><th>Club</th><th>Count</th></tr></thead><tbody><?php foreach ($popularClubs as $row): ?><tr><td><?= e($row['name']) ?></td><td><?= e($row['total']) ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
                <div class="col-lg-6"><div class="card shadow-sm border-0"><div class="card-header bg-white"><h5>Most Popular Events</h5></div><div class="card-body"><table class="table table-hover mb-0"><thead><tr><th>Event</th><th>Count</th></tr></thead><tbody><?php foreach ($popularEvents as $row): ?><tr><td><?= e($row['title']) ?></td><td><?= e($row['total']) ?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
            </div>
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white"><h5>Students by Department</h5></div>
                <div class="card-body"><table class="table table-hover mb-0"><thead><tr><th>Department</th><th>Students</th></tr></thead><tbody><?php foreach ($byDepartment as $row): ?><tr><td><?= e($row['department']) ?></td><td><?= e($row['total']) ?></td></tr><?php endforeach; ?></tbody></table></div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
