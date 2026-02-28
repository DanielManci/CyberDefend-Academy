<?php
// csrf.php
// Minimal CSRF helper for POST forms (session-based, one token per session).

// If you centralise session_start() elsewhere, this guard prevents duplicates.
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Optional: set safer cookie params before starting the session.
    // If you already set these globally, you can remove this block.
    session_set_cookie_params([
        'httponly' => true,
        'secure'   => !empty($_SERVER['HTTPS']),
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * Return the current CSRF token, generating it if missing.
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        // 32 random bytes -> 64 hex chars
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

/**
 * Echo a hidden input field for use inside HTML forms.
 */
function csrf_field(): string {
    $t = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf" value="' . $t . '">';
}

/**
 * Validate the CSRF token for POST requests.
 * Call this at the very top of any POST handler, before reading $_POST.
 */
function csrf_check(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf'], $_SESSION['csrf'])) {
            http_response_code(403);
            exit('Invalid CSRF token (missing).');
        }
        if (!hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
            http_response_code(403);
            exit('Invalid CSRF token.');
        }
    }
}
