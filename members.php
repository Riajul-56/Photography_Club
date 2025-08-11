<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$members = $pdo->query("SELECT member_id, full_name, username, profile_pic FROM members ORDER BY join_date DESC")->fetchAll();
?>
<section class="members-page">
  <h2>Our Members</h2>
  <div class="members-grid">
    <?php foreach ($members as $m): ?>
      <div class="member-card">
        <a href="member.php?id=<?= (int)$m['member_id'] ?>">
          <img src="uploads/profiles/<?= h($m['profile_pic']) ?>" alt="<?= h($m['full_name']) ?>">
          <h3><?= h($m['full_name']) ?></h3>
          <p>@<?= h($m['username']) ?></p>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
