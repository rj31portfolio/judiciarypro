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

function decode_json_list($value)
{
    if (!$value) {
        return [];
    }
    $decoded = json_decode($value, true);
    return is_array($decoded) ? $decoded : [];
}

function save_uploaded_file($tmpPath, $originalName, $destDir)
{
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $safeExtension = preg_replace('/[^a-z0-9]/i', '', $extension);
    $filename = uniqid('upload_', true) . ($safeExtension ? '.' . $safeExtension : '');
    $targetDir = __DIR__ . '/../' . trim($destDir, '/');
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    $targetPath = $targetDir . '/' . $filename;
    if (!move_uploaded_file($tmpPath, $targetPath)) {
        return null;
    }
    return trim($destDir, '/') . '/' . $filename;
}

function handle_multi_upload($field, $destDir = 'uploads', array $allowedExt = [])
{
    if (!isset($_FILES[$field]) || !is_array($_FILES[$field]['name'])) {
        return [];
    }
    $uploaded = [];
    $count = count($_FILES[$field]['name']);
    for ($i = 0; $i < $count; $i++) {
        $error = $_FILES[$field]['error'][$i];
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($error !== UPLOAD_ERR_OK) {
            continue;
        }
        $name = $_FILES[$field]['name'][$i];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if ($allowedExt && !in_array($ext, $allowedExt, true)) {
            continue;
        }
        $tmp = $_FILES[$field]['tmp_name'][$i];
        $path = save_uploaded_file($tmp, $name, $destDir);
        if ($path) {
            $uploaded[] = $path;
        }
    }
    return $uploaded;
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
    'slider_one_json' => '',
    'slider_two_json' => '',
    'feature_video_url' => '',
    'pdfs_json' => '',
    'extra_youtube_json' => '',
    'is_featured' => 0,
    'status' => 'published',
];

$categories = db()->query("SELECT name FROM course_categories WHERE status = 'active' ORDER BY name")->fetchAll();

if ($id) {
    $stmt = db()->prepare('SELECT * FROM courses WHERE id = ?');
    $stmt->execute([$id]);
    $course = array_merge($course, $stmt->fetch() ?: []);
}

