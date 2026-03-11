<?php
require __DIR__ . '/includes/bootstrap.php';
$pageKey = 'event';
$bodyClass = 'page page-template';
$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare("SELECT * FROM events WHERE slug = ? AND status = 'published' LIMIT 1");
$stmt->execute([$slug]);
$event = $stmt->fetch();

if (!$event) {
    http_response_code(404);
    $seoDefaults = ['meta_title' => 'Event Not Found'];
    include __DIR__ . '/includes/header.php';
    echo '<div class="container"><h2>Event not found.</h2></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}
$seoDefaults = [
    'meta_title' => $event['title'] . ' - JudiciaryPRO',
    'meta_description' => $event['short_description'],
];
include __DIR__ . '/includes/header.php';

$eventImage = trim($event['image'] ?? '') !== '' ? url_for($event['image']) : url_for('assets/img/events/single-event.jpg');
$eventDateText = '';
$eventDay = '';
$eventMonth = '';
$eventYear = '';
if (!empty($event['event_date'])) {
    $eventDateText = date('d F, Y', strtotime($event['event_date']));
    $eventDay = date('d', strtotime($event['event_date']));
    $eventMonth = date('M', strtotime($event['event_date']));
    $eventYear = date('Y', strtotime($event['event_date']));
}
$eventTimeText = '';
if (!empty($event['start_time']) && !empty($event['end_time'])) {
    $eventTimeText = $event['start_time'] . ' - ' . $event['end_time'];
} elseif (!empty($event['start_time'])) {
    $eventTimeText = $event['start_time'];
} elseif (!empty($event['end_time'])) {
    $eventTimeText = $event['end_time'];
}
?>
<section>
    <div class="lgx-banner lgx-banner-inner">
        <div class="lgx-inner">
        </div><!-- //.INNER -->
    </div>
</section>
<!--EVENT-->
    <section>
        <div id="lgx-course" class="lgx-event lgx-normal-single">
            <div class="lgx-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <article>
                                <header>
                                    <div class="text-area">
                                        <?php if ($eventDateText): ?>
                                            <div class="jp-event-date">
                                                <span class="jp-event-month"><?= h($eventMonth) ?></span>
                                                <span class="jp-event-day"><?= h($eventDay) ?></span>
                                                <span class="jp-event-year"><?= h($eventYear) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <h1 class="title"><a href="#"><?= h($event['title']) ?></a></h1>
                                        <div class="jp-event-meta">
                                            <?php if ($eventDateText): ?>
                                                <span><i class="fa fa-calendar"></i> <?= h($eventDateText) ?></span>
                                            <?php endif; ?>
                                            <?php if ($eventTimeText): ?>
                                                <span><i class="fa fa-clock-o"></i> <?= h($eventTimeText) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($event['location'])): ?>
                                                <span><i class="fa fa-map-marker"></i> <?= h($event['location']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($event['short_description'])): ?>
                                            <p class="jp-event-lead"><?= h($event['short_description']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <figure>
                                        <a href="#"><img src="<?= h($eventImage) ?>" alt="<?= h($event['title']) ?>"/></a>
                                    </figure>
                                </header>
                                <section>
                                    <?php if (!empty($event['description'])): ?>
                                        <?= $event['description'] ?>
                                    <?php elseif (!empty($event['short_description'])): ?>
                                        <p><?= h($event['short_description']) ?></p>
                                    <?php endif; ?>
                                </section>
                                <footer>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h4 class="title">Share</h4>
                                            <div class="lgx-share">
                                                <ul class="list-inline lgx-social">
                                                    
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
    </section> <!--//.EVENT-->
<?php include __DIR__ . '/includes/footer.php'; ?>

