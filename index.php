<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

// Featured photos: join members to show author name
$stmt = $pdo->query("
  SELECT p.photo_id, p.title, p.file_path, m.full_name AS author
  FROM photos p
  JOIN members m ON p.member_id = m.member_id
  ORDER BY p.upload_date DESC
  LIMIT 6
");
$featured = $stmt->fetchAll();
?>
<section class="hero">
  <div class="hero-content">
    <h2>Capture the Moment</h2>
    <p>Join our community of passionate photographers</p>
    <a href="register.php" class="btn">Join Now</a>
  </div>
</section>

<section class="featured-photos">
  <h2>Featured Photos</h2>
  <div class="photo-grid">
    <?php foreach ($featured as $photo): ?>
      <div class="photo-item">
        <a href="photo.php?id=<?= (int)$photo['photo_id'] ?>">
          <img src="uploads/<?= h($photo['file_path']) ?>" alt="<?= h($photo['title']) ?>">
        </a>
        <div class="photo-info">
          <h3><?= h($photo['title']) ?></h3>
          <p>By <?= h($photo['author']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="upcoming-events">
  <h2>Upcoming Events</h2>
  <div class="events-grid">
    <?php
      $stmt = $pdo->query("
        SELECT event_id, title, event_date, location
        FROM events
        WHERE event_date >= CURDATE()
        ORDER BY event_date ASC
        LIMIT 3
      ");
      foreach ($stmt as $event):
    ?>
      <div class="event-card">
        <h3><?= h($event['title']) ?></h3>
        <p class="event-date"><?= date('F j, Y', strtotime($event['event_date'])) ?></p>
        <p class="event-location"><?= h($event['location']) ?></p>
        <a href="event.php?id=<?= (int)$event['event_id'] ?>" class="btn">Details</a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
