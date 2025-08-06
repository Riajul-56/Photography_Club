<?php
session_start();
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photography Club</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Photography Club</h1>
            </div>
            <button class="mobile-menu-toggle">☰</button>
            <nav>
                <ul>
                    <li><a href="<?php echo $base_url; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $base_url; ?>about.php">About</a></li>
                    <li><a href="<?php echo $base_url; ?>gallery.php">Gallery</a></li>
                    <li><a href="<?php echo $base_url; ?>events.php">Events</a></li>
                    <li><a href="<?php echo $base_url; ?>members.php">Members</a></li>
                    <?php if(isset($_SESSION['member_id'])): ?>
                        <li><a href="<?php echo $base_url; ?>dashboard/">Dashboard</a></li>
                        <li><a href="<?php echo $base_url; ?>logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo $base_url; ?>login.php">Login</a></li>
                        <li><a href="<?php echo $base_url; ?>register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">