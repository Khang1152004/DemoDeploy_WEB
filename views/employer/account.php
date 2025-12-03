<h3 class="text-lg font-semibold text-slate-900 mb-4">Quản lý tài khoản (Doanh nghiệp)</h3>
<?php if (!empty($msg)): ?><div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if (!empty($err)): ?><div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<div class="row">
  <div class="col-md-6">
    <h5>Thông tin doanh nghiệp</h5>
    <form method="post">
      <input type="hidden" name="update_profile" value="1">
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
        <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" value="<?= htmlspecialchars($user['email']) ?>" disabled>
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Tên công ty</label>
        <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="ten_cong_ty" value="<?= htmlspecialchars($profile['ten_cong_ty'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Địa chỉ</label>
        <textarea class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="dia_chi"><?= htmlspecialchars($profile['dia_chi'] ?? '') ?></textarea>
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Mô tả</label>
        <textarea class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="mo_ta"><?= htmlspecialchars($profile['mo_ta'] ?? '') ?></textarea>
      </div>
      <button class="inline-flex items-center px-3 py-2 rounded-lg bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600">Lưu thông tin</button>
    </form>
  </div>
  <div class="col-md-6">
    <h5>Đổi mật khẩu</h5>
    <form method="post">
      <input type="hidden" name="change_password" value="1">
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Mật khẩu hiện tại</label>
        <input type="password" name="current_password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" required>
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Mật khẩu mới</label>
        <input type="password" name="new_password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" required>
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Xác nhận mật khẩu mới</label>
        <input type="password" name="confirm_password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" required>
      </div>
      <button class="btn btn-warning">Đổi mật khẩu</button>
    </form>
  </div>
</div>
