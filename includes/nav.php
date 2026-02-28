<?php
// includes/nav.php
// Usage: include at bottom of every user page, pass $activeNav = 'staff' | 'reports'
$activeNav = $activeNav ?? 'staff';
?>
  <nav class="bottom-nav">
    <a href="/user/dashboard.php" class="nav-item <?= $activeNav === 'staff' ? 'active' : '' ?>">
      <span class="nav-icon">👥</span>
      <span class="nav-label">Staff</span>
    </a>
    <a href="/user/reports.php" class="nav-item <?= $activeNav === 'reports' ? 'active' : '' ?>">
      <span class="nav-icon">📊</span>
      <span class="nav-label">Reports</span>
    </a>
    <a href="/logout.php" class="nav-item">
      <span class="nav-icon">🚪</span>
      <span class="nav-label">Logout</span>
    </a>
  </nav>

</div><!-- /.app -->
</body>
</html>
