<?php
$pageKey = 'home';
$seoDefaults = [
    'meta_title' => 'JudiciaryPRO',
    'meta_description' => 'JudiciaryPRO offers judiciary and prosecutor exam preparation with clear concepts, disciplined practice, and personal mentorship.',
    'meta_keywords' => 'judiciary, prosecutor, exam prep, law, coaching, Gurugram',
];
$bodyClass = 'home';
include __DIR__ . '/includes/header.php';
$featuredCourses = db()->query("SELECT * FROM courses WHERE status = 'published' ORDER BY is_featured DESC, created_at DESC LIMIT 6")->fetchAll();
$courseCategories = db()->query("SELECT DISTINCT category FROM courses WHERE status = 'published' AND TRIM(category) <> '' ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
$courseCategories = array_values(array_filter(array_map('trim', $courseCategories)));
$latestEvents = db()->query("SELECT * FROM events WHERE status = 'published' ORDER BY event_date DESC, created_at DESC LIMIT 4")->fetchAll();
$latestNews = db()->query("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC, created_at DESC LIMIT 3")->fetchAll();
$rankings = db()->query("SELECT * FROM student_rankings WHERE status = 'published' ORDER BY year DESC, created_at DESC")->fetchAll();
?>
<!--BANNER-->
<section class="jp-edu-banner">
    <div class="container">
        <div class="row jp-edu-hero">
            <div class="col-xs-12 col-md-6">
                <h2 class="jp-edu-title">India’s Premier Law Institute

 <span>JudiciaryPRO</span></h2>
                <p class="jp-edu-text">Prepare for Judicial Services Examinations (PCS-J), CLAT PG and CLAT UG with expert mentorship, structured courses, and proven guidance. With 12+ years of experience in legal education, JudiciaryPRO has helped hundreds of aspirants build strong legal foundations and achieve success in competitive law examinations.</p>
                <div class="jp-signup-panel" id="jp-signup-panel">
                    <div class="jp-edu-search jp-edu-phone">
                        <span class="jp-edu-flag">IN</span>
                        <span class="jp-edu-code">+91</span>
                        <input type="tel" id="jp-phone" placeholder="Enter 10-digit mobile number" inputmode="numeric" maxlength="10" pattern="[0-9]{10}" autocomplete="tel">
                        <button type="button" id="jp-send-otp" aria-label="Send OTP">Send OTP</button>
                    </div>
                    <div class="jp-edu-help" id="jp-otp-help"> Get A Call Back From Our Expert Mentor.</div>

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
                <!-- <div class="jp-edu-tutors">
                    <div class="jp-edu-avatars">
                        <img src="assets/img/icon/1.png" alt="Tutor 1">
                        <img src="assets/img/icon/2.png" alt="Tutor 2">
                        <img src="assets/img/icon/3.png" alt="Tutor 3">
                        <span>2k+</span>
                    </div>
                    <div class="jp-edu-tutor-text">More Than<br>2k+ Tutors</div>
                </div> -->
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="jp-edu-courses">
                    <div class="jp-edu-courses-head">
                        <span class="jp-edu-pill">Top Courses</span>
                        <h3>Choose Your Program</h3>
                        <p>Focused courses designed for Judiciary & CLAT Aspirants.</p>
                    </div>
                    <div class="jp-edu-courses-grid">
                        <div class="jp-edu-course-card">
                            <span class="jp-edu-course-icon"><i class="fa fa-gavel" aria-hidden="true"></i></span>
                            <h4>Judiciary Preparation</h4>
                            <p>Complete preparation for Prelims, Mains and Interview stages.</p>
                        </div>
                        <div class="jp-edu-course-card">
                            <span class="jp-edu-course-icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></span>
                            <h4>CLAT UG Preparation</h4>
                            <p>Comprehensive preparation for CLAT and other top Law University entrance exams.</p>
                        </div>
                        <div class="jp-edu-course-card">
                            <span class="jp-edu-course-icon"><i class="fa fa-book" aria-hidden="true"></i></span>
                            <h4>Judiciary Test Series</h4>
                            <p>Structured Prelims and Mains test series with detailed evaluation and mentor feedback.</p>
                        </div>
                        <div class="jp-edu-course-card">
                            <span class="jp-edu-course-icon"><i class="fa fa-line-chart" aria-hidden="true"></i></span>
                            <h4>Answer Writing Program</h4>
                            <p>Focused training to improve Judiciary - Mains answer writing skills.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--BANNER END-->
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
                        <input type="text" class="form-control" name="course" placeholder="Course Interested In">
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
<!--COURSES HIGHLIGHT-->
<section class="jp-courses-highlight" id="jp-course-highlights">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="jp-courses-head">
                    <h2>Our Courses</h2>
                    <p>Structured Courses Designed for Judiciary & Law Entrance Exams</p>
                </div>
            </div>
        </div>
        <div class="jp-courses-cards">
            <article class="jp-course-card">
                <a class="jp-course-link" href="clat-ug">
                    <!-- <div class="jp-course-media">
                        <img src="assets/img/courses/course1.jpg" alt="CLAT UG Foundation">
                    </div> -->
                    <div class="jp-course-body">
                        <div class="jp-course-icon"><i class="fa fa-graduation-cap" aria-hidden="true"></i></div>
                        <h3>CLAT UG Foundation</h3>
                        <ul>
                            <li>Complete preparation for CLAT and other Law entrance exams</li>
                            <li>Legal Reasoning, Logical Reasoning, English & Current Affairs</li>
                            <li>Weekly sectional tests with detailed analysis</li>
                            <li>Full-length mock tests based on the latest CLAT pattern</li>
                            <li>Personalized guidance and doubt-clearing sessions</li>
                        </ul>
                        <span class="jp-course-cta">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                    </div>
                </a>
            </article>
            <article class="jp-course-card">
                <a class="jp-course-link" href="clat-pg">
                    <!-- <div class="jp-course-media">
                        <img src="assets/img/courses/course2.jpg" alt="CLAT PG Target">
                    </div> -->
                    <div class="jp-course-body">
                        <div class="jp-course-icon"><i class="fa fa-book" aria-hidden="true"></i></div>
                        <h3>CLAT PG Target</h3>
                        <ul>
                            <li>Comprehensive preparation for CLAT PG and LLM entrance exams</li>
                            <li>Important case laws and subject-wise coverage</li>
                            <li>Topic-wise MCQ practice and mock tests</li>
                            <li>Strategy sessions for maximizing CLAT PG scores</li>
                            <li>Performance tracking with mentor feedback</li>
                        </ul>
                        <span class="jp-course-cta">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                    </div>
                </a>
            </article>
            <article class="jp-course-card">
                <a class="jp-course-link" href="judiciary-foundation">
                    <!-- <div class="jp-course-media">
                        <img src="assets/img/courses/course3.jpg" alt="Judiciary Foundation">
                    </div> -->
                    <div class="jp-course-body">
                        <div class="jp-course-icon"><i class="fa fa-gavel" aria-hidden="true"></i></div>
                        <h3>Judiciary Foundation</h3>
                        <ul>
                            <li>Complete preparation for Judicial Services Examinations (PCS-J)</li>
                            <li>Integrated Prelims + Mains preparation strategy</li>
                            <li>Comprehensive coverage of major & minor law subjects</li>
                            <li>Weekly tests and answer writing practice</li>
                            <li>Personal mentorship and performance evaluation</li>
                            
                        </ul>
                        <span class="jp-course-cta">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                    </div>
                </a>
            </article>
            <article class="jp-course-card">
                <a class="jp-course-link" href="mains-answer-writing">
                    <!-- <div class="jp-course-media">
                        <img src="assets/img/courses/course4.jpg" alt="Mains Answer Writing">
                    </div> -->
                    <div class="jp-course-body">
                        <div class="jp-course-icon"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>
                        <h3>Judiciary Saarthi</h3>
                        <ul>
                            <li>Daily answer writing practice</li>
                            <li>Training on structure, presentation, and legal articulation</li>
                            <li>Topic-wise evaluation with detailed faculty feedback</li>
                            <li>Improvement focused mentoring to boost mains performance</li>
                            
                        </ul>
                        <span class="jp-course-cta">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                    </div>
                </a>
            </article>
            <article class="jp-course-card">
                <a class="jp-course-link" href="prosecutor">
                    <!-- <div class="jp-course-media">
                        <img src="assets/img/courses/course5.jpg" alt="Prosecutor Batch">
                    </div> -->
                    <div class="jp-course-body">
                        <div class="jp-course-icon"><i class="fa fa-balance-scale" aria-hidden="true"></i></div>
                        <h3>Prosecutor Batch</h3>
                        <ul>
                            <li>Focused preparation for APO / Prosecutor examinations</li>
                            <li>Strong coverage of criminal law and procedural subjects</li>
                            <li>PYQs, mock tests, and exam-oriented strategy sessions</li>
                            <li>Regular doubt-clearing and revision support</li>
                        </ul>
                        <span class="jp-course-cta">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                    </div>
                </a>
            </article>
            <article class="jp-course-card">
                <a class="jp-course-link" href="crash-revision">
                    <!-- <div class="jp-course-media">
                        <img src="assets/img/courses/course6.jpg" alt="Crash Revision Program">
                    </div> -->
                    <div class="jp-course-body">
                        <div class="jp-course-icon"><i class="fa fa-line-chart" aria-hidden="true"></i></div>
                        <h3>Judiciary Brahmastra </h3>
                        <ul>
                            <li>Advanced-level revision for Judiciary examinations</li>
                            <li>Rapid coverage of high-yield and exam-relevant topics</li>
                            <li>Intensive revision classes, mock tests, and strategy support</li>
                            <li>Designed for serious aspirants preparing for the final stage of revision</li>
                        </ul>
                        <span class="jp-course-cta">View Details <i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                    </div>
                </a>
            </article>
        </div>
    </div>
</section>
<!--COURSES HIGHLIGHT END-->
<!--AUTO SLIDE BANNER-->
<section class="jp-auto-slider" id="jp-auto-slider">
    <div class="container">
        <div id="jp-auto-carousel" class="carousel slide" data-ride="carousel" data-interval="3500" data-pause="false">
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <img src="assets/img/slider/banners1.webp" alt="JudiciaryPRO Banner 1">
                    <!-- <div class="jp-auto-caption">
                        <h3>CLAT UG + PG Coaching</h3>
                        <p>Concept classes, mocks, and doubt support with a clear study plan.</p>
                    </div> -->
                </div>
                <div class="item">
                    <img src="assets/img/slider/banners2.webp" alt="JudiciaryPRO Banner 2">
                    <!-- <div class="jp-auto-caption">
                        <h3>Judiciary Foundation Batch</h3>
                        <p>Prelims + Mains integrated prep with weekly tests and feedback.</p>
                    </div> -->
                </div>
                <!-- <div class="item">
                    <img src="assets/img/slider/3.jpg" alt="JudiciaryPRO Banner 3">
                    <div class="jp-auto-caption">
                        <h3>Prosecutor Exam Track</h3>
                        <p>Criminal law focus, PYQs, and exam-aligned test series.</p>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</section>
<!--AUTO SLIDE BANNER END-->
<!--ABOUT-->
<section class="jp-about-section" id="jp-about">
    <div class="container">
        <div class="row jp-about-row">
            <div class="col-xs-12 col-md-6">
                <div class="jp-about-media">
                    <img src="assets/img/about-us.png" alt="About JudiciaryPRO">
                    <div class="jp-about-badge">
                        Since 2013
                        <span>50+ Selections</span>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="jp-about-card">
                    <h2>About JudiciaryPRO</h2>
                    <p>JudiciaryPRO is founded by Sparsh Jain, a mentor with over 12 years of experience in legal education and judiciary preparation.</p>
                    <p>Over the years, thousands of law students have been guided through structured classroom programs focused on conceptual clarity, answer writing practice, and disciplined preparation.</p>
                    <p>Our teaching approach combines expert faculty guidance, regular testing, and personal mentorship to help aspirants succeed in Judicial Services examinations and law entrance tests.</p>
                    <p>Today, JudiciaryPRO continues that mission through a modern hybrid learning model (online + offline) designed to support aspirants across India.</p>
                    <div class="jp-about-points">
                        <div class="jp-point"><i class="fa fa-check"></i><span>Integrated Prelims + Mains strategy</span></div>
                        <div class="jp-point"><i class="fa fa-check"></i><span>Weekly tests with mentor feedback</span></div>
                        <div class="jp-point"><i class="fa fa-check"></i><span>Personal mentorship till final selection</span></div>
                    </div>
                    <a class="lgx-btn lgx-btn-sm" href="about">Read More</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--ABOUT END-->
<!--LEAD FORM + SLIDER-->
<section>
    <div id="lgx-lead" class="jp-lead">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="jp-lead-card">
                            <h3>Get Free Career Guidance</h3>
                            <p>Not sure where to begin your Judiciary or CLAT preparation?</p>
                            <form action="ajax/counselling.php" method="POST" class="jp-lead-form" id="jp-counselling-form">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Full Name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email Address" name="email" required>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Phone Number" name="phone" required>
                                </div>
                                <button type="submit" class="lgx-btn lgx-btn-sm">Request Call Back</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="jp-lead-carousel">
                            <div id="jp-lead-carousel" class="carousel slide" data-ride="carousel" data-interval="4000">
                                <ol class="carousel-indicators">
                                    <li data-target="#jp-lead-carousel" data-slide-to="0" class="active"></li>
                                    <li data-target="#jp-lead-carousel" data-slide-to="1"></li>
                                    <li data-target="#jp-lead-carousel" data-slide-to="2"></li>
                                </ol>
                                <div class="carousel-inner" role="listbox">
                                    <div class="item active">
                                        <img src="assets/img/aboutus.webp" alt="Judiciary Preparation Banner">
                                        <!-- <div class="carousel-caption">
                                            <h4>Judiciary Foundation Batch</h4>
                                            <p>Complete preparation with mentorship and weekly tests.</p>
                                        </div> -->
                                    </div>
                                    <div class="item">
                                        <img src="assets/img/leadslider/rank2.webp" alt="Mains Answer Writing">
                                        <!-- <div class="carousel-caption">
                                            <h4>Mains Answer Writing</h4>
                                            <p>Structure, speed, and clarity with guided practice.</p>
                                        </div> -->
                                    </div>
                                    <div class="item">
                                        <img src="assets/img/leadslider/rank3.webp" alt="Prosecutor Batch">
                                        <!-- <div class="carousel-caption">
                                            <h4>Prosecutor Batch</h4>
                                            <p>Foundation + test series for prosecutor exams.</p>
                                        </div> -->
                                    </div>
                                </div>
                                <a class="left carousel-control" href="#jp-lead-carousel" role="button" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#jp-lead-carousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>
<!--LEAD FORM + SLIDER END-->
<!--RESULTS-->
<section>
    <div id="lgx-results" class="jp-results">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h2 class="heading-title">Our Results</h2>
                            <h4 class="heading-subtitle">Over the years, our students have secured ranks and selections in Judicial Services examinations and top law entrance exams.</h4>
                        </div>
                    </div>
                </div>
                <div class="row jp-results-wrap">
                    <div class="col-xs-12">
                        <div class="jp-results-grid owl-carousel" id="jp-results-carousel">
                            <?php if (!$rankings): ?>
                                <p>No student rankings published yet.</p>
                            <?php else: ?>
                                <?php foreach ($rankings as $student): ?>
                                    <?php
                                    $rankLabel = trim($student['rank_title'] ?? '');
                                    $rankLabel = $rankLabel !== '' ? $rankLabel : trim($student['score'] ?? '');
                                    $rankLabel = $rankLabel !== '' ? $rankLabel : '-';
                                    $photoPath = trim($student['photo'] ?? '');
                                    $photoUrl = $photoPath !== '' ? url_for($photoPath) : url_for('assets/img/teachers/teacher1.jpg');
                                    $yearValue = trim($student['year'] ?? '');
                                    $yearAttr = $yearValue !== '' ? $yearValue : 'unknown';
                                    $sublineParts = array_filter([trim($student['exam'] ?? ''), $yearValue]);
                                    $subline = $sublineParts ? implode(' � ', $sublineParts) : '';
                                    ?>
                                    <article class="jp-result-card">
                                        <div class="jp-result-top">
                                            <div class="jp-result-rank">
                                                <div class="jp-result-rank-label">RANK</div>
                                                <div class="jp-result-rank-number"><?= h($rankLabel) ?></div>
                                            </div>
                                            <div class="jp-result-photo">
                                                <img src="<?= h($photoUrl) ?>" alt="<?= h($student['student_name'] ?? 'Student') ?>">
                                            </div>
                                        </div>
                                        <div class="jp-result-info">
                                            <h4><?= h($student['student_name'] ?? '') ?></h4>
                                            <?php if ($subline !== ''): ?>
                                                <p><?= h($subline) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--RESULTS END-->
<section>
    <div id="lgx-courses" class="lgx-courses">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h2 class="heading-title">Courses</h2>
                            <h4 class="heading-subtitle">Focused programs for judiciary and prosecutor exams</h4>
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
                <div class="lgx-tab">
                    <?php if (!$featuredCourses): ?>
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
                                <?php foreach ($featuredCourses as $course): ?>
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
<!--MILESTONE-->
<section>
    <div id="lgx-register" class="lgx-register">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="lgx-registration-area">
                            <div class="lgx-heading-registration">
                                <h3 class="subtitle">Begin Your Journey to the Bench</h3>
                                <h2 class="title">Enroll Today!</h2>
                            </div>
                            <div class="lgx-registration-info">
                                <p class="text">Start your preparation for Judicial Services and CLAT with structured guidance, expert mentorship, and a proven learning system.</p>
                                <p class="text">Build strong legal concepts, develop answer writing skills, and prepare with discipline to achieve your goal of becoming a judge or a successful law professional.</p>
                                <a class="lgx-btn registration-btn" href="#">Enroll Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <iframe width="100%" height="400 px" src="https://www.youtube.com/embed/jQzQiOa749I?si=x8v2IcPoAqfp1Dn8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<!--MILESTONE END-->

<!--EVENTS-->
<section>
    <div id="lgx-events" class="lgx-events">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h2 class="heading-title">Upcoming Batches & Events</h2>
                            <h4 class="heading-subtitle">Stay updated with our career guidance sessions, seminars, and preparation workshops designed for law aspirants.</h4>
                            
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-events-area">
                            <div class="row">
                                <?php if (!$latestEvents): ?>
                                    <div class="col-xs-12">
                                        <p>No upcoming events yet.</p>
                                    </div>
                                <?php else: ?>
                                    <?php
                                    $featuredEvent = $latestEvents[0];
                                    $otherEvents = array_slice($latestEvents, 1);
                                    $featuredImage = trim($featuredEvent['image'] ?? '') !== '' ? url_for($featuredEvent['image']) : url_for('assets/img/blog-couese.jpeg');
                                    $featuredSlug = trim($featuredEvent['slug'] ?? '');
                                    $featuredLink = $featuredSlug !== '' ? url_for('event/' . rawurlencode($featuredSlug)) : '#';
                                    ?>
                                    <div class="col-md-6">
                                        <div class="lgx-featured-event">
                                            <figure>
                                                <a href="<?= $featuredLink ?>"><img src="<?= h($featuredImage) ?>" alt="<?= h($featuredEvent['title'] ?? 'featured event') ?>"></a>
                                                <figcaption>
                                                    <div class="figcaption">
                                                        <h3><?= h($featuredEvent['title'] ?? '') ?></h3>
                                                    </div>
                                                    <div class="event-info">
                                                        <?php if (!empty($featuredEvent['short_description'])): ?>
                                                            <p><?= h($featuredEvent['short_description']) ?></p>
                                                        <?php endif; ?>
                                                        <?php if (!empty($featuredEvent['location'])): ?>
                                                            <p><?= h($featuredEvent['location']) ?></p>
                                                        <?php endif; ?>
                                                        <?php if (!empty($featuredEvent['event_date'])): ?>
                                                            <p><?= h(date('d M Y', strtotime($featuredEvent['event_date']))) ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                    <?php foreach ($otherEvents as $event): ?>
                                        <?php
                                        $eventImage = trim($event['image'] ?? '') !== '' ? url_for($event['image']) : url_for('assets/img/blog-couese.jpeg');
                                        $eventSlug = trim($event['slug'] ?? '');
                                        $eventLink = $eventSlug !== '' ? url_for('event/' . rawurlencode($eventSlug)) : '#';
                                        ?>
                                        <div class="col-md-6">
                                            <div class="lgx-single-event">
                                                <div class="thumb">
                                                    <a href="<?= $eventLink ?>"><img src="<?= h($eventImage) ?>" alt="<?= h($event['title'] ?? 'event') ?>"></a>
                                                </div>
                                                <div class="event-info">
                                                    <h4 class="title"><?= h($event['title'] ?? '') ?></h4>
                                                    <?php if (!empty($event['short_description'])): ?>
                                                        <p><?= h($event['short_description']) ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($event['event_date']) || !empty($event['location'])): ?>
                                                        <ul>
                                                            <?php if (!empty($event['event_date'])): ?>
                                                                <li><?= h(date('d M Y', strtotime($event['event_date']))) ?></li>
                                                            <?php endif; ?>
                                                            <?php if (!empty($event['location'])): ?>
                                                                <li><?= h($event['location']) ?></li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
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
<!--BLOG -->
<section>
    <div id="lgx-news" class="lgx-blog">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading lgx-heading-white">
                            <h2 class="heading-title">Insights & Preparation Resources</h2>
                            <h4 class="heading-subtitle">Latest updates, preparation strategies, and exam insights for Judiciary and Law Entrance aspirants.</h4>
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
                <div class="row">
                    <?php if (!$latestNews): ?>
                        <div class="col-xs-12">
                            <p>No news published yet.</p>
                        </div>
                    <?php else: ?>
                        <div id="lgx-owlnews" class="owl-carousel lgx-owlnews">
                            <?php foreach ($latestNews as $news): ?>
                                <?php
                                $newsImage = trim($news['image'] ?? '') !== '' ? url_for($news['image']) : url_for('assets/img/news/news1.jpg');
                                $newsSlug = trim($news['slug'] ?? '');
                                $newsLink = $newsSlug !== '' ? url_for('news/' . rawurlencode($newsSlug)) : '#';
                                $authorName = $news['author'] ?: 'JudiciaryPRO';
                                $publishedAt = $news['published_at'] ?: $news['created_at'];
                                ?>
                                <div class="item">
                                    <div class="lgx-news-single">
                                        <figure>
                                            <img src="<?= h($newsImage) ?>" alt="<?= h($news['title'] ?? 'News') ?>" title="<?= h($news['title'] ?? 'News') ?>"/>
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
                                            <h3 class="title"><a href="<?= $newsLink ?>"><?= h($news['title'] ?? '') ?></a></h3>
                                            <?php if (!empty($news['excerpt'])): ?>
                                                <p class="text"><?= h($news['excerpt']) ?></p>
                                            <?php endif; ?>
                                            <div class="hits-area">
                                                <span class="date"></span>
                                            </div>
                                            <div class="text-bottom">
                                                <a class="date" href="#"><?= $publishedAt ? h(date('d M Y', strtotime($publishedAt))) : '' ?></a>
                                                <a class="link" href="<?= $newsLink ?>"><i class="fa  fa-long-arrow-right" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<!--BLOG END-->
