<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Add Event';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$clubs = $pdo->query('SELECT * FROM clubs WHERE status = "active" ORDER BY name')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $clubId = (int)($_POST['club_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $eventDate = $_POST['event_date'] ?? '';
    $eventTime = $_POST['event_time'] ?? '';
    $venue = trim($_POST['venue'] ?? '');
    $maxParticipants = (int)($_POST['max_participants'] ?? 0);
    $deadline = $_POST['registration_deadline'] ?? '';
    $status = $_POST['status'] ?? 'open';
    if ($clubId <= 0 || $title === '' || $description === '' || $eventDate === '' || $eventTime === '' || $venue === '' || $maxParticipants <= 0 || $deadline === '') {
        setFlash('danger', 'Please fill in all event fields.');
        redirect(BASE_URL . '/admin/add_event.php');
    }
    try {
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $imagePath = uploadFile($_FILES['image'], __DIR__ . '/../uploads/events');
        }
        $stmt = $pdo->prepare('INSERT INTO events (club_id, title, description, event_date, event_time, venue, max_participants, registration_deadline, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$clubId, $title, $description, $eventDate, $eventTime, $venue, $maxParticipants, $deadline, $imagePath, $status]);
        setFlash('success', 'Event created successfully.');
        redirect(BASE_URL . '/admin/events.php');
    } catch (Exception $e) {
        setFlash('danger', 'Unable to create event.');
        redirect(BASE_URL . '/admin/add_event.php');
    }
}
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container py-4">
            <h2 class="mb-4">Add Event</h2>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Club</label><select class="form-select" name="club_id" required>
                                <option value="">Select Club</option>
                                <?php foreach ($clubs as $club): ?><option value="<?= (int)$club['id'] ?>"><?= e($club['name']) ?></option><?php endforeach; ?>
                            </select></div>
                            <div class="col-md-6"><label class="form-label">Event Title</label><input type="text" name="title" class="form-control" required></div>
                            <div class="col-md-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4" required></textarea></div>
                            <div class="col-md-4"><label class="form-label">Event Date</label><input type="date" name="event_date" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label">Event Time</label><input type="time" name="event_time" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label">Venue</label><input type="text" name="venue" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label">Max Participants</label><input type="number" name="max_participants" class="form-control" min="1" required></div>
                            <div class="col-md-4"><label class="form-label">Registration Deadline</label><input type="date" name="registration_deadline" class="form-control" required></div>
                            <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="open">Open</option><option value="closed">Closed</option></select></div>
                            <div class="col-md-12"><label class="form-label">Event Image</label><input type="file" name="image" class="form-control"></div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Event</button>
                            <a href="<?= BASE_URL ?>/admin/events.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
