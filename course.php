<?php
require __DIR__ . '/includes/bootstrap.php';
$pageKey = 'course';
$bodyClass = 'page page-template';
$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare("SELECT * FROM courses WHERE slug = ? AND status = 'published' LIMIT 1");
$stmt->execute([$slug]);
$course = $stmt->fetch();

if (!$course) {
    http_response_code(404);
    $seoDefaults = ['meta_title' => 'Course Not Found'];
    include __DIR__ . '/includes/header.php';
    echo '<div class="container"><h2>Course not found.</h2></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$seoDefaults = [
    'meta_title' => $course['title'] . ' - JudiciaryPRO',
    'meta_description' => $course['summary'],
];
include __DIR__ . '/includes/header.php';
?>
<section>
    <div class="lgx-banner lgx-banner-inner">
        <div class="lgx-inner">
        </div><!-- //.INNER -->
    </div>
</section>
    <!--NEWS-->
    <section>
        <div id="lgx-course" class="lgx-course lgx-normal-single">
            <div class="lgx-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <article>
                                <header>
                                    <div class="text-area">
                                        <h1 class="title"><a href="#"><?= h($course['title']) ?></a></h1>
                                        <div class="course-hits-area">
                                            <ul class="list-inline course-hit">
                                                <li>
                                                    <div class="course-author">
                                                        <?php
                                                        $authorImage = trim($course['author_image'] ?? '') !== '' ? url_for($course['author_image']) : url_for('assets/img/founder.png');
                                                        ?>
                                                        <img src="<?= h($authorImage) ?>" class="avatar" alt="Author avatar">
                                                        <div class="author-info">
                                                            <h4 class="title"><a href="#"><?= h($course['author_name'] ?: 'JudiciaryPRO') ?></a></h4>
                                                            <h5 class="subtitle"><?= h($course['author_title'] ?: 'Instructor') ?></h5>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="course-hit-info">
                                                        <h4 class="title"><a href="#">Categories</a></h4>
                                                        <h5 class="subtitle"><a href="#" rel="tag"><?= h($course['category'] ?: 'General') ?></a></h5>
                                                    </div>
                                                </li>
                                            </ul>

                                            <div class="course-hitcourse-payment">
                                                <div class="course-price">
                                                    <div class="value free-course"><?= ($course['price'] ?? 0) > 0 ? '&#8377;' . h(number_format((float)$course['price'], 2)) : 'Free' ?></div>
                                                </div>
                                                <form name="purchase-course" class="purchase-course form-purchase-course">
                                                    <button type="button" id="jp-joinus" class="button lgx-btn">Join Us!</button>
                                                    <input name="purchase-course" value="<?= h($course['price'] ?? 0) ?>" type="hidden">
                                                </form>
                                            </div>
                                        </div>
                                        <div class="jp-signup-panel jp-course-otp">
                                            <h6>Get A Call Back From Our Expert Mentor.</h6>
                                            <div class="jp-edu-search jp-edu-phone">
                                                <span class="jp-edu-flag">IN</span>
                                                <span class="jp-edu-code">+91</span>
                                                <input type="tel" id="jp-phone" placeholder="Enter 10-digit mobile number" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" autocomplete="tel">
                                                <button type="button" id="jp-send-otp" aria-label="Send OTP">Send OTP</button>
                                            </div>
                                            <div class="jp-edu-help" id="jp-otp-help">We will send an OTP for verification.</div>
                                            <div class="jp-otp-step" id="jp-otp-step">
                                                <div class="jp-edu-search jp-edu-phone">
                                                    <span class="jp-edu-code">OTP</span>
                                                    <input type="tel" id="jp-otp" placeholder="Enter OTP" inputmode="numeric" maxlength="6">
                                                    <button type="button" id="jp-verify-otp" aria-label="Verify OTP">Verify</button>
                                                </div>
                                                <div class="jp-edu-help jp-otp-note">Enter the OTP sent to your number.</div>
                                                <div class="jp-otp-actions">
                                                    <button type="button" class="jp-otp-link" id="jp-change-number">Change number</button>
                                                    <span class="jp-otp-timer" id="jp-otp-timer" aria-live="polite"></span>
                                                </div>
                                            </div>
                                            <div class="jp-edu-help jp-signup-status" id="jp-signup-status"></div>
                                        </div>
                                    </div>
                                    <figure>
                                        <?php
                                        $courseImage = trim($course['image'] ?? '') !== '' ? url_for($course['image']) : url_for('assets/img/courses/course1.jpg');
                                        ?>
                                        <a href="#"><img src="<?= h($courseImage) ?>" alt="<?= h($course['title']) ?>"/></a>
                                    </figure>
                                </header>
                                <section>
                                    <div class="lgx-course-feature-area">
                                        <h3 class="title">Course Features</h3>
                                        <ul class="list-unstyled lgx-course-feature">
                                            <li class="lectures-feature"> <i class="fa fa-files-o"></i> <span class="label">Lectures</span> <span class="value"><?= h($course['lectures'] ?? 0) ?></span></li>
                                            <li class="quizzes-feature"> <i class="fa fa-puzzle-piece"></i> <span class="label">Quizzes</span> <span class="value"><?= h($course['quizzes'] ?? 0) ?></span></li>
                                            <li class="duration-feature"> <i class="fa fa-clock-o"></i> <span class="label">Duration</span> <span class="value"><?= h($course['duration'] ?? '') ?></span></li>
                                            <li class="skill-feature"> <i class="fa fa-level-up"></i> <span class="label">Skill level</span> <span class="value"><?= h($course['skill_level'] ?? '') ?></span></li>
                                            <li class="language-feature"> <i class="fa fa-language"></i> <span class="label">Language</span> <span class="value"><?= h($course['language'] ?? '') ?></span></li>
                                            <li class="students-feature"> <i class="fa fa-users"></i> <span class="label">Students</span> <span class="value"><?= h($course['students_count'] ?? 0) ?></span></li>
                                            <li class="assessments-feature"> <i class="fa fa-check-square-o"></i> <span class="label">Assessments</span> <span class="value"><?= h($course['assessments'] ?? '') ?></span></li>
                                        </ul>
                                    </div>
                                    <h3>Course Description</h3>
                                    <?php if (!empty($course['description'])): ?>
                                        <?= $course['description'] ?>
                                    <?php elseif (!empty($course['summary'])): ?>
                                        <p><?= h($course['summary']) ?></p>
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
<!--SIGNUP MODAL-->
<div class="modal fade" id="jp-signup-modal" tabindex="-1" role="dialog" aria-labelledby="jpSignupLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content jp-signup-modal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="jpSignupLabel">Student Signup</h4>
            </div>
            <div class="modal-body">
                <form class="jp-signup-form" id="jp-signup-form">
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" class="form-control" name="phone" placeholder="Mobile Number" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="course" placeholder="Course Interested In" value="<?= h($course['title']) ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="city" placeholder="City">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="message" rows="3" placeholder="Message"></textarea>
                    </div>
                    <button type="submit" class="jp-signup-submit">Submit Details</button>
                    <div class="jp-edu-help jp-signup-status" id="jp-signup-status-modal"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--SIGNUP MODAL END-->
<?php include __DIR__ . '/includes/footer.php'; ?>



