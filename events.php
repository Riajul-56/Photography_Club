<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Get upcoming events
$stmt = $pdo->query("SELECT e.*, m.full_name AS organizer_name FROM events e JOIN members m ON e.organizer_id = m.member_id WHERE e.event_date >= CURDATE() ORDER BY e.event_date ASC");
$upcoming_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get past events
$stmt = $pdo->query("SELECT e.*, m.full_name AS organizer_name FROM events e JOIN members m ON e.organizer_id = m.member_id WHERE e.event_date < CURDATE() ORDER BY e.event_date DESC LIMIT 5");
$past_events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="events-section">
    <h2>Upcoming Events</h2>
    
    <?php if (empty($upcoming_events)): ?>
        <p>No upcoming events scheduled. Check back later!</p>
    <?php else: ?>
        <div class="events-list">
            <?php foreach ($upcoming_events as $event): ?>
                <div class="event-card">
                    <div class="event-date">
                        <span class="day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                        <span class="month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                    </div>
                    <div class="event-details">
                        <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-meta">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                            <i class="fas fa-user"></i> Organized by <?php echo htmlspecialchars($event['organizer_name']); ?>
                        </p>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                        <div class="event-actions">
                            <?php if (isset($_SESSION['member_id'])): ?>
                                <?php
                                // Check if user is already registered
                                $stmt = $pdo->prepare("SELECT * FROM event_participants WHERE event_id = ? AND member_id = ?");
                                $stmt->execute([$event['event_id'], $_SESSION['member_id']]);
                                $is_registered = $stmt->rowCount() > 0;
                                ?>
                                
                                <?php if ($is_registered): ?>
                                    <span class="btn btn-disabled">Already Registered</span>
                                <?php else: ?>
                                    <a href="register_event.php?event_id=<?php echo $event['event_id']; ?>" class="btn">Register</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="login.php" class="btn">Login to Register</a>
                            <?php endif; ?>
                            <a href="event.php?id=<?php echo $event['event_id']; ?>" class="btn btn-outline">Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <h2>Past Events</h2>
    
    <?php if (empty($past_events)): ?>
        <p>No past events to display.</p>
    <?php else: ?>
        <div class="past-events">
            <?php foreach ($past_events as $event): ?>
                <div class="past-event-card">
                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p class="event-meta">
                        <i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($event['event_date'])); ?>
                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                    </p>
                    <a href="event.php?id=<?php echo $event['event_id']; ?>" class="btn btn-outline">View Photos</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>