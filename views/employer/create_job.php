<h3>Đăng tin tuyển dụng mới</h3>
<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>
<form method="POST">
  <div class="mb-3">
    <label class="form-label">Tiêu đề</label>
    <input type="text" name="tieu_de" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Mô tả công việc</label>
    <textarea name="mo_ta_cong_viec" class="form-control" rows="4" required></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Yêu cầu ứng viên</label>
    <textarea name="yeu_cau_ung_vien" class="form-control" rows="4" required></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Mức lương (khoảng)</label>
    <input type="text" name="muc_luong_khoang" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Hạn nộp hồ sơ</label>
    <input type="date" name="han_nop_ho_so" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Lưu tin</button>
  <a href="index.php?c=employer&a=dashboard" class="btn btn-secondary">Quay lại</a>
</form>
