<?php
require __DIR__ . '/includes/auth.php';
$title = 'Material Editor';

db()->exec("CREATE TABLE IF NOT EXISTS material_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    slug VARCHAR(180) NOT NULL UNIQUE,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

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

function ensure_material_slug($slug, $id = 0)
{
    $base = $slug;
    $i = 1;
    while (true) {
        $stmt = db()->prepare('SELECT id FROM materials WHERE slug = ? AND id != ? LIMIT 1');
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
$material = [
    'title' => '',
    'slug' => '',
    'summary' => '',
    'overview_html' => '',
    'detail_html' => '',
    'category' => '',
    'meta_title' => '',
    'meta_description' => '',
    'cover_image' => '',
    'images_json' => '',
    'pdfs_json' => '',
    'youtube_json' => '',
    'status' => 'published',
];

$categories = db()->query("SELECT name FROM material_categories WHERE status = 'active' ORDER BY name")->fetchAll();

if ($id) {
    $stmt = db()->prepare('SELECT * FROM materials WHERE id = ?');
    $stmt->execute([$id]);
    $material = $stmt->fetch() ?: $material;
}

$existingImages = decode_json_list($material['images_json']);
$existingPdfs = decode_json_list($material['pdfs_json']);
$existingVideos = decode_json_list($material['youtube_json']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $material['title'] = trim($_POST['title'] ?? '');
    $material['slug'] = trim($_POST['slug'] ?? '');
    $material['summary'] = trim($_POST['summary'] ?? '');
    $material['overview_html'] = trim($_POST['overview_html'] ?? '');
    $material['detail_html'] = trim($_POST['detail_html'] ?? '');
    $material['category'] = trim($_POST['category'] ?? '');
    $material['meta_title'] = trim($_POST['meta_title'] ?? '');
    $material['meta_description'] = trim($_POST['meta_description'] ?? '');
    $material['status'] = $_POST['status'] ?? 'published';

    if ($material['slug'] === '') {
        $material['slug'] = slugify($material['title']);
    }
    $material['slug'] = ensure_material_slug($material['slug'], $id);

    $cover = handle_upload('cover_image');
    if ($cover) {
        $material['cover_image'] = $cover;
    }

    $removeImages = $_POST['remove_images'] ?? [];
    if (!is_array($removeImages)) {
        $removeImages = [];
    }
    $keptImages = array_values(array_diff($existingImages, $removeImages));
    $newImages = handle_multi_upload('gallery_images', 'uploads');
    $allImages = array_slice(array_values(array_filter(array_merge($keptImages, $newImages))), 0, 3);
    $material['images_json'] = json_encode($allImages);

    $removePdfFiles = $_POST['remove_pdfs'] ?? [];
    if (!is_array($removePdfFiles)) {
        $removePdfFiles = [];
    }
    $keptPdfs = [];
    foreach ($existingPdfs as $pdf) {
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
    $material['pdfs_json'] = json_encode($keptPdfs);

    $removeVideos = $_POST['remove_videos'] ?? [];
    if (!is_array($removeVideos)) {
        $removeVideos = [];
    }
    $keptVideos = array_values(array_filter($existingVideos, function ($url) use ($removeVideos) {
        return $url !== '' && !in_array($url, $removeVideos, true);
    }));
    $newVideosInput = trim($_POST['youtube_links'] ?? '');
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
    $material['youtube_json'] = json_encode($keptVideos);

    if ($id) {
        $stmt = db()->prepare('UPDATE materials SET title=?, slug=?, summary=?, overview_html=?, detail_html=?, category=?, meta_title=?, meta_description=?, cover_image=?, images_json=?, pdfs_json=?, youtube_json=?, status=? WHERE id=?');
        $stmt->execute([
            $material['title'], $material['slug'], $material['summary'], $material['overview_html'],
            $material['detail_html'], $material['category'], $material['meta_title'], $material['meta_description'],
            $material['cover_image'], $material['images_json'], $material['pdfs_json'], $material['youtube_json'],
            $material['status'], $id
        ]);
    } else {
        $stmt = db()->prepare('INSERT INTO materials (title, slug, summary, overview_html, detail_html, category, meta_title, meta_description, cover_image, images_json, pdfs_json, youtube_json, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $material['title'], $material['slug'], $material['summary'], $material['overview_html'],
            $material['detail_html'], $material['category'], $material['meta_title'], $material['meta_description'],
            $material['cover_image'], $material['images_json'], $material['pdfs_json'], $material['youtube_json'],
            $material['status']
        ]);
        $id = (int)db()->lastInsertId();
    }

    redirect('admin/materials.php');
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <h3><?= $id ? 'Edit Material' : 'Add Material' ?></h3>
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= h($material['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= h($material['slug']) ?>">
                    <p class="form-note">Leave blank to auto-generate from the title.</p>
                </div>
                <div class="form-group">
                    <label>Summary</label>
                    <textarea name="summary" class="form-control" rows="3"><?= h($material['summary']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Overview (CKEditor)</label>
                    <textarea name="overview_html" id="material-overview" class="form-control" rows="8"><?= h($material['overview_html']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>More Information (CKEditor)</label>
                    <textarea name="detail_html" id="material-detail" class="form-control" rows="8"><?= h($material['detail_html']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="<?= h($material['meta_title']) ?>">
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2"><?= h($material['meta_description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>YouTube Links (one per line)</label>
                    <textarea name="youtube_links" class="form-control" rows="3" placeholder="https://www.youtube.com/watch?v=..."></textarea>
                    <?php if ($existingVideos): ?>
                        <div class="form-note" style="margin-top:10px;">
                            <strong>Existing Videos</strong>
                            <?php foreach ($existingVideos as $video): ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="remove_videos[]" value="<?= h($video) ?>"> Remove</label>
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
                            <option value="<?= h($row['name']) ?>" <?= $material['category'] === $row['name'] ? 'selected' : '' ?>>
                                <?= h($row['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cover Image</label>
                    <input type="file" name="cover_image" class="form-control">
                    <?php if ($material['cover_image']): ?>
                        <p class="form-note">Current: <?= h($material['cover_image']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Gallery Images (max 3)</label>
                    <input type="file" name="gallery_images[]" class="form-control" multiple>
                    <?php if ($existingImages): ?>
                        <div class="form-note" style="margin-top:10px;">
                            <strong>Existing Images</strong>
                            <?php foreach ($existingImages as $img): ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="remove_images[]" value="<?= h($img) ?>"> Remove</label>
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
                    <?php if ($existingPdfs): ?>
                        <div class="form-note" style="margin-top:10px;">
                            <strong>Existing PDFs</strong>
                            <?php foreach ($existingPdfs as $pdf): ?>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="remove_pdfs[]" value="<?= h($pdf['file'] ?? '') ?>"> Remove</label>
                                    <span><?= h($pdf['name'] ?? ($pdf['file'] ?? '')) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="published" <?= $material['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="draft" <?= $material['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-default" href="materials.php">Cancel</a>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    if (window.ClassicEditor) {
        ClassicEditor.create(document.querySelector('#material-overview')).catch(function () {});
        ClassicEditor.create(document.querySelector('#material-detail')).catch(function () {});
    }
    (function () {
        var btn = document.getElementById('add-pdf-row');
        var rows = document.getElementById('pdf-rows');
        if (!btn || !rows) return;
        btn.addEventListener('click', function () {
            var wrap = document.createElement('div');
            wrap.className = 'pdf-row';
            wrap.style.marginBottom = '8px';
            wrap.innerHTML = '<input type="text" name="pdf_name[]" class="form-control" placeholder="PDF Name" style="margin-bottom:6px;">' +
                '<input type="file" name="pdf_file[]" class="form-control" accept=".pdf">';
            rows.appendChild(wrap);
        });
    })();
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
