<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
redirect_if_not_logged_in();

if (!isset($_GET['id'])) { header('Location: ' . base_url() . 'dashboard/'); exit; }
$photo_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT file_path FROM photos WHERE photo_id = ? AND member_id = ?");
$stmt->execute([$photo_id, $_SESSION['member_id']]);
$photo = $stmt->fetch();

if ($photo) {
  $abs = __DIR__ . '/../uploads/' . $photo['photos/file_path.jpg']; // file_path like 'photos/filename.jpg'
  if (is_file($abs)) { @unlink($abs); }
  $pdo->prepare("DELETE FROM photos WHERE photo_id = ?")->execute([$photo_id]);
}

header('Location: ' . base_url() . 'dashboard/'); exit;
