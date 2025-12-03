<h3>Ứng tuyển: <?= htmlspecialchars($job['tieu_de']) ?></h3>
<p class="text-muted">Công ty: <?= htmlspecialchars($job['ten_cong_ty'] ?? '') ?></p>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
  <div class="mb-3">
    <label class="form-label">Họ tên liên hệ</label>
    <input type="text" name="ho_ten_lien_he" class="form-control"
           value="<?= htmlspecialchars($prefill['ho_ten_lien_he'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email liên hệ</label>
    <input type="email" name="email_lien_he" class="form-control"
           value="<?= htmlspecialchars($prefill['email_lien_he'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Số điện thoại liên hệ</label>
    <input type="text" name="sdt_lien_he" class="form-control"
           value="<?= htmlspecialchars($prefill['sdt_lien_he'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label class="form-label">File CV (pdf/doc/docx)</label>
    <input type="file" name="cv_file" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Gửi hồ sơ</button>
  <a href="index.php" class="btn btn-secondary">Quay lại</a>
</form>

<p class="mt-3 text-muted">
  Bạn có thể ứng tuyển không cần đăng nhập. Nếu đăng nhập với vai trò Ứng viên,
  bạn sẽ xem được danh sách hồ sơ đã ứng tuyển trong Dashboard.
</p>
