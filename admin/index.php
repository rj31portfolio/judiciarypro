<?php
require __DIR__ . '/includes/auth.php';
$title = 'Dashboard';

$safeCount = function (string $table): int {
    try {
        $stmt = db()->query("SELECT COUNT(*) FROM `{$table}`");
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        return 0;
    }
};

$stats = [
    'courses' => $safeCount('courses'),
    'events' => $safeCount('events'),
    'news' => $safeCount('news'),
    'students' => $safeCount('student_rankings'),
    'signups' => $safeCount('student_signups'),
    'categories' => $safeCount('course_categories'),
    'materials' => $safeCount('materials'),
    'material_categories' => $safeCount('material_categories'),
    'material_leads' => $safeCount('material_leads'),
];

include __DIR__ . '/includes/header.php';
?>
<div class="admin-card">
    <h2>Welcome back, <?= h($_SESSION['admin_name'] ?? 'Admin') ?></h2>
    <p>Quickly manage courses, events, student rankings, news, and SEO from one place.</p>
</div>

<div class="row" style="margin-top: 12px;">
    <div class="col-sm-4">
        <div class="admin-stat">
            <h4>Total Courses</h4>
            <p><?= h($stats['courses']) ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="admin-stat">
            <h4>Course Categories</h4>
            <p><?= h($stats['categories']) ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="admin-stat">
            <h4>Student Signups</h4>
            <p><?= h($stats['signups']) ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="admin-stat">
            <h4>Events</h4>
            <p><?= h($stats['events']) ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="admin-stat">
            <h4>News Posts</h4>
            <p><?= h($stats['news']) ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="admin-stat">
            <h4>Ranked Students</h4>
            <p><?= h($stats['students']) ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="admin-stat">
            <h4>Materials</h4>
            <p><?= h($stats['materials']) ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="admin-stat">
            <h4>Material Categories</h4>
            <p><?= h($stats['material_categories']) ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="admin-stat">
            <h4>Material Leads</h4>
            <p><?= h($stats['material_leads']) ?></p>
        </div>
    </div>
</div>

<div class="admin-card">
    <h3>Quick Actions</h3>
    <div class="row">
        <div class="col-sm-3"><a class="btn btn-primary btn-block" href="course-edit.php">Add Course</a></div>
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="course-categories.php">Manage Categories</a></div>
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="event-edit.php">Add Event</a></div>
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="news-edit.php">Add News</a></div>
    </div>
    <div class="row" style="margin-top: 12px;">
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="students.php">Student Rankings</a></div>
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="signups.php">View Signups</a></div>
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="events.php">All Events</a></div>
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="news.php">All News</a></div>
    </div>
    <div class="row" style="margin-top: 12px;">
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="material-edit.php">Add Material</a></div>
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="material-categories.php">Material Categories</a></div>
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="materials.php">All Materials</a></div>
        <div class="col-sm-3"><a class="btn btn-default btn-block" href="material-leads.php">Material Leads</a></div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
