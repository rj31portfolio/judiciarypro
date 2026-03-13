<?php
ob_start();
require __DIR__ . '/../includes/bootstrap.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method === 'POST') {
    $payload = $_POST;
} elseif ($method === 'GET') {
    $payload = $_GET;
} else {
    http_response_code(405);
    ob_clean();
    log_sms_event('OTP request blocked: method not allowed (' . $method . ').');
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$action = $payload['action'] ?? '';
$phone = trim($payload['phone'] ?? '');
log_sms_event('OTP request received: method=' . $method . ' | payload=' . json_encode($payload));

if ($phone === '') {
    http_response_code(400);
    ob_clean();
    log_sms_event('OTP request blocked: missing phone.');
    echo json_encode(['ok' => false, 'message' => 'Phone number is required.']);
    exit;
}

function otp_sms_config()
{
    $sms = config()['sms'] ?? [];
    if (($sms['provider'] ?? '') !== 'nimbusit' || empty($sms['enabled'])) {
        return null;
    }
    $required = ['base_url', 'user_id', 'password', 'sender_id', 'entity_id', 'template_id', 'otp_message'];
    foreach ($required as $key) {
        if (empty($sms[$key])) {
            return null;
        }
    }
    return $sms;
}

function normalize_phone($phone)
{
    $digits = preg_replace('/\D+/', '', $phone);
    if (strlen($digits) >= 10) {
        return substr($digits, -10);
    }
    return $digits;
}

function send_nimbusit_sms(array $sms, $phone, $message)
{
    $GLOBALS['otp_last_error'] = null;
    $params = [
        'UserID' => $sms['user_id'],
        'Password' => $sms['password'],
        'SenderID' => $sms['sender_id'],
        'Phno' => $phone,
        'Msg' => $message,
        'EntityID' => $sms['entity_id'],
        'TemplateID' => $sms['template_id'],
    ];

    $query = http_build_query($params);
    $url = rtrim($sms['base_url'], '?') . '?' . $query;

    if (function_exists('curl_init')) {
        $ch = curl_init();
        $verify = isset($sms['ssl_verify']) ? (bool)$sms['ssl_verify'] : true;
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_SSL_VERIFYPEER => $verify,
            CURLOPT_SSL_VERIFYHOST => $verify ? 2 : 0,
        ]);
        $response = curl_exec($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        if ($response === false || $httpCode >= 400) {
            $detail = $response === false ? ('Curl error: ' . $curlError) : ('HTTP ' . $httpCode . ' | Response: ' . substr((string)$response, 0, 200));
            log_sms_event('OTP send failed: ' . $detail);
            $GLOBALS['otp_last_error'] = $detail;
            return false;
        }
        log_sms_event('OTP send response: HTTP ' . $httpCode . ' | ' . substr((string)$response, 0, 200));
        return $response;
    }

    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 12,
        ],
    ]);

    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        $detail = 'file_get_contents error';
        log_sms_event('OTP send failed: ' . $detail);
        $GLOBALS['otp_last_error'] = $detail;
    } else {
        log_sms_event('OTP send response: ' . substr((string)$response, 0, 200));
    }
    return $response;
}

function log_sms_event($message)
{
    $dir = __DIR__ . '/../logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $path = $dir . '/sms.log';
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    @file_put_contents($path, $line, FILE_APPEND);
}

function is_sms_response_success($response)
{
    if ($response === false) {
        return false;
    }
    $trimmed = trim((string)$response);
    if ($trimmed === '') {
        return false;
    }
    if (preg_match('/(error|invalid|failed)/i', $trimmed)) {
        return false;
    }
    return true;
}

