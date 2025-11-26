<?php
Auth::start();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Website tuyển dụng mini</title>
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
          primary: {
            50: '#eef2ff',
            100: '#e0e7ff',
            200: '#c7d2fe',
            300: '#a5b4fc',
            400: '#818cf8',
            500: '#6366f1',
            600: '#4f46e5',
            700: '#7c3aed',
            800: '#5b21b6',
            900: '#4c1d95',
          }
        }
        }
      }
    }
  </script>
</head>
<body class="bg-slate-100 text-slate-900">
<header class="border-b border-slate-200 bg-white shadow-sm">
  <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      <!-- Left: Logo + brand -->
      <div class="flex items-center space-x-3">
        <a href="index.php" class="flex items-center space-x-2">
          <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-primary-500 to-primary-400 text-white font-bold shadow-md">
            J
          </span>
          <div class="flex flex-col leading-tight">
            <span class="font-semibold text-sm sm:text-base text-slate-900">JobMatch</span>
            <span class="text-[11px] text-slate-500 hidden sm:block">Nền tảng tuyển dụng mini</span>
          </div>
        </a>
      </div>

      <!-- Center: main nav -->
      <div class="hidden md:flex items-center space-x-4">
        <a href="index.php" class="text-sm font-medium px-3 py-2 rounded-lg hover:bg-slate-100 <?php echo (!isset($_GET['c']) || $_GET['c']==='Home') ? 'text-primary-600' : 'text-slate-600'; ?>">
          Trang chủ
        </a>
        <?php if (Auth::role() === 'ung_vien'): ?>
          <a href="index.php?c=Candidate&a=cv" class="text-sm font-medium px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-600">
            Quản lý CV
          </a>
        <?php elseif (Auth::role() === 'doanh_nghiep'): ?>
          <a href="index.php?c=Employer&a=jobs" class="text-sm font-medium px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-600">
            Tin tuyển dụng của tôi
          </a>
          <a href="index.php?c=Employer&a=searchCV" class="text-sm font-medium px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-600">
            Tìm kiếm CV
          </a>
        <?php elseif (Auth::role() === 'admin'): ?>
          <a href="index.php?c=Admin&a=dashboard" class="text-sm font-medium px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-600">
            Trang admin
          </a>
          <a href="index.php?c=Admin&a=fields" class="text-sm font-medium px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-600">
            Lĩnh vực
          </a>
          <a href="index.php?c=Admin&a=skills" class="text-sm font-medium px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-600">
            Kỹ năng
          </a>
        <?php endif; ?>
      </div>

      <!-- Right: notification + user -->
      <div class="flex items-center space-x-2">
        <?php if (Auth::userId()): ?>
          <!-- Notification bell -->
          <div class="relative">
            <button id="notifDropdown" type="button"
              class="relative inline-flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 h-9 w-9 text-slate-600">
              <span class="sr-only">Thông báo</span>
              <span class="text-lg">🔔</span>
              <span id="notifCount" class="hidden absolute -top-1 -right-1 min-w-[18px] px-1.5 rounded-full bg-red-500 text-[10px] font-semibold text-white text-center"></span>
            </button>
            <div id="notifMenuWrapper" class="hidden absolute right-0 mt-2 w-80 rounded-xl border border-slate-200 bg-white shadow-lg z-30">
              <div class="px-3 py-2 border-b border-slate-100 text-xs font-semibold text-slate-500">
                Thông báo
              </div>
              <div id="notifMenu" class="max-h-80 overflow-y-auto">
                <div id="notifEmpty" class="px-3 py-3 text-xs text-slate-500">
                  Không có thông báo.
                </div>
              </div>
            </div>
          </div>

          <!-- User dropdown -->
          <div class="relative">
            <button id="userDropdownBtn" type="button"
              class="inline-flex items-center space-x-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs sm:text-sm text-slate-700 hover:bg-slate-50">
              <span class="hidden sm:inline-block max-w-[140px] truncate">
                <?= htmlspecialchars(Auth::email()); ?>
              </span>
              <span class="inline-flex items-center justify-center rounded-full bg-primary-100 text-primary-700 text-[10px] px-2 py-0.5">
                <?= htmlspecialchars(Auth::role()); ?>
              </span>
              <svg class="h-3 w-3 text-slate-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
              </svg>
            </button>
            <div id="userDropdownMenu" class="hidden absolute right-0 mt-2 w-52 rounded-xl border border-slate-200 bg-white shadow-lg z-30 text-sm">
              <?php if (Auth::role() === 'ung_vien'): ?>
                <a class="block px-3 py-2 hover:bg-slate-50" href="index.php?c=Candidate&a=account">Quản lý tài khoản</a>
              <?php elseif (Auth::role() === 'doanh_nghiep'): ?>
                <a class="block px-3 py-2 hover:bg-slate-50" href="index.php?c=Employer&a=account">Quản lý tài khoản</a>
              <?php elseif (Auth::role() === 'admin'): ?>
                <a class="block px-3 py-2 hover:bg-slate-50" href="index.php?c=Admin&a=dashboard">Trang admin</a>
              <?php endif; ?>
              <div class="border-t border-slate-100 mt-1"></div>
              <a class="block px-3 py-2 text-red-600 hover:bg-red-50" href="index.php?c=Auth&a=logout">Đăng xuất</a>
            </div>
          </div>
        <?php else: ?>
          <a href="index.php?c=Auth&a=login" class="text-xs sm:text-sm font-medium px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50">
            Đăng nhập
          </a>
          <a href="index.php?c=Auth&a=register" class="text-xs sm:text-sm font-medium px-3 py-1.5 rounded-lg bg-primary-500 text-white hover:bg-primary-600">
            Đăng ký
          </a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
