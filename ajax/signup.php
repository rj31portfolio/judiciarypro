<?php
ob_start();
require __DIR__ . '/../includes/bootstrap.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$phoneRaw = trim($_POST['phone'] ?? '');
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$course = trim($_POST['course'] ?? '');
$city = trim($_POST['city'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($phoneRaw === '') {
    http_response_code(400);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Phone number is required.']);
    exit;
}

$phoneDigits = preg_replace('/\D+/', '', $phoneRaw);
if (strlen($phoneDigits) < 10) {
    http_response_code(400);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Please enter a valid mobile number.']);
    exit;
}
$phone = substr($phoneDigits, -10);

if (empty($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    http_response_code(403);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Please verify OTP before submitting your details.']);
    exit;
}

if (!empty($_SESSION['otp_phone']) && $_SESSION['otp_phone'] !== $phone) {
    http_response_code(403);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'OTP was verified for a different number. Please resend OTP.']);
    exit;
}

if ($name === '') {
    http_response_code(400);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Name is required.']);
    exit;
}

try {
    db()->exec("CREATE TABLE IF NOT EXISTS student_signups (
        id INT AUTO_INCREMENT PRIMARY KEY,
        phone VARCHAR(30) NOT NULL,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(150),
        course VARCHAR(150),
        city VARCHAR(120),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $stmt = db()->prepare('INSERT INTO student_signups (phone, name, email, course, city, message) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$phone, $name, $email, $course, $city, $message]);
} catch (Throwable $e) {
    http_response_code(500);
    ob_clean();
    echo json_encode(['ok' => false, 'message' => 'Unable to save details. Please try again.']);
    exit;
}

$_SESSION['otp_verified'] = false;
unset($_SESSION['otp_code'], $_SESSION['otp_expires']);

ob_clean();
echo json_encode(['ok' => true, 'message' => 'Signup submitted successfully.']);
exit;
