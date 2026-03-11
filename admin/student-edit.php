<?php
require __DIR__ . '/includes/auth.php';
$title = 'Student Ranking Editor';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$student = [
    'student_name' => '',
    'rank_title' => '',
    'score' => '',
    'exam' => '',
    'year' => '',
    'photo' => '',
    'testimonial' => '',
    'status' => 'published',
];

/* FETCH STUDENT IF EDIT MODE */
if ($id) {

    $stmt = db()->prepare("SELECT * FROM student_rankings WHERE id=?");
    $stmt->execute([$id]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $student = $data;
    }
}

/* FORM SUBMIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student['student_name'] = trim($_POST['student_name'] ?? '');
    $student['rank_title'] = trim($_POST['rank_title'] ?? '');
    $student['score'] = trim($_POST['score'] ?? '');
    $student['exam'] = trim($_POST['exam'] ?? '');
    $student['year'] = trim($_POST['year'] ?? '');
    $student['testimonial'] = trim($_POST['testimonial'] ?? '');
    $student['status'] = $_POST['status'] ?? 'published';

    /* PHOTO UPLOAD */
    $photo = handle_upload('photo');

    if ($photo) {
        $student['photo'] = $photo;
    }

    /* UPDATE */
    if ($id) {

        $stmt = db()->prepare("
            UPDATE student_rankings
            SET student_name=?,
                rank_title=?,
                score=?,
                exam=?,
                year=?,
                photo=?,
                testimonial=?,
                status=?
            WHERE id=?
        ");

        $stmt->execute([
            $student['student_name'],
            $student['rank_title'],
            $student['score'],
            $student['exam'],
            $student['year'],
            $student['photo'],
            $student['testimonial'],
            $student['status'],
            $id
        ]);

    } else {

        /* INSERT */

        $stmt = db()->prepare("
            INSERT INTO student_rankings
            (student_name, rank_title, score, exam, year, photo, testimonial, status)
            VALUES (?,?,?,?,?,?,?,?)
        ");

        $stmt->execute([
            $student['student_name'],
            $student['rank_title'],
            $student['score'],
            $student['exam'],
            $student['year'],
            $student['photo'],
            $student['testimonial'],
            $student['status']
        ]);
    }

    header("Location: students.php");
    exit;
}

include __DIR__ . '/includes/header.php';
?>

<div class="admin-card">

<h3><?= $id ? 'Edit Student Ranking' : 'Add Student Ranking' ?></h3>

<form method="post" enctype="multipart/form-data">

<div class="row">

<div class="col-md-8">

<div class="form-group">
<label>Student Name</label>
<input type="text" name="student_name" class="form-control"
value="<?= h($student['student_name']) ?>" required>
</div>

<div class="form-group">
<label>Rank</label>
<input type="text" name="rank_title" class="form-control"
value="<?= h($student['rank_title']) ?>">
</div>

<div class="form-group">
<label>Exam</label>
<input type="text" name="exam" class="form-control"
value="<?= h($student['exam']) ?>">
</div>

<div class="form-group">
<label>Year (Optional)</label>
<input type="text" name="year" class="form-control"
value="<?= h($student['year']) ?>">
</div>

</div>

<div class="col-md-4">

<div class="form-group">

<label>Photo (250 × 300 px)</label>

<input type="file" name="photo" class="form-control">

<?php if ($student['photo']): ?>

<p style="margin-top:10px">
<img src="../uploads/<?= h($student['photo']) ?>"
style="width:120px">
</p>

<?php endif; ?>

</div>

<div class="form-group">

<label>Status</label>

<select name="status" class="form-control">

<option value="published"
<?= $student['status']=='published'?'selected':'' ?>>
Published
</option>

<option value="draft"
<?= $student['status']=='draft'?'selected':'' ?>>
Draft
</option>

</select>

</div>

<button type="submit" class="btn btn-primary">Save</button>

<a href="students.php" class="btn btn-default">Cancel</a>

</div>

</div>

</form>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>