<?php
require __DIR__ . '/includes/auth.php';
$title = 'Student Signups';

$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = (int)db()->query('SELECT COUNT(*) FROM student_signups')->fetchColumn();
$totalPages = (int)ceil($total / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;
$stmt = db()->prepare('SELECT * FROM student_signups ORDER BY created_at DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$signups = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <div class="row">
        <div class="col-xs-12">
            <h3>Student Signups</h3>
            <p class="form-note">Demo OTP signups collected from the homepage banner form.</p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$signups): ?>
                    <tr><td colspan="5">No signups yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($signups as $signup): ?>
                        <tr>
                            <td><?= h($signup['name']) ?></td>
                            <td><?= h($signup['phone']) ?></td>
                            <td><?= h($signup['course']) ?></td>
                            <td><?= h($signup['created_at']) ?></td>
                            <td><a class="btn btn-xs btn-default" href="signup-view.php?id=<?= (int)$signup['id'] ?>">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?= pagination_links($page, $totalPages, 'signups.php') ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
