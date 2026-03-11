<?php
require __DIR__ . '/includes/auth.php';
$title = 'SEO Editor';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$seo = [
    'page_key' => '',
    'meta_title' => '',
    'meta_description' => '',
    'meta_keywords' => '',
    'og_title' => '',
    'og_description' => '',
    'og_image' => '',
    'twitter_title' => '',
    'twitter_description' => '',
    'twitter_image' => '',
    'canonical_url' => '',
    'robots' => '',
];

if ($id) {
    $stmt = db()->prepare('SELECT * FROM seo_meta WHERE id = ?');
    $stmt->execute([$id]);
    $seo = $stmt->fetch() ?: $seo;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($seo as $key => $value) {
        $seo[$key] = trim($_POST[$key] ?? '');
    }

    if ($id) {
        $stmt = db()->prepare('UPDATE seo_meta SET page_key=?, meta_title=?, meta_description=?, meta_keywords=?, og_title=?, og_description=?, og_image=?, twitter_title=?, twitter_description=?, twitter_image=?, canonical_url=?, robots=? WHERE id=?');
        $stmt->execute([
            $seo['page_key'], $seo['meta_title'], $seo['meta_description'], $seo['meta_keywords'],
            $seo['og_title'], $seo['og_description'], $seo['og_image'], $seo['twitter_title'],
            $seo['twitter_description'], $seo['twitter_image'], $seo['canonical_url'], $seo['robots'], $id
        ]);
    } else {
        $stmt = db()->prepare('INSERT INTO seo_meta (page_key, meta_title, meta_description, meta_keywords, og_title, og_description, og_image, twitter_title, twitter_description, twitter_image, canonical_url, robots) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $seo['page_key'], $seo['meta_title'], $seo['meta_description'], $seo['meta_keywords'],
            $seo['og_title'], $seo['og_description'], $seo['og_image'], $seo['twitter_title'],
            $seo['twitter_description'], $seo['twitter_image'], $seo['canonical_url'], $seo['robots']
        ]);
    }

    redirect('admin/seo.php');
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <h3><?= $id ? 'Edit SEO' : 'Add SEO Page' ?></h3>
    <form method="post">
        <div class="form-group">
            <label>Page Key</label>
            <input type="text" name="page_key" class="form-control" value="<?= h($seo['page_key']) ?>" required>
            <p class="form-note">Example: home, about, courses, events, news, contact.</p>
        </div>
        <div class="form-group">
            <label>Meta Title</label>
            <input type="text" name="meta_title" class="form-control" value="<?= h($seo['meta_title']) ?>">
        </div>
        <div class="form-group">
            <label>Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="3"><?= h($seo['meta_description']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Meta Keywords</label>
            <textarea name="meta_keywords" class="form-control" rows="2"><?= h($seo['meta_keywords']) ?></textarea>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>OG Title</label>
                    <input type="text" name="og_title" class="form-control" value="<?= h($seo['og_title']) ?>">
                </div>
                <div class="form-group">
                    <label>OG Description</label>
                    <textarea name="og_description" class="form-control" rows="3"><?= h($seo['og_description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>OG Image URL</label>
                    <input type="text" name="og_image" class="form-control" value="<?= h($seo['og_image']) ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Twitter Title</label>
                    <input type="text" name="twitter_title" class="form-control" value="<?= h($seo['twitter_title']) ?>">
                </div>
                <div class="form-group">
                    <label>Twitter Description</label>
                    <textarea name="twitter_description" class="form-control" rows="3"><?= h($seo['twitter_description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Twitter Image URL</label>
                    <input type="text" name="twitter_image" class="form-control" value="<?= h($seo['twitter_image']) ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Canonical URL</label>
            <input type="text" name="canonical_url" class="form-control" value="<?= h($seo['canonical_url']) ?>">
        </div>
        <div class="form-group">
            <label>Robots</label>
            <input type="text" name="robots" class="form-control" value="<?= h($seo['robots']) ?>" placeholder="index,follow">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn btn-default" href="seo.php">Cancel</a>
    </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
