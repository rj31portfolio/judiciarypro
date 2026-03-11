<?php
require __DIR__ . '/includes/auth.php';
$title = 'Event Editor';

function ensure_event_slug($slug, $id = 0)
{
    $base = $slug;
    $i = 1;
    while (true) {
        $stmt = db()->prepare('SELECT id FROM events WHERE slug = ? AND id != ? LIMIT 1');
        $stmt->execute([$slug, $id]);
        if (!$stmt->fetch()) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
    }
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$event = [
    'title' => '',
    'slug' => '',
    'short_description' => '',
    'description' => '',
    'event_date' => '',
    'start_time' => '',
    'end_time' => '',
    'location' => '',
    'image' => '',
    'status' => 'published',
];

if ($id) {
    $stmt = db()->prepare('SELECT * FROM events WHERE id = ?');
    $stmt->execute([$id]);
    $event = $stmt->fetch() ?: $event;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event['title'] = trim($_POST['title'] ?? '');
    $event['slug'] = trim($_POST['slug'] ?? '');
    $event['short_description'] = trim($_POST['short_description'] ?? '');
    $event['description'] = trim($_POST['description'] ?? '');
    $event['event_date'] = $_POST['event_date'] ?? '';
    $event['start_time'] = trim($_POST['start_time'] ?? '');
    $event['end_time'] = trim($_POST['end_time'] ?? '');
    $event['location'] = trim($_POST['location'] ?? '');
    $event['status'] = $_POST['status'] ?? 'published';

    if ($event['slug'] === '') {
        $event['slug'] = slugify($event['title']);
    }
    $event['slug'] = ensure_event_slug($event['slug'], $id);

    $image = handle_upload('image');
    if ($image) {
        $event['image'] = $image;
    }

    if ($id) {
        $stmt = db()->prepare('UPDATE events SET title=?, slug=?, short_description=?, description=?, event_date=?, start_time=?, end_time=?, location=?, image=?, status=? WHERE id=?');
        $stmt->execute([
            $event['title'], $event['slug'], $event['short_description'], $event['description'], $event['event_date'],
            $event['start_time'], $event['end_time'], $event['location'], $event['image'], $event['status'], $id
        ]);
    } else {
        $stmt = db()->prepare('INSERT INTO events (title, slug, short_description, description, event_date, start_time, end_time, location, image, status) VALUES (?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $event['title'], $event['slug'], $event['short_description'], $event['description'], $event['event_date'],
            $event['start_time'], $event['end_time'], $event['location'], $event['image'], $event['status']
        ]);
    }

    redirect('admin/events.php');
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <h3><?= $id ? 'Edit Event' : 'Add Event' ?></h3>
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?= h($event['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= h($event['slug']) ?>">
                    <p class="form-note">Leave blank to auto-generate from the title.</p>
                </div>
                <div class="form-group">
                    <label>Short Description</label>
                    <textarea name="short_description" class="form-control" rows="3"><?= h($event['short_description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="event-description" class="form-control" rows="8"><?= h($event['description']) ?></textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Event Date</label>
                    <input type="date" name="event_date" class="form-control" value="<?= h($event['event_date']) ?>">
                </div>
                <div class="form-group">
                    <label>Start Time</label>
                    <input type="text" name="start_time" class="form-control" value="<?= h($event['start_time']) ?>" placeholder="10:00 AM">
                </div>
                <div class="form-group">
                    <label>End Time</label>
                    <input type="text" name="end_time" class="form-control" value="<?= h($event['end_time']) ?>" placeholder="1:00 PM">
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="<?= h($event['location']) ?>">
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control">
                    <?php if ($event['image']): ?>
                        <p class="form-note">Current: <?= h($event['image']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="published" <?= $event['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="draft" <?= $event['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-default" href="events.php">Cancel</a>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    if (window.ClassicEditor) {
        ClassicEditor.create(document.querySelector('#event-description')).catch(function () {});
    }
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
