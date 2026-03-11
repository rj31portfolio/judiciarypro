<?php
require __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['admin_id'])) {
    redirect('admin/login.php');
}
