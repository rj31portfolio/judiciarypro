<?php
$pageKey = 'materials';
$seoDefaults = [
    'meta_title' => 'Study Materials - JudiciaryPRO',
    'meta_description' => 'Download study materials, PDFs, and video resources for judiciary preparation.',
    'meta_keywords' => 'judiciary materials, study notes, pdf, videos',
];
$bodyClass = 'page page-template';
include __DIR__ . '/includes/header.php';

$stmt = db()->prepare("SELECT * FROM materials WHERE status = 'published' ORDER BY created_at DESC");
$stmt->execute();
$materials = $stmt->fetchAll();
$categoryRows = db()->query("SELECT name FROM material_categories WHERE status = 'active' ORDER BY name")->fetchAll();
$materialCategories = array_map(function ($row) {
    return $row['name'];
}, $categoryRows);
?>
<section>
    <div class="lgx-banner lgx-banner-inner jp-material-banner">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading-area">
                            <div class="lgx-heading lgx-heading-white">
                                <h2 class="heading-title">Study Materials</h2>
                            </div>
                            <ul class="breadcrumb">
                                <li><a href="index"><i class="icon-home6"></i>Home</a></li>
                                <li class="active">Materials</li>
                            </ul>
                        </div>
                    </div>
                </div><!--//.ROW-->
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<section>
    <div id="lgx-materials" class="jp-materials">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h2 class="heading-title">Latest Materials</h2>
                            <h4 class="heading-subtitle">PDFs, notes, and video explainers in one place</h4>
                        </div>
                    </div>
                </div>
                <div class="lgx-tab">
                    <?php if (!$materials): ?>
                        <div class="row">
                            <div class="col-xs-12">
                                <p>No materials published yet.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php if ($materialCategories): ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="lgx-filter-area">
                                        <ul id="lgx-filter" class="lgx-filter list-inline text-center">
                                            <li class="active"><a class="lgx-filter-item" href="javascript:void(0)" data-filter="*">All</a></li>
                                            <?php foreach ($materialCategories as $catName): ?>
                                                <?php $catClass = slugify($catName); ?>
                                                <li><a class="lgx-filter-item" href="javascript:void(0)" data-filter=".<?= h($catClass) ?>"><?= h($catName) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div id="lgx-grid-wrapper" class="lgx-grid-wrapper jp-material-grid">
                                <?php foreach ($materials as $material): ?>
                                    <?php
                                    $images = [];
                                    if (!empty($material['images_json'])) {
                                        $decoded = json_decode($material['images_json'], true);
                                        if (is_array($decoded)) {
                                            $images = $decoded;
                                        }
                                    }
                                    $cover = trim($material['cover_image'] ?? '') !== '' ? $material['cover_image'] : ($images[0] ?? '');
                                    $coverUrl = $cover !== '' ? url_for($cover) : url_for('assets/img/courses/course1.jpg');
                                    $slug = trim($material['slug'] ?? '');
                                    $link = $slug !== '' ? url_for('material/' . rawurlencode($slug)) : '#';
                                    $categoryClass = slugify(trim($material['category'] ?? 'general'));
                                    $summary = trim($material['summary'] ?? '');
                                    $pdfs = [];
                                    if (!empty($material['pdfs_json'])) {
                                        $decoded = json_decode($material['pdfs_json'], true);
                                        if (is_array($decoded)) {
                                            $pdfs = $decoded;
                                        }
                                    }
                                    $videos = [];
                                    if (!empty($material['youtube_json'])) {
                                        $decoded = json_decode($material['youtube_json'], true);
                                        if (is_array($decoded)) {
                                            $videos = $decoded;
                                        }
                                    }
                                    ?>
                                    <div class="lgx-grid-item col-xs-12 col-sm-6 col-md-4 <?= h($categoryClass) ?>">
                                        <div class="jp-material-card">
                                            <a class="jp-material-link" href="<?= $link ?>">
                                                <div class="jp-material-media" style="background-image:url('<?= h($coverUrl) ?>');"></div>
                                                <div class="jp-material-body">
                                                    <span class="jp-material-chip"><?= h($material['category'] ?: 'General') ?></span>
                                                    <h3><?= h($material['title'] ?? '') ?></h3>
                                                    <?php if ($summary !== ''): ?>
                                                        <p><?= h($summary) ?></p>
                                                    <?php endif; ?>
                                                    <div class="jp-material-meta">
                                                        <span><i class="fa fa-file-pdf-o"></i><?= count($pdfs) ?> PDFs</span>
                                                        <span><i class="fa fa-youtube-play"></i><?= count($videos) ?> Videos</span>
                                                    </div>
                                                    <div class="jp-material-cta">View Details <i class="fa fa-arrow-right"></i></div>
                                                </div>
                                            </a>
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
