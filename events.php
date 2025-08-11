<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

// Upcoming events
$upcoming = $pdo->query("
  SELECT e.event_id, e.title, e.description, e.event_date, e.location, m.full_name AS organizer_name
  FROM events e
  JOIN members m ON e.organizer_id = m.member_id
  WHERE e.event_date >= CURDATE()
  ORDER BY e.event_date ASC
")->fetchAll();

// Past events (latest 5)
$past = $pdo->query("
  SELECT e.event_id, e.title, e.event_date, e.location, m.full_name AS organizer_name
  FROM events e
  JOIN members m ON e.organizer_id = m.member_id
  WHERE e.event_date < CURDATE()
  ORDER BY e.event_date DESC
  LIMIT 5
")->fetchAll();
?>
<section class="events-section">
  <h2>Upcoming Events</h2>
  <?php if (!$upcoming): ?>
    <p>No upcoming events scheduled. Check back later!</p>
  <?php else: ?>
    <div class="events-list">
      <?php foreach ($upcoming as $event): ?>
        <div class="event-card">
          <div class="event-date">
            <span class="day"><?= date('d', strtotime($event['event_date'])) ?></span>
            <span class="month"><?= date('M', strtotime($event['event_date'])) ?></span>
          </div>
          <div class="event-details">
            <h3><?= h($event['title']) ?></h3>
            <p class="event-meta">
              <i class="fas fa-map-marker-alt"></i> <?= h($event['location']) ?>
              <i class="fas fa-user"></i> Organized by <?= h($event['organizer_name']) ?>
            </p>
            <p><?= h($event['description']) ?></p>
            <div class="event-actions">
              <?php if (is_logged_in()): ?>
                <?php
                  $stmt = $pdo->prepare("SELECT 1 FROM event_participants WHERE event_id = ? AND member_id = ?");
                  $stmt->execute([$event['event_id'], $_SESSION['member_id']]);
                  $is_registered = (bool)$stmt->fetchColumn();
                ?>
                <?php if ($is_registered): ?>
                  <span class="btn btn-disabled">Already Registered</span>
                <?php else: ?>
                  <a href="register_event.php?event_id=<?= (int)$event['event_id'] ?>" class="btn">Register</a>
                <?php endif; ?>
              <?php else: ?>
                <a href="login.php" class="btn">Login to Register</a>
              <?php endif; ?>
              <a href="event.php?id=<?= (int)$event['event_id'] ?>" class="btn btn-outline">Details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <h2>Past Events</h2>
  <?php if (!$past): ?>
    <p>No past events to display.</p>
  <?php else: ?>
    <div class="past-events">
      <?php foreach ($past as $event): ?>
        <div class="past-event-card">
          <h3><?= h($event['title']) ?></h3>
          <p class="event-meta">
            <i class="fas fa-calendar"></i> <?= date('F j, Y', strtotime($event['event_date'])) ?>
            <i class="fas fa-map-marker-alt"></i> <?= h($event['location']) ?>
          </p>
          <a href="event.php?id=<?= (int)$event['event_id'] ?>" class="btn btn-outline">View Photos</a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
