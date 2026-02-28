<?php
// ─────────────────────────────────────────
//  TrackLeaves — Database Configuration
//  Update these values with your Hostinger
//  MySQL credentials from hPanel
// ─────────────────────────────────────────

define('DB_HOST', 'localhost');
define('DB_NAME', 'u248088683_staff_leaves');   // e.g. u123456789_trackleaves
define('DB_USER', 'u248088683_admin');   // e.g. u123456789_admin
define('DB_PASS', 'o!JM#*zt3U4iQN');
define('DB_CHARSET', 'utf8mb4');

// ── Connect ──────────────────────────────
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
}

// ── Session helper ───────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin() {
    if (empty($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        header('Location: /user/dashboard.php');
        exit;
    }
}

function isAdmin() {
    return !empty($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function currentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function currentUserName() {
    return $_SESSION['name'] ?? 'User';
}

function initials($name) {
    $parts = explode(' ', trim($name));
    $init  = '';
    foreach ($parts as $p) $init .= strtoupper($p[0] ?? '');
    return substr($init, 0, 2);
}

function avatarColor($id) {
    $colors = [
        ['bg' => 'rgba(79,142,247,0.15)',  'color' => '#4F8EF7'],
        ['bg' => 'rgba(52,211,153,0.15)',  'color' => '#34D399'],
        ['bg' => 'rgba(251,191,36,0.15)',  'color' => '#FBBF24'],
        ['bg' => 'rgba(248,113,113,0.15)', 'color' => '#F87171'],
        ['bg' => 'rgba(167,139,250,0.15)', 'color' => '#A78BFA'],
    ];
    return $colors[$id % count($colors)];
}

function formatDateRange($from, $to) {
    $f = new DateTime($from);
    $t = new DateTime($to);
    $days = (int)$f->diff($t)->days + 1;
    $fromFmt = $f->format('d M');
    $toFmt   = ($from === $to) ? '' : ' → ' . $t->format('d M');
    return ['label' => $fromFmt . $toFmt, 'days' => $days];
}
