<?php
require __DIR__ . '/includes/auth.php';
$title = 'Courses';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = db()->prepare('DELETE FROM courses WHERE id = ?');
    $stmt->execute([$id]);
    redirect('admin/courses.php');
}

$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = (int)db()->query('SELECT COUNT(*) FROM courses')->fetchColumn();
$totalPages = (int)ceil($total / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;
$stmt = db()->prepare('SELECT * FROM courses ORDER BY created_at DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$courses = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <div class="clearfix">
        <h3 class="pull-left">Courses</h3>
        <a class="btn btn-primary pull-right" href="course-edit.php">Add Course</a>
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
        <?php foreach ($courses as $course): ?>
            <tr>
                <td><?= h($course['title']) ?></td>
                <td><?= h($course['category']) ?></td>
                <td><?= h($course['status']) ?></td>
                <td><?= h($course['updated_at']) ?></td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" href="course-edit.php?id=<?= (int)$course['id'] ?>">Edit</a>
                    <a class="btn btn-xs btn-danger" href="courses.php?delete=<?= (int)$course['id'] ?>" onclick="return confirm('Delete this course?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$courses): ?>
            <tr><td colspan="5">No courses added yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?= pagination_links($page, $totalPages, 'courses.php') ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
