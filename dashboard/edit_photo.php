<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['member_id']) || !isset($_GET['id'])) {
    header('Location: ../login.php');
    exit;
}

$photo_id = $_GET['id'];

// Fetch photo details
$stmt = $pdo->prepare("SELECT * FROM photos WHERE photo_id = ? AND member_id = ?");
$stmt->execute([$photo_id, $_SESSION['member_id']]);
$photo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$photo) {
    header('Location: ../dashboard/');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);

    $stmt = $pdo->prepare("UPDATE photos SET title = ?, description = ?, category = ? WHERE photo_id = ?");
    $stmt->execute([$title, $description, $category, $photo_id]);
    header('Location: ../photo.php?id=' . $photo_id);
    exit;
}
?>

<div class="dashboard">
    <aside class="sidebar">
        <div class="profile-summary">
            <img src="<?php echo $base_url; ?>uploads/profiles/<?php echo htmlspecialchars($_SESSION['profile_pic'] ?? 'default.jpg'); ?>" alt="Profile Picture">
            <h3><?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
            <p>@<?php echo htmlspecialchars($_SESSION['username']); ?></p>
        </div>
        
        <nav class="dashboard-nav">
            <ul>
                <li><a href="<?php echo $base_url; ?>dashboard/"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="<?php echo $base_url; ?>dashboard/upload.php"><i class="fas fa-upload"></i> Upload Photo</a></li>
                <li class="active"><a href="<?php echo $base_url; ?>dashboard/profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="<?php echo $base_url; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="dashboard-content">
        <h2>Edit Photo</h2>
        <form method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($photo['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?= htmlspecialchars($photo['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <?php
                    $categories = ['nature', 'portrait', 'street', 'wildlife', 'macro', 'landscape', 'other'];
                    foreach ($categories as $cat) {
                        $selected = $cat === $photo['category'] ? 'selected' : '';
                        echo "<option value='$cat' $selected>" . ucfirst($cat) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>