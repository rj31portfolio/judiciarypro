<?php
require __DIR__ . '/includes/bootstrap.php';
$pageKey = 'material';
$bodyClass = 'page page-template';
$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare("SELECT * FROM materials WHERE slug = ? AND status = 'published' LIMIT 1");
$stmt->execute([$slug]);
$material = $stmt->fetch();

if (!$material) {
    http_response_code(404);
    $seoDefaults = ['meta_title' => 'Material Not Found'];
    include __DIR__ . '/includes/header.php';
    echo '<div class="container"><h2>Material not found.</h2></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$images = [];
if (!empty($material['images_json'])) {
    $decoded = json_decode($material['images_json'], true);
    if (is_array($decoded)) {
        $images = $decoded;
    }
}
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

function youtube_id($url)
{
    $url = trim((string)$url);
    if ($url === '') {
        return '';
    }
    if (preg_match('~youtu\.be/([^\?&/]+)~', $url, $m)) {
        return $m[1];
    }
    if (preg_match('~v=([^\?&/]+)~', $url, $m)) {
        return $m[1];
    }
    if (preg_match('~youtube\.com/embed/([^\?&/]+)~', $url, $m)) {
        return $m[1];
    }
    if (preg_match('~youtube\.com/shorts/([^\?&/]+)~', $url, $m)) {
        return $m[1];
    }
    return '';
}

$cover = trim($material['cover_image'] ?? '') !== '' ? $material['cover_image'] : ($images[0] ?? '');
$coverUrl = $cover !== '' ? url_for($cover) : url_for('assets/img/courses/course1.jpg');

$seoDefaults = [
    'meta_title' => ($material['meta_title'] ?: $material['title']) . ' - JudiciaryPRO',
    'meta_description' => $material['meta_description'] ?: $material['summary'],
];
include __DIR__ . '/includes/header.php';
?>
<section>
    <div class="lgx-banner lgx-banner-inner jp-material-banner">
        <div class="lgx-inner"></div>
    </div>
</section>
<section>
    <div class="jp-material-detail">
        <div class="lgx-inner">
            <div class="container">
                <div class="jp-material-hero">
                    <div class="jp-material-hero-text">
                        <span class="jp-material-chip"><?= h($material['category'] ?: 'General') ?></span>
                        <h1><?= h($material['title']) ?></h1>
                        <?php if (!empty($material['summary'])): ?>
                            <p><?= h($material['summary']) ?></p>
                        <?php endif; ?>
                        <div class="jp-material-hero-meta">
                            <span><i class="fa fa-file-pdf-o"></i><?= count($pdfs) ?> PDFs</span>
                            <span><i class="fa fa-youtube-play"></i><?= count($videos) ?> Videos</span>
                        </div>
                    </div>
                    <div class="jp-material-hero-media">
                        <img src="<?= h($coverUrl) ?>" alt="<?= h($material['title']) ?>">
                    </div>
                </div>

                <?php if ($images): ?>
                    <div class="jp-material-gallery">
                        <?php foreach ($images as $img): ?>
                            <?php $imgUrl = url_for($img); ?>
                            <div class="jp-material-gallery-card">
                                <img src="<?= h($imgUrl) ?>" alt="<?= h($material['title']) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="row jp-material-sections">
                    <div class="col-md-8">
                        <?php if (!empty($material['overview_html'])): ?>
                            <div class="jp-material-card-block">
                                <h3>Overview</h3>
                                <?= $material['overview_html'] ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($material['detail_html'])): ?>
                            <div class="jp-material-card-block">
                                <h3>More Information</h3>
                                <?= $material['detail_html'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <div class="jp-material-card-block jp-material-resources">
                            <h3>Download PDFs</h3>
                            <?php if (!$pdfs): ?>
                                <p>No PDFs available yet.</p>
                            <?php else: ?>
                                <?php foreach ($pdfs as $pdf): ?>
                                    <?php
                                    $pdfFile = trim($pdf['file'] ?? '');
                                    $pdfName = trim($pdf['name'] ?? '') ?: basename($pdfFile);
                                    if ($pdfFile === '') continue;
                                    $pdfUrl = url_for($pdfFile);
                                    ?>
                                    <div class="jp-material-pdf">
                                        <div class="jp-material-pdf-info">
                                            <i class="fa fa-file-pdf-o"></i>
                                            <span><?= h($pdfName) ?></span>
                                        </div>
                                        <button type="button" class="jp-pdf-download"
                                                data-name="<?= h($pdfName) ?>"
                                                data-file="<?= h($pdfFile) ?>"
                                                data-url="<?= h($pdfUrl) ?>">
                                            Download
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($videos): ?>
                    <div class="jp-material-card-block jp-material-videos">
                        <h3>Video Resources</h3>
                        <div class="jp-material-video-grid">
                            <?php foreach ($videos as $videoUrl): ?>
                                <?php
                                $id = youtube_id($videoUrl);
                                if ($id === '') continue;
                                $thumb = 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg';
                                ?>
                                <a class="jp-material-video" href="<?= h($videoUrl) ?>" target="_blank" rel="noopener">
                                    <div class="jp-material-video-thumb" style="background-image:url('<?= h($thumb) ?>');"></div>
                                    <span><i class="fa fa-play-circle"></i> Watch on YouTube</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="jp-material-lead-modal" tabindex="-1" role="dialog" aria-labelledby="jpMaterialLeadLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content jp-lead-modal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="jpMaterialLeadLabel">Get Your PDF</h4>
            </div>
            <div class="modal-body">
                <p class="jp-lead-note">Please share your details to download <strong id="jp-lead-pdf-name"></strong>.</p>
                <form id="jp-material-lead-form" action="ajax/material-lead" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" class="form-control" name="phone" placeholder="Phone Number" required>
                    </div>
                    <input type="hidden" name="material_id" value="<?= (int)$material['id'] ?>">
                    <input type="hidden" name="material_title" value="<?= h($material['title']) ?>">
                    <input type="hidden" name="pdf_name" id="jp-lead-pdf-input" value="">
                    <input type="hidden" name="pdf_file" id="jp-lead-pdf-file" value="">
                    <button type="submit" class="jp-lead-submit">Submit & Download</button>
                    <div class="jp-lead-status" id="jp-lead-status"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', function () {
        if (!window.jQuery) {
            return;
        }
        var $ = window.jQuery;
        var leadKey = 'jpMaterialLeadDone';
        var pending = null;
        var $buttons = $('.jp-pdf-download');
        if (!$buttons.length) {
            return;
        }
        var $modal = $('#jp-material-lead-modal');
        var $form = $('#jp-material-lead-form');
        var $status = $('#jp-lead-status');
        var $pdfName = $('#jp-lead-pdf-name');
        var $pdfInput = $('#jp-lead-pdf-input');
        var $pdfFileInput = $('#jp-lead-pdf-file');

        function leadDone() {
            try {
                return localStorage.getItem(leadKey) === '1';
            } catch (e) {
                return false;
            }
        }

        function markLeadDone() {
            try {
                localStorage.setItem(leadKey, '1');
            } catch (e) {}
        }

        $buttons.on('click', function () {
            var name = $(this).data('name') || '';
            var file = $(this).data('file') || '';
            var url = $(this).data('url') || '';
            if (leadDone()) {
                window.open(url, '_blank');
                return;
            }
            pending = {name: name, file: file, url: url};
            $pdfName.text(name);
            $pdfInput.val(name);
            $pdfFileInput.val(file);
            $status.text('');
            $modal.modal('show');
        });

        $form.on('submit', function (e) {
            e.preventDefault();
            if (!pending) {
                return;
            }
            var $btn = $form.find('button[type="submit"]');
            $btn.prop('disabled', true).text('Submitting...');
            $.ajax({
                url: 'ajax/material-lead',
                method: 'POST',
                dataType: 'json',
                data: $form.serialize()
            }).done(function (res) {
                if (res && res.ok) {
                    markLeadDone();
                    $modal.modal('hide');
                    var downloadUrl = res.download_url || pending.url;
                    window.open(downloadUrl, '_blank');
                    $form[0].reset();
                } else {
                    $status.text((res && res.message) || 'Unable to submit.').css('color', '#b91c1c');
                }
            }).fail(function (xhr) {
                var message = 'Unable to submit right now.';
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                $status.text(message).css('color', '#b91c1c');
            }).always(function () {
                $btn.prop('disabled', false).text('Submit & Download');
            });
        });
    });
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