<!--PHOTO GALLERY-->
<section>
    <div id="lgx-photo-gallery" class="lgx-photo-gallery"> <!--lgx-gallery-without-subsctibe-->
        <div id="lgx-memorisinner" class="lgx-memorisinner">
            <div class="lgx-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="lgx-heading">
                                <h2 class="heading-title">Photo Gallery</h2>
                                <h4 class="heading-subtitle">Some Amazing Think From Our Campus</h4>
                            </div>
                        </div>
                    </div>
                    <div class="lgx-gallery-area">
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/l1.webp" alt="Gurugram University 1" title="Gurugram University 1" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="Gurugram University 1" href="assets/img/gallery/l1.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/l2.webp" alt="IILM 1" title="IILM 1" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="IILM 1" href="assets/img/gallery/l2.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/l3.webp" alt="IILM 3" title="IILM 3" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="IILM 3" href="assets/img/gallery/l3.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/l4.webp" alt="NIMT 2" title="NIMT 2" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="NIMT 2" href="assets/img/gallery/l4.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/l5.webp" alt="Sunder deep 1" title="Sunder deep 1" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="Sunder deep 1" href="assets/img/gallery/l5.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/l6.webp" alt="Sunder deep 2" title="Sunder deep 2" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="Sunder deep 2" href="assets/img/gallery/l6.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/p1.webp" alt="Sushant 2" title="Sushant 2" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="Sushant 2" href="assets/img/gallery/p1.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/p2.webp" alt="Innovative College 3" title="Innovative College 3" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="Innovative College 3" href="assets/img/gallery/p2.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/p3.webp" alt="K.R Mangalam 1" title="K.R Mangalam 1" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="K.R Mangalam 1" href="assets/img/gallery/p3.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/p4.webp" alt="Northcap 2" title="Northcap 2" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="Northcap 2" href="assets/img/gallery/p4.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/p5.webp" alt="Sushant 3" title="Sushant 3" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="Sushant 3" href="assets/img/gallery/p5.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="lgx-gallery-single">
                            <figure>
                                <img src="assets/img/gallery/p6.webp" alt="Sunder deep 3" title="Sunder deep 3" />
                                <figcaption class="lgx-figcaption">
                                    <div class="lgx-hover-link">
                                        <div class="lgx-vertical">
                                            <a title="Sunder deep 3" href="assets/img/gallery/p6.webp">
                                                <i class="fa fa-search fa-2x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
                        </div>

                    

                    </div>
                </div> <!--//.CONAINER-->
            </div>
        </div><!--//.lgx CONTACT INNER-->
    </div>
