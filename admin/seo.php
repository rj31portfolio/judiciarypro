<?php
require __DIR__ . '/includes/auth.php';
$title = 'SEO Settings';

$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = (int)db()->query('SELECT COUNT(*) FROM seo_meta')->fetchColumn();
$totalPages = (int)ceil($total / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;
$stmt = db()->prepare('SELECT * FROM seo_meta ORDER BY page_key LIMIT ? OFFSET ?');
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$seoRows = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <div class="clearfix">
        <h3 class="pull-left">SEO Settings</h3>
        <a class="btn btn-primary pull-right" href="seo-edit.php">Add Page</a>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Page Key</th>
            <th>Meta Title</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($seoRows as $row): ?>
            <tr>
                <td><?= h($row['page_key']) ?></td>
                <td><?= h($row['meta_title']) ?></td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" href="seo-edit.php?id=<?= (int)$row['id'] ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$seoRows): ?>
            <tr><td colspan="3">No SEO entries yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?= pagination_links($page, $totalPages, 'seo.php') ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
