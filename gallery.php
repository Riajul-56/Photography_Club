<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$category = $_GET['category'] ?? 'all';
$search = $_GET['search'] ?? '';

$sql = "SELECT p.photo_id, p.title, p.description, p.file_path, p.category, m.full_name 
        FROM photos p 
        JOIN members m ON p.member_id = m.member_id";
$params = [];

if ($search !== '') {
  $sql .= " WHERE p.title LIKE ? OR p.description LIKE ?";
  $like = "%" . $search . "%";
  $params = [$like, $like];
} elseif ($category !== 'all') {
  $sql .= " WHERE p.category = ?";
  $params = [$category];
}

$sql .= " ORDER BY p.upload_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$photos = $stmt->fetchAll();
?>
<section class="gallery-header">
  <h2>Photo Gallery</h2>
  <form action="gallery.php" method="GET" class="search-form">
    <div class="form-group">
      <input type="text" name="search" placeholder="Search photos..." value="<?= h($search) ?>">
      <button type="submit"><i class="fas fa-search"></i></button>
    </div>
  </form>
  <div class="category-filter">
    <?php
      $cats = ['all','nature','portrait','street','wildlife','macro','landscape'];
      foreach ($cats as $cat):
        $active = ($category === $cat) ? 'active' : '';
        $href = $cat === 'all' ? 'gallery.php' : 'gallery.php?category='.$cat;
    ?>
      <a href="<?= $href ?>" class="<?= $active ?>"><?= ucfirst($cat) ?></a>
    <?php endforeach; ?>
  </div>
</section>
<section class="photo-gallery">
  <?php if (!$photos): ?>
    <p>No photos found.</p>
  <?php else: ?>
    <div class="masonry-grid">
      <?php foreach ($photos as $photo): ?>
        <div class="photo-card">
          <a href="photo.php?id=<?= (int)$photo['photo_id'] ?>">
            <img src="uploads/<?= h($photo['file_path']) ?>" alt="<?= h($photo['title']) ?>">
          </a>
          <div class="photo-details">
            <h3><?= h($photo['title']) ?></h3>
            <p class="author">By <?= h($photo['full_name']) ?></p>
            <p class="category"><?= ucfirst(h($photo['category'])) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
