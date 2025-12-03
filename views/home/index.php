<?php
// Trang chủ - Job portal style (colorful modern)
?>
<section class="mb-8">
  <div class="rounded-3xl bg-gradient-to-r from-sky-50 via-indigo-50 to-emerald-50 px-6 py-6 sm:px-10 sm:py-10 shadow-sm border border-indigo-100">
    <div class="grid gap-8 md:grid-cols-[minmax(0,2fr)_minmax(0,1.2fr)] items-center">
      <div>
        <p class="text-xs font-semibold tracking-wide text-primary-500 mb-2 uppercase">Tìm việc làm phù hợp</p>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 mb-3">
          Kết nối ứng viên &amp; doanh nghiệp <span class="text-primary-600">nhanh chóng</span>
        </h1>
        <p class="text-sm sm:text-base text-slate-700 mb-4 max-w-xl">
          Đăng tin tuyển dụng, quản lý CV, lọc ứng viên theo lĩnh vực &amp; kỹ năng. Một nền tảng mini nhưng đầy đủ tính năng cho đồ án và thực tế.
        </p>
        <div class="flex flex-wrap gap-3">
          <a href="index.php?c=Auth&a=register" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold shadow hover:bg-primary-700">
            Bắt đầu ngay
          </a>
          <a href="#job-list" class="inline-flex items-center px-4 py-2.5 rounded-xl border border-slate-200 bg-white/70 text-sm font-medium text-slate-800 hover:bg-white">
            Xem tin tuyển dụng
          </a>
        </div>
        <div class="mt-4 flex flex-wrap gap-4 text-xs text-slate-600">
          <div class="flex items-center space-x-1">
            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
            <span>Quản lý CV theo lĩnh vực &amp; kỹ năng</span>
          </div>
          <div class="flex items-center space-x-1">
            <span class="h-2 w-2 rounded-full bg-sky-400"></span>
            <span>Thông báo real-time qua chuông</span>
          </div>
        </div>
      </div>

      <div class="bg-white/80 backdrop-blur rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-5">
        <h2 class="text-sm font-semibold text-slate-800 mb-3">Tìm kiếm nâng cao</h2>
        <form class="space-y-3" method="get" action="index.php">
          <input type="hidden" name="c" value="Home">
          <input type="hidden" name="a" value="index">
          <div>
            <label class="block text-xs font-medium text-slate-600 mb-1">Từ khóa</label>
            <input type="text"
                   name="q"
                   value="<?= htmlspecialchars($keyword ?? '') ?>"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                   placeholder="Vị trí, kỹ năng, công ty..." />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-medium text-slate-600 mb-1">Lĩnh vực</label>
              <select name="field"
                      class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
                <option value="0">Tất cả lĩnh vực</option>
                <?php if (!empty($fields)): ?>
                  <?php foreach ($fields as $f): ?>
                    <option value="<?= (int)$f['ma_linh_vuc'] ?>"
                      <?= (int)($fieldId ?? 0) === (int)$f['ma_linh_vuc'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($f['ten_linh_vuc']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div>
              <label class="block text-xs font-medium text-slate-600 mb-1">Khoảng lương (từ khóa)</label>
              <input type="text"
                     name="salary"
                     value="<?= htmlspecialchars($_GET['salary'] ?? '') ?>"
                     class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400"
                     placeholder="VD: 10-15 triệu, thỏa thuận..." />
            </div>
          </div>
          <button type="submit"
                  class="w-full inline-flex items-center justify-center rounded-lg bg-primary-600 text-white text-sm font-semibold py-2.5 hover:bg-primary-700">
            Tìm kiếm
          </button>
        </form>
      </div>
      </div>
    </div>
  </div>
</section>

<section id="job-list" class="mt-4">
  <?php $hasSearch = !empty($keyword) || !empty($fieldId); ?>
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
      <?php if (!empty($keyword) || !empty($fieldId)): ?>
        <p class="text-sm text-slate-600 mb-1">Không tìm thấy tin tuyển dụng phù hợp với điều kiện tìm kiếm.</p>
        <p class="text-xs text-slate-400">Hãy thử từ khóa khác hoặc bỏ bớt điều kiện lọc.</p>
      <?php else: ?>
        <p class="text-sm text-slate-600 mb-1">Chưa có tin tuyển dụng nào được duyệt.</p>
        <p class="text-xs text-slate-400">Hãy đăng nhập bằng tài khoản doanh nghiệp để đăng tin đầu tiên.</p>
      <?php endif; ?>
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
          <p class="px-4 mt-1 text-xs text-slate-600 mb-2 line-clamp-3">
            <?= nl2br(htmlspecialchars(substr($job['mo_ta_cong_viec'], 0, 140))) ?>...
          </p>
          <div class="flex items-center justify-between px-4 pb-4 mt-2">
            <span class="text-[11px] font-medium text-slate-500">
              <?= htmlspecialchars($job['ten_cong_ty'] ?? 'Doanh nghiệp ẩn danh') ?>
            </span>
            <span class="text-[11px] text-primary-500 group-hover:text-primary-600 font-medium">
              Xem chi tiết &rarr;
            </span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
