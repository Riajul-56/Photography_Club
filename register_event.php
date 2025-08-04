<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['member_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['event_id'])) {
    header('Location: events.php');
    exit;
}

$event_id = $_GET['event_id'];

// Check if event exists
$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header('Location: events.php');
    exit;
}

// Check if user is already registered
$stmt = $pdo->prepare("SELECT * FROM event_participants WHERE event_id = ? AND member_id = ?");
$stmt->execute([$event_id, $_SESSION['member_id']]);
$is_registered = $stmt->rowCount() > 0;

if (!$is_registered) {
    // Register user for event
    $stmt = $pdo->prepare("INSERT INTO event_participants (event_id, member_id) VALUES (?, ?)");
    $stmt->execute([$event_id, $_SESSION['member_id']]);
}

header("Location: event.php?id=$event_id");
exit;
?>