<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

// Redirect if not logged in
redirect_if_not_logged_in();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_pic'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Allowed file types
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            $errors[] = 'Only JPG, PNG, and GIF files are allowed';
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            $errors[] = 'File size must be less than 5MB';
        }

        if (!$errors) {
            $dir = __DIR__ . '/../uploads/profiles/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            // Generate a unique name for the file
            $new_name = uniqid('profile_', true) . '.' . $ext;
            $abs = $dir . $new_name;

            // Move the uploaded file
            if (move_uploaded_file($file['tmp_name'], $abs)) {
                $stmt = $pdo->prepare("UPDATE members SET profile_pic = ? WHERE member_id = ?");
                $stmt->execute([$new_name, $_SESSION['member_id']]);
                $_SESSION['profile_pic'] = $new_name; // Update session with new profile pic

                $success = true;
            } else {
                $errors[] = 'Failed to upload profile picture';
            }
        }
    } else {
        $errors[] = 'No file uploaded';
    }
}
header('Location: profile.php');
exit;
