<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('admin');
$id = (int)($_GET['id'] ?? 0);
$pdo = getPDO();
$stmt = $pdo->prepare('DELETE FROM clubs WHERE id = ?');
$stmt->execute([$id]);
setFlash('success', 'Club deleted successfully.');
redirect(BASE_URL . '/admin/clubs.php');
