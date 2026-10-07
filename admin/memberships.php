<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Memberships';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$memberships = $pdo->query('SELECT m.*, u.name AS student_name, u.student_id, c.name AS club_name FROM memberships m JOIN users u ON u.id = m.user_id JOIN clubs c ON c.id = m.club_id ORDER BY m.joined_at DESC')->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Memberships</h2>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light"><tr><th>Student</th><th>Student ID</th><th>Club</th><th>Status</th><th>Joined On</th></tr></thead>
                            <tbody>
                                <?php foreach ($memberships as $row): ?>
                                    <tr>
                                        <td><?= e($row['student_name']) ?></td>
                                        <td><?= e($row['student_id']) ?></td>
                                        <td><?= e($row['club_name']) ?></td>
                                        <td><span class="badge bg-info"><?= e($row['status']) ?></span></td>
                                        <td><?= e($row['joined_at']) ?></td>
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
