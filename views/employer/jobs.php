<h3 class="text-lg font-semibold text-slate-900 mb-4">Tin tuyển dụng của tôi</h3>

<?php if (!empty($error)): ?>
  <div class="mb-3 text-red-600 text-sm">
    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>


<div class="mb-4">
  <h5>Thêm tin mới</h5>
  <form method="post">
    <div class="mb-3">
      <label class="block text-xs font-medium text-slate-600 mb-1">Tiêu đề</label>
      <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="tieu_de" required>
    </div>
    <div class="mb-3">
      <label class="block text-xs font-medium text-slate-600 mb-1">Mô tả công việc</label>
      <textarea class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="mo_ta_cong_viec"></textarea>
    </div>
    <div class="mb-3">
      <label class="block text-xs font-medium text-slate-600 mb-1">Yêu cầu ứng viên</label>
      <textarea class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="yeu_cau_ung_vien"></textarea>
    </div>
    <div class="mb-3">
      <label class="block text-xs font-medium text-slate-600 mb-1">Mức lương khoảng</label>
      <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="muc_luong_khoang">
    </div>
    <div class="mb-3">
      <label class="block text-xs font-medium text-slate-600 mb-1">Lĩnh vực</label>
      <select name="ma_linh_vuc" class="form-select">
        <option value="0">-- Chưa chọn --</option>
        <?php foreach ($fields as $f): ?>
          <option value="<?= (int)$f['ma_linh_vuc'] ?>"><?= htmlspecialchars($f['ten_linh_vuc']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="block text-xs font-medium text-slate-600 mb-1">Địa điểm làm việc</label>
      <select name="ma_dia_diem" class="form-select">
        <option value="">-- Chưa chọn --</option>
        <?php if (!empty($locations)): ?>
          <?php foreach ($locations as $loc): ?>
            <option value="<?= (int)$loc['ma_dia_diem'] ?>"><?= htmlspecialchars($loc['ten_danh_muc']) ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="block text-xs font-medium text-slate-600 mb-1">Hạn nộp hồ sơ</label>
<?php $today = date('Y-m-d'); ?>
      <input type="date" name="han_nop_ho_so" min="<?= $today ?>" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
    </div>
    <button class="inline-flex items-center px-3 py-2 rounded-lg bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600">Lưu tin (chờ admin duyệt)</button>
  </form>
</div>

<h5>Danh sách tin của bạn</h5>
<table class="min-w-full text-xs border border-slate-200 rounded-lg overflow-hidden">
  <thead class="bg-slate-50 text-slate-600">
    <tr>
      <th>#</th>
      <th>Tiêu đề</th>
      <th>Trạng thái</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody class="divide-y divide-slate-100">
    <?php foreach ($jobs as $i => $j): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars($j['tieu_de']) ?></td>
        <td>
          <?php
            $st = $j['trang_thai_tin_dang'];
            if ($st === 'pending') echo 'Chờ duyệt';
            elseif ($st === 'approved') echo 'Đã duyệt';
            elseif ($st === 'rejected') echo 'Bị từ chối';
            elseif ($st === 'delete_pending') echo 'Chờ xóa';
            elseif ($st === 'deleted') echo 'Đã xóa';
            else echo htmlspecialchars($st);
          ?>
        </td>
        <td>
          <a class="btn btn-sm btn-outline-primary mb-1"
             href="index.php?c=Employer&a=applications&job_id=<?= (int)$j['ma_tin_tuyen_dung'] ?>">
             Xem ứng viên
          </a>
          <?php if ($j['trang_thai_tin_dang'] === 'approved'): ?>
            <a class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Gửi yêu cầu xóa tin này?');"
               href="index.php?c=Employer&a=requestDeleteJob&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>">
               Yêu cầu xóa
            </a>
          <?php elseif ($j['trang_thai_tin_dang'] === 'delete_pending'): ?>
            <span class="text-muted">Đã gửi yêu cầu xóa</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
