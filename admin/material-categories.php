<?php
require __DIR__ . '/includes/auth.php';
$title = 'Material Categories';

db()->exec("CREATE TABLE IF NOT EXISTS material_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    slug VARCHAR(180) NOT NULL UNIQUE,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

function ensure_material_category_slug($slug, $id = 0)
{
    $base = $slug;
    $i = 1;
    while (true) {
        $stmt = db()->prepare('SELECT id FROM material_categories WHERE slug = ? AND id != ? LIMIT 1');
        $stmt->execute([$slug, $id]);
        if (!$stmt->fetch()) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = db()->prepare('DELETE FROM material_categories WHERE id = ?');
    $stmt->execute([$id]);
    redirect('admin/material-categories.php');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$category = [
    'name' => '',
    'slug' => '',
    'status' => 'active',
];

if ($id) {
    $stmt = db()->prepare('SELECT * FROM material_categories WHERE id = ?');
    $stmt->execute([$id]);
    $category = $stmt->fetch() ?: $category;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category['name'] = trim($_POST['name'] ?? '');
    $category['slug'] = trim($_POST['slug'] ?? '');
    $category['status'] = $_POST['status'] ?? 'active';

    if ($category['slug'] === '') {
        $category['slug'] = slugify($category['name']);
    }
    $category['slug'] = ensure_material_category_slug($category['slug'], $id);

    if ($id) {
        $stmt = db()->prepare('UPDATE material_categories SET name=?, slug=?, status=? WHERE id=?');
        $stmt->execute([$category['name'], $category['slug'], $category['status'], $id]);
    } else {
        $stmt = db()->prepare('INSERT INTO material_categories (name, slug, status) VALUES (?,?,?)');
        $stmt->execute([$category['name'], $category['slug'], $category['status']]);
    }

    redirect('admin/material-categories.php');
}

$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$total = (int)db()->query('SELECT COUNT(*) FROM material_categories')->fetchColumn();
$totalPages = (int)ceil($total / $perPage);
if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;
$stmt = db()->prepare('SELECT * FROM material_categories ORDER BY name ASC LIMIT ? OFFSET ?');
$stmt->bindValue(1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$categories = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <div class="clearfix">
        <h3 class="pull-left">Material Categories</h3>
    </div>
    <form method="post" class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?= h($category['name']) ?>" required>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" value="<?= h($category['slug']) ?>">
                <p class="form-note">Leave blank to auto-generate from the name.</p>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" <?= $category['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $category['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>
        <div class="col-sm-2">
            <label class="hidden-xs">&nbsp;</label>
            <div>
                <button class="btn btn-primary" type="submit"><?= $id ? 'Update' : 'Add' ?></button>
                <?php if ($id): ?>
                    <a class="btn btn-default" href="material-categories.php">Cancel</a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<div class="admin-card">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $row): ?>
            <tr>
                <td><?= h($row['name']) ?></td>
                <td><?= h($row['slug']) ?></td>
                <td><?= h($row['status']) ?></td>
                <td class="text-right">
                    <a class="btn btn-xs btn-default" href="material-categories.php?id=<?= (int)$row['id'] ?>">Edit</a>
                    <a class="btn btn-xs btn-danger" href="material-categories.php?delete=<?= (int)$row['id'] ?>" onclick="return confirm('Delete this category?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$categories): ?>
            <tr><td colspan="4">No categories added yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?= pagination_links($page, $totalPages, 'material-categories.php') ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
