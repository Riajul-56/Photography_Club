<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['member_id'])) {
    header('Location: /login.php');
    exit;
}

// Get member info
$stmt = $pdo->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->execute([$_SESSION['member_id']]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

// Count photos
$stmt = $pdo->prepare("SELECT COUNT(*) FROM photos WHERE member_id = ?");
$stmt->execute([$_SESSION['member_id']]);
$photo_count = $stmt->fetchColumn();

// Count events
$stmt = $pdo->prepare("SELECT COUNT(*) FROM event_participants WHERE member_id = ?");
$stmt->execute([$_SESSION['member_id']]);
$event_count = $stmt->fetchColumn();
?>

<div class="dashboard">
    <aside class="sidebar">
        <div class="profile-summary">
            <img src="/uploads/profiles/<?php echo htmlspecialchars($member['profile_pic']); ?>" alt="Profile Picture">
            <h3><?php echo htmlspecialchars($member['full_name']); ?></h3>
            <p>@<?php echo htmlspecialchars($member['username']); ?></p>
        </div>
        
        <nav class="dashboard-nav">
            <ul>
                <li class="active"><a href="/dashboard/"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="/dashboard/upload.php"><i class="fas fa-upload"></i> Upload Photo</a></li>
                <li><a href="/dashboard/profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="dashboard-content">
        <h2>Dashboard</h2>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Photos</h3>
                <p><?php echo $photo_count; ?></p>
            </div>
            
            <div class="stat-card">
                <h3>Events</h3>
                <p><?php echo $event_count; ?></p>
            </div>
        </div>
        
        <div class="recent-photos">
            <h3>Your Recent Photos</h3>
            <div class="photo-grid">
                <?php
                $stmt = $pdo->prepare("SELECT * FROM photos WHERE member_id = ? ORDER BY upload_date DESC LIMIT 4");
                $stmt->execute([$_SESSION['member_id']]);
                while ($photo = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="photo-item">';
                    echo '<img src="/uploads/' . htmlspecialchars($photo['file_path']) . '" alt="' . htmlspecialchars($photo['title']) . '">';
                    echo '<div class="photo-info">';
                    echo '<h4>' . htmlspecialchars($photo['title']) . '</h4>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>