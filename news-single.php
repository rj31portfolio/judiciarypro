<?php
require __DIR__ . '/includes/bootstrap.php';
$pageKey = 'news-single';
$bodyClass = 'page page-template';
$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare("SELECT * FROM news WHERE slug = ? AND status = 'published' LIMIT 1");
$stmt->execute([$slug]);
$item = $stmt->fetch();

if (!$item) {
    http_response_code(404);
    $seoDefaults = ['meta_title' => 'News Not Found'];
    include __DIR__ . '/includes/header.php';
    echo '<div class="container"><h2>News item not found.</h2></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$seoDefaults = [
    'meta_title' => $item['title'] . ' - JudiciaryPRO',
    'meta_description' => $item['excerpt'],
];
include __DIR__ . '/includes/header.php';

$publishedAt = $item['published_at'] ?: $item['created_at'];
$newsImage = trim($item['image'] ?? '') !== '' ? url_for($item['image']) : url_for('assets/img/news/news1.jpg');
$authorName = $item['author'] ?: 'JudiciaryPRO';
?>
<section>
    <div class="lgx-banner lgx-banner-inner">
        <div class="lgx-inner">

        </div><!-- //.INNER -->
    </div>
</section>
    <!--NEWS-->
    <section>
        <div id="lgx-news" class="lgx-news lgx-news-single">
            <div class="lgx-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <article>
                                <header>
                                    <div class="text-area">
                                        <h1 class="title"><a href="#"><?= h($item['title']) ?></a></h1>
                                        <div class="hits-area">
                                            <div class="date">
                                                <a href="#"><i class="fa fa-user"></i> <?= h($authorName) ?></a>
                                                <?php if ($publishedAt): ?>
                                                    <a href="#"><i class="fa fa-calendar"></i> <?= h(date('d F, Y', strtotime($publishedAt))) ?></a>
                                                <?php endif; ?>
                                                <a href="#"><i class="fa fa-folder"></i> News</a>
                                            </div>
                                        </div>
                                    </div>
                                    <figure>
                                        <a href="#"><img src="<?= h($newsImage) ?>" alt="<?= h($item['title']) ?>"/></a>
                                    </figure>
                                </header>
                                <section>
                                    <?php if (!empty($item['content'])): ?>
                                        <?= $item['content'] ?>
                                    <?php elseif (!empty($item['excerpt'])): ?>
                                        <p><?= h($item['excerpt']) ?></p>
                                    <?php endif; ?>
                                </section>
                                <footer>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h4 class="title">Share</h4>
                                            <div class="lgx-share">
                                                <ul class="list-inline lgx-social">
                                                    <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-facebook-f" aria-hidden="true"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
                                                    <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </footer>
                            </article>
                        </div>
                    </div>
                </div><!-- //.CONTAINER -->
            </div><!-- //.INNER -->
        </div>
    </section> <!--//.NEWS-->
<?php include __DIR__ . '/includes/footer.php'; ?>

