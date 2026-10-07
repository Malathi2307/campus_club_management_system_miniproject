<?php
require_once __DIR__ . '/config/auth.php';
requireGuest();
$pageTitle = 'Home';
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
$pdo = getPDO();
$featuredClubs = $pdo->query('SELECT * FROM clubs WHERE status = "active" ORDER BY created_at DESC LIMIT 3')->fetchAll();
$upcomingEvents = $pdo->query('SELECT e.*, c.name AS club_name FROM events e JOIN clubs c ON c.id = e.club_id WHERE e.status = "open" ORDER BY e.event_date ASC LIMIT 3')->fetchAll();
$announcements = $pdo->query('SELECT * FROM announcements WHERE status = "published" ORDER BY created_at DESC LIMIT 3')->fetchAll();
?>
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="badge bg-primary-subtle text-primary-emphasis mb-3">Campus Life</span>
                <h1>Campus Club Management System</h1>
                <p>Discover campus clubs, participate in events, build communities and stay connected with college activities.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="#clubs" class="btn btn-primary btn-lg">Explore Clubs</a>
                    <a href="#events" class="btn btn-outline-primary btn-lg">View Events</a>
                    <a href="<?= BASE_URL ?>/login.php" class="btn btn-success btn-lg">Student Login</a>
                    <a href="<?= BASE_URL ?>/register.php" class="btn btn-secondary btn-lg">Register</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=80" alt="Campus" class="hero-image img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</section>
<main>
    <section id="clubs" class="py-5">
        <div class="container">
            <div class="section-heading text-center mb-4"><h2>Featured Clubs</h2></div>
            <div class="row g-4">
                <?php foreach ($featuredClubs as $club): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h5><?= e($club['name']) ?></h5>
                                <p class="text-muted"><?= e($club['category']) ?></p>
                                <p><?= e(substr($club['description'], 0, 120)) ?>...</p>
                                <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <section id="events" class="py-5 bg-light">
        <div class="container">
            <div class="section-heading text-center mb-4"><h2>Upcoming Events</h2></div>
            <div class="row g-4">
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2"><?= e($event['club_name']) ?></span>
                                <h5><?= e($event['title']) ?></h5>
                                <p><?= e($event['event_date']) ?> at <?= e($event['event_time']) ?></p>
                                <p><?= e($event['venue']) ?></p>
                                <a href="<?= BASE_URL ?>/login.php" class="btn btn-primary btn-sm">Register</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <section id="announcements" class="py-5">
        <div class="container">
            <div class="section-heading text-center mb-4"><h2>Latest Announcements</h2></div>
            <div class="row g-4">
                <?php foreach ($announcements as $item): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h5><?= e($item['title']) ?></h5>
                                <p><?= e($item['message']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <section id="about" class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4"><h3>Student Participation</h3><p>Connect with societies and develop new skills.</p></div>
                <div class="col-md-4"><h3>Event Access</h3><p>Register for workshops, competitions and networking sessions.</p></div>
                <div class="col-md-4"><h3>Strong Community</h3><p>Build lasting friendships and leadership experience.</p></div>
            </div>
        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <div class="section-heading text-center mb-4"><h2>Why Join a Club?</h2></div>
            <div class="row g-4">
                <div class="col-md-3 text-center"><div class="feature-box"><h5>Networking</h5></div></div>
                <div class="col-md-3 text-center"><div class="feature-box"><h5>Leadership</h5></div></div>
                <div class="col-md-3 text-center"><div class="feature-box"><h5>Innovation</h5></div></div>
                <div class="col-md-3 text-center"><div class="feature-box"><h5>Events</h5></div></div>
            </div>
        </div>
    </section>
    <footer class="bg-dark text-white py-4">
        <div class="container"><div class="row"><div class="col-md-6"><h5>Campus Club Management</h5><p>Campus life made simple.</p></div><div class="col-md-6 text-md-end"><p>Contact: support@campusclub.edu</p></div></div></div>
    </footer>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