if ($action === 'send') {
    log_sms_event('OTP send request received.');
    $normalizedPhone = normalize_phone($phone);
    if (strlen($normalizedPhone) !== 10) {
        http_response_code(400);
        ob_clean();
        log_sms_event('OTP request blocked: invalid phone ' . substr($normalizedPhone, 0, 2) . '******' . substr($normalizedPhone, -2));
        echo json_encode(['ok' => false, 'message' => 'Please enter a valid 10-digit mobile number.']);
        exit;
    }

    if (!empty($_SESSION['otp_last_sent']) && (time() - (int)$_SESSION['otp_last_sent']) < 30) {
        http_response_code(429);
        ob_clean();
        log_sms_event('OTP request blocked: resend throttled.');
        echo json_encode(['ok' => false, 'message' => 'Please wait a few seconds before resending OTP.']);
        exit;
    }

    $sms = otp_sms_config();
    if ($sms === null) {
        http_response_code(500);
        ob_clean();
        log_sms_event('OTP request blocked: SMS config missing/invalid.');
        echo json_encode(['ok' => false, 'message' => 'SMS configuration is missing.']);
        exit;
    }

    $otp = (string)random_int(100000, 999999);
    $message = str_replace(['{otp}', '{#var#}'], $otp, $sms['otp_message']);

    if (!empty($sms['local_mode'])) {
        log_sms_event('OTP local_mode enabled. OTP=' . $otp . ' for ' . substr($normalizedPhone, 0, 2) . '******' . substr($normalizedPhone, -2));
        $_SESSION['otp_phone'] = $normalizedPhone;
        $_SESSION['otp_code'] = $otp;
        $_SESSION['otp_verified'] = false;
        $_SESSION['otp_expires'] = time() + (5 * 60);
        $_SESSION['otp_last_sent'] = time();
        ob_clean();
        echo json_encode(['ok' => true, 'message' => 'OTP generated (local mode).', 'otp' => $otp]);
        exit;
    }

    $response = send_nimbusit_sms($sms, $normalizedPhone, $message);
    if (!is_sms_response_success($response)) {
        log_sms_event('OTP send failed for ' . substr($normalizedPhone, 0, 2) . '******' . substr($normalizedPhone, -2) . ' | Response: ' . substr((string)$response, 0, 200));
        http_response_code(502);
        ob_clean();
        $payload = ['ok' => false, 'message' => 'Unable to send OTP right now.'];
        if (!empty($sms['debug'])) {
            $payload['details'] = $GLOBALS['otp_last_error'] ?: substr((string)$response, 0, 200);
        }
        echo json_encode($payload);
        exit;
    }

    $_SESSION['otp_phone'] = $normalizedPhone;
    $_SESSION['otp_code'] = $otp;
    $_SESSION['otp_verified'] = false;
    $_SESSION['otp_expires'] = time() + (5 * 60);
    $_SESSION['otp_last_sent'] = time();

    ob_clean();
    echo json_encode(['ok' => true, 'message' => 'OTP sent to your mobile number.']);
    exit;
}

if ($action === 'status') {
    $normalizedPhone = normalize_phone($phone);
    $valid = isset($_SESSION['otp_verified'], $_SESSION['otp_phone'], $_SESSION['otp_expires'])
        && $_SESSION['otp_verified'] === true
        && $_SESSION['otp_phone'] === $normalizedPhone
        && $_SESSION['otp_expires'] >= time();

    ob_clean();
    echo json_encode([
        'ok' => $valid,
        'message' => $valid ? 'OTP already verified.' : 'OTP not verified.',
    ]);
    exit;
}

if ($action === 'verify') {
    $normalizedPhone = normalize_phone($phone);
    $code = trim($payload['code'] ?? '');
    if ($code === '') {
        http_response_code(400);
        ob_clean();
        echo json_encode(['ok' => false, 'message' => 'OTP is required.']);
        exit;
    }

    $valid = isset($_SESSION['otp_code'], $_SESSION['otp_phone'], $_SESSION['otp_expires'])
        && $_SESSION['otp_phone'] === $normalizedPhone
        && $_SESSION['otp_expires'] >= time()
        && hash_equals($_SESSION['otp_code'], $code);

    if (!$valid) {
        http_response_code(400);
        ob_clean();
        echo json_encode(['ok' => false, 'message' => 'Invalid or expired OTP.']);
        exit;
    }

    $_SESSION['otp_verified'] = true;
    ob_clean();
    echo json_encode(['ok' => true, 'message' => 'OTP verified.']);
    exit;
}

http_response_code(400);
ob_clean();
echo json_encode(['ok' => false, 'message' => 'Invalid action.']);
