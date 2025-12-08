<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
  <div class="flex items-start justify-between gap-4">
    <div>
      <h1 class="text-xl font-semibold text-slate-900 mb-1">
        <?= htmlspecialchars($job['tieu_de']) ?>
      </h1>
      <p class="text-sm text-slate-600">
        <?= htmlspecialchars($job['ten_cong_ty'] ?? 'Doanh nghiệp ẩn danh') ?>
      </p>
      <p class="mt-1 text-xs text-slate-500">
        <span class="mr-3">
          <strong>Lĩnh vực:</strong>
          <?= htmlspecialchars($job['ten_linh_vuc'] ?? 'Chưa cập nhật') ?>
        </span>
        <span>
          <strong>Địa điểm:</strong>
          <?= htmlspecialchars($job['ten_dia_diem'] ?? 'Chưa cập nhật') ?>
        </span>
      </p>
    </div>
    <div class="text-right space-y-1">
      <div class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-medium text-primary-600">
        Mức lương: <?= htmlspecialchars($job['muc_luong_khoang'] ?: 'Thỏa thuận') ?>
      </div>
      <?php if (!empty($job['han_nop_ho_so'])): ?>
        <p class="text-[11px] text-slate-500">
          Hạn nộp hồ sơ: <?= htmlspecialchars($job['han_nop_ho_so']) ?>
        </p>
      <?php endif; ?>
    </div>
  </div>

  <div class="grid md:grid-cols-3 gap-6 mt-2">
    <div class="md:col-span-2 space-y-4">
      <div>
        <h2 class="text-sm font-semibold text-slate-800 mb-1">Mô tả công việc</h2>
        <div class="prose prose-sm max-w-none text-sm text-slate-700">
          <?= nl2br(htmlspecialchars($job['mo_ta_cong_viec'])) ?>
        </div>
      </div>

      <div>
        <h2 class="text-sm font-semibold text-slate-800 mb-1">Yêu cầu ứng viên</h2>
        <div class="prose prose-sm max-w-none text-sm text-slate-700">
          <?= nl2br(htmlspecialchars($job['yeu_cau_ung_vien'])) ?>
        </div>
      </div>
    </div>

    <div class="space-y-3">
      <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs text-slate-700 space-y-1">
        <p><strong>Công ty:</strong> <?= htmlspecialchars($job['ten_cong_ty'] ?? 'Doanh nghiệp ẩn danh') ?></p>
        <p><strong>Lĩnh vực:</strong> <?= htmlspecialchars($job['ten_linh_vuc'] ?? 'Chưa cập nhật') ?></p>
        <p><strong>Địa điểm:</strong> <?= htmlspecialchars($job['ten_dia_diem'] ?? 'Chưa cập nhật') ?></p>
        <?php if (!empty($job['han_nop_ho_so'])): ?>
          <p><strong>Hạn nộp:</strong> <?= htmlspecialchars($job['han_nop_ho_so']) ?></p>
        <?php endif; ?>
      </div>

      <a href="index.php?c=Job&a=apply&id=<?= (int)$job['ma_tin_tuyen_dung'] ?>"
         class="inline-flex w-full items-center justify-center px-3 py-2 rounded-lg bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600">
        Ứng tuyển
      </a>
    </div>
  </div>
</div>
