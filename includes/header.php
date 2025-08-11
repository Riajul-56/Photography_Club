<?php
// includes/header.php
require_once __DIR__ . '/helpers.php';
$BASE = base_url();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Photography Club</title>
  <link rel="stylesheet" href="<?= $BASE ?>assets/css/style.css?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
  <header>
    <div class="container">
      <div class="logo">
        <h1><a style="color:white;text-decoration:none" href="<?= $BASE ?>">Photography Club</a></h1>
      </div>
      <nav id="main-nav">
        <ul>
          <li><a href="<?= $BASE ?>">Home</a></li>
          <li><a href="<?= $BASE ?>about.php">About</a></li>
          <li><a href="<?= $BASE ?>gallery.php">Gallery</a></li>
          <li><a href="<?= $BASE ?>events.php">Events</a></li>
          <li><a href="<?= $BASE ?>members.php">Members</a></li>
          <?php if (is_logged_in()): ?>
            <li><a href="<?= $BASE ?>dashboard/">Dashboard</a></li>
            <li><a href="<?= $BASE ?>logout.php">Logout</a></li>
          <?php else: ?>
            <li><a href="<?= $BASE ?>login.php">Login</a></li>
            <li><a href="<?= $BASE ?>register.php">Register</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>
  <main class="container">