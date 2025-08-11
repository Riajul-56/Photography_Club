<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/helpers.php';
redirect_if_not_logged_in();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $description = trim($_POST['description'] ?? '');
  $category = trim($_POST['category'] ?? 'other');

  if ($title === '') $errors[] = 'Title is required';

  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['photo'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif'];
    if (!in_array($ext, $allowed, true)) $errors[] = 'Only JPG, PNG, and GIF files are allowed';
    if ($file['size'] > 5 * 1024 * 1024) $errors[] = 'File size must be less than 5MB';

    if (!$errors) {
      $dir = __DIR__ . '/../uploads/photos/';
      if (!is_dir($dir)) mkdir($dir, 0755, true);
      $name = uniqid('ph_', true) . '.' . $ext;
      $abs = $dir . $name;
      if (move_uploaded_file($file['tmp_name'], $abs)) {
        $rel = 'photos/' . $name; // store relative to uploads/
        $stmt = $pdo->prepare("INSERT INTO photos (member_id, title, description, file_path, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['member_id'], $title, $description, $rel, $category]);
        $success = true;
      } else {
        $errors[] = 'Failed to upload file';
      }
    }
  } else {
    $errors[] = 'Photo is required';
  }
}
?>
<div class="dashboard">
  <aside class="sidebar">
    <div class="profile-summary">
      <img src="<?= base_url(); ?>uploads/profiles/<?= h($_SESSION['profile_pic'] ?? 'default.jpg') ?>" alt="Profile Picture">
      <h3><?= h($_SESSION['full_name'] ?? '') ?></h3>
      <p>@<?= h($_SESSION['username'] ?? '') ?></p>
    </div>
    <nav class="dashboard-nav">
      <ul>
        <li><a href="<?= base_url(); ?>dashboard/"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active"><a href="<?= base_url(); ?>dashboard/upload.php"><i class="fas fa-upload"></i> Upload Photo</a></li>
        <li><a href="<?= base_url(); ?>dashboard/profile.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="<?= base_url(); ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>
  </aside>
  <main class="dashboard-content">
    <h2>Upload Photo</h2>
    <?php if ($success): ?>
      <div class="alert alert-success">Photo uploaded successfully!</div>
    <?php elseif ($errors): ?>
      <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo '<li>'.h($e).'</li>'; ?></ul></div>
    <?php endif; ?>

    <form action="<?= base_url(); ?>dashboard/upload.php" method="POST" enctype="multipart/form-data">
      <div class="form-group"><label for="title">Title</label><input id="title" name="title" required></div>
      <div class="form-group"><label for="description">Description</label><textarea id="description" name="description" rows="4"></textarea></div>
      <div class="form-group">
        <label for="category">Category</label>
        <select id="category" name="category">
          <?php foreach (['nature','portrait','street','wildlife','macro','landscape','other'] as $c): ?>
            <option value="<?= $c ?>"><?= ucfirst($c) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group"><label for="photo">Photo</label><input type="file" id="photo" name="photo" accept="image/*" required></div>
      <button type="submit" class="btn">Upload</button>
    </form>
  </main>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
