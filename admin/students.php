<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$pageTitle = 'Student Management';
include __DIR__ . '/../includes/header.php';
$pdo = getPDO();
$search = $_GET['search'] ?? '';
$where = ['role = ?'];
$params = ['student'];
if ($search !== '') {
    $where[] = '(name LIKE ? OR student_id LIKE ? OR email LIKE ? OR department LIKE ?)';
    $searchTerm = '%' . $search . '%';
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
}
$filterDepartment = $_GET['department'] ?? '';
if ($filterDepartment !== '') {
    $where[] = 'department = ?';
    $params[] = $filterDepartment;
}
$filterYear = $_GET['year'] ?? '';
if ($filterYear !== '') {
    $where[] = 'year = ?';
    $params[] = $filterYear;
}
$stmt = $pdo->prepare('SELECT * FROM users WHERE ' . implode(' AND ', $where) . ' ORDER BY created_at DESC');
$stmt->execute($params);
$students = $stmt->fetchAll();
$departments = $pdo->query('SELECT DISTINCT department FROM users WHERE role = "student" ORDER BY department')->fetchAll(PDO::FETCH_COLUMN);
$years = $pdo->query('SELECT DISTINCT year FROM users WHERE role = "student" ORDER BY year')->fetchAll(PDO::FETCH_COLUMN);
?>
<div class="d-flex">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Students</h2>
            </div>
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4"><input type="text" class="form-control" name="search" value="<?= e($search) ?>" placeholder="Search by name, student ID, email, department"></div>
                <div class="col-md-2">
                    <select class="form-select" name="department">
                        <option value="">All departments</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= e($dept) ?>" <?= $filterDepartment === $dept ? 'selected' : '' ?>><?= e($dept) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="year">
                        <option value="">All years</option>
                        <?php foreach ($years as $yr): ?>
                            <option value="<?= e($yr) ?>" <?= $filterYear === $yr ? 'selected' : '' ?>><?= e($yr) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-primary w-100" type="submit">Filter</button></div>
                <div class="col-md-2"><a class="btn btn-outline-secondary w-100" href="<?= BASE_URL ?>/admin/students.php">Reset</a></div>
            </form>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr><th>Name</th><th>Student ID</th><th>Email</th><th>Department</th><th>Year</th><th>Status</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= e($student['name']) ?></td>
                                        <td><?= e($student['student_id']) ?></td>
                                        <td><?= e($student['email']) ?></td>
                                        <td><?= e($student['department']) ?></td>
                                        <td><?= e($student['year']) ?></td>
                                        <td><span class="badge <?= $student['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>"><?= e(ucfirst($student['status'])) ?></span></td>
                                        <td><a href="<?= BASE_URL ?>/admin/student_view.php?id=<?= (int)$student['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
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
