<?php
require_once __DIR__ . '/config/auth.php';
requireGuest();
$pageTitle = 'Login';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') {
        setFlash('danger', 'Email and password are required.');
        redirect(BASE_URL . '/login.php');
    }
    $stmt = getPDO()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_role'] = $user['role'];
        setFlash('success', 'Login successful.');
        redirect($user['role'] === 'admin' ? BASE_URL . '/admin/dashboard.php' : BASE_URL . '/student/dashboard.php');
    }
    setFlash('danger', 'Invalid email or password');
    redirect(BASE_URL . '/login.php');
}
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h2 class="mb-4 text-center">Login</h2>
                    <form method="POST">
                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" name="password" required></div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <p class="mt-3 mb-0 text-center">Need an account? <a href="<?= BASE_URL ?>/register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
