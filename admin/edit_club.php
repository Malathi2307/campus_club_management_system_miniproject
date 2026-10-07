<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Edit Club';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM clubs WHERE id = ?');
$stmt->execute([$id]);
$club = $stmt->fetch();
if (!$club) {
    setFlash('danger', 'Club not found.');
    redirect(BASE_URL . '/admin/clubs.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $faculty = trim($_POST['faculty_coordinator'] ?? '');
    $email = trim($_POST['contact_email'] ?? '');
    $location = trim($_POST['meeting_location'] ?? '');
    $schedule = trim($_POST['meeting_schedule'] ?? '');
    $status = $_POST['status'] ?? 'active';
    $imagePath = $club['image'];
    if (!empty($_FILES['image']['name'])) {
        $imagePath = uploadFile($_FILES['image'], __DIR__ . '/../uploads/clubs');
    }
    $stmt = $pdo->prepare('UPDATE clubs SET name = ?, description = ?, category = ?, faculty_coordinator = ?, contact_email = ?, meeting_location = ?, meeting_schedule = ?, image = ?, status = ? WHERE id = ?');
    $stmt->execute([$name, $description, $category, $faculty, $email, $location, $schedule, $imagePath, $status, $id]);
    setFlash('success', 'Club updated successfully.');
    redirect(BASE_URL . '/admin/clubs.php');
}
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container py-4">
            <h2 class="mb-4">Edit Club</h2>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Club Name</label><input type="text" name="name" class="form-control" value="<?= e($club['name']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Category</label><input type="text" name="category" class="form-control" value="<?= e($club['category']) ?>" required></div>
                            <div class="col-md-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4" required><?= e($club['description']) ?></textarea></div>
                            <div class="col-md-6"><label class="form-label">Faculty Coordinator</label><input type="text" name="faculty_coordinator" class="form-control" value="<?= e($club['faculty_coordinator']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Contact Email</label><input type="email" name="contact_email" class="form-control" value="<?= e($club['contact_email']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Meeting Location</label><input type="text" name="meeting_location" class="form-control" value="<?= e($club['meeting_location']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Meeting Schedule</label><input type="text" name="meeting_schedule" class="form-control" value="<?= e($club['meeting_schedule']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Club Logo/Image</label><input type="file" name="image" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active" <?= $club['status'] === 'active' ? 'selected' : '' ?>>Active</option><option value="inactive" <?= $club['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option></select></div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Club</button>
                            <a href="<?= BASE_URL ?>/admin/clubs.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
