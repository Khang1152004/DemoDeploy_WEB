<h3>Quản lý tin tuyển dụng</h3>

<form method="get" class="row g-2 mb-3">
  <input type="hidden" name="c" value="Admin">
  <input type="hidden" name="a" value="jobs">
  <div class="col-md-4">
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="pending"   <?= ($status ?? '')==='pending' ? 'selected' : '' ?>>Chờ duyệt</option>
      <option value="approved"  <?= ($status ?? '')==='approved' ? 'selected' : '' ?>>Đã duyệt</option>
      <option value="rejected"  <?= ($status ?? '')==='rejected' ? 'selected' : '' ?>>Bị từ chối</option>
      <option value="delete_pending" <?= ($status ?? '')==='delete_pending' ? 'selected' : '' ?>>Chờ xóa</option>
      <option value="deleted"   <?= ($status ?? '')==='deleted' ? 'selected' : '' ?>>Đã xóa</option>
    </select>
  </div>
</form>

<?php if (empty($jobs)): ?>
  <div class="alert alert-info">Không có tin với trạng thái này.</div>
<?php else: ?>
  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>#</th>
        <th>Tiêu đề</th>
        <th>Doanh nghiệp</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($jobs as $i => $j): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= htmlspecialchars($j['tieu_de']) ?></td>
          <td><?= htmlspecialchars($j['ten_cong_ty'] ?? '') ?></td>
          <td><?= htmlspecialchars($j['trang_thai_tin_dang']) ?></td>
          <td>
            <?php if ($j['trang_thai_tin_dang'] === 'pending'): ?>
              <a class="btn btn-sm btn-success" href="index.php?c=Admin&a=approveJob&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>&status=approved">Duyệt</a>
              <a class="btn btn-sm btn-danger" href="index.php?c=Admin&a=approveJob&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>&status=rejected">Từ chối</a>
            <?php elseif ($j['trang_thai_tin_dang'] === 'approved'): ?>
              <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa trực tiếp tin này?');" href="index.php?c=Admin&a=deleteJobDirect&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>">Xóa (admin)</a>
            <?php elseif ($j['trang_thai_tin_dang'] === 'delete_pending'): ?>
              <a class="btn btn-sm btn-danger" href="index.php?c=Admin&a=handleDeleteRequest&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>&action=approve">Xóa theo yêu cầu</a>
              <a class="btn btn-sm btn-secondary" href="index.php?c=Admin&a=handleDeleteRequest&id=<?= (int)$j['ma_tin_tuyen_dung'] ?>&action=reject">Từ chối yêu cầu</a>
            <?php else: ?>
              <span class="text-muted">Đã xử lý</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
