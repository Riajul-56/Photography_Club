<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['member_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    
    // Validate inputs
    if (empty($title)) {
        $errors[] = 'Title is required';
    }
    
    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['photo'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            $errors[] = 'Only JPG, PNG, and GIF files are allowed';
        }
        
        if ($file['size'] > 5 * 1024 * 1024) { // 5MB
            $errors[] = 'File size must be less than 5MB';
        }
        
        if (empty($errors)) {
            $upload_dir = '..uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = uniqid() . '.' . $file_ext;
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                // Insert into database
                $stmt = $pdo->prepare("INSERT INTO photos (member_id, title, description, file_path, category) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_SESSION['member_id'],
                    $title,
                    $description,
                    $file_name,
                    $category
                ]);
                
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
            <img src="uploads/profiles/<?php echo htmlspecialchars($_SESSION['profile_pic'] ?? 'default.jpg'); ?>" alt="Profile Picture">
            <h3><?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
            <p>@<?php echo htmlspecialchars($_SESSION['username']); ?></p>
        </div>
        
        <nav class="dashboard-nav">
            <ul>
                <li><a href="dashboard/"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="active"><a href="dashboard/upload.php"><i class="fas fa-upload"></i> Upload Photo</a></li>
                <li><a href="dashboard/profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="dashboard-content">
        <h2>Upload Photo</h2>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                Photo uploaded successfully!
            </div>
        <?php elseif (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="dashboard/upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="nature">Nature</option>
                    <option value="portrait">Portrait</option>
                    <option value="street">Street</option>
                    <option value="wildlife">Wildlife</option>
                    <option value="macro">Macro</option>
                    <option value="landscape">Landscape</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
            </div>
            
            <button type="submit" class="btn">Upload</button>
        </form>
    </main>
</div>

<?php require_once 'includes/footer.php'; ?>