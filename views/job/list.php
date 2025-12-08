<?php
$hasSearch = !empty($keyword) || !empty($fieldId) || !empty($locationId) || !empty($salaryKeyword);
?>

<section class="mb-6">
  <div class="rounded-3xl bg-white border border-slate-200 shadow-sm p-5">
    <h1 class="text-xl font-bold text-slate-900 mb-1">Tất cả tin tuyển dụng</h1>
    <p class="text-sm text-slate-600 mb-4">
      Tìm kiếm và lọc theo lĩnh vực, địa điểm, mức lương.
    </p>

    <form method="get" class="grid gap-4 md:grid-cols-4 items-end">
      <input type="hidden" name="c" value="Job">
      <input type="hidden" name="a" value="index">

      <div class="md:col-span-2">
        <label class="block text-xs font-medium text-slate-600 mb-1">Từ khóa</label>
        <input type="text"
               name="q"
               value="<?= htmlspecialchars($keyword ?? '') ?>"
               class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
               placeholder="Vị trí, kỹ năng, công ty..." />
      </div>

      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Lĩnh vực</label>
        <select name="field"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
          <option value="0">Tất cả</option>
          <?php if (!empty($fields)): ?>
            <?php foreach ($fields as $f): ?>
              <option value="<?= (int)$f['ma_linh_vuc'] ?>"
                <?= ($fieldId ?? 0) == $f['ma_linh_vuc'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($f['ten_linh_vuc']) ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>

      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Địa điểm</label>
        <select name="location"
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
          <option value="0">Tất cả</option>
          <?php if (!empty($locations)): ?>
            <?php foreach ($locations as $loc): ?>
              <option value="<?= (int)$loc['ma_dia_diem'] ?>"
                <?= ($locationId ?? 0) == $loc['ma_dia_diem'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($loc['ten_danh_muc']) ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>

      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Mức lương (từ khóa)</label>
        <input type="text"
               name="salary"
               value="<?= htmlspecialchars($salaryKeyword ?? '') ?>"
               class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
               placeholder="VD: 10-15 triệu" />
      </div>

      <div class="md:col-span-4 flex justify-end gap-3">
        <a href="index.php?c=Job&a=index"
           class="inline-flex items-center px-3 py-2 rounded-lg border border-slate-200 text-xs sm:text-sm text-slate-600 hover:bg-slate-50">
          Xóa lọc
        </a>
        <button type="submit"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-primary-500 text-white text-xs sm:text-sm font-semibold hover:bg-primary-600">
          Tìm kiếm
        </button>
      </div>
    </form>
  </div>
</section>

<section id="job-list" class="mt-4">
  <div class="flex items-center justify-between mb-3">
    <h2 class="text-base sm:text-lg font-semibold text-slate-900">
      <?= $hasSearch ? 'Kết quả tìm kiếm' : 'Tin tuyển dụng mới nhất' ?>
    </h2>
    <?php if (!empty($jobs)): ?>
      <span class="text-xs text-slate-500">
        <?= count($jobs) ?> tin <?= $hasSearch ? 'phù hợp' : 'đang hiển thị' ?>
      </span>
    <?php endif; ?>
  </div>

  <?php if (empty($jobs)): ?>
    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-6 text-center">
      <p class="text-sm text-slate-600 mb-1">Không tìm thấy tin tuyển dụng phù hợp.</p>
      <p class="text-xs text-slate-400">Hãy thử lại với điều kiện tìm kiếm khác.</p>
    </div>
  <?php else: ?>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <?php foreach ($jobs as $job): ?>
        <a class="group rounded-2xl border border-slate-200 bg-white hover:border-primary-200 hover:shadow-md transition"
           href="index.php?c=Job&a=detail&id=<?= (int)$job['ma_tin_tuyen_dung'] ?>">
          <div class="flex items-start justify-between gap-2 mb-2 px-4 pt-4">
            <h3 class="text-sm font-semibold text-slate-900 group-hover:text-primary-600 line-clamp-2">
              <?= htmlspecialchars($job['tieu_de']) ?>
            </h3>
            <span class="inline-flex items-center rounded-full bg-primary-50 px-2 py-0.5 text-[11px] font-medium text-primary-600">
              <?= htmlspecialchars($job['muc_luong_khoang']) ?: 'Thỏa thuận' ?>
            </span>
          </div>
          <p class="px-4 mt-1 text-xs text-slate-600 mb-1 line-clamp-3">
            <?= nl2br(htmlspecialchars(substr($job['mo_ta_cong_viec'], 0, 140))) ?>...
          </p>
          <p class="px-4 text-[11px] text-slate-500 mb-2">
            <?= htmlspecialchars($job['ten_cong_ty'] ?? 'Doanh nghiệp ẩn danh') ?>
          </p>
          <div class="flex items-center justify-between px-4 pb-4 text-[11px] text-slate-500">
            <span>
              <?= htmlspecialchars($job['ten_linh_vuc'] ?? '') ?>
              <?php if (!empty($job['ten_dia_diem'])): ?>
                · <?= htmlspecialchars($job['ten_dia_diem']) ?>
              <?php endif; ?>
            </span>
            <span class="text-primary-500 group-hover:text-primary-600 font-medium">
              Xem chi tiết &rarr;
            </span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
