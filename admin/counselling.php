<?php
require __DIR__ . '/includes/auth.php';
$title = 'Counselling Requests';

db()->exec("CREATE TABLE IF NOT EXISTS counselling_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150),
    phone VARCHAR(30) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

$requests = db()->query('SELECT * FROM counselling_requests ORDER BY created_at DESC')->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <h3>Counselling Requests</h3>
    <p class="form-note">Latest counselling requests from the homepage form.</p>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Submitted</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$requests): ?>
                <tr><td colspan="5">No counselling requests yet.</td></tr>
            <?php else: ?>
                <?php foreach ($requests as $row): ?>
                    <tr>
                        <td><?= h($row['id']) ?></td>
                        <td><?= h($row['name']) ?></td>
                        <td><?= h($row['email']) ?></td>
                        <td><?= h($row['phone']) ?></td>
                        <td><?= h(date('d M Y, h:i A', strtotime($row['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
