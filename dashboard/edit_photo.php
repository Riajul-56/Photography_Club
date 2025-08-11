<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/helpers.php';
redirect_if_not_logged_in();

if (!isset($_GET['id'])) { header('Location: ' . base_url() . 'dashboard/'); exit; }
$photo_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM photos WHERE photo_id = ? AND member_id = ?");
$stmt->execute([$photo_id, $_SESSION['member_id']]);
$photo = $stmt->fetch();
if (!$photo) { header('Location: ' . base_url() . 'dashboard/'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $category = trim($_POST['category'] ?? 'other');
  $stmt = $pdo->prepare("UPDATE photos SET title = ?, description = ?, category = ? WHERE photo_id = ?");
  $stmt->execute([$title, $description, $category, $photo_id]);
  header('Location: ' . base_url() . 'photo.php?id=' . $photo_id); exit;
}
?>
<div class="dashboard">
  <aside class="sidebar">
    <nav class="dashboard-nav">
      <ul>
        <li><a href="<?= base_url(); ?>dashboard/"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active"><a href="#"><i class="fas fa-edit"></i> Edit Photo</a></li>
      </ul>
    </nav>
  </aside>
  <main class="dashboard-content">
    <h2>Edit Photo</h2>
    <form method="POST">
      <div class="form-group"><label for="title">Title</label><input id="title" name="title" value="<?= h($photo['title']) ?>" required></div>
      <div class="form-group"><label for="description">Description</label><textarea id="description" name="description"><?= h($photo['description']) ?></textarea></div>
      <div class="form-group">
        <label for="category">Category</label>
        <select id="category" name="category">
          <?php foreach (['nature','portrait','street','wildlife','macro','landscape','other'] as $c): ?>
            <option value="<?= $c ?>" <?= $c === $photo['category'] ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn">Save Changes</button>
    </form>
  </main>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
