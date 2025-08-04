<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['member_id']) || !isset($_GET['id'])) {
    header('Location: login.php');
    exit;
}

$photo_id = $_GET['id'];

// Verify ownership
$stmt = $pdo->prepare("SELECT file_path FROM photos WHERE photo_id = ? AND member_id = ?");
$stmt->execute([$photo_id, $_SESSION['member_id']]);
$photo = $stmt->fetch(PDO::FETCH_ASSOC);

if ($photo) {
    // Delete file
    unlink("..uploads/" . $photo['file_path']);
    
    // Delete from database
    $pdo->prepare("DELETE FROM photos WHERE photo_id = ?")->execute([$photo_id]);
}

header('Location: dashboard/');
exit;
?>