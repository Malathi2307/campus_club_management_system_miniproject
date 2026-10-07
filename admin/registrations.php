<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Event Registrations';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$registrations = $pdo->query('SELECT er.*, u.name AS student_name, u.student_id, e.title AS event_title FROM event_registrations er JOIN users u ON u.id = er.user_id JOIN events e ON e.id = er.event_id ORDER BY er.registered_at DESC')->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Event Registrations</h2>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light"><tr><th>Student</th><th>Student ID</th><th>Event</th><th>Status</th><th>Registered On</th></tr></thead>
                            <tbody>
                                <?php foreach ($registrations as $row): ?>
                                    <tr>
                                        <td><?= e($row['student_name']) ?></td>
                                        <td><?= e($row['student_id']) ?></td>
                                        <td><?= e($row['event_title']) ?></td>
                                        <td><span class="badge bg-primary"><?= e($row['status']) ?></span></td>
                                        <td><?= e($row['registered_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
