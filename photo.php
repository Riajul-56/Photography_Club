<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: gallery.php');
    exit;
}

$photo_id = $_GET['id'];

// Get photo details
$stmt = $pdo->prepare("SELECT p.*, m.username, m.full_name, m.profile_pic FROM photos p JOIN members m ON p.member_id = m.member_id WHERE p.photo_id = ?");
$stmt->execute([$photo_id]);
$photo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$photo) {
    header('Location: gallery.php');
    exit;
}

// Get related photos
$stmt = $pdo->prepare("SELECT * FROM photos WHERE member_id = ? AND photo_id != ? ORDER BY upload_date DESC LIMIT 4");
$stmt->execute([$photo['member_id'], $photo_id]);
$related_photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="photo-detail">
    <div class="photo-container">
        <img src="uploads/<?php echo htmlspecialchars($photo['file_path']); ?>" alt="<?php echo htmlspecialchars($photo['title']); ?>">
    </div>
    
    <div class="photo-info">
        <h2><?php echo htmlspecialchars($photo['title']); ?></h2>
        <p class="photo-meta">
            By <a href="member.php?id=<?php echo $photo['member_id']; ?>"><?php echo htmlspecialchars($photo['full_name']); ?></a> 
            on <?php echo date('F j, Y', strtotime($photo['upload_date'])); ?>
            in <?php echo ucfirst(htmlspecialchars($photo['category'])); ?>
        </p>
        
        <?php if (!empty($photo['description'])): ?>
            <div class="photo-description">
                <p><?php echo nl2br(htmlspecialchars($photo['description'])); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['member_id']) && $_SESSION['member_id'] == $photo['member_id']): ?>
            <div class="photo-actions">
                <a href="dashboard/edit_photo.php?id=<?php echo $photo_id; ?>" class="btn">Edit</a>
                <a href="dashboard/delete_photo.php?id=<?php echo $photo_id; ?>" class="btn btn-outline">Delete</a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($related_photos)): ?>
        <div class="related-photos">
            <h3>More from <?php echo htmlspecialchars($photo['full_name']); ?></h3>
            <div class="photo-grid">
                <?php foreach ($related_photos as $related): ?>
                    <div class="photo-item">
                        <a href="photo.php?id=<?php echo $related['photo_id']; ?>">
                            <img src="uploads/<?php echo htmlspecialchars($related['file_path']); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>