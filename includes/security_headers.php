<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

$is_local = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === 'localhost');
$proto_policy = $is_local ? "http: https:" : "https:";

header("Content-Security-Policy: " .
    "default-src 'self' $proto_policy; " .
    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com; " .
    "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
    "img-src 'self' data: $proto_policy; " .
    "font-src 'self' data: $proto_policy; " .
    "connect-src 'self';");

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
}
