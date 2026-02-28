<?php
// user/delete_leave.php — Remove a leave entry (only owner can delete)
require_once __DIR__ . '/../includes/db.php';
requireLogin();
if (isAdmin()) { header('Location: /admin/dashboard.php'); exit; }

$uid     = currentUserId();
$leaveId = (int)($_GET['id'] ?? 0);
$filter  = $_GET['filter'] ?? 'this_month';

if ($leaveId) {
    // Only allow deletion if this leave belongs to this user
    $stmt = $pdo->prepare("DELETE FROM leaves WHERE id = ? AND user_id = ?");
    $stmt->execute([$leaveId, $uid]);
}

header('Location: /user/reports.php?filter=' . urlencode($filter));
exit;
