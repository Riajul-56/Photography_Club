<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Redirect if already logged in
if (isset($_SESSION['member_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = trim($_POST['full_name']);
    
    // Validate inputs
    if (empty($username)) {
        $errors[] = 'Username is required';
    } elseif (strlen($username) < 4) {
        $errors[] = 'Username must be at least 4 characters';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }
    
    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT * FROM members WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = 'Username or email already exists';
    }

    // Process registration if no errors
    if (empty($errors)) {
        try {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO members 
                                 (username, password, email, full_name, join_date, profile_pic) 
                                 VALUES (?, ?, ?, ?, CURDATE(), ?)");

            $default_profile_pic = 'default.jpg';
            $stmt->execute([
                $username,
                $hashed_password,
                $email,
                $full_name,
                $default_profile_pic
            ]);

            // YOUR SPECIFIC SUCCESS REDIRECT CODE
            $success = true;
            if ($success) {
                $_SESSION['success'] = 'Registration successful! Please login.';
                header('Location: login.php');
                exit;
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $errors[] = 'Registration failed. Please try again.';
        }
    }
}
?>

<section class="form-section">
    <h2>Register</h2>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            Registration successful! You can now <a href="/login.php">login</a>.
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
    
    <form action="register.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn">Register</button>
    </form>
    
    <p>Already have an account? <a href="login.php">Login here</a></p>
</section>

<?php require_once 'includes/footer.php'; ?>