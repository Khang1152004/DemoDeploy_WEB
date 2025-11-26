<h3>Ứng viên ứng tuyển: <?= htmlspecialchars($job['tieu_de']) ?></h3>
<?php if (empty($apps)): ?>
  <div class="alert alert-info">Chưa có ứng viên nào ứng tuyển.</div>
<?php else: ?>
  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>Họ tên</th>
        <th>Email</th>
        <th>SĐT</th>
        <th>CV</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($apps as $a): ?>
        <tr>
          <td><?= htmlspecialchars($a['ho_ten_lien_he']) ?></td>
          <td><?= htmlspecialchars($a['email_lien_he']) ?></td>
          <td><?= htmlspecialchars($a['sdt_lien_he']) ?></td>
          <td><?php if (!empty($a['cv_file'])): ?><a href="<?= htmlspecialchars($a['cv_file']) ?>" target="_blank">Xem CV</a><?php endif; ?></td>
          <td><?= htmlspecialchars($a['trang_thai']) ?></td>
          <td>
            <?php if ($a['trang_thai'] === 'submitted'): ?>
              <a class="btn btn-sm btn-success mb-1" href="index.php?c=Employer&a=updateApplication&id=<?= (int)$a['ma_don'] ?>&status=interview">Mời phỏng vấn</a>
              <a class="btn btn-sm btn-danger" href="index.php?c=Employer&a=updateApplication&id=<?= (int)$a['ma_don'] ?>&status=rejected" onclick="return confirm('Từ chối hồ sơ này?');">Từ chối</a>
            <?php else: ?>
              <span class="text-muted">Đã xử lý</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
