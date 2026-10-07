<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Manage Clubs';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$where = ['1=1'];
$params = [];
if ($search !== '') {
    $where[] = '(name LIKE ? OR category LIKE ? OR faculty_coordinator LIKE ?)';
    $term = '%' . $search . '%';
    $params = array_merge($params, [$term, $term, $term]);
}
if ($category !== '') {
    $where[] = 'category = ?';
    $params[] = $category;
}
$stmt = $pdo->prepare('SELECT * FROM clubs WHERE ' . implode(' AND ', $where) . ' ORDER BY created_at DESC');
$stmt->execute($params);
$clubs = $stmt->fetchAll();
$categories = $pdo->query('SELECT DISTINCT category FROM clubs ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Clubs</h2>
                <a href="<?= BASE_URL ?>/admin/add_club.php" class="btn btn-primary">Add Club</a>
            </div>
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-6"><input type="text" class="form-control" name="search" value="<?= e($search) ?>" placeholder="Search club name, category or coordinator"></div>
                <div class="col-md-3">
                    <select class="form-select" name="category">
                        <option value="">All categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3"><button type="submit" class="btn btn-primary w-100">Search</button></div>
            </form>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light"><tr><th>Name</th><th>Category</th><th>Coordinator</th><th>Status</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php foreach ($clubs as $club): ?>
                                    <tr>
                                        <td><?= e($club['name']) ?></td>
                                        <td><?= e($club['category']) ?></td>
                                        <td><?= e($club['faculty_coordinator']) ?></td>
                                        <td><span class="badge <?= $club['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>"><?= e(ucfirst($club['status'])) ?></span></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/admin/edit_club.php?id=<?= (int)$club['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <a href="<?= BASE_URL ?>/admin/delete_club.php?id=<?= (int)$club['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this club?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
