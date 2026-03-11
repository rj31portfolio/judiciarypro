<?php
require __DIR__ . '/includes/auth.php';
$title = 'Events';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = db()->prepare('DELETE FROM events WHERE id = ?');
    $stmt->execute([$id]);
    redirect('admin/events.php');
}

$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = (int)db()->query('SELECT COUNT(*) FROM events')->fetchColumn();
$totalPages = (int)ceil($total / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;
$stmt = db()->prepare('SELECT * FROM events ORDER BY event_date DESC, created_at DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$events = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <div class="clearfix">
        <h3 class="pull-left">Events</h3>
        <a class="btn btn-primary pull-right" href="event-edit.php">Add Event</a>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Location</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?= h($event['title']) ?></td>
                <td><?= h($event['event_date']) ?></td>
                <td><?= h($event['location']) ?></td>
                <td><?= h($event['status']) ?></td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" href="event-edit.php?id=<?= (int)$event['id'] ?>">Edit</a>
                    <a class="btn btn-xs btn-danger" href="events.php?delete=<?= (int)$event['id'] ?>" onclick="return confirm('Delete this event?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$events): ?>
            <tr><td colspan="5">No events added yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?= pagination_links($page, $totalPages, 'events.php') ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
