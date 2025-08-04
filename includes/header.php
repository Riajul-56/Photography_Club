<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photography Club</title>
   <link rel="stylesheet" href="css/style.css">
    
    
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Photography Club</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="/index.php">Home</a></li>
                    <li><a href="/about.php">About</a></li>
                    <li><a href="/gallery.php">Gallery</a></li>
                    <li><a href="/events.php">Events</a></li>
                    <li><a href="/members.php">Members</a></li>
                    <?php if(isset($_SESSION['member_id'])): ?>
                        <li><a href="/dashboard/">Dashboard</a></li>
                        <li><a href="/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/login.php">Login</a></li>
                        <li><a href="/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">