<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!empty($_GET['debug'])) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}
require_once __DIR__ . '/../../includes/functions.php';
