<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/helpers.php';
redirect_if_not_logged_in();

// Member info
$stmt = $pdo->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->execute([$_SESSION['member_id']]);
$member = $stmt->fetch();

$photo_count = (int)$pdo->prepare("SELECT COUNT(*) FROM photos WHERE member_id = ?")
  ->execute([$_SESSION['member_id']]) ?: 0;

$st = $pdo->prepare("SELECT COUNT(*) FROM photos WHERE member_id = ?");
$st->execute([$_SESSION['member_id']]);
$photo_count = (int)$st->fetchColumn();

$st = $pdo->prepare("SELECT COUNT(*) FROM event_participants WHERE member_id = ?");
$st->execute([$_SESSION['member_id']]);
$event_count = (int)$st->fetchColumn();
?>
<div class="dashboard">
  <aside class="sidebar">
    <div class="profile-summary">
      <img src="<?= base_url(); ?>uploads/profiles/<?= h($member['profile_pic']) ?>" alt="Profile Picture">
      <h3><?= h($member['full_name']) ?></h3>
      <p>@<?= h($member['username']) ?></p>
    </div>
    <nav class="dashboard-nav">
      <ul>
        <li class="active"><a href="<?= base_url(); ?>dashboard/"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?= base_url(); ?>dashboard/upload.php"><i class="fas fa-upload"></i> Upload Photo</a></li>
        <li><a href="<?= base_url(); ?>dashboard/profile.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="<?= base_url(); ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>
  </aside>
  <main class="dashboard-content">
    <h2>Dashboard</h2>
    <div class="stats">
      <div class="stat-card"><h3>Photos</h3><p><?= $photo_count ?></p></div>
      <div class="stat-card"><h3>Events</h3><p><?= $event_count ?></p></div>
    </div>
    <div class="recent-photos">
      <h3>Your Recent Photos</h3>
      <div class="photo-grid">
        <?php
          $stmt = $pdo->prepare("SELECT photo_id, title, file_path FROM photos WHERE member_id = ? ORDER BY upload_date DESC LIMIT 4");
          $stmt->execute([$_SESSION['member_id']]);
          foreach ($stmt as $p):
        ?>
          <div class="photo-item">
            <a href="<?= base_url(); ?>photo.php?id=<?= (int)$p['photo_id'] ?>">
              <img src="<?= base_url(); ?>uploads/<?= h($p['file_path']) ?>" alt="<?= h($p['title']) ?>">
            </a>
            <div class="photo-info"><h4><?= h($p['title']) ?></h4></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
