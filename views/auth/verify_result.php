<div class="min-h-[60vh] flex items-center justify-center">
  <div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-7">
      <h1 class="text-xl font-semibold text-slate-900 mb-2">Xác nhận email</h1>

      <?php if (!empty($message)): ?>
        <?php if (!empty($success)): ?>
          <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">
            <?= htmlspecialchars($message) ?>
          </div>
        <?php else: ?>
          <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700">
            <?= htmlspecialchars($message) ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <a href="index.php?c=Auth&a=login"
         class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
        Đến trang đăng nhập
      </a>
    </div>
  </div>
</div>
