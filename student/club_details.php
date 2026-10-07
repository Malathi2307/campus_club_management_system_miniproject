<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$pageTitle = 'Club Details';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$user = currentUser();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM clubs WHERE id = ? AND status = "active"');
$stmt->execute([$id]);
$club = $stmt->fetch();
if (!$club) {
    setFlash('danger', 'Club not found.');
    redirect(BASE_URL . '/student/clubs.php');
}
$joined = $pdo->prepare('SELECT id FROM memberships WHERE user_id = ? AND club_id = ?');
$joined->execute([$user['id'], $id]);
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/student_sidebar.php'; ?>
    <main class="main-content">
        <div class="container py-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2><?= e($club['name']) ?></h2>
                    <p class="text-muted mb-3"><?= e($club['category']) ?></p>
                    <p><?= e($club['description']) ?></p>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item"><strong>Faculty Coordinator:</strong> <?= e($club['faculty_coordinator']) ?></li>
                        <li class="list-group-item"><strong>Contact Email:</strong> <?= e($club['contact_email']) ?></li>
                        <li class="list-group-item"><strong>Meeting Location:</strong> <?= e($club['meeting_location']) ?></li>
                        <li class="list-group-item"><strong>Meeting Schedule:</strong> <?= e($club['meeting_schedule']) ?></li>
                    </ul>
                    <?php if ($joined->rowCount() === 0): ?><a href="<?= BASE_URL ?>/student/join_club.php?id=<?= (int)$club['id'] ?>" class="btn btn-primary">Join Club</a><?php else: ?><span class="badge bg-success">Already Joined</span><?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
