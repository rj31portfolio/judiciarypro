<?php
require __DIR__ . '/includes/auth.php';
$title = 'Contact Enquiries';

db()->exec("CREATE TABLE IF NOT EXISTS contact_enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

$enquiries = db()->query('SELECT * FROM contact_enquiries ORDER BY created_at DESC')->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <h3>Contact Enquiries</h3>
    <p class="form-note">Messages submitted from the contact form.</p>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Submitted</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$enquiries): ?>
                <tr><td colspan="6">No enquiries yet.</td></tr>
            <?php else: ?>
                <?php foreach ($enquiries as $row): ?>
                    <tr>
                        <td><?= h($row['id']) ?></td>
                        <td><?= h($row['name']) ?></td>
                        <td><?= h($row['email']) ?></td>
                        <td><?= h($row['subject']) ?></td>
                        <td><?= h($row['message']) ?></td>
                        <td><?= h(date('d M Y, h:i A', strtotime($row['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
