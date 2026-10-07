<?php require_once __DIR__ . '/../config/auth.php'; $user = currentUser(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/index.php">Campus Club Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php#clubs">Clubs</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php#events">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php#announcements">Announcements</a></li>
                <?php if ($user): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $user['role'] === 'admin' ? BASE_URL . '/admin/dashboard.php' : BASE_URL . '/student/dashboard.php' ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-light ms-2" href="<?= BASE_URL ?>/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
