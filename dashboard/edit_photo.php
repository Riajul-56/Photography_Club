<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['member_id']) || !isset($_GET['id'])) {
    header('Location: login.php');
    exit;
}

$photo_id = $_GET['id'];

// Fetch photo details
$stmt = $pdo->prepare("SELECT * FROM photos WHERE photo_id = ? AND member_id = ?");
$stmt->execute([$photo_id, $_SESSION['member_id']]);
$photo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$photo) {
    header('Location: dashboard/');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);

    $stmt = $pdo->prepare("UPDATE photos SET title = ?, description = ?, category = ? WHERE photo_id = ?");
    $stmt->execute([$title, $description, $category, $photo_id]);
    header('Location: /photo.php?id=' . $photo_id);
    exit;
}
?>

<div class="dashboard">
    <aside class="sidebar">
        <!-- Sidebar content from dashboard/index.php -->
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

<?php require_once 'includes/footer.php'; ?>