<?php
// Includes for database connection, header, and helpers
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/helpers.php';

// Redirect if not logged in
redirect_if_not_logged_in();

// Fetch member data
$stmt = $pdo->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->execute([$_SESSION['member_id']]);
$member = $stmt->fetch();

// Fetch user's uploaded photos
$stmt = $pdo->prepare("SELECT * FROM photos WHERE member_id = ? ORDER BY upload_date DESC");
$stmt->execute([$_SESSION['member_id']]);
$photos = $stmt->fetchAll();

// Fetch events attended by the user
$stmt = $pdo->prepare("
    SELECT e.event_id, e.title, e.event_date, e.location
    FROM events e
    JOIN event_participants ep ON e.event_id = ep.event_id
    WHERE ep.member_id = ?
    ORDER BY e.event_date DESC
");
$stmt->execute([$_SESSION['member_id']]);
$events = $stmt->fetchAll();
?>

<section class="profile-section">
    <div class="profile-header">
        <div class="profile-pic">
            <img src="<?= base_url(); ?>uploads/profiles/<?= h($member['profile_pic']) ?>" alt="<?= h($member['full_name']) ?>">
        </div>
        <div class="profile-info">
            <h2><?= h($member['full_name']) ?></h2>
            <p class="username">@<?= h($member['username']) ?></p>
            <p class="join-date">Member since <?= date('F Y', strtotime($member['join_date'])) ?></p>
            <?php if (!empty($member['bio'])): ?>
                <div class="bio">
                    <p><?= nl2br(h($member['bio'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="profile-stats">
        <div class="stat">
            <h3><?= count($photos) ?></h3>
            <p>Photos</p>
        </div>
        <div class="stat">
            <h3><?= count($events) ?></h3>
            <p>Events</p>
        </div>
    </div>

    <div class="profile-section">
        <h3>Uploaded Photos</h3>
        <?php if ($photos): ?>
            <div class="photo-grid">
                <?php foreach ($photos as $p): ?>
                    <div class="photo-item">
                        <a href="photo.php?id=<?= (int)$p['photo_id'] ?>">
                            <img src="<?= base_url(); ?>uploads/<?= h($p['file_path']) ?>" alt="<?= h($p['title']) ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No photos uploaded yet.</p>
        <?php endif; ?>
    </div>

    <div class="profile-section">
        <h3>Events Attended</h3>
        <?php if ($events): ?>
            <div class="events-list">
                <?php foreach ($events as $e): ?>
                    <div class="event-card">
                        <h4><?= h($e['title']) ?></h4>
                        <p class="event-meta">
                            <i class="fas fa-calendar"></i> <?= date('F j, Y', strtotime($e['event_date'])) ?>
                            <i class="fas fa-map-marker-alt"></i> <?= h($e['location']) ?>
                        </p>
                        <a href="event.php?id=<?= (int)$e['event_id'] ?>" class="btn btn-outline">View Event</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No events attended yet.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>