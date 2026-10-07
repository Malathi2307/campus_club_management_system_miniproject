<?php
require_once __DIR__ . '/config/auth.php';
requireGuest();
$pageTitle = 'Register';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $department = trim($_POST['department'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $studentId = trim($_POST['student_id'] ?? '');

    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '' || $department === '' || $year === '' || $phone === '' || $studentId === '') {
        setFlash('danger', 'Please fill in all required fields.');
        redirect(BASE_URL . '/register.php');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlash('danger', 'Please enter a valid email address.');
        redirect(BASE_URL . '/register.php');
    }
    if ($password !== $confirmPassword) {
        setFlash('danger', 'Passwords do not match.');
        redirect(BASE_URL . '/register.php');
    }
    $pdo = getPDO();
    $exists = $pdo->prepare('SELECT id FROM users WHERE email = ? OR student_id = ?');
    $exists->execute([$email, $studentId]);
    if ($exists->fetch()) {
        setFlash('danger', 'Email already registered or student ID already exists.');
        redirect(BASE_URL . '/register.php');
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (student_id, name, email, password, phone, department, year, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, "student", "active", NOW())');
    $stmt->execute([$studentId, $name, $email, $hash, $phone, $department, $year]);
    setFlash('success', 'Registration successful. Please login.');
    redirect(BASE_URL . '/login.php');
}
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h2 class="mb-4 text-center">Student Registration</h2>
                    <form method="POST">
                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Confirm Password</label><input type="password" name="confirm_password" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Department</label><input type="text" name="department" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Year</label><input type="text" name="year" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Phone Number</label><input type="tel" name="phone" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label">Student ID</label><input type="text" name="student_id" class="form-control" required></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-4">Register</button>
                    </form>
                    <p class="mt-3 mb-0 text-center">Already have an account? <a href="<?= BASE_URL ?>/login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
