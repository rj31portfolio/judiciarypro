<?php
require __DIR__ . '/includes/auth.php';
$title = 'News Editor';

function ensure_news_slug($slug, $id = 0)
{
    $base = $slug;
    $i = 1;
    while (true) {
        $stmt = db()->prepare('SELECT id FROM news WHERE slug = ? AND id != ? LIMIT 1');
        $stmt->execute([$slug, $id]);
        if (!$stmt->fetch()) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
    }
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$item = [
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'image' => '',
    'author' => '',
    'published_at' => '',
    'status' => 'published',
];

if ($id) {
    $stmt = db()->prepare('SELECT * FROM news WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch() ?: $item;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item['title'] = trim($_POST['title'] ?? '');
    $item['slug'] = trim($_POST['slug'] ?? '');
    $item['excerpt'] = trim($_POST['excerpt'] ?? '');
    $item['content'] = trim($_POST['content'] ?? '');
    $item['author'] = trim($_POST['author'] ?? '');
    $item['published_at'] = $_POST['published_at'] ?? '';
    $item['status'] = $_POST['status'] ?? 'published';

    if ($item['slug'] === '') {
        $item['slug'] = slugify($item['title']);
    }
    $item['slug'] = ensure_news_slug($item['slug'], $id);

    $image = handle_upload('image');
    if ($image) {
        $item['image'] = $image;
    }

    if ($id) {
        $stmt = db()->prepare('UPDATE news SET title=?, slug=?, excerpt=?, content=?, image=?, author=?, published_at=?, status=? WHERE id=?');
        $stmt->execute([
            $item['title'], $item['slug'], $item['excerpt'], $item['content'], $item['image'],
            $item['author'], $item['published_at'], $item['status'], $id
        ]);
    } else {
        $stmt = db()->prepare('INSERT INTO news (title, slug, excerpt, content, image, author, published_at, status) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $item['title'], $item['slug'], $item['excerpt'], $item['content'], $item['image'],
            $item['author'], $item['published_at'], $item['status']
        ]);
    }

    redirect('admin/news.php');
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <h3><?= $id ? 'Edit News' : 'Add News' ?></h3>
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= h($item['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= h($item['slug']) ?>">
                    <p class="form-note">Leave blank to auto-generate from the title.</p>
                </div>
                <div class="form-group">
                    <label>Excerpt</label>
                    <textarea name="excerpt" class="form-control" rows="3"><?= h($item['excerpt']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" id="news-content" class="form-control" rows="10"><?= h($item['content']) ?></textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Author</label>
                    <input type="text" name="author" class="form-control" value="<?= h($item['author']) ?>">
                </div>
                <div class="form-group">
                    <label>Published At</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="<?= $item['published_at'] ? date('Y-m-d\TH:i', strtotime($item['published_at'])) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control">
                    <?php if ($item['image']): ?>
                        <p class="form-note">Current: <?= h($item['image']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="published" <?= $item['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="draft" <?= $item['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-default" href="news.php">Cancel</a>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    if (window.ClassicEditor) {
        ClassicEditor.create(document.querySelector('#news-content')).catch(function () {});
    }
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
