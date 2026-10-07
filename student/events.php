<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$pageTitle = 'Events';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$search = $_GET['search'] ?? '';
$where = ['e.status = "open"'];
$params = [];
if ($search !== '') {
    $where[] = '(e.title LIKE ? OR c.name LIKE ? OR e.venue LIKE ?)';
    $term = '%' . $search . '%';
    $params = array_merge($params, [$term, $term, $term]);
}
$stmt = $pdo->prepare('SELECT e.*, c.name AS club_name FROM events e JOIN clubs c ON c.id = e.club_id WHERE ' . implode(' AND ', $where) . ' ORDER BY e.event_date ASC');
$stmt->execute($params);
$events = $stmt->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/student_sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Upcoming Events</h2>
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-10"><input type="text" name="search" class="form-control" value="<?= e($search) ?>" placeholder="Search event name, club, venue"></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Search</button></div>
            </form>
            <div class="row g-4">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2"><?= e($event['club_name']) ?></span>
                                <h5><?= e($event['title']) ?></h5>
                                <p><?= e($event['event_date']) ?> at <?= e($event['event_time']) ?></p>
                                <p><?= e($event['venue']) ?></p>
                                <a href="<?= BASE_URL ?>/student/event_details.php?id=<?= (int)$event['id'] ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
