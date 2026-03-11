<?php
require __DIR__ . '/includes/auth.php';
$title = 'Material Leads';

db()->exec("CREATE TABLE IF NOT EXISTS material_leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    material_id INT DEFAULT NULL,
    material_title VARCHAR(200),
    pdf_name VARCHAR(200),
    pdf_file VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

$leads = db()->query('SELECT * FROM material_leads ORDER BY created_at DESC')->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <h3>Material Leads</h3>
    <p class="form-note">Leads captured when users request PDF downloads.</p>
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
                <th>Material</th>
                <th>PDF</th>
                <th>Submitted</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$leads): ?>
                <tr><td colspan="7">No material leads yet.</td></tr>
            <?php else: ?>
                <?php foreach ($leads as $row): ?>
                    <tr>
                        <td><?= h($row['id']) ?></td>
                        <td><?= h($row['name']) ?></td>
                        <td><?= h($row['email']) ?></td>
                        <td><?= h($row['phone']) ?></td>
                        <td><?= h($row['material_title']) ?></td>
                        <td><?= h($row['pdf_name']) ?></td>
                        <td><?= h(date('d M Y, h:i A', strtotime($row['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
