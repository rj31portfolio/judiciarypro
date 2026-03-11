<?php
ob_start();
require __DIR__ . '/../includes/bootstrap.php';
require __DIR__ . '/../includes/mailer.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($name === '' || $phone === '') {
    http_response_code(400);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Name and phone are required.']);
    exit;
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Please enter a valid email.']);
    exit;
}

try {
    db()->exec("CREATE TABLE IF NOT EXISTS counselling_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(150),
        phone VARCHAR(30) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $stmt = db()->prepare('INSERT INTO counselling_requests (name, email, phone) VALUES (?,?,?)');
    $stmt->execute([$name, $email, $phone]);
} catch (Throwable $e) {
    http_response_code(500);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Unable to save your request. Please try again.']);
    exit;
}

$subject = 'New Counselling Request - JudiciaryPRO';
$textBody = "New counselling request\nName: {$name}\nEmail: {$email}\nPhone: {$phone}";
$htmlBody = '<h3>New Counselling Request</h3>'
    . '<p><strong>Name:</strong> ' . h($name) . '</p>'
    . '<p><strong>Email:</strong> ' . h($email) . '</p>'
    . '<p><strong>Phone:</strong> ' . h($phone) . '</p>';

$mailResult = send_smtp_mail($subject, $htmlBody, $textBody, $email, $name);
if (!$mailResult['ok']) {
    http_response_code(500);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Saved, but email could not be sent.']);
    exit;
}

ob_clean();
echo json_encode(['ok' => true, 'message' => 'Thanks! We will call you shortly.']);
exit;
