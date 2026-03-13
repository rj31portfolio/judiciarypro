<?php
return [
    'db' => [
        'host' => 'localhost',
        'name' => 'judiciarypro',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'site' => [
        // Set to your app's web root (e.g. "/judiciarypro" or "http://localhost/judiciarypro").
        // Use "auto" to detect the base path dynamically.
        'base_url' => 'auto',
        'default_robots' => 'index,follow',
    ],
    // 'db' => [
    //     'host' => 'localhost',
    //     'name' => 'viralqls_judiciarypro',
    //     'user' => 'viralqls_user_judiciarypro',
    //     'pass' => 'Viral@#1008',
    //     'charset' => 'utf8mb4',
    // ],
    // 'site' => [
    //     'base_url' => 'https://www.judiciarypro.com/',
    //     'default_robots' => 'index,follow',
    // ],
    // Demo SMS API config (Twilio placeholders).

    'sms' => [
        // NimbusIT OTP settings.
        'provider' => 'nimbusit',
        'enabled' => true,
        'base_url' => 'https://nimbusit.biz/api/SmsApi/SendSingleApi',
        'user_id' => 'chinarbiz',
        'password' => 'lbif4972LB',
        // DLT values must match your approved template and header.
        'sender_id' => 'CHNRLI',
        'entity_id' => '1201161216671532442',
        'template_id' => '1707167612312718280',
        // DLT template uses {#var#} for the OTP variable.
        'otp_message' => 'Dear user your requested OTP is {#var#} Plz do not share with anyone. CHINAR LAW INSTITUTE {#var#}',
        // For local XAMPP environments without CA certs.
        'ssl_verify' => false,
        // Enable to return provider error details in the JSON response (use false in production).
        'debug' => false,
        // Local dev: skip SMS API and return OTP in response.
        'local_mode' => false,
    ],
    'mail' => [
        'enabled' => true,
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'rj9work@gmail.com',
        'password' => 'crss gawk hecz yedz',
        'encryption' => 'tls', // tls or ssl
        'from_email' => 'rj9work@gmail.com',
        'from_name' => 'JudiciaryPRO',
        'to_email' => 'help.judiciarypro@gmail.com',
        'to_name' => 'JudiciaryPRO Admin',
    ],
];
