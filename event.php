<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

if (!isset($_GET['id'])) {
  header('Location: events.php'); exit;
}
$event_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
  SELECT e.*, m.full_name AS organizer_name 
  FROM events e 
  JOIN members m ON e.organizer_id = m.member_id 
  WHERE e.event_id = ?
");
$stmt->execute([$event_id]);
$event = $stmt->fetch();
if (!$event) { header('Location: events.php'); exit; }

// Participants
$stmt = $pdo->prepare("
  SELECT m.member_id, m.username, m.full_name, m.profile_pic 
  FROM event_participants ep 
  JOIN members m ON ep.member_id = m.member_id 
  WHERE ep.event_id = ?
");
$stmt->execute([$event_id]);
$participants = $stmt->fetchAll();

// Event photos
$stmt = $pdo->prepare("
  SELECT p.photo_id, p.title, p.file_path, m.full_name 
  FROM photos p 
  JOIN members m ON p.member_id = m.member_id 
  WHERE p.photo_id IN (SELECT photo_id FROM event_photos WHERE event_id = ?)
");
$stmt->execute([$event_id]);
$photos = $stmt->fetchAll();

$is_registered = false;
if (is_logged_in()) {
  $stmt = $pdo->prepare("SELECT 1 FROM event_participants WHERE event_id = ? AND member_id = ?");
  $stmt->execute([$event_id, $_SESSION['member_id']]);
  $is_registered = (bool)$stmt->fetchColumn();
}
?>
<section class="event-detail">
  <div class="event-header">
    <h2><?= h($event['title']) ?></h2>
    <p class="event-meta">
      <i class="fas fa-calendar"></i> <?= date('F j, Y', strtotime($event['event_date'])) ?>
      <i class="fas fa-map-marker-alt"></i> <?= h($event['location']) ?>
      <i class="fas fa-user"></i> Organized by <?= h($event['organizer_name']) ?>
    </p>
    <?php if (is_logged_in()): ?>
      <div class="event-actions">
        <?php if ($is_registered): ?>
          <span class="btn btn-disabled">You're Registered</span>
        <?php else: ?>
          <a href="register_event.php?event_id=<?= (int)$event_id ?>" class="btn">Register for Event</a>
        <?php endif; ?>
        <?php if ($_SESSION['member_id'] == $event['organizer_id']): ?>
          <a href="dashboard/edit_event.php?id=<?= (int)$event_id ?>" class="btn btn-outline">Edit Event</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="event-description">
    <h3>About This Event</h3>
    <p><?= nl2br(h($event['description'])) ?></p>
  </div>

  <div class="event-participants">
    <h3>Participants (<?= count($participants) ?>)</h3>
    <?php if ($participants): ?>
      <div class="participants-grid">
        <?php foreach ($participants as $p): ?>
          <a href="member.php?id=<?= (int)$p['member_id'] ?>" class="participant">
            <img src="uploads/profiles/<?= h($p['profile_pic']) ?>" alt="<?= h($p['full_name']) ?>">
            <span><?= h($p['full_name']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No participants yet.</p>
    <?php endif; ?>
  </div>

  <div class="event-photos">
    <h3>Event Photos</h3>
    <?php if ($photos): ?>
      <div class="photo-grid">
        <?php foreach ($photos as $photo): ?>
          <div class="photo-item">
            <a href="photo.php?id=<?= (int)$photo['photo_id'] ?>">
              <img src="uploads/<?= h($photo['file_path']) ?>" alt="<?= h($photo['title']) ?>">
            </a>
            <div class="photo-info">
              <h4><?= h($photo['title']) ?></h4>
              <p>By <?= h($photo['full_name']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No photos uploaded yet.</p>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
