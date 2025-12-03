<div class="min-h-[60vh] flex items-center justify-center">
  <div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-7">
      <h1 class="text-xl font-semibold text-slate-900 mb-1">Đăng nhập</h1>
      <p class="text-xs text-slate-500 mb-4">Đăng nhập để quản lý tin tuyển dụng, CV và tài khoản của bạn.</p>

      <?php if (!empty($error)): ?>
        <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="post" class="space-y-3">
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
          <input type="email" name="email"
                 class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                 required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1">Mật khẩu</label>
          <input type="password" name="password"
                 class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                 required>
        </div>
        <button class="w-full inline-flex items-center justify-center rounded-lg bg-primary-500 text-white text-sm font-semibold py-2.5 hover:bg-primary-600">
          Đăng nhập
        </button>
      </form>
    </div>

    <p class="mt-3 text-center text-xs text-slate-500">
      Chưa có tài khoản?
      <a href="index.php?c=Auth&a=register" class="font-medium text-primary-600 hover:text-primary-700">Đăng ký ngay</a>
    </p>
  </div>
</div>
