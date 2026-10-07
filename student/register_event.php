<?php
require_once __DIR__ . '/../config/auth.php';
requireRole('student');
$user = currentUser();
$id = (int)($_GET['id'] ?? 0);
$pdo = getPDO();
$event = $pdo->prepare('SELECT * FROM events WHERE id = ? AND status = "open"');
$event->execute([$id]);
$eventData = $event->fetch();
if (!$eventData) {
    setFlash('danger', 'Event not found.');
    redirect(BASE_URL . '/student/events.php');
}
$registered = $pdo->prepare('SELECT id FROM event_registrations WHERE user_id = ? AND event_id = ?');
$registered->execute([$user['id'], $id]);
if ($registered->rowCount() > 0) {
    setFlash('danger', 'You have already registered for this event.');
    redirect(BASE_URL . '/student/my_events.php');
}
$count = $pdo->prepare('SELECT COUNT(*) FROM event_registrations WHERE event_id = ?');
$count->execute([$id]);
if ((int)$count->fetchColumn() >= (int)$eventData['max_participants']) {
    setFlash('danger', 'Event registration is full.');
    redirect(BASE_URL . '/student/events.php');
}
$stmt = $pdo->prepare('INSERT INTO event_registrations (user_id, event_id, registered_at, status) VALUES (?, ?, NOW(), "registered")');
$stmt->execute([$user['id'], $id]);
setFlash('success', 'Event registered successfully.');
redirect(BASE_URL . '/student/my_events.php');
