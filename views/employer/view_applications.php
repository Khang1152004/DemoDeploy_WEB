<h3>Danh sách ứng viên ứng tuyển</h3>

<?php if (empty($applications)): ?>
  <div class="alert alert-info">Chưa có ứng viên ứng tuyển tin này.</div>
<?php else: ?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Họ tên liên hệ</th>
        <th>Email liên hệ</th>
        <th>SĐT</th>
        <th>CV</th>
        <th>Ngày nộp</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($applications as $app): ?>
        <tr>
          <td><?= htmlspecialchars($app['ho_ten_lien_he'] ?? $app['ten_ung_vien'] ?? '') ?></td>
          <td><?= htmlspecialchars($app['email_lien_he'] ?? $app['email_tai_khoan'] ?? '') ?></td>
          <td><?= htmlspecialchars($app['sdt_lien_he'] ?? '') ?></td>
          <td>
            <?php if (!empty($app['cv_file'])): ?>
              <a href="<?= htmlspecialchars($app['cv_file']) ?>" target="_blank">Xem CV</a>
            <?php endif; ?>
          </td>
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
          <td>
            <?php if (($app['trang_thai'] ?? 'submitted') === 'submitted'): ?>
              <a class="btn btn-sm btn-success mb-1" href="index.php?c=employer&a=updateApplicationStatus&job_id=<?= (int)$jobId ?>&application_id=<?= (int)$app['ma_don'] ?>&status=invited">Mời phỏng vấn</a>
              <a class="btn btn-sm btn-outline-danger" href="index.php?c=employer&a=updateApplicationStatus&job_id=<?= (int)$jobId ?>&application_id=<?= (int)$app['ma_don'] ?>&status=rejected" onclick="return confirm('Bạn chắc chắn muốn từ chối hồ sơ này?');">Từ chối</a>
            <?php else: ?>
              <span class="text-muted small">Đã xử lý</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
