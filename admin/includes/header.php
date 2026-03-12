<?php
require __DIR__ . '/bootstrap.php';
$title = $title ?? 'Admin';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($title) ?> - JudiciaryPRO Admin</title>
    <link rel="stylesheet" href="../assets/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/libs/fontawesome/css/font-awesome.min.css">
    <style>
        :root {
            --admin-bg: #eef1f7;
            --admin-surface: #ffffff;
            --admin-ink: #1f2430;
            --admin-muted: #6b7280;
            --admin-accent: #d7a338;
            --admin-shadow: 0 18px 30px rgba(31, 36, 48, 0.08);
            --admin-radius: 14px;
        }
        body {
            padding-top: 80px;
            background: var(--admin-bg);
            color: var(--admin-ink);
            font-family: "Poppins", "Segoe UI", sans-serif;
        }
        .navbar-inverse {
            background: #111827;
            border-color: #111827;
            box-shadow: 0 10px 24px rgba(17, 24, 39, 0.35);
        }
        .navbar-inverse .navbar-brand,
        .navbar-inverse .navbar-nav > li > a {
            color: #f9fafb;
        }
        .navbar-inverse .navbar-nav > li > a:hover,
        .navbar-inverse .navbar-nav > li > a:focus {
            color: var(--admin-accent);
        }
        .navbar-inverse .navbar-toggle {
            border-color: rgba(255, 255, 255, 0.35);
        }
        .navbar-inverse .navbar-toggle .icon-bar {
            background-color: #f9fafb;
        }
        .navbar-inverse .navbar-nav {
            display: flex;
            flex-wrap: wrap;
        }
        .navbar-inverse .navbar-nav > li {
            float: none;
        }
        .navbar-inverse .navbar-nav > li > a {
            padding: 12px 10px;
        }
        .navbar-inverse .navbar-collapse {
            max-height: none;
        }
        .admin-card {
            background: var(--admin-surface);
            border: 1px solid #e6e8ef;
            padding: 22px;
            border-radius: var(--admin-radius);
            box-shadow: var(--admin-shadow);
        }
        .admin-card h3 {
            font-weight: 700;
            margin-top: 0;
        }
        .table > thead > tr > th {
            border-bottom: 2px solid #e5e7eb;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 11px;
            color: var(--admin-muted);
        }
        .table > tbody > tr > td {
            vertical-align: middle;
            padding: 12px;
        }
        .form-note { color: var(--admin-muted); font-size: 12px; }
        .btn-primary {
            background: var(--admin-accent);
            border-color: var(--admin-accent);
        }
        .btn-primary:hover,
        .btn-primary:focus {
            background: #c7922d;
            border-color: #c7922d;
        }
        .btn-default {
            border-color: #e5e7eb;
        }
        .admin-card + .admin-card {
            margin-top: 20px;
        }
        .pagination {
            margin: 12px 0 0;
        }
        .pagination > li > a,
        .pagination > li > span {
            color: var(--admin-ink);
            border-color: #e5e7eb;
        }
        .pagination > .active > a,
        .pagination > .active > span,
        .pagination > .active > a:hover,
        .pagination > .active > span:hover,
        .pagination > .active > a:focus,
        .pagination > .active > span:focus {
            background: var(--admin-accent);
            border-color: var(--admin-accent);
            color: #fff;
        }
        .pagination > .disabled > span,
        .pagination > .disabled > span:hover,
        .pagination > .disabled > span:focus {
            color: var(--admin-muted);
            border-color: #e5e7eb;
            background: #fff;
        }
        .admin-stat {
            background: linear-gradient(135deg, #ffffff 0%, #fdf6e4 100%);
            border-radius: var(--admin-radius);
            padding: 16px 18px;
            box-shadow: var(--admin-shadow);
            border: 1px solid #f1e7c9;
            margin-bottom: 16px;
        }
        .admin-stat h4 {
            margin: 0 0 6px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--admin-muted);
        }
        .admin-stat p {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: var(--admin-ink);
        }
        .admin-footer {
            margin-top: 28px;
            padding: 14px 0 10px;
            text-align: center;
            color: var(--admin-muted);
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
        @media (max-width: 767px) {
            body { padding-top: 70px; }
            .navbar-inverse .navbar-nav {
                display: block;
            }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#admin-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">JudiciaryPRO Admin</a>
        </div>
        <div class="collapse navbar-collapse" id="admin-navbar">
            <ul class="nav navbar-nav">
                <li><a href="courses.php">Courses</a></li>
                
                <!-- <li><a href="materials.php">Materials</a></li>
                
                <li><a href="material-leads.php">Material Leads</a></li> -->
                <li><a href="students.php">Student Ranking</a></li>
                <li><a href="signups.php">Student Signups</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="news.php">News</a></li>
                <li><a href="counselling.php">Counselling</a></li>
                <li><a href="enquiries.php">Enquiries</a></li>
                <li><a href="seo.php">SEO</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
