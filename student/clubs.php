<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$pageTitle = 'Available Clubs';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$user = currentUser();
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$where = ['c.status = "active"'];
$params = [];
if ($search !== '') {
    $where[] = '(c.name LIKE ? OR c.category LIKE ? OR c.faculty_coordinator LIKE ?)';
    $term = '%' . $search . '%';
    $params = array_merge($params, [$term, $term, $term]);
}
if ($category !== '') {$where[] = 'c.category = ?'; $params[] = $category; }
$stmt = $pdo->prepare('SELECT c.* FROM clubs c WHERE ' . implode(' AND ', $where) . ' ORDER BY c.created_at DESC');
$stmt->execute($params);
$clubs = $stmt->fetchAll();
$categories = $pdo->query('SELECT DISTINCT category FROM clubs WHERE status = "active" ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/student_sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <h2 class="mb-4">Available Clubs</h2>
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-6"><input type="text" name="search" value="<?= e($search) ?>" class="form-control" placeholder="Search clubs"></div>
                <div class="col-md-4"><select name="category" class="form-select"><option value="">All categories</option><?php foreach ($categories as $cat): ?><option value="<?= e($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= e($cat) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Search</button></div>
            </form>
            <div class="row g-4">
                <?php foreach ($clubs as $club): ?>
                    <?php $joined = $pdo->prepare('SELECT id FROM memberships WHERE user_id = ? AND club_id = ?'); $joined->execute([$user['id'], $club['id']]); ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h5><?= e($club['name']) ?></h5>
                                <p class="text-muted mb-2"><?= e($club['category']) ?></p>
                                <p><?= e($club['description']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="<?= BASE_URL ?>/student/club_details.php?id=<?= (int)$club['id'] ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                                    <?php if ($joined->rowCount() === 0): ?><a href="<?= BASE_URL ?>/student/join_club.php?id=<?= (int)$club['id'] ?>" class="btn btn-primary btn-sm">Join Club</a><?php else: ?><span class="badge bg-success">Joined</span><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
