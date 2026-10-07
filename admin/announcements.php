<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Announcements';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $status = $_POST['status'] ?? 'published';
    if ($title === '' || $message === '') {
        setFlash('danger', 'Title and message are required.');
        redirect(BASE_URL . '/admin/announcements.php');
    }
    $stmt = $pdo->prepare('INSERT INTO announcements (title, message, posted_by, status, created_at) VALUES (?, ?, ?, ?, NOW())');
    $stmt->execute([$title, $message, currentUser()['name'], $status]);
    setFlash('success', 'Announcement created successfully.');
    redirect(BASE_URL . '/admin/announcements.php');
}
$announcements = $pdo->query('SELECT * FROM announcements ORDER BY created_at DESC')->fetchAll();
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Announcements</h2>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Title</label><input type="text" name="title" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="published">Published</option><option value="draft">Draft</option></select></div>
                            <div class="col-md-12"><label class="form-label">Message</label><textarea name="message" rows="4" class="form-control" required></textarea></div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Create Announcement</button>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light"><tr><th>Title</th><th>Posted By</th><th>Status</th><th>Created At</th></tr></thead>
                            <tbody>
                                <?php foreach ($announcements as $item): ?>
                                    <tr>
                                        <td><?= e($item['title']) ?></td>
                                        <td><?= e($item['posted_by']) ?></td>
                                        <td><span class="badge <?= $item['status'] === 'published' ? 'bg-success' : 'bg-secondary' ?>"><?= e($item['status']) ?></span></td>
                                        <td><?= e($item['created_at']) ?></td>
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
