<?php
use App\Core\Auth;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Headhunter</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Việc làm</a>
        </li>
      </ul>
            <ul class="navbar-nav ms-auto">
        <?php if (empty($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="index.php?c=auth&a=login">Đăng nhập</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php?c=auth&a=register">Đăng ký</a></li>
        <?php else: ?>
          <li class="nav-item dropdown me-2">
            <a class="nav-link position-relative" href="#" id="notifBell" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-bell"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifCount" style="display:none;"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifBell" style="min-width:280px;">
              <li><h6 class="dropdown-header">Thông báo</h6></li>
              <div id="notifList">
                <li><span class="dropdown-item text-muted small">Đang tải...</span></li>
              </div>
            </ul>
          </li>

          <?php if (Auth::userRole() === 'ung_vien'): ?>
            <li class="nav-item"><a class="nav-link" href="index.php?c=candidate&a=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?c=candidate&a=profile">Hồ sơ</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?c=candidate&a=manageCV">CV của tôi</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?c=candidate&a=changePassword">Đổi mật khẩu</a></li>
          <?php elseif (Auth::userRole() === 'doanh_nghiep'): ?>
            <li class="nav-item"><a class="nav-link" href="index.php?c=employer&a=dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?c=employer&a=profile">Thông tin công ty</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?c=employer&a=searchCV">Tìm kiếm CV</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?c=employer&a=changePassword">Đổi mật khẩu</a></li>
          <?php elseif (Auth::userRole() === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="index.php?c=admin&a=dashboard">Admin</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="index.php?c=auth&a=logout">Đăng xuất</a></li>
        <?php endif; ?>
      </ul>

    </div>
  </div>
</nav>
