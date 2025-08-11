<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm_password'] ?? '';
  $full_name = trim($_POST['full_name'] ?? '');

  if ($username === '' || strlen($username) < 4) $errors[] = 'Username must be at least 4 characters';
  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
  if ($full_name === '') $errors[] = 'Full name is required';
  if ($password === '' || strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
  if ($password !== $confirm) $errors[] = 'Passwords do not match';

  $stmt = $pdo->prepare("SELECT 1 FROM members WHERE username = ? OR email = ?");
  $stmt->execute([$username, $email]);
  if ($stmt->fetchColumn()) $errors[] = 'Username or email already exists';

  if (!$errors) {
    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $pdo->prepare("
      INSERT INTO members (username, email, password, full_name, profile_pic) 
      VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$username, $email, $hash, $full_name, 'default.jpg']);
    $_SESSION['success'] = 'Registration successful! Please login.';
    header('Location: login.php'); exit;
  }
}
?>
<section class="form-section">
  <h2>Register</h2>
  <?php if ($errors): ?>
    <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo '<li>'.h($e).'</li>'; ?></ul></div>
  <?php endif; ?>
  <form action="register.php" method="POST">
    <div class="form-group"><label for="username">Username</label><input id="username" name="username" required></div>
    <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
    <div class="form-group"><label for="full_name">Full Name</label><input id="full_name" name="full_name" required></div>
    <div class="form-group"><label for="password">Password</label><input type="password" id="password" name="password" required></div>
    <div class="form-group"><label for="confirm_password">Confirm Password</label><input type="password" id="confirm_password" name="confirm_password" required></div>
    <button type="submit" class="btn">Register</button>
  </form>
  <p>Already have an account? <a href="login.php">Login here</a></p>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
