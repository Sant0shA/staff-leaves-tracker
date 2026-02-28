<?php
// user/apply_leave.php — Apply leave for a staff member
require_once __DIR__ . '/../includes/db.php';
requireLogin();
if (isAdmin()) { header('Location: /admin/dashboard.php'); exit; }

$uid      = currentUserId();
$staffId  = (int)($_GET['staff_id'] ?? 0);
$error    = '';
$conflict = false;
$dayCount = 0;

// Verify this staff belongs to this user
$stmt = $pdo->prepare("SELECT * FROM staff WHERE id = ? AND created_by = ? LIMIT 1");
$stmt->execute([$staffId, $uid]);
$staff = $stmt->fetch();

if (!$staff) {
    header('Location: /user/dashboard.php');
    exit;
}

// Fetch this staff's recent leaves for reference
$stmt2 = $pdo->prepare("
    SELECT * FROM leaves
    WHERE staff_id = ?
    ORDER BY from_date DESC
    LIMIT 5
");
$stmt2->execute([$staffId]);
$recentLeaves = $stmt2->fetchAll();

// Handle AJAX date-range conflict check
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['check'])) {
    $from = $_GET['from'] ?? '';
    $to   = $_GET['to']   ?? '';
    if ($from && $to) {
        $chk = $pdo->prepare("
            SELECT COUNT(*) FROM leaves
            WHERE staff_id = ?
              AND NOT (to_date < ? OR from_date > ?)
        ");
        $chk->execute([$staffId, $from, $to]);
        echo json_encode(['conflict' => (bool)$chk->fetchColumn()]);
    }
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from  = $_POST['from_date'] ?? '';
    $to    = $_POST['to_date']   ?? '';
    $notes = trim($_POST['notes'] ?? '');

    if (!$from || !$to) {
        $error = 'Please select both start and end dates.';
    } elseif ($from > $to) {
        $error = 'End date cannot be before start date.';
    } else {
        // Duplicate / overlap check
        $chk = $pdo->prepare("
            SELECT COUNT(*) FROM leaves
            WHERE staff_id = ?
              AND NOT (to_date < ? OR from_date > ?)
        ");
        $chk->execute([$staffId, $from, $to]);
        $conflict = (bool)$chk->fetchColumn();

        if ($conflict && empty($_POST['force'])) {
            $error = 'overlap';
        } else {
            // Insert leave
            $ins = $pdo->prepare("
                INSERT INTO leaves (staff_id, user_id, from_date, to_date, notes)
                VALUES (?, ?, ?, ?, ?)
            ");
            $ins->execute([$staffId, $uid, $from, $to, $notes]);
            header('Location: /user/dashboard.php?leave_added=1');
            exit;
        }
    }
}

$today   = date('Y-m-d');
$pageTitle = 'Apply Leave';
$backUrl   = '/user/dashboard.php';
$subTitle  = $staff['name'];
include __DIR__ . '/../includes/header.php';
?>

<div class="form-wrap">

  <?php if ($error === 'overlap'): ?>
    <div class="alert alert-warning">
      ⚠️ This staff already has leave entries overlapping these dates.
      Tick the box below to save anyway.
    </div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- Staff info chip -->
  <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
    <?php $av = avatarColor($staff['id']); ?>
    <div class="staff-avatar" style="background:<?= $av['bg'] ?>;color:<?= $av['color'] ?>">
      <?= initials($staff['name']) ?>
    </div>
    <div>
      <div style="font-weight:500;font-size:15px;"><?= htmlspecialchars($staff['name']) ?></div>
      <div style="font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($staff['designation'] ?? 'Staff') ?></div>
    </div>
  </div>

  <form method="POST" action="/user/apply_leave.php?staff_id=<?= $staffId ?>" id="leaveForm">

    <div class="field">
      <label>Date Range</label>
      <div class="date-row">
        <input
          type="date"
          name="from_date"
          id="fromDate"
          min="<?= date('Y-m-01') ?>"
          value="<?= htmlspecialchars($_POST['from_date'] ?? $today) ?>"
          required
        >
        <input
          type="date"
          name="to_date"
          id="toDate"
          min="<?= date('Y-m-01') ?>"
          value="<?= htmlspecialchars($_POST['to_date'] ?? $today) ?>"
          required
        >
      </div>
    </div>

    <!-- Day count display -->
    <div class="day-count" id="dayCount" style="display:none;">
      📅 <span id="dayCountText"></span>
    </div>

    <!-- Conflict warning (JS-driven) -->
    <div class="alert alert-warning" id="conflictWarn" style="display:none;">
      ⚠️ Overlapping leave exists for this period.
    </div>

    <div class="field">
      <label>Notes (Optional)</label>
      <textarea name="notes" placeholder="Reason for leave…"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
    </div>

    <?php if ($error === 'overlap'): ?>
      <div class="field" style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
        <input type="checkbox" name="force" id="force" style="width:auto;accent-color:var(--accent)">
        <label for="force" style="text-transform:none;font-size:14px;color:var(--text-muted);letter-spacing:0">
          Save anyway (override duplicate)
        </label>
      </div>
    <?php endif; ?>

    <button type="submit" class="btn-primary" id="submitBtn">Confirm Leave →</button>
    <a href="/user/dashboard.php" class="btn-outline">Cancel</a>

  </form>

  <?php if (!empty($recentLeaves)): ?>
    <div style="margin-top:32px;">
      <div class="section-label">Recent Leave History</div>
      <?php foreach ($recentLeaves as $l):
        $dr = formatDateRange($l['from_date'], $l['to_date']);
      ?>
        <div class="leave-row">
          <div class="leave-dot"></div>
          <div style="flex:1">
            <div class="leave-dates"><?= $dr['label'] ?></div>
            <?php if ($l['notes']): ?>
              <div class="leave-notes"><?= htmlspecialchars($l['notes']) ?></div>
            <?php endif; ?>
          </div>
          <div class="leave-days"><?= $dr['days'] ?>d</div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>

<script>
const fromEl = document.getElementById('fromDate');
const toEl   = document.getElementById('toDate');
const dayBox = document.getElementById('dayCount');
const dayTxt = document.getElementById('dayCountText');
const warnEl = document.getElementById('conflictWarn');
const staffId = <?= $staffId ?>;

function updateDays() {
  const from = fromEl.value;
  const to   = toEl.value;
  if (from && to && from <= to) {
    const diff = Math.round((new Date(to) - new Date(from)) / 86400000) + 1;
    dayTxt.textContent = diff + ' day' + (diff > 1 ? 's' : '') + ' selected';
    dayBox.style.display = 'flex';
  } else {
    dayBox.style.display = 'none';
  }
}

function checkConflict() {
  const from = fromEl.value;
  const to   = toEl.value;
  if (!from || !to || from > to) return;

  fetch(`/user/apply_leave.php?staff_id=${staffId}&check=1&from=${from}&to=${to}`)
    .then(r => r.json())
    .then(data => {
      warnEl.style.display = data.conflict ? 'flex' : 'none';
    })
    .catch(() => {});
}

fromEl.addEventListener('change', () => { updateDays(); checkConflict(); });
toEl.addEventListener('change',   () => { updateDays(); checkConflict(); });

// Ensure to_date >= from_date
fromEl.addEventListener('change', () => {
  if (toEl.value && toEl.value < fromEl.value) toEl.value = fromEl.value;
});

updateDays();
</script>

<?php
$activeNav = 'staff';
include __DIR__ . '/../includes/nav.php';
?>
