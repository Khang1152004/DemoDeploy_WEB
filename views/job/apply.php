<h3 class="text-lg font-semibold text-slate-900 mb-4">Ứng tuyển: <?= htmlspecialchars($job['tieu_de']) ?></h3>
<?php if (!empty($error)): ?><div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if (!empty($success)): ?><div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label class="block text-xs font-medium text-slate-600 mb-1">Họ tên</label>
    <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="ho_ten" required>
  </div>
  <div class="mb-3">
    <label class="block text-xs font-medium text-slate-600 mb-1">Email liên hệ</label>
    <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" type="email" name="email" required>
  </div>
  <div class="mb-3">
    <label class="block text-xs font-medium text-slate-600 mb-1">SĐT liên hệ</label>
    <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="sdt" required>
  </div>
  <div class="mb-3">
    <label class="block text-xs font-medium text-slate-600 mb-1">CV đính kèm</label>
    <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" type="file" name="cv_file">
  </div>
  <button class="inline-flex items-center px-3 py-2 rounded-lg bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600">Gửi đơn ứng tuyển</button>
</form>