$_sliderOne = decode_json_list($course['slider_one_json'] ?? '');
$_sliderTwo = decode_json_list($course['slider_two_json'] ?? '');
$_pdfs = decode_json_list($course['pdfs_json'] ?? '');
$_extraVideos = decode_json_list($course['extra_youtube_json'] ?? '');

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
    $course['feature_video_url'] = trim($_POST['feature_video_url'] ?? '');
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

    $removeSliderOne = $_POST['remove_slider_one'] ?? [];
    if (!is_array($removeSliderOne)) {
        $removeSliderOne = [];
    }
    $keptSliderOne = array_values(array_diff($_sliderOne, $removeSliderOne));
    $newSliderOne = handle_multi_upload('slider_one_images', 'uploads');
    $course['slider_one_json'] = json_encode(array_slice(array_values(array_filter(array_merge($keptSliderOne, $newSliderOne))), 0, 6));

    $removeSliderTwo = $_POST['remove_slider_two'] ?? [];
    if (!is_array($removeSliderTwo)) {
        $removeSliderTwo = [];
    }
    $keptSliderTwo = array_values(array_diff($_sliderTwo, $removeSliderTwo));
    $newSliderTwo = handle_multi_upload('slider_two_images', 'uploads');
    $course['slider_two_json'] = json_encode(array_slice(array_values(array_filter(array_merge($keptSliderTwo, $newSliderTwo))), 0, 6));

    $removePdfFiles = $_POST['remove_pdfs'] ?? [];
    if (!is_array($removePdfFiles)) {
        $removePdfFiles = [];
    }
    $keptPdfs = [];
    foreach ($_pdfs as $pdf) {
        if (!isset($pdf['file']) || in_array($pdf['file'], $removePdfFiles, true)) {
            continue;
        }
        $keptPdfs[] = $pdf;
    }
    $pdfNames = $_POST['pdf_name'] ?? [];
    if (isset($_FILES['pdf_file']) && is_array($_FILES['pdf_file']['name'])) {
        $count = count($_FILES['pdf_file']['name']);
        for ($i = 0; $i < $count; $i++) {
            $error = $_FILES['pdf_file']['error'][$i];
            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if ($error !== UPLOAD_ERR_OK) {
                continue;
            }
            $original = $_FILES['pdf_file']['name'][$i];
            $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                continue;
            }
            $tmp = $_FILES['pdf_file']['tmp_name'][$i];
            $pdfPath = save_uploaded_file($tmp, $original, 'uploads');
            if (!$pdfPath) {
                continue;
            }
            $label = '';
            if (is_array($pdfNames) && isset($pdfNames[$i])) {
                $label = trim($pdfNames[$i]);
            }
            if ($label === '') {
                $label = basename($pdfPath);
            }
            $keptPdfs[] = [
                'name' => $label,
                'file' => $pdfPath,
            ];
        }
    }
    $course['pdfs_json'] = json_encode($keptPdfs);

    $removeVideos = $_POST['remove_extra_videos'] ?? [];
    if (!is_array($removeVideos)) {
        $removeVideos = [];
    }
    $keptVideos = array_values(array_filter($_extraVideos, function ($url) use ($removeVideos) {
        return $url !== '' && !in_array($url, $removeVideos, true);
    }));
    $newVideosInput = trim($_POST['extra_youtube_links'] ?? '');
    if ($newVideosInput !== '') {
        $lines = preg_split('/\r\n|\r|\n/', $newVideosInput);
        foreach ($lines as $line) {
            $link = trim($line);
            if ($link !== '') {
                $keptVideos[] = $link;
            }
        }
    }
    $keptVideos = array_values(array_unique($keptVideos));
    $course['extra_youtube_json'] = json_encode($keptVideos);

    if ($id) {
        $stmt = db()->prepare('UPDATE courses SET title=?, slug=?, summary=?, description=?, category=?, price=?, students_count=?, comments_count=?, lectures=?, quizzes=?, duration=?, skill_level=?, language=?, assessments=?, author_name=?, author_title=?, author_image=?, image=?, slider_one_json=?, slider_two_json=?, feature_video_url=?, pdfs_json=?, extra_youtube_json=?, is_featured=?, status=? WHERE id=?');
        $stmt->execute([
            $course['title'], $course['slug'], $course['summary'], $course['description'], $course['category'],
            $course['price'], $course['students_count'], $course['comments_count'], $course['lectures'],
            $course['quizzes'], $course['duration'], $course['skill_level'], $course['language'],
            $course['assessments'], $course['author_name'], $course['author_title'], $course['author_image'],
            $course['image'], $course['slider_one_json'], $course['slider_two_json'], $course['feature_video_url'],
            $course['pdfs_json'], $course['extra_youtube_json'], $course['is_featured'],
            $course['status'], $id
        ]);
    } else {
        $stmt = db()->prepare('INSERT INTO courses (title, slug, summary, description, category, price, students_count, comments_count, lectures, quizzes, duration, skill_level, language, assessments, author_name, author_title, author_image, image, slider_one_json, slider_two_json, feature_video_url, pdfs_json, extra_youtube_json, is_featured, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $course['title'], $course['slug'], $course['summary'], $course['description'], $course['category'],
            $course['price'], $course['students_count'], $course['comments_count'], $course['lectures'],
            $course['quizzes'], $course['duration'], $course['skill_level'], $course['language'],
            $course['assessments'], $course['author_name'], $course['author_title'], $course['author_image'],
            $course['image'], $course['slider_one_json'], $course['slider_two_json'], $course['feature_video_url'],
            $course['pdfs_json'], $course['extra_youtube_json'], $course['is_featured'],
            $course['status']
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
                <div class="form-group">
                    <label>Feature YouTube Video Link</label>
                    <input type="text" name="feature_video_url" class="form-control" value="<?= h($course['feature_video_url']) ?>" placeholder="https://www.youtube.com/watch?v=...">
                </div>
                <div class="form-group">
                    <label>Extra YouTube Videos (one per line)</label>
                    <textarea name="extra_youtube_links" class="form-control" rows="3" placeholder="https://www.youtube.com/watch?v=..."></textarea>
                    <?php if ($_extraVideos): ?>
                        <div class="form-note" style="margin-top:10px;">
                            <strong>Existing Videos</strong>
                            <?php foreach ($_extraVideos as $video): ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="remove_extra_videos[]" value="<?= h($video) ?>"> Remove</label>
                                    <span><?= h($video) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
                <div class="form-group">
                    <label>Slider One Images (max 6)</label>
                    <input type="file" name="slider_one_images[]" class="form-control" multiple>
                    <?php if ($_sliderOne): ?>
                        <div class="form-note" style="margin-top:10px;">
                            <strong>Existing Slider One</strong>
                            <?php foreach ($_sliderOne as $img): ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="remove_slider_one[]" value="<?= h($img) ?>"> Remove</label>
                                    <span><?= h($img) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Slider Two Images (max 6)</label>
                    <input type="file" name="slider_two_images[]" class="form-control" multiple>
                    <?php if ($_sliderTwo): ?>
                        <div class="form-note" style="margin-top:10px;">
                            <strong>Existing Slider Two</strong>
                            <?php foreach ($_sliderTwo as $img): ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="remove_slider_two[]" value="<?= h($img) ?>"> Remove</label>
                                    <span><?= h($img) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>PDF Attachments</label>
                    <div id="pdf-rows">
                        <div class="pdf-row" style="margin-bottom:8px;">
                            <input type="text" name="pdf_name[]" class="form-control" placeholder="PDF Name" style="margin-bottom:6px;">
                            <input type="file" name="pdf_file[]" class="form-control" accept=".pdf">
                        </div>
                    </div>
                    <button type="button" class="btn btn-default btn-xs" id="add-pdf-row">Add Another PDF</button>
                    <?php if ($_pdfs): ?>
                        <div class="form-note" style="margin-top:10px;">
                            <strong>Existing PDFs</strong>
                            <?php foreach ($_pdfs as $pdf): ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="remove_pdfs[]" value="<?= h($pdf['file'] ?? '') ?>"> Remove</label>
                                    <span><?= h($pdf['name'] ?? ($pdf['file'] ?? '')) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
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
    (function () {
        var pdfBtn = document.getElementById('add-pdf-row');
        var pdfRows = document.getElementById('pdf-rows');
        if (pdfBtn && pdfRows) {
            pdfBtn.addEventListener('click', function () {
                var wrap = document.createElement('div');
                wrap.className = 'pdf-row';
                wrap.style.marginBottom = '8px';
                wrap.innerHTML = '<input type="text" name="pdf_name[]" class="form-control" placeholder="PDF Name" style="margin-bottom:6px;">' +
                    '<input type="file" name="pdf_file[]" class="form-control" accept=".pdf">';
                pdfRows.appendChild(wrap);
            });
        }
    })();
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
