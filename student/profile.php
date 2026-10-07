<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$pageTitle = 'Student Profile';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$user = currentUser();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, phone = ?, department = ?, year = ?' . ($newPassword !== '' ? ', password = ?' : '') . ' WHERE id = ?');
    $params = [$name, $email, $phone, $department, $year];
    if ($newPassword !== '') { $params[] = password_hash($newPassword, PASSWORD_DEFAULT); }
    $params[] = $user['id'];
    $stmt->execute($params);
    setFlash('success', 'Profile updated successfully.');
    redirect(BASE_URL . '/student/profile.php');
}
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/student_sidebar.php'; ?>
    <main class="main-content">
        <div class="container py-4">
            <h2 class="mb-4">My Profile</h2>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" type="text" name="name" value="<?= e($user['name']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="<?= e($user['email']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" type="text" name="phone" value="<?= e($user['phone']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Department</label><input class="form-control" type="text" name="department" value="<?= e($user['department']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Year</label><input class="form-control" type="text" name="year" value="<?= e($user['year']) ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Student ID</label><input class="form-control" type="text" value="<?= e($user['student_id']) ?>" readonly></div>
                            <div class="col-md-12"><label class="form-label">New Password (optional)</label><input class="form-control" type="password" name="new_password"></div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
