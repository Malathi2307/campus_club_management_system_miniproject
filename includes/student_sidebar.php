<?php require_once __DIR__ . '/../config/auth.php'; ?>
<aside class="sidebar">
    <div class="sidebar-brand">Student Portal</div>
    <ul class="nav flex-column p-3">
        <li><a href="<?= BASE_URL ?>/student/dashboard.php" class="nav-link">Dashboard</a></li>
        <li><a href="<?= BASE_URL ?>/student/clubs.php" class="nav-link">Clubs</a></li>
        <li><a href="<?= BASE_URL ?>/student/my_clubs.php" class="nav-link">My Clubs</a></li>
        <li><a href="<?= BASE_URL ?>/student/events.php" class="nav-link">Events</a></li>
        <li><a href="<?= BASE_URL ?>/student/my_events.php" class="nav-link">My Events</a></li>
        <li><a href="<?= BASE_URL ?>/student/announcements.php" class="nav-link">Announcements</a></li>
        <li><a href="<?= BASE_URL ?>/student/profile.php" class="nav-link">Profile</a></li>
        <li><a href="<?= BASE_URL ?>/logout.php" class="nav-link">Logout</a></li>
    </ul>
</aside>
