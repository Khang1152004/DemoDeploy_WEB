<div class="min-h-[60vh] flex items-center justify-center">
  <div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-7">
      <h1 class="text-xl font-semibold text-slate-900 mb-1">Đăng ký</h1>
      <p class="text-xs text-slate-500 mb-4">Tạo tài khoản ứng viên hoặc doanh nghiệp để sử dụng hệ thống.</p>

      <?php if (!empty($error)): ?>
        <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">
          <?= htmlspecialchars($success) ?>
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
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1">Xác nhận mật khẩu</label>
          <input type="password" name="confirm"
                 class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                 required>
        </div>
        <div>
          <label class="block text-xs font-medium text-slate-600 mb-1">Loại tài khoản</label>
          <select name="role"
                  class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
            <option value="ung_vien">Ứng viên</option>
            <option value="doanh_nghiep">Doanh nghiệp</option>
          </select>
        </div>
        <button class="w-full inline-flex items-center justify-center rounded-lg bg-primary-500 text-white text-sm font-semibold py-2.5 hover:bg-primary-600">
          Đăng ký
        </button>
      </form>
    </div>

    <p class="mt-3 text-center text-xs text-slate-500">
      Đã có tài khoản?
      <a href="index.php?c=Auth&a=login" class="font-medium text-primary-600 hover:text-primary-700">Đăng nhập</a>
    </p>
  </div>
</div>
