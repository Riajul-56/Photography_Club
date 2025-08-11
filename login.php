<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '') $errors[] = 'Username is required';
  if ($password === '') $errors[] = 'Password is required';

  if (!$errors) {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['member_id'] = $user['member_id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['full_name'] = $user['full_name'];
      $_SESSION['profile_pic'] = $user['profile_pic'];
      header('Location: dashboard/'); exit;
    } else {
      $errors[] = 'Invalid username or password';
    }
  }
}
?>
<section class="form-section">
  <h2>Login</h2>
  <?php if ($errors): ?>
    <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo '<li>'.h($e).'</li>'; ?></ul></div>
  <?php endif; ?>
  <form action="login.php" method="POST">
    <div class="form-group"><label for="username">Username</label><input id="username" name="username" required></div>
    <div class="form-group"><label for="password">Password</label><input type="password" id="password" name="password" required></div>
    <button type="submit" class="btn">Login</button>
  </form>
  <p>Don't have an account? <a href="register.php">Register here</a></p>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
