<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$category = $_GET['category'] ?? 'all';
$search = $_GET['search'] ?? '';

// Build query
$sql = "SELECT p.*, m.username, m.full_name FROM photos p JOIN members m ON p.member_id = m.member_id";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE p.title LIKE ? OR p.description LIKE ?";
    $params = ["%$search%", "%$search%"];
} elseif ($category !== 'all') {
    $sql .= " WHERE p.category = ?";
    $params = [$category];
}

$sql .= " ORDER BY p.upload_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="gallery-header">
    <h2>Photo Gallery</h2>
    
    <form action="gallery.php" method="GET" class="search-form">
        <div class="form-group">
            <input type="text" name="search" placeholder="Search photos..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
    
    <div class="category-filter">
        <a href="gallery.php" class="<?php echo $category === 'all' ? 'active' : ''; ?>">All</a>
        <a href="gallery.php?category=nature" class="<?php echo $category === 'nature' ? 'active' : ''; ?>">Nature</a>
        <a href="gallery.php?category=portrait" class="<?php echo $category === 'portrait' ? 'active' : ''; ?>">Portrait</a>
        <a href="gallery.php?category=street" class="<?php echo $category === 'street' ? 'active' : ''; ?>">Street</a>
        <a href="gallery.php?category=wildlife" class="<?php echo $category === 'wildlife' ? 'active' : ''; ?>">Wildlife</a>
        <a href="gallery.php?category=macro" class="<?php echo $category === 'macro' ? 'active' : ''; ?>">Macro</a>
        <a href="gallery.php?category=landscape" class="<?php echo $category === 'landscape' ? 'active' : ''; ?>">Landscape</a>
    </div>
</section>

<section class="photo-gallery">
    <?php if (empty($photos)): ?>
        <p>No photos found.</p>
    <?php else: ?>
        <div class="masonry-grid">
            <?php foreach ($photos as $photo): ?>
                <div class="photo-card">
                    <a href="photo.php?id=<?php echo $photo['photo_id']; ?>">
                        <img src="uploads/<?php echo htmlspecialchars($photo['file_path']); ?>" alt="<?php echo htmlspecialchars($photo['title']); ?>">
                    </a>
                    <div class="photo-details">
                        <h3><?php echo htmlspecialchars($photo['title']); ?></h3>
                        <p class="author">By <?php echo htmlspecialchars($photo['full_name']); ?></p>
                        <p class="category"><?php echo ucfirst(htmlspecialchars($photo['category'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>