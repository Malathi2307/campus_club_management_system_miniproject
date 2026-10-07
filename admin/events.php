<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Manage Events';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$search = $_GET['search'] ?? '';
$where = ['1=1'];
$params = [];
if ($search !== '') {
    $where[] = '(title LIKE ? OR venue LIKE ? OR description LIKE ?)';
    $term = '%' . $search . '%';
    $params = array_merge($params, [$term, $term, $term]);
}
$stmt = $pdo->prepare('SELECT e.*, c.name AS club_name FROM events e JOIN clubs c ON c.id = e.club_id WHERE ' . implode(' AND ', $where) . ' ORDER BY e.event_date ASC');
$stmt->execute($params);
$events = $stmt->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Events</h2>
                <a href="<?= BASE_URL ?>/admin/add_event.php" class="btn btn-primary">Add Event</a>
            </div>
            <form class="row g-2 mb-3" method="GET">
                <div class="col-md-10"><input type="text" class="form-control" name="search" value="<?= e($search) ?>" placeholder="Search by event name, venue, description"></div>
                <div class="col-md-2"><button class="btn btn-primary w-100" type="submit">Search</button></div>
            </form>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light"><tr><th>Title</th><th>Club</th><th>Date</th><th>Venue</th><th>Status</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td><?= e($event['title']) ?></td>
                                        <td><?= e($event['club_name']) ?></td>
                                        <td><?= e($event['event_date']) ?></td>
                                        <td><?= e($event['venue']) ?></td>
                                        <td><span class="badge <?= $event['status'] === 'open' ? 'bg-success' : 'bg-secondary' ?>"><?= e(ucfirst($event['status'])) ?></span></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/admin/edit_event.php?id=<?= (int)$event['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="<?= BASE_URL ?>/admin/delete_event.php?id=<?= (int)$event['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this event?')">Delete</a>
                                        </td>
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
