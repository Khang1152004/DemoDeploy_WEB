<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Dashboard Doanh nghiệp</h3>
  <div>
    <a href="index.php?c=employer&a=profile" class="btn btn-outline-secondary me-2">Thông tin công ty</a>
    <a href="index.php?c=employer&a=createJob" class="btn btn-primary">Đăng tin tuyển dụng</a>
  </div>
</div>

<?php if (!empty($employer)): ?>
  <div class="mb-4">
    <h5>Thông tin doanh nghiệp:</h5>
    <p><strong>Tên công ty:</strong> <?= htmlspecialchars($employer['ten_cong_ty']) ?></p>
    <p><strong>Trạng thái duyệt:</strong> <?= htmlspecialchars($employer['trang_thai_duyet']) ?></p>
  </div>
<?php endif; ?>

<h5>Tin tuyển dụng đã đăng</h5>
<?php if (empty($jobs)): ?>
  <div class="alert alert-info">Bạn chưa đăng tin tuyển dụng nào.</div>
<?php else: ?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Tiêu đề</th>
        <th>Hạn nộp</th>
        <th>Trạng thái</th>
        <th>Ứng viên</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($jobs as $job): ?>
        <tr>
          <td><?= htmlspecialchars($job['tieu_de']) ?></td>
          <td><?= htmlspecialchars($job['han_nop_ho_so']) ?></td>
          <td><?= htmlspecialchars($job['trang_thai_tin_dang']) ?></td>
          <td>
            <a class="btn btn-sm btn-outline-secondary" href="index.php?c=employer&a=viewApplications&job_id=<?= $job['ma_tin_tuyen_dung'] ?>">Xem ứng viên</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
