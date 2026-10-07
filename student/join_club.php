<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$user = currentUser();
$id = (int)($_GET['id'] ?? 0);
$pdo = getPDO();
$exists = $pdo->prepare('SELECT id FROM memberships WHERE user_id = ? AND club_id = ?');
$exists->execute([$user['id'], $id]);
if ($exists->rowCount() > 0) {
    setFlash('danger', 'You have already joined this club.');
    redirect(BASE_URL . '/student/clubs.php');
}
$stmt = $pdo->prepare('INSERT INTO memberships (user_id, club_id, status, joined_at) VALUES (?, ?, "active", NOW())');
$stmt->execute([$user['id'], $id]);
setFlash('success', 'Club joined successfully.');
redirect(BASE_URL . '/student/my_clubs.php');
