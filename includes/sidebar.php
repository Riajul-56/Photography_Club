<div class="profile-summary">
    <img src="../uploads/profiles/<?php echo htmlspecialchars($_SESSION['profile_pic'] ?? 'default.jpg'); ?>" alt="Profile Picture">
    <h3><?php echo htmlspecialchars($_SESSION['full_name']); ?></h3>
    <p>@<?php echo htmlspecialchars($_SESSION['username']); ?></p>
</div>

<nav class="dashboard-nav">
    <ul>
        <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="upload.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'upload.php' ? 'active' : ''; ?>"><i class="fas fa-upload"></i> Upload Photo</a></li>
        <li><a href="events.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>"><i class="fas fa-calendar"></i> Events</a></li>
        <li><a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>