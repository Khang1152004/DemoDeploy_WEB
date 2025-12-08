<h1 class="text-lg font-semibold text-slate-900 mb-4">Trang quản trị</h1>


<div class="row mb-4">
  <div class="col-md-3 mb-3">
    <div class="card border-primary h-100">
      <div class="card-body py-3">
        <div class="fw-semibold small text-muted">Tổng số người dùng</div>
        <div class="fs-4 fw-bold"><?= (int)($totalUsers ?? 0) ?></div>
        <div class="small text-muted mt-1">
          Ứng viên: <?= (int)($candidateCount ?? 0) ?> ·
          Doanh nghiệp: <?= (int)($employerCount ?? 0) ?> ·
          Admin: <?= (int)($adminCount ?? 0) ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card border-success h-100">
      <div class="card-body py-3">
        <div class="fw-semibold small text-muted">Tổng số tin tuyển dụng</div>
        <div class="fs-4 fw-bold"><?= (int)($totalJobs ?? 0) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card border-warning h-100">
      <div class="card-body py-3">
        <div class="fw-semibold small text-muted">Tin đang chờ duyệt</div>
        <div class="fs-4 fw-bold"><?= (int)($pendingJobsCount ?? 0) ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-3">
    <div class="card border-danger h-100">
      <div class="card-body py-3">
        <div class="fw-semibold small text-muted">Tài khoản đang bị khóa</div>
        <div class="fs-4 fw-bold"><?= (int)($lockedUsers ?? 0) ?></div>
      </div>
    </div>
  </div>
</div>

<div class="grid gap-5 lg:grid-cols-2">
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
    <h2 class="text-sm font-semibold text-slate-800 mb-3">Tin tuyển dụng chờ duyệt</h2>
    <?php if (empty($pendingJobs)): ?>
      <p class="text-xs text-slate-500">Không có tin chờ duyệt.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="min-w-full text-xs border border-slate-200 rounded-lg overflow-hidden">
          <thead class="bg-slate-50 text-slate-600">
            <tr>
              <th class="px-3 py-2 text-left font-medium">Tiêu đề</th>
              <th class="px-3 py-2 text-left font-medium">Doanh nghiệp</th>
              <th>Xem chi tiết</th>
              <th class="px-3 py-2 text-left font-medium"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php foreach ($pendingJobs as $j): ?>
              <tr>
                <td class="px-3 py-2"><?= htmlspecialchars($j['tieu_de']) ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($j['ten_cong_ty'] ?? '') ?></td>
                <td>
                  <a href="index.php?c=Job&a=detail&id=<?= (int)$job['ma_tin_tuyen_dung'] ?>&from=admin_pending"
                    class="inline-flex items-center px-3 py-1 rounded-lg border border-slate-300 text-xs text-slate-700 hover:bg-slate-100">
                    Xem chi tiết
                  </a>
                </td>

                <td class="px-3 py-2 space-x-1">
                  <a class="inline-flex items-center px-2 py-1 rounded-md bg-emerald-500 text-white text-xs font-medium hover:bg-emerald-600"
                    href="index.php?c=Admin&a=approveJob&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>&status=approved">
                    Duyệt
                  </a>
                  <a class="inline-flex items-center px-2 py-1 rounded-md bg-red-500 text-white text-xs font-medium hover:bg-red-600"
                    href="index.php?c=Admin&a=approveJob&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>&status=rejected">
                    Từ chối
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
    <h2 class="text-sm font-semibold text-slate-800 mb-3">Yêu cầu xóa tin</h2>
    <?php if (empty($deleteRequests)): ?>
      <p class="text-xs text-slate-500">Không có yêu cầu xóa.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="min-w-full text-xs border border-slate-200 rounded-lg overflow-hidden">
          <thead class="bg-slate-50 text-slate-600">
            <tr>
              <th class="px-3 py-2 text-left font-medium">Tiêu đề</th>
              <th class="px-3 py-2 text-left font-medium">Doanh nghiệp</th>
              <th>Xem chi tiết</th>
              <th class="px-3 py-2 text-left font-medium"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php foreach ($deleteRequests as $j): ?>
              <tr>
                <td class="px-3 py-2"><?= htmlspecialchars($j['tieu_de']) ?></td>
                <td class="px-3 py-2"><?= htmlspecialchars($j['ten_cong_ty'] ?? '') ?></td>
                <td>
                  <a href="index.php?c=Job&a=detail&id=<?= (int)$item['ma_tin_tuyen_dung'] ?>"
                    class="inline-flex items-center px-3 py-1 rounded-lg border border-slate-300 text-xs text-slate-700 hover:bg-slate-100">
                    Xem chi tiết
                  </a>
                </td>
                <td class="px-3 py-2 space-x-1">
                  <a class="inline-flex items-center px-2 py-1 rounded-md bg-red-500 text-white text-xs font-medium hover:bg-red-600"
                    href="index.php?c=Admin&a=handleDeleteRequest&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>&action=approve">
                    Xóa theo yêu cầu
                  </a>
                  <a class="inline-flex items-center px-2 py-1 rounded-md border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50"
                    href="index.php?c=Admin&a=handleDeleteRequest&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>&action=reject">
                    Từ chối yêu cầu
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>