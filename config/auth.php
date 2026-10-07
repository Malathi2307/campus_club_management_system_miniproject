<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

define('BASE_URL', '/CampusClubManagement');

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect($path): void
{
    header('Location: ' . $path);
    exit;
}

function setFlash($type, $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function currentUser(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $stmt = getPDO()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function requireLogin(): void
{
    if (!currentUser()) {
        setFlash('danger', 'Please login to continue.');
        redirect(BASE_URL . '/login.php');
    }
}

function requireRole($role): void
{
    requireLogin();
    $user = currentUser();
    if (!$user || $user['role'] !== $role) {
        setFlash('danger', 'You do not have permission to access that page.');
        redirect(BASE_URL . '/index.php');
    }
}

function requireGuest(): void
{
    if (currentUser()) {
        $user = currentUser();
        if ($user['role'] === 'admin') {
            redirect(BASE_URL . '/admin/dashboard.php');
        }
        redirect(BASE_URL . '/student/dashboard.php');
    }
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void
{
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        setFlash('danger', 'Invalid request. Please try again.');
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

function uploadFile($file, $directory): ?string
{
    if (!isset($file['name']) || $file['name'] === '') {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed.');
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        throw new RuntimeException('Only JPG, PNG, GIF, and WEBP images are allowed.');
    }

    if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
        throw new RuntimeException('Upload directory is not writable.');
    }

    $filename = uniqid('img_', true) . '.' . $ext;
    $destination = $directory . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Unable to move uploaded file.');
    }

    return str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $destination));
}
