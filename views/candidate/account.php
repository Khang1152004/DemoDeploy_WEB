<h3 class="text-lg font-semibold text-slate-900 mb-4">Quản lý tài khoản (Ứng viên)</h3>
<?php if (!empty($msg)): ?><div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if (!empty($err)): ?><div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<div class="row">
  <div class="col-md-6">
    <h5>Thông tin cá nhân</h5>
    <form method="post">
      <input type="hidden" name="update_profile" value="1">
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
        <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" value="<?= htmlspecialchars($user['email']) ?>" disabled>
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Họ tên</label>
        <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="ho_ten" value="<?= htmlspecialchars($profile['ho_ten'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">SĐT</label>
        <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="sdt" value="<?= htmlspecialchars($profile['sdt'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Địa chỉ</label>
        <textarea class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="dia_chi"><?= htmlspecialchars($profile['dia_chi'] ?? '') ?></textarea>
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Mô tả ngắn</label>
        <textarea class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="mo_ta_ngan"><?= htmlspecialchars($profile['mo_ta_ngan'] ?? '') ?></textarea>
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Mức lương mong muốn</label>
        <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="muc_luong_mong_muon" value="<?= htmlspecialchars($profile['muc_luong_mong_muon'] ?? '') ?>">
      </div>
      <div class="mb-3 flex items-center gap-2">
        <input
          type="checkbox"
          id="subscribe_email"
          name="subscribe_email"
          value="1"
          class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500"
          <?= !empty($user['nhan_email_tuyendung']) ? 'checked' : '' ?>
        >
        <label for="subscribe_email" class="text-xs font-medium text-slate-700">
          Nhận email khi có tin tuyển dụng mới
        </label>
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
