<?php require_once __DIR__ . '/../config/auth.php'; $user = currentUser(); ?>
<aside class="sidebar">
    <div class="sidebar-brand">Campus Club</div>
    <ul class="nav flex-column p-3">
        <li><a href="<?= BASE_URL ?>/admin/dashboard.php" class="nav-link">Dashboard</a></li>
        <li><a href="<?= BASE_URL ?>/admin/students.php" class="nav-link">Students</a></li>
        <li><a href="<?= BASE_URL ?>/admin/clubs.php" class="nav-link">Clubs</a></li>
        <li><a href="<?= BASE_URL ?>/admin/events.php" class="nav-link">Events</a></li>
        <li><a href="<?= BASE_URL ?>/admin/memberships.php" class="nav-link">Memberships</a></li>
        <li><a href="<?= BASE_URL ?>/admin/registrations.php" class="nav-link">Event Registrations</a></li>
        <li><a href="<?= BASE_URL ?>/admin/announcements.php" class="nav-link">Announcements</a></li>
        <li><a href="<?= BASE_URL ?>/admin/reports.php" class="nav-link">Reports</a></li>
        <li><a href="<?= BASE_URL ?>/admin/profile.php" class="nav-link">Profile</a></li>
        <li><a href="<?= BASE_URL ?>/logout.php" class="nav-link">Logout</a></li>
    </ul>
</aside>
