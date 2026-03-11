<?php
require __DIR__ . '/includes/auth.php';
$title = 'Student Ranking';

/* DELETE STUDENT */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $stmt = db()->prepare("DELETE FROM student_rankings WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: students.php");
    exit;
}

/* PAGINATION */
$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));

$total = (int) db()->query("SELECT COUNT(*) FROM student_rankings")->fetchColumn();
$totalPages = ceil($total / $perPage);

if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $perPage;

/* FETCH DATA */
$stmt = db()->prepare("SELECT * FROM student_rankings ORDER BY created_at DESC LIMIT :limit OFFSET :offset");

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>

<div class="admin-card">

    <div class="clearfix">
        <h3 class="pull-left">Student Ranking</h3>

        <a class="btn btn-primary pull-right" href="student-edit.php">
            Add Student
        </a>
    </div>

    <table class="table table-striped">

        <thead>
        <tr>
            <th>Name</th>
            <th>Rank</th>
            <th>Exam</th>
            <th>Status</th>
            <th width="120">Action</th>
        </tr>
        </thead>

        <tbody>

        <?php if ($students): ?>

            <?php foreach ($students as $student): ?>

                <tr>

                    <td><?= h($student['student_name']) ?></td>

                    <td><?= h($student['rank_title']) ?></td>

                    <td><?= h($student['exam']) ?></td>

                    <td><?= h($student['status']) ?></td>

                    <td class="text-right">

                        <a class="btn btn-xs btn-default"
                           href="student-edit.php?id=<?= (int)$student['id'] ?>">
                            Edit
                        </a>

                        <a class="btn btn-xs btn-danger"
                           href="students.php?delete=<?= (int)$student['id'] ?>"
                           onclick="return confirm('Are you sure you want to delete this student?')">
                            Delete
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="5">No rankings added yet.</td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

    <!-- PAGINATION -->
    <?= pagination_links($page, $totalPages, 'students.php') ?>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>