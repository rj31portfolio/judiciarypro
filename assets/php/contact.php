<?php
require __DIR__ . '/../../includes/bootstrap.php';
require __DIR__ . '/../../includes/mailer.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => true, 'message' => ['form' => 'Method not allowed.'], 'error_field' => ['form']]);
    exit;
}

$name = trim($_POST['lgxname'] ?? '');
$email = trim($_POST['lgxemail'] ?? '');
$subject = trim($_POST['lgxsubject'] ?? '');
$message = trim($_POST['lgxmessage'] ?? '');

$errors = [];
if ($name === '') {
    $errors['lgxname'] = 'Name is required.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['lgxemail'] = 'Valid email is required.';
}
if ($subject === '') {
    $errors['lgxsubject'] = 'Subject is required.';
}
if ($message === '') {
    $errors['lgxmessage'] = 'Message is required.';
}

if ($errors) {
    echo json_encode(['error' => true, 'message' => $errors, 'error_field' => array_keys($errors)]);
    exit;
}

try {
    db()->exec("CREATE TABLE IF NOT EXISTS contact_enquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(150) NOT NULL,
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");

    $stmt = db()->prepare('INSERT INTO contact_enquiries (name, email, subject, message) VALUES (?,?,?,?)');
    $stmt->execute([$name, $email, $subject, $message]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => ['form' => 'Unable to save enquiry.'], 'error_field' => ['form']]);
    exit;
}

$mailSubject = 'New Contact Enquiry - JudiciaryPRO';
$textBody = "New contact enquiry\nName: {$name}\nEmail: {$email}\nSubject: {$subject}\nMessage: {$message}";
$htmlBody = '<h3>New Contact Enquiry</h3>'
    . '<p><strong>Name:</strong> ' . h($name) . '</p>'
    . '<p><strong>Email:</strong> ' . h($email) . '</p>'
    . '<p><strong>Subject:</strong> ' . h($subject) . '</p>'
    . '<p><strong>Message:</strong><br>' . nl2br(h($message)) . '</p>';

$mailResult = send_smtp_mail($mailSubject, $htmlBody, $textBody, $email, $name);
if (!$mailResult['ok']) {
    if (!empty($mailResult['error'])) {
        error_log('Contact form mail failed: ' . $mailResult['error']);
    }
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => ['form' => 'Saved, but email could not be sent.'], 'error_field' => ['form']]);
    exit;
}

echo json_encode(['error' => false, 'message' => 'Thanks! We have received your enquiry.']);
exit;
