<?php
$pageKey = 'courses';
$seoDefaults = [
    'meta_title' => 'Courses - JudiciaryPRO',
    'meta_description' => 'Explore JudiciaryPRO courses for judiciary and prosecutor exams.',
    'meta_keywords' => 'judiciary courses, prosecutor courses, exam prep',
];
$bodyClass = 'page page-template';
include __DIR__ . '/includes/header.php';

$stmt = db()->prepare("SELECT * FROM courses WHERE status = 'published' ORDER BY created_at DESC");
$stmt->execute();
$courses = $stmt->fetchAll();
$categoryRows = db()->query("SELECT name FROM course_categories WHERE status = 'active' ORDER BY name")->fetchAll();
$courseCategories = array_map(function ($row) {
    return $row['name'];
}, $categoryRows);
?>
<section>
    <div class="lgx-banner lgx-banner-inner">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading-area">
                            <div class="lgx-heading lgx-heading-white">
                                <h2 class="heading-title">Offerd Courses</h2>
                            </div>
                            <ul class="breadcrumb">
                                <li><a href="index"><i class="icon-home6"></i>Home</a></li>
                                <li class="active">Courses</li>
                            </ul>
                        </div>
                    </div>
                </div><!--//.ROW-->
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<section>
    <div id="lgx-courses" class="lgx-courses">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h2 class="heading-title">Offerd Courses</h2>
                            <h4 class="heading-subtitle">Explore our latest courses</h4>
                        </div>
                    </div>
                </div>
                <div class="lgx-tab">
                    <?php if (!$courses): ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <p>No courses published yet.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php if ($courseCategories): ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="lgx-filter-area">
                                        <ul id="lgx-filter" class="lgx-filter list-inline text-center">
                                            <li class="active"><a class="lgx-filter-item" href="javascript:void(0)" data-filter="*">All</a></li>
                                            <?php foreach ($courseCategories as $catName): ?>
                                                <?php $catClass = slugify($catName); ?>
                                                <li><a class="lgx-filter-item" href="javascript:void(0)" data-filter=".<?= h($catClass) ?>"><?= h($catName) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div id="lgx-grid-wrapper" class="lgx-grid-wrapper">
                                <?php foreach ($courses as $course): ?>
                                    <?php
                                    $courseImage = trim($course['image'] ?? '') !== '' ? url_for($course['image']) : url_for('assets/img/courses/course1.jpg');
                                    $authorImage = trim($course['author_image'] ?? '') !== '' ? url_for($course['author_image']) : url_for('assets/img/founder.png');
                                    $courseSlug = trim($course['slug'] ?? '');
                                    $courseLink = $courseSlug !== '' ? url_for('course/' . rawurlencode($courseSlug)) : '#';
                                    $categoryClass = slugify(trim($course['category'] ?? 'general'));
                                    $summary = trim($course['summary'] ?? '');
                                    ?>
                                    <div class="lgx-grid-item col-xs-12 col-sm-6 col-md-4 <?= h($categoryClass) ?>">
                                        <div class="lgx-single-course">
                                            <div class="lgx-single-course-inner">
                                                <figure>
                                                    <img src="<?= h($courseImage) ?>" alt="course">
                                                    <figcaption>
                                                        <div class="lgx-hover-link">
                                                            <div class="lgx-vertical">
                                                                <a href="<?= $courseLink ?>">
                                                                    <i class="fa fa-book"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </figcaption>
                                                </figure>
                                                <div class="course-info">
                                                    <div class="course-author">
                                                        <img src="<?= h($authorImage) ?>" alt="course">
                                                        <div class="author-info">
                                                            <h4 class="title"><a href="#"><?= h($course['author_name'] ?: 'JudiciaryPRO') ?></a></h4>
                                                            <h5 class="subtitle"><?= h($summary !== '' ? $summary : ($course['author_title'] ?: 'Instructor')) ?></h5>
                                                        </div>
                                                    </div>
                                                    <h3 class="title"><a href="<?= $courseLink ?>"><?= h($course['title'] ?? '') ?></a></h3>
                                                    <div class="course-bottom">
                                                        <ul class="list-inline">
                                                            <?php if (!empty($course['students_count'])): ?>
                                                                <li><a href="#"><i class="fa fa-user-circle"></i><?= h($course['students_count']) ?></a></li>
                                                            <?php endif; ?>
                                                            <?php if (!empty($course['comments_count'])): ?>
                                                                <li><a href="#"><i class="fa fa-commenting"></i><?= h($course['comments_count']) ?></a></li>
                                                            <?php endif; ?>
                                                            <li><a href="#"><?= ($course['price'] ?? 0) > 0 ? '&#8377;' . h(number_format((float)$course['price'], 2)) : 'Free' ?></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>



