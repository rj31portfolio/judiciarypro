<?php
require __DIR__ . '/includes/auth.php';
$title = 'News';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = db()->prepare('DELETE FROM news WHERE id = ?');
    $stmt->execute([$id]);
    redirect('admin/news.php');
}

$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = (int)db()->query('SELECT COUNT(*) FROM news')->fetchColumn();
$totalPages = (int)ceil($total / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;
$stmt = db()->prepare('SELECT * FROM news ORDER BY published_at DESC, created_at DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$news = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <div class="clearfix">
        <h3 class="pull-left">News</h3>
        <a class="btn btn-primary pull-right" href="news-edit.php">Add News</a>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Title</th>
            <th>Published</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($news as $item): ?>
            <tr>
                <td><?= h($item['title']) ?></td>
                <td><?= h($item['published_at']) ?></td>
                <td><?= h($item['status']) ?></td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" href="news-edit.php?id=<?= (int)$item['id'] ?>">Edit</a>
                    <a class="btn btn-xs btn-danger" href="news.php?delete=<?= (int)$item['id'] ?>" onclick="return confirm('Delete this news item?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$news): ?>
            <tr><td colspan="4">No news added yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?= pagination_links($page, $totalPages, 'news.php') ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