</section>
<!--PHOTO GALLERY END-->
<!--FAQ-->
<section class="jp-faq" id="jp-faq">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="jp-faq-head">
                    <h2>Frequently Asked Questions</h2>
                    <p>Find answers to common queries regarding courses, batches, test series, mentorship, and enrollment.</p>
                </div>
            </div>                
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1">

                <div class="panel-group jp-faq-accordion" id="jp-faq-accordion" role="tablist" aria-multiselectable="true">

                    <!-- Q1 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h1">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c1">
                                    Why is JudiciaryPRO considered one of the best judiciary coaching institutes in Gurugram and Delhi NCR?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c1" class="panel-collapse collapse in">
                            <div class="panel-body">
                                JudiciaryPRO is regarded as one of the best judiciary coaching institutes in Gurugram and Delhi NCR because of its structured preparation approach, experienced faculty, and result-oriented teaching methodology. Under the mentorship of Sparsh Sir, students receive conceptual clarity, answer writing training, and regular test practice required to succeed in Judicial Services examinations (PCS-J).
                            </div>
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h2">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c2">
                                    Who teaches judiciary preparation at JudiciaryPRO?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c2" class="panel-collapse collapse">
                            <div class="panel-body">
                                Judiciary preparation at JudiciaryPRO is guided by Sparsh Jain (Sparsh Sir), who has more than 12 years of experience in teaching law and mentoring judiciary aspirants. His teaching focuses on conceptual understanding of law, structured answer writing, and exam-oriented preparation for Judicial Services examinations.
                            </div>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h3">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c3">
                                    Do you provide judiciary coaching in Gurugram and Delhi NCR?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c3" class="panel-collapse collapse">
                            <div class="panel-body">
                                Yes. JudiciaryPRO provides offline judiciary coaching in Gurugram and also offers online classes for students across Delhi NCR and other parts of India. The hybrid model allows students to attend classroom sessions or learn through online live classes.
                            </div>
                        </div>
                    </div>

                    <!-- Q4 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h4">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c4">
                                    Is JudiciaryPRO the best coaching for judiciary preparation in Delhi NCR?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c4" class="panel-collapse collapse">
                            <div class="panel-body">
                                JudiciaryPRO is widely recognized among aspirants as one of the best coaching institutes for judiciary preparation in Delhi NCR because of its structured curriculum, experienced mentorship, and focus on both prelims and mains preparation. Students benefit from regular tests, answer writing practice, and personal mentorship.
                            </div>
                        </div>
                    </div>

                    <!-- Q5 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h5">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c5">
                                    Do you also provide CLAT coaching in Gurugram and Delhi NCR?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c5" class="panel-collapse collapse">
                            <div class="panel-body">
                                Yes. JudiciaryPRO also offers CLAT UG coaching for students in Gurugram, Delhi, and Delhi NCR. The CLAT program covers Legal Reasoning, Logical Reasoning, English, Current Affairs, and Quantitative Techniques with regular mock tests and expert guidance.
                            </div>
                        </div>
                    </div>

                    <!-- Q6 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h6">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c6">
                                    What courses are available for judiciary preparation at JudiciaryPRO?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c6" class="panel-collapse collapse">
                            <div class="panel-body">
                                JudiciaryPRO offers several courses for judiciary preparation including Judiciary Foundation Programs, Judiciary Test Series, Judiciary Mains Answer Writing Programs, and Judiciary Brahmastra Crash Courses. These programs are designed to prepare students for Judicial Services examinations across different states.
                            </div>
                        </div>
                    </div>

                    <!-- Q7 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h7">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c7">
                                    Can beginners join judiciary coaching at JudiciaryPRO?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c7" class="panel-collapse collapse">
                            <div class="panel-body">
                                Yes. Beginners who have recently completed or are pursuing LL.B can join judiciary coaching at JudiciaryPRO. The foundation programs are designed to build strong legal concepts and guide students step-by-step through the preparation process for Judicial Services examinations.
                            </div>
                        </div>
                    </div>

                    <!-- Q8 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h8">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c8">
                                    Do you provide both online and offline judiciary coaching?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c8" class="panel-collapse collapse">
                            <div class="panel-body">
                                Yes. JudiciaryPRO provides offline classroom coaching in Gurugram as well as online judiciary coaching for students across Delhi NCR and India. This hybrid learning model ensures flexibility and accessibility for all aspirants.
                            </div>
                        </div>
                    </div>

                    <!-- Q9 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h9">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c9">
                                    How can I enroll in judiciary coaching at JudiciaryPRO?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c9" class="panel-collapse collapse">
                            <div class="panel-body">
                                Students can enroll in judiciary coaching at JudiciaryPRO by visiting the official website, filling out the counseling form, or contacting the institute directly for free career guidance and course selection assistance.
                            </div>
                        </div>
                    </div>

                    <!-- Q10 -->
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="jp-faq-h10">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#jp-faq-accordion" href="#jp-faq-c10">
                                    Why should I choose JudiciaryPRO for CLAT and judiciary preparation?
                                </a>
                            </h4>
                        </div>
                        <div id="jp-faq-c10" class="panel-collapse collapse">
                            <div class="panel-body">
                                JudiciaryPRO offers structured courses, expert mentorship by Sparsh Sir, regular testing, answer writing practice, and personalized guidance. With more than 12 years of experience in legal education, the institute helps students prepare effectively for Judicial Services examinations and CLAT law entrance exams.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--FAQ END-->
<?php include __DIR__ . '/includes/footer.php'; ?>



