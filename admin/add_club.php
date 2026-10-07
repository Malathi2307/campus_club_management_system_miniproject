<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Add Club';
include __DIR__ . '/../includes/header.php';

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

    if ($name === '' || $description === '' || $category === '' || $faculty === '' || $email === '' || $location === '' || $schedule === '') {
        setFlash('danger', 'Please fill in all club fields.');
        redirect(BASE_URL . '/admin/add_club.php');
    }

    try {
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $imagePath = uploadFile($_FILES['image'], __DIR__ . '/../uploads/clubs');
        }
        $stmt = getPDO()->prepare('INSERT INTO clubs (name, description, category, faculty_coordinator, contact_email, meeting_location, meeting_schedule, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$name, $description, $category, $faculty, $email, $location, $schedule, $imagePath, $status]);
        setFlash('success', 'Club created successfully.');
        redirect(BASE_URL . '/admin/clubs.php');
    } catch (Exception $e) {
        setFlash('danger', 'Unable to create club.');
        redirect(BASE_URL . '/admin/add_club.php');
    }
}
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container py-4">
            <h2 class="mb-4">Add Club</h2>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Club Name</label><input type="text" name="name" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Category</label><input type="text" name="category" class="form-control" required></div>
                            <div class="col-md-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4" required></textarea></div>
                            <div class="col-md-6"><label class="form-label">Faculty Coordinator</label><input type="text" name="faculty_coordinator" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Contact Email</label><input type="email" name="contact_email" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Meeting Location</label><input type="text" name="meeting_location" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Meeting Schedule</label><input type="text" name="meeting_schedule" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Club Logo/Image</label><input type="file" name="image" class="form-control"></div>
                            <div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Club</button>
                            <a href="<?= BASE_URL ?>/admin/clubs.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
