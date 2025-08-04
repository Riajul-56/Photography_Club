<?php require_once 'includes/header.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h2>Capture the Moment</h2>
        <p>Join our community of passionate photographers</p>
        <a href="/register.php" class="btn">Join Now</a>
    </div>
</section>

<section class="featured-photos">
    <h2>Featured Photos</h2>
    <div class="photo-grid">
        <?php
        require_once 'includes/db.php';
        $stmt = $pdo->query("SELECT * FROM photos ORDER BY upload_date DESC LIMIT 6");
        while ($photo = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="photo-item">';
            echo '<img src="/uploads/' . htmlspecialchars($photo['file_path']) . '" alt="' . htmlspecialchars($photo['title']) . '">';
            echo '<div class="photo-info">';
            echo '<h3>' . htmlspecialchars($photo['title']) . '</h3>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</section>

<section class="upcoming-events">
    <h2>Upcoming Events</h2>
    <div class="events-grid">
        <?php
        $stmt = $pdo->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3");
        while ($event = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="event-card">';
            echo '<h3>' . htmlspecialchars($event['title']) . '</h3>';
            echo '<p class="event-date">' . date('F j, Y', strtotime($event['event_date'])) . '</p>';
            echo '<p class="event-location">' . htmlspecialchars($event['location']) . '</p>';
            echo '<a href="/event.php?id=' . $event['event_id'] . '" class="btn">Details</a>';
            echo '</div>';
        }
        ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>