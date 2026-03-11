<?php
require __DIR__ . '/../includes/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

db()->exec("CREATE TABLE IF NOT EXISTS material_leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    material_id INT DEFAULT NULL,
    material_title VARCHAR(200),
    pdf_name VARCHAR(200),
    pdf_file VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Invalid request.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$materialId = (int)($_POST['material_id'] ?? 0);
$materialTitle = trim($_POST['material_title'] ?? '');
$pdfName = trim($_POST['pdf_name'] ?? '');
$pdfFile = trim($_POST['pdf_file'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $materialId <= 0 || $pdfFile === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Please fill all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$stmt = db()->prepare("SELECT id, title, pdfs_json FROM materials WHERE id = ? LIMIT 1");
$stmt->execute([$materialId]);
$material = $stmt->fetch();

if (!$material) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'Material not found.']);
    exit;
}

$pdfs = [];
if (!empty($material['pdfs_json'])) {
    $decoded = json_decode($material['pdfs_json'], true);
    if (is_array($decoded)) {
        $pdfs = $decoded;
    }
}

$matched = null;
foreach ($pdfs as $pdf) {
    if (!is_array($pdf)) {
        continue;
    }
    $file = trim($pdf['file'] ?? '');
    if ($file !== '' && $file === $pdfFile) {
        $matched = $pdf;
        break;
    }
}

if (!$matched) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'PDF not available.']);
    exit;
}

$finalPdfName = $pdfName !== '' ? $pdfName : ($matched['name'] ?? basename($matched['file']));
$materialTitle = $materialTitle !== '' ? $materialTitle : ($material['title'] ?? '');

$stmt = db()->prepare('INSERT INTO material_leads (name, email, phone, material_id, material_title, pdf_name, pdf_file) VALUES (?,?,?,?,?,?,?)');
$stmt->execute([$name, $email, $phone, $materialId, $materialTitle, $finalPdfName, $matched['file']]);

$downloadUrl = url_for($matched['file']);

echo json_encode([
    'ok' => true,
    'message' => 'Lead captured.',
    'download_url' => $downloadUrl,
]);
