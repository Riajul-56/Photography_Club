<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Get all members
$stmt = $pdo->query("SELECT * FROM members ORDER BY join_date DESC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="members-page">
    <h2>Our Members</h2>
    
    <div class="members-grid">
        <?php foreach ($members as $member): ?>
            <div class="member-card">
                <a href="/member.php?id=<?php echo $member['member_id']; ?>">
                    <img src="/uploads/profiles/<?php echo htmlspecialchars($member['profile_pic']); ?>" alt="<?php echo htmlspecialchars($member['full_name']); ?>">
                    <h3><?php echo htmlspecialchars($member['full_name']); ?></h3>
                    <p>@<?php echo htmlspecialchars($member['username']); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>