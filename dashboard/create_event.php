<?php
require_once '..includes/db.php';
require_once '..includes/header.php';
redirect_if_not_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $location = trim($_POST['location']);

    $stmt = $pdo->prepare("INSERT INTO events (title, description, event_date, location, organizer_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $event_date, $location, $_SESSION['member_id']]);
    header('Location: /events.php');
    exit;
}
?>

<div class="dashboard">
    <aside class="sidebar">
        <!-- Sidebar content -->
    </aside>
    
    <main class="dashboard-content">
        <h2>Create New Event</h2>
        <form method="POST">
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="event_date">Date</label>
                <input type="date" id="event_date" name="event_date" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>
            </div>
            <button type="submit" class="btn">Create Event</button>
        </form>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>