<?php
// includes/helpers.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function base_url(): string
{
    // Absolute URL to the project root folder (e.g., /photoclub_fixed/)
    $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    $protocol = $is_https ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Guess project root as the first path segment (e.g., /photoclub_fixed/)
    // Works for URLs like /photoclub_fixed/dashboard/index.php
    $script = $_SERVER['SCRIPT_NAME'] ?? '/';
    $segments = explode('/', trim($script, '/')); // ['photoclub_fixed','dashboard','index.php']
    $rootPath = '/';
    if (!empty($segments)) {
        // if placed in a folder under webroot
        $rootPath = '/' . $segments[0] . '/';
    }

    return $protocol . $host . $rootPath;
}


function h(string $v): string
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

function is_logged_in(): bool
{
    return isset($_SESSION['member_id']);
}

function redirect_if_not_logged_in(): void
{
    if (!is_logged_in()) {
        header('Location: ' . base_url() . 'login.php');
        exit;
    }
}
