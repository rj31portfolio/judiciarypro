<?php
$pageKey = 'news';
$seoDefaults = [
    'meta_title' => 'News - JudiciaryPRO',
    'meta_description' => 'Latest JudiciaryPRO news and announcements.',
];
$bodyClass = 'page page-template';
include __DIR__ . '/includes/header.php';
$stmt = db()->prepare("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC, created_at DESC");
$stmt->execute();
$items = $stmt->fetchAll();
?>
<section>
    <div class="lgx-banner lgx-banner-inner">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading-area">
                            <div class="lgx-heading lgx-heading-white">
                                <h2 class="heading-title">News Updates</h2>
                            </div>
                            <ul class="breadcrumb">
                                <li><a href="index"><i class="icon-home6"></i>Home</a></li>
                                <li class="active">News Updates</li>
                            </ul>
                        </div>
                    </div>
                </div><!--//.ROW-->
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<!--BLOG -->
<section>
    <div id="lgx-blog" class="lgx-blog lgx-blog-normal">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <?php if (!$items): ?>
                        <div class="col-xs-12">
                            <p>No news published yet.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <?php
                            $newsImage = trim($item['image'] ?? '') !== '' ? url_for($item['image']) : url_for('assets/img/news/news1.jpg');
                            $newsSlug = trim($item['slug'] ?? '');
                            $newsLink = $newsSlug !== '' ? url_for('news/' . rawurlencode($newsSlug)) : '#';
                            $authorName = $item['author'] ?: 'JudiciaryPRO';
                            $publishedAt = $item['published_at'] ?: $item['created_at'];
                            ?>
                            <div class="lgx-news-single">
                                <figure>
                                    <img src="<?= h($newsImage) ?>" alt="<?= h($item['title'] ?? 'News') ?>" title="<?= h($item['title'] ?? 'News') ?>"/>
                                    <figcaption>
                                        <div class="figcaption">
                                            <div class="lgx-hover-link">
                                                <div class="lgx-vertical">
                                                    <a href="<?= $newsLink ?>"><i class="fa fa-book"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="author">
                                            <div class="author-info">
                                                <img src="assets/img/founder.png" alt="author">
                                                <div class="author-info">
                                                    <h4 class="title"><a href="<?= $newsLink ?>"><?= h($authorName) ?></a></h4>
                                                    <h5 class="subtitle">JudiciaryPRO</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </figcaption>
                                </figure>
                                <div class="text-area">
                                    <h3 class="title"><a href="<?= $newsLink ?>"><?= h($item['title'] ?? '') ?></a></h3>
                                    <?php if (!empty($item['excerpt'])): ?>
                                        <p class="text"><?= h($item['excerpt']) ?></p>
                                    <?php endif; ?>
                                    <div class="hits-area">
                                        <span class="date"></span>
                                    </div>
                                    <div class="text-bottom">
                                        <a class="date" href="#"><?= $publishedAt ? h(date('d M Y', strtotime($publishedAt))) : '' ?></a>
                                        <a class="link" href="<?= $newsLink ?>"><i class="fa  fa-long-arrow-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div> <!--//.News-single-->
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<!--BLOG END-->
<?php include __DIR__ . '/includes/footer.php'; ?>

