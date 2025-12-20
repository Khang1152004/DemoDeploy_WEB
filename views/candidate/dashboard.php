<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Dashboard Ứng viên</h3>
  <a href="index.php?c=candidate&a=account" class="btn btn-outline-primary">Chỉnh sửa hồ sơ</a>
</div>

<p class="text-muted">Danh sách các tin bạn đã ứng tuyển.</p>

<?php if (empty($applications)): ?>
  <div class="alert alert-info">Bạn chưa ứng tuyển tin nào.</div>
<?php else: ?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Tin tuyển dụng</th>
        <th>Công ty</th>
        <th>Ngày nộp</th>
        <th>Trạng thái</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($applications as $app): ?>
        <tr>
          <td><?= htmlspecialchars($app['tieu_de']) ?></td>
          <td><?= htmlspecialchars($app['ten_cong_ty'] ?? '') ?></td>
          <td><?= htmlspecialchars($app['ngay_nop']) ?></td>
          <td>
          <?php
            $status = $app['trang_thai'] ?? 'submitted';
            $label = 'Đã nộp';
            $cls = 'secondary';
            if ($status === 'invited') {
                $label = 'Được mời phỏng vấn';
                $cls = 'success';
            } elseif ($status === 'rejected') {
                $label = 'Đã từ chối';
                $cls = 'danger';
            }
          ?>
          <span class="badge bg-<?= $cls ?>"><?= $label ?></span>
        </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
