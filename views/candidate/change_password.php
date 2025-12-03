<h3>Đổi mật khẩu (Ứng viên)</h3>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post">
  <div class="mb-3">
    <label class="form-label">Mật khẩu hiện tại</label>
    <input type="password" name="current_password" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Mật khẩu mới</label>
    <input type="password" name="new_password" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Xác nhận mật khẩu mới</label>
    <input type="password" name="confirm_password" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
</form>
