<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: /events.php');
    exit;
}

$event_id = $_GET['id'];

// Get event details
$stmt = $pdo->prepare("SELECT e.*, m.full_name AS organizer_name FROM events e JOIN members m ON e.organizer_id = m.member_id WHERE e.event_id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header('Location: /events.php');
    exit;
}

// Get participants
$stmt = $pdo->prepare("SELECT m.member_id, m.username, m.full_name, m.profile_pic FROM event_participants ep JOIN members m ON ep.member_id = m.member_id WHERE ep.event_id = ?");
$stmt->execute([$event_id]);
$participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get event photos
$stmt = $pdo->prepare("SELECT p.*, m.username, m.full_name FROM photos p JOIN members m ON p.member_id = m.member_id WHERE p.photo_id IN (SELECT photo_id FROM event_photos WHERE event_id = ?)");
$stmt->execute([$event_id]);
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if current user is registered
$is_registered = false;
if (isset($_SESSION['member_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM event_participants WHERE event_id = ? AND member_id = ?");
    $stmt->execute([$event_id, $_SESSION['member_id']]);
    $is_registered = $stmt->rowCount() > 0;
}
?>

<section class="event-detail">
    <div class="event-header">
        <h2><?php echo htmlspecialchars($event['title']); ?></h2>
        <p class="event-meta">
            <i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($event['event_date'])); ?>
            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
            <i class="fas fa-user"></i> Organized by <?php echo htmlspecialchars($event['organizer_name']); ?>
        </p>
        
        <?php if (isset($_SESSION['member_id'])): ?>
            <div class="event-actions">
                <?php if ($is_registered): ?>
                    <span class="btn btn-disabled">You're Registered</span>
                <?php else: ?>
                    <a href="/register_event.php?event_id=<?php echo $event_id; ?>" class="btn">Register for Event</a>
                <?php endif; ?>
                
                <?php if ($_SESSION['member_id'] == $event['organizer_id']): ?>
                    <a href="dashboard/edit_event.php?id=<?php echo $event_id; ?>" class="btn btn-outline">Edit Event</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="event-description">
        <h3>About This Event</h3>
        <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
    </div>
    
    <div class="event-participants">
        <h3>Participants (<?php echo count($participants); ?>)</h3>
        <?php if (!empty($participants)): ?>
            <div class="participants-grid">
                <?php foreach ($participants as $participant): ?>
                    <a href="member.php?id=<?php echo $participant['member_id']; ?>" class="participant">
                        <img src="uploads/profiles/<?php echo htmlspecialchars($participant['profile_pic']); ?>" alt="<?php echo htmlspecialchars($participant['full_name']); ?>">
                        <span><?php echo htmlspecialchars($participant['full_name']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No participants yet.</p>
        <?php endif; ?>
    </div>
    
    <div class="event-photos">
        <h3>Event Photos</h3>
        <?php if (!empty($photos)): ?>
            <div class="photo-grid">
                <?php foreach ($photos as $photo): ?>
                    <div class="photo-item">
                        <a href="photo.php?id=<?php echo $photo['photo_id']; ?>">
                            <img src="uploads/<?php echo htmlspecialchars($photo['file_path']); ?>" alt="<?php echo htmlspecialchars($photo['title']); ?>">
                        </a>
                        <div class="photo-info">
                            <h4><?php echo htmlspecialchars($photo['title']); ?></h4>
                            <p>By <?php echo htmlspecialchars($photo['full_name']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No photos uploaded yet.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>