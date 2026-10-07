<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Admin Dashboard';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$stats = [
    'students' => $pdo->query('SELECT COUNT(*) FROM users WHERE role = "student"')->fetchColumn(),
    'clubs' => $pdo->query('SELECT COUNT(*) FROM clubs')->fetchColumn(),
    'events' => $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn(),
    'memberships' => $pdo->query('SELECT COUNT(*) FROM memberships')->fetchColumn(),
    'registrations' => $pdo->query('SELECT COUNT(*) FROM event_registrations')->fetchColumn(),
];
$popularClubs = $pdo->query('SELECT c.name, COUNT(m.id) AS members FROM clubs c LEFT JOIN memberships m ON m.club_id = c.id GROUP BY c.id ORDER BY members DESC LIMIT 5')->fetchAll();
$departmentStats = $pdo->query('SELECT department, COUNT(*) AS total FROM users WHERE role = "student" GROUP BY department ORDER BY total DESC')->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Admin Dashboard</h2>
            <div class="row g-4">
                <?php foreach ([['Total Students', $stats['students'], 'primary'], ['Total Clubs', $stats['clubs'], 'success'], ['Total Events', $stats['events'], 'warning'], ['Total Memberships', $stats['memberships'], 'info'], ['Total Event Registrations', $stats['registrations'], 'danger']] as $item): ?>
                    <div class="col-md-6 col-xl-2">
                        <div class="card stat-card border-0 shadow-sm h-100 bg-<?= $item[2] ?> text-white">
                            <div class="card-body">
                                <div class="text-uppercase small opacity-75"><?= e($item[0]) ?></div>
                                <div class="display-6 fw-bold mt-2"><?= e($item[1]) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="row mt-4 g-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">Popular Clubs</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr><th>Club</th><th>Members</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($popularClubs as $row): ?>
                                        <tr><td><?= e($row['name']) ?></td><td><?= e($row['members']) ?></td></tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-0">Students by Department</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover align-middle mb-0">
                                <thead><tr><th>Department</th><th>Students</th></tr></thead>
                                <tbody>
                                    <?php foreach ($departmentStats as $row): ?>
                                        <tr><td><?= e($row['department']) ?></td><td><?= e($row['total']) ?></td></tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
