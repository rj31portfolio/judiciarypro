<?php
require __DIR__ . '/includes/auth.php';
$title = 'Materials';

db()->exec("CREATE TABLE IF NOT EXISTS materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    summary TEXT,
    overview_html MEDIUMTEXT,
    detail_html MEDIUMTEXT,
    category VARCHAR(150),
    meta_title VARCHAR(200),
    meta_description TEXT,
    cover_image VARCHAR(255),
    images_json MEDIUMTEXT,
    pdfs_json MEDIUMTEXT,
    youtube_json MEDIUMTEXT,
    status ENUM('draft','published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB");

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = db()->prepare('DELETE FROM materials WHERE id = ?');
    $stmt->execute([$id]);
    redirect('admin/materials.php');
}

$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = (int)db()->query('SELECT COUNT(*) FROM materials')->fetchColumn();
$totalPages = (int)ceil($total / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;
$stmt = db()->prepare('SELECT * FROM materials ORDER BY created_at DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$materials = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <div class="clearfix">
        <h3 class="pull-left">Materials</h3>
        <a class="btn btn-primary pull-right" href="material-edit.php">Add Material</a>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Updated</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($materials as $material): ?>
            <tr>
                <td><?= h($material['title']) ?></td>
                <td><?= h($material['category']) ?></td>
                <td><?= h($material['status']) ?></td>
                <td><?= h($material['updated_at']) ?></td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" href="material-edit.php?id=<?= (int)$material['id'] ?>">Edit</a>
                    <a class="btn btn-xs btn-danger" href="materials.php?delete=<?= (int)$material['id'] ?>" onclick="return confirm('Delete this material?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$materials): ?>
            <tr><td colspan="5">No materials added yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?= pagination_links($page, $totalPages, 'materials.php') ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
