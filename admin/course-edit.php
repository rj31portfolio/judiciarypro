<?php
require __DIR__ . '/includes/auth.php';
$title = 'Course Editor';

function ensure_unique_slug($slug, $id = 0)
{
    $base = $slug;
    $i = 1;
    while (true) {
        $stmt = db()->prepare('SELECT id FROM courses WHERE slug = ? AND id != ? LIMIT 1');
        $stmt->execute([$slug, $id]);
        if (!$stmt->fetch()) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
    }
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$course = [
    'title' => '',
    'slug' => '',
    'summary' => '',
    'description' => '',
    'category' => '',
    'price' => '0.00',
    'students_count' => 0,
    'comments_count' => 0,
    'lectures' => 7,
    'quizzes' => 1,
    'duration' => '33 hours',
    'skill_level' => 'Beginner',
    'language' => 'English',
    'assessments' => 'Self',
    'author_name' => '',
    'author_title' => '',
    'author_image' => '',
    'image' => '',
    'is_featured' => 0,
    'status' => 'published',
];

$categories = db()->query("SELECT name FROM course_categories WHERE status = 'active' ORDER BY name")->fetchAll();

if ($id) {
    $stmt = db()->prepare('SELECT * FROM courses WHERE id = ?');
    $stmt->execute([$id]);
    $course = $stmt->fetch() ?: $course;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course['title'] = trim($_POST['title'] ?? '');
    $course['slug'] = trim($_POST['slug'] ?? '');
    $course['summary'] = trim($_POST['summary'] ?? '');
    $course['description'] = trim($_POST['description'] ?? '');
    $course['category'] = trim($_POST['category'] ?? '');
    $course['price'] = (float)($_POST['price'] ?? 0);
    $course['students_count'] = (int)($_POST['students_count'] ?? 0);
    $course['comments_count'] = (int)($_POST['comments_count'] ?? 0);
    $course['lectures'] = (int)($_POST['lectures'] ?? 0);
    $course['quizzes'] = (int)($_POST['quizzes'] ?? 0);
    $course['duration'] = trim($_POST['duration'] ?? '');
    $course['skill_level'] = trim($_POST['skill_level'] ?? '');
    $course['language'] = trim($_POST['language'] ?? '');
    $course['assessments'] = trim($_POST['assessments'] ?? '');
    $course['author_name'] = trim($_POST['author_name'] ?? '');
    $course['author_title'] = trim($_POST['author_title'] ?? '');
    $course['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
    $course['status'] = $_POST['status'] ?? 'published';

    if ($course['slug'] === '') {
        $course['slug'] = slugify($course['title']);
    }
    $course['slug'] = ensure_unique_slug($course['slug'], $id);

    $image = handle_upload('image');
    if ($image) {
        $course['image'] = $image;
    }
    $authorImage = handle_upload('author_image');
    if ($authorImage) {
        $course['author_image'] = $authorImage;
    }

    if ($id) {
        $stmt = db()->prepare('UPDATE courses SET title=?, slug=?, summary=?, description=?, category=?, price=?, students_count=?, comments_count=?, lectures=?, quizzes=?, duration=?, skill_level=?, language=?, assessments=?, author_name=?, author_title=?, author_image=?, image=?, is_featured=?, status=? WHERE id=?');
        $stmt->execute([
            $course['title'], $course['slug'], $course['summary'], $course['description'], $course['category'],
            $course['price'], $course['students_count'], $course['comments_count'], $course['lectures'],
            $course['quizzes'], $course['duration'], $course['skill_level'], $course['language'],
            $course['assessments'], $course['author_name'], $course['author_title'], $course['author_image'],
            $course['image'], $course['is_featured'], $course['status'], $id
        ]);
    } else {
        $stmt = db()->prepare('INSERT INTO courses (title, slug, summary, description, category, price, students_count, comments_count, lectures, quizzes, duration, skill_level, language, assessments, author_name, author_title, author_image, image, is_featured, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $course['title'], $course['slug'], $course['summary'], $course['description'], $course['category'],
            $course['price'], $course['students_count'], $course['comments_count'], $course['lectures'],
            $course['quizzes'], $course['duration'], $course['skill_level'], $course['language'],
            $course['assessments'], $course['author_name'], $course['author_title'], $course['author_image'],
            $course['image'], $course['is_featured'], $course['status']
        ]);
        $id = (int)db()->lastInsertId();
    }

    redirect('admin/courses.php');
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <h3><?= $id ? 'Edit Course' : 'Add Course' ?></h3>
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= h($course['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= h($course['slug']) ?>">
                    <p class="form-note">Leave blank to auto-generate from the title.</p>
                </div>
                <div class="form-group">
                    <label>Summary</label>
                    <textarea name="summary" class="form-control" rows="3"><?= h($course['summary']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="course-description" class="form-control" rows="10"><?= h($course['description']) ?></textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        <option value="">Select category</option>
                        <?php foreach ($categories as $row): ?>
                            <option value="<?= h($row['name']) ?>" <?= $course['category'] === $row['name'] ? 'selected' : '' ?>>
                                <?= h($row['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?= h($course['price']) ?>">
                </div>
                <div class="form-group">
                    <label>Students Count</label>
                    <input type="number" name="students_count" class="form-control" value="<?= h($course['students_count']) ?>">
                </div>
                <div class="form-group">
                    <label>Comments Count</label>
                    <input type="number" name="comments_count" class="form-control" value="<?= h($course['comments_count']) ?>">
                </div>
                <div class="form-group">
                    <label>Lectures</label>
                    <input type="number" name="lectures" class="form-control" value="<?= h($course['lectures']) ?>">
                </div>
                <div class="form-group">
                    <label>Quizzes</label>
                    <input type="number" name="quizzes" class="form-control" value="<?= h($course['quizzes']) ?>">
                </div>
                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" name="duration" class="form-control" value="<?= h($course['duration']) ?>" placeholder="33 hours">
                </div>
                <div class="form-group">
                    <label>Skill Level</label>
                    <input type="text" name="skill_level" class="form-control" value="<?= h($course['skill_level']) ?>" placeholder="Beginner">
                </div>
                <div class="form-group">
                    <label>Language</label>
                    <input type="text" name="language" class="form-control" value="<?= h($course['language']) ?>" placeholder="English">
                </div>
                <div class="form-group">
                    <label>Assessments</label>
                    <input type="text" name="assessments" class="form-control" value="<?= h($course['assessments']) ?>" placeholder="Self">
                </div>
                <div class="form-group">
                    <label>Author Name</label>
                    <input type="text" name="author_name" class="form-control" value="<?= h($course['author_name']) ?>">
                </div>
                <div class="form-group">
                    <label>Author Title</label>
                    <input type="text" name="author_title" class="form-control" value="<?= h($course['author_title']) ?>">
                </div>
                <div class="form-group">
                    <label>Author Image</label>
                    <input type="file" name="author_image" class="form-control">
                    <?php if ($course['author_image']): ?>
                        <p class="form-note">Current: <?= h($course['author_image']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Course Image</label>
                    <input type="file" name="image" class="form-control">
                    <?php if ($course['image']): ?>
                        <p class="form-note">Current: <?= h($course['image']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" name="is_featured" <?= $course['is_featured'] ? 'checked' : '' ?>> Featured</label>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="published" <?= $course['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="draft" <?= $course['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-default" href="courses.php">Cancel</a>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    if (window.ClassicEditor) {
        ClassicEditor.create(document.querySelector('#course-description')).catch(function () {});
    }
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
