<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

if (!isset($_GET['id'])) { header('Location: gallery.php'); exit; }
$photo_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
  SELECT p.*, m.member_id, m.full_name, m.username, m.profile_pic
  FROM photos p
  JOIN members m ON p.member_id = m.member_id
  WHERE p.photo_id = ?
");
$stmt->execute([$photo_id]);
$photo = $stmt->fetch();
if (!$photo) { header('Location: gallery.php'); exit; }

$stmt = $pdo->prepare("
  SELECT photo_id, title, file_path 
  FROM photos 
  WHERE member_id = ? AND photo_id != ?
  ORDER BY upload_date DESC LIMIT 4
");
$stmt->execute([$photo['member_id'], $photo_id]);
$related = $stmt->fetchAll();
?>
<section class="photo-detail">
  <div class="photo-container">
    <img src="uploads/<?= h($photo['file_path']) ?>" alt="<?= h($photo['title']) ?>">
  </div>
  <div class="photo-info">
    <h2><?= h($photo['title']) ?></h2>
    <p class="photo-meta">
      By <a href="member.php?id=<?= (int)$photo['member_id'] ?>"><?= h($photo['full_name']) ?></a>
      on <?= date('F j, Y', strtotime($photo['upload_date'])) ?>
      <?= $photo['category'] ? ' in ' . ucfirst(h($photo['category'])) : '' ?>
    </p>
    <?php if (!empty($photo['description'])): ?>
      <div class="photo-description"><p><?= nl2br(h($photo['description'])) ?></p></div>
    <?php endif; ?>

    <?php if (is_logged_in() && $_SESSION['member_id'] == $photo['member_id']): ?>
      <div class="photo-actions">
        <a href="dashboard/edit_photo.php?id=<?= (int)$photo_id ?>" class="btn">Edit</a>
        <a href="dashboard/delete_photo.php?id=<?= (int)$photo_id ?>" class="btn btn-outline" onclick="return confirm('Delete this photo?')">Delete</a>
      </div>
    <?php endif; ?>
  </div>

  <?php if ($related): ?>
    <div class="related-photos">
      <h3>More from <?= h($photo['full_name']) ?></h3>
      <div class="photo-grid">
        <?php foreach ($related as $p): ?>
          <div class="photo-item">
            <a href="photo.php?id=<?= (int)$p['photo_id'] ?>">
              <img src="uploads/<?= h($p['file_path']) ?>" alt="<?= h($p['title']) ?>">
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
