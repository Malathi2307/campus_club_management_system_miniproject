<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$pageTitle = 'Event Details';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$user = currentUser();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT e.*, c.name AS club_name FROM events e JOIN clubs c ON c.id = e.club_id WHERE e.id = ? AND e.status = "open"');
$stmt->execute([$id]);
$event = $stmt->fetch();
if (!$event) {
    setFlash('danger', 'Event not found.');
    redirect(BASE_URL . '/student/events.php');
}
$registered = $pdo->prepare('SELECT id FROM event_registrations WHERE user_id = ? AND event_id = ?');
$registered->execute([$user['id'], $id]);
$participants = $pdo->prepare('SELECT COUNT(*) FROM event_registrations WHERE event_id = ?');
$participants->execute([$id]);
$participantCount = (int)$participants->fetchColumn();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/student_sidebar.php'; ?>
    <main class="main-content">
        <div class="container py-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2><?= e($event['title']) ?></h2>
                    <p class="text-muted"><?= e($event['club_name']) ?></p>
                    <p><?= e($event['description']) ?></p>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item"><strong>Date:</strong> <?= e($event['event_date']) ?></li>
                        <li class="list-group-item"><strong>Time:</strong> <?= e($event['event_time']) ?></li>
                        <li class="list-group-item"><strong>Venue:</strong> <?= e($event['venue']) ?></li>
                        <li class="list-group-item"><strong>Participants:</strong> <?= e($participantCount) ?> / <?= e($event['max_participants']) ?></li>
                    </ul>
                    <?php if ($registered->rowCount() === 0 && $participantCount < (int)$event['max_participants']): ?><a href="<?= BASE_URL ?>/student/register_event.php?id=<?= (int)$event['id'] ?>" class="btn btn-primary">Register</a><?php else: ?><span class="badge bg-secondary"><?= $registered->rowCount() > 0 ? 'Already Registered' : 'Registration Full' ?></span><?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
