<?php
require __DIR__ . '/bootstrap.php';
$pageKey = $pageKey ?? 'page';
$seoDefaults = $seoDefaults ?? [
    'meta_title' => 'JudiciaryPRO',
    'meta_description' => 'JudiciaryPRO offers judiciary and prosecutor exam preparation with clear concepts, disciplined practice, and personal mentorship.',
    'meta_keywords' => 'judiciary, prosecutor, exam prep, law, coaching, Gurugram',
    'robots' => config()['site']['default_robots'],
];
$bodyClass = $bodyClass ?? 'page';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$baseUrl = base_url();
$baseHref = $baseUrl !== '' ? rtrim($baseUrl, '/') . '/' : '/';
$currentUrl = $requestUri !== '' ? $requestUri : $baseUrl;
if ($baseUrl !== '' && preg_match('/^https?:\\/\\//i', $baseUrl)) {
    $currentUrl = rtrim($baseUrl, '/') . $requestUri;
}
$seoDefaults['canonical_url'] = $seoDefaults['canonical_url'] ?? $currentUrl;
$seoDefaults['site_name'] = $seoDefaults['site_name'] ?? 'JudiciaryPRO';
$seoDefaults['og_type'] = $seoDefaults['og_type'] ?? 'website';
$seoDefaults['twitter_card'] = $seoDefaults['twitter_card'] ?? 'summary';
$seoDefaults['twitter_site'] = $seoDefaults['twitter_site'] ?? '';
$seoDefaults['twitter_creator'] = $seoDefaults['twitter_creator'] ?? $seoDefaults['twitter_site'];
$seoMeta = function_exists('seo_merge') ? seo_merge($pageKey, $seoDefaults) : $seoDefaults;
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <base href="<?= h($baseHref) ?>">
    <?php if (function_exists('render_seo')): ?>
        <?php render_seo($pageKey, $seoDefaults); ?>
    <?php else: ?>
        <title><?= h($seoDefaults['meta_title'] ?? 'JudiciaryPRO') ?></title>
    <?php endif; ?>

    <meta name="author" content="JudiciaryPRO"/>

    <?php if (!empty($seoMeta['og_type'])): ?>
        <meta property="og:type" content="<?= h($seoMeta['og_type']) ?>"/>
    <?php endif; ?>
    <?php if (!empty($seoMeta['canonical_url'])): ?>
        <meta property="og:url" content="<?= h($seoMeta['canonical_url']) ?>"/>
    <?php endif; ?>
    <?php if (!empty($seoMeta['site_name'])): ?>
        <meta property="og:site_name" content="<?= h($seoMeta['site_name']) ?>"/>
    <?php endif; ?>
    <?php if (!empty($seoMeta['twitter_card'])): ?>
        <meta name="twitter:card" content="<?= h($seoMeta['twitter_card']) ?>"/>
    <?php endif; ?>
    <?php if (!empty($seoMeta['twitter_site'])): ?>
        <meta name="twitter:site" content="<?= h($seoMeta['twitter_site']) ?>"/>
    <?php endif; ?>
    <?php if (!empty($seoMeta['twitter_creator'])): ?>
        <meta name="twitter:creator" content="<?= h($seoMeta['twitter_creator']) ?>"/>
    <?php endif; ?>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <link rel="apple-touch-icon" sizes="57x57" href="assets/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicon/manifest.json">
    <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.min.css" media="all"/>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/libs/fontawesome/css/font-awesome.min.css" media="all"/>
    <link rel="stylesheet" href="assets/libs/maginificpopup/magnific-popup.css" media="all"/>
    <link rel="stylesheet" href="assets/libs/owlcarousel/owl.carousel.min.css" media="all" />
    <link rel="stylesheet" href="assets/libs/owlcarousel/owl.theme.default.min.css" media="all" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,900%7cUbuntu:300,300i,400,500,700"/>
    <link rel="stylesheet" href="assets/libs/animate/animate.css" media="all" />
    <link id="lgx-master-style" rel="stylesheet" href="assets/css/style-default.min.css" media="all"/>
    <link rel="stylesheet" href="assets/css/custom.css" media="all"/>
    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
    <style>
/* Thsi is for the footer section  */
/* whatsapp */
.whatsapp {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 60px;
  height: 60px;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: .1s all ease-in-out;
  border-radius: 50%;
  font-size: 2rem;
  color: white;
  box-shadow: 0 0 .75rem #18d26e;
  line-height: 0.5;

  background-color: #18d26e;
}

.whatsapp:hover {
  background-color: white;
  color: #18d26e;
}

.call {
  position: fixed;
  bottom: 20px;
  left: 20px;
  width: 60px;
  transition: .1s all ease-in-out;
  height: 60px;
  display: flex;
  line-height: 1;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  font-size: 2rem;
  color: white;
  background-color: #222222;
  box-shadow: 0 0 .5rem #eee;
}

.call:hover {
  background-color: white;
  color: #222222;
}

</style>
</head>
<body class="<?= h($bodyClass) ?>">
<div class="lgx-container ">
<!-- ***  ADD YOUR SITE CONTENT HERE *** -->
<!--HEADER-->
<header>
    <div id="lgx-header" class="lgx-header">
        <div class="header-top">
            <div class="header-top-inner">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact">
                                <ul class="list-inline">
                                    <li><i class="fa fa-phone" aria-hidden="true"></i> +91 8447777020</li>
                                    <li><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:help.judiciarypro@gmail.com">help.judiciarypro@gmail.com</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="right-menu">
                                <ul class="list-inline">
                                    
                                    <li><a href="https://www.facebook.com/people/Judiciary-Pro/61585445547667/" target="_blank"><i class="fa fa-facebook-f" aria-hidden="true"></i></a></li>
                                    <li><a href="https://www.instagram.com/judiciary.pro/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                    <li><a href="https://in.linkedin.com/company/judiciarypro" target="_blank" ><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                                    <li><a href="https://www.youtube.com/@Judiciary-PRO"><i class="fa fa-youtube-play" target="_blank"aria-hidden="true"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lgx-header-bottom lgx-header-bottom-fixed-black lgx-header-bottom-fixed-transparent lgx-header-bottom-scrol-black">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <nav class="navbar navbar-default lgx-navbar">
                            <div class="container">
                                <nav class="navbar navbar-default lgx-navbar lgx-navbar-search">
                                    <div class="lgxcontainer">
                                        <div class="navbar-header">
                                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                                <span class="sr-only">Toggle navigation</span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                            </button>
                                            
                                            <div class="lgx-logo">
                                                <a href="index" class="lgx-scroll">
                                                    <img src="assets/img/logo2.png" alt="Logo"/>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="collapse navbar-collapse">
                                            <ul class="nav navbar-nav lgx-nav">
                                                <li><a href="index" class="active">Home</a></li>
                                                <li><a class="lgx-scroll active" href="about">About</a></li>
                                                <li><a class="lgx-scroll" href="courses">Courses</a></li>
                                                <li><a class="lgx-scroll" href="materials">Materials</a></li>
                                                <li><a class="lgx-scroll" href="events">Events</a></li>
                                                <li><a class="lgx-scroll" href="news">News</a></li>
                                                <li><a class="lgx-scroll" href="contact">Contact</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--HEADER END-->
