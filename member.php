<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: /members.php');
    exit;
}

$member_id = $_GET['id'];

// Get member info
$stmt = $pdo->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->execute([$member_id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    header('Location: /members.php');
    exit;
}

// Get member photos
$stmt = $pdo->prepare("SELECT * FROM photos WHERE member_id = ? ORDER BY upload_date DESC");
$stmt->execute([$member_id]);
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get member events
$stmt = $pdo->prepare("SELECT e.* FROM events e JOIN event_participants ep ON e.event_id = ep.event_id WHERE ep.member_id = ? ORDER BY e.event_date DESC");
$stmt->execute([$member_id]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="member-profile">
    <div class="profile-header">
        <div class="profile-pic">
            <img src="/uploads/profiles/<?php echo htmlspecialchars($member['profile_pic']); ?>" alt="<?php echo htmlspecialchars($member['full_name']); ?>">
        </div>
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($member['full_name']); ?></h2>
            <p class="username">@<?php echo htmlspecialchars($member['username']); ?></p>
            <p class="join-date">Member since <?php echo date('F Y', strtotime($member['join_date'])); ?></p>
            
            <?php if (!empty($member['bio'])): ?>
                <div class="bio">
                    <p><?php echo nl2br(htmlspecialchars($member['bio'])); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="profile-stats">
        <div class="stat">
            <h3><?php echo count($photos); ?></h3>
            <p>Photos</p>
        </div>
        <div class="stat">
            <h3><?php echo count($events); ?></h3>
            <p>Events</p>
        </div>
    </div>
    
    <div class="profile-section">
        <h3>Photos</h3>
        <?php if (!empty($photos)): ?>
            <div class="photo-grid">
                <?php foreach ($photos as $photo): ?>
                    <div class="photo-item">
                        <a href="/photo.php?id=<?php echo $photo['photo_id']; ?>">
                            <img src="/uploads/<?php echo htmlspecialchars($photo['file_path']); ?>" alt="<?php echo htmlspecialchars($photo['title']); ?>">
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
        <?php if (!empty($events)): ?>
            <div class="events-list">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                        <p class="event-meta">
                            <i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($event['event_date'])); ?>
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                        </p>
                        <a href="/event.php?id=<?php echo $event['event_id']; ?>" class="btn btn-outline">View Event</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No events attended yet.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>