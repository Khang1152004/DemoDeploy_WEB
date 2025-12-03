<h3 class="mb-4">Quản lý tài khoản người dùng</h3>

<div class="table-responsive">
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:70px;">ID</th>
        <th>Email</th>
        <th>Vai trò</th>
        <th class="text-center" style="width:140px;">Trạng thái</th>
        <th class="text-center" style="width:160px;">Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($users)): ?>
        <tr>
          <td colspan="5" class="text-center text-muted">Chưa có người dùng nào.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= (int)$u['ma_nguoi_dung'] ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td>
              <?php
                $roleLabel = $u['vai_tro'];
                if ($roleLabel === 'ung_vien') $roleLabel = 'Ứng viên';
                elseif ($roleLabel === 'doanh_nghiep') $roleLabel = 'Doanh nghiệp';
                elseif ($roleLabel === 'admin') $roleLabel = 'Quản trị viên';
              ?>
              <?= htmlspecialchars($roleLabel) ?>
            </td>
            <td class="text-center">
              <?php if ((int)$u['trang_thai_hoat_dong'] === 1): ?>
                <span class="badge bg-success">Đang hoạt động</span>
              <?php else: ?>
                <span class="badge bg-secondary">Đã khóa</span>
              <?php endif; ?>
            </td>
            <td class="text-center">
              <?php if ((int)$u['ma_nguoi_dung'] === (int)Auth::userId()): ?>
                <span class="text-muted small">Không thể khóa chính mình</span>
              <?php else: ?>
                <?php if ((int)$u['trang_thai_hoat_dong'] === 1): ?>
                  <a href="index.php?c=Admin&a=toggleUserStatus&id=<?= (int)$u['ma_nguoi_dung'] ?>&action=lock"
                     class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Khóa tài khoản này?');">
                    Khóa
                  </a>
                <?php else: ?>
                  <a href="index.php?c=Admin&a=toggleUserStatus&id=<?= (int)$u['ma_nguoi_dung'] ?>&action=unlock"
                     class="btn btn-sm btn-outline-success"
                     onclick="return confirm('Mở khóa tài khoản này?');">
                    Mở khóa
                  </a>
                <?php endif; ?>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
