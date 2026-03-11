<?php
$pageKey = 'events';
$seoDefaults = [
    'meta_title' => 'Events - JudiciaryPRO',
    'meta_description' => 'Upcoming JudiciaryPRO events and sessions.',
];
$bodyClass = 'page page-template';
include __DIR__ . '/includes/header.php';

$stmt = db()->prepare("SELECT * FROM events WHERE status = 'published' ORDER BY event_date DESC, created_at DESC");
$stmt->execute();
$events = $stmt->fetchAll();
?>
<section>
    <div class="lgx-banner lgx-banner-inner">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading-area">
                            <div class="lgx-heading lgx-heading-white">
                                <h2 class="heading-title">Upcoming Events</h2>
                            </div>
                            <ul class="breadcrumb">
                                <li><a href="index"><i class="icon-home6"></i>Home</a></li>
                                <li class="active">Events</li>
                            </ul>
                        </div>
                    </div>
                </div><!--//.ROW-->
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<!--EVENTS-->
<section>
    <div id="lgx-events" class="lgx-events lgx-event-normal">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-events-area">
                            <div class="row">
                                <?php if (!$events): ?>
                                    <div class="col-xs-12">
                                        <p>No upcoming events yet.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($events as $event): ?>
                                        <?php
                                        $eventImage = trim($event['image'] ?? '') !== '' ? url_for($event['image']) : url_for('assets/img/blog-couese.jpeg');
                                        $eventSlug = trim($event['slug'] ?? '');
                                        $eventLink = $eventSlug !== '' ? url_for('event/' . rawurlencode($eventSlug)) : '#';
                                        ?>
                                        <div class="lgx-single-event">
                                            <div class="thumb">
                                                <a href="<?= $eventLink ?>"><img src="<?= h($eventImage) ?>" alt="<?= h($event['title'] ?? 'event') ?>"></a>
                                            </div>
                                            <div class="event-info">
                                                <?php if (!empty($event['event_date'])): ?>
                                                    <a class="date" href="#"><?= h(date('F d, Y', strtotime($event['event_date']))) ?></a>
                                                <?php endif; ?>
                                                <?php if (!empty($event['location'])): ?>
                                                    <h4 class="location"><?= h($event['location']) ?></h4>
                                                <?php endif; ?>
                                                <h3 class="title"><a href="<?= $eventLink ?>"><?= h($event['title'] ?? '') ?></a></h3>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
        <!-- //.INNER -->
    </div>
</section>
<!--EVENTS END-->
<?php include __DIR__ . '/includes/footer.php'; ?>



