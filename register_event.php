<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
redirect_if_not_logged_in();

if (!isset($_GET['event_id'])) { header('Location: events.php'); exit; }
$event_id = (int)$_GET['event_id'];

$stmt = $pdo->prepare("SELECT 1 FROM events WHERE event_id = ?");
$stmt->execute([$event_id]);
if (!$stmt->fetchColumn()) { header('Location: events.php'); exit; }

$stmt = $pdo->prepare("SELECT 1 FROM event_participants WHERE event_id = ? AND member_id = ?");
$stmt->execute([$event_id, $_SESSION['member_id']]);
if (!$stmt->fetchColumn()) {
  $stmt = $pdo->prepare("INSERT INTO event_participants (event_id, member_id) VALUES (?, ?)");
  $stmt->execute([$event_id, $_SESSION['member_id']]);
}

header("Location: event.php?id={$event_id}"); exit;
