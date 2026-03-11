<?php
require __DIR__ . '/includes/auth.php';
$title = 'Signup Details';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$signup = null;

if ($id) {
    $stmt = db()->prepare('SELECT * FROM student_signups WHERE id = ?');
    $stmt->execute([$id]);
    $signup = $stmt->fetch();
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <?php if (!$signup): ?>
        <p>Signup not found.</p>
        <a class="btn btn-default" href="signups.php">Back</a>
    <?php else: ?>
        <h3>Signup Details</h3>
        <table class="table table-bordered">
            <tr><th>Name</th><td><?= h($signup['name']) ?></td></tr>
            <tr><th>Phone</th><td><?= h($signup['phone']) ?></td></tr>
            <tr><th>Email</th><td><?= h($signup['email']) ?></td></tr>
            <tr><th>Course</th><td><?= h($signup['course']) ?></td></tr>
            <tr><th>City</th><td><?= h($signup['city']) ?></td></tr>
            <tr><th>Message</th><td><?= nl2br(h($signup['message'])) ?></td></tr>
            <tr><th>Created</th><td><?= h($signup['created_at']) ?></td></tr>
        </table>
        <a class="btn btn-default" href="signups.php">Back</a>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
