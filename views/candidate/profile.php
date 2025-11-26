<h3>Hồ sơ ứng viên</h3>

<form method="POST">
  <div class="mb-3">
    <label class="form-label">Họ tên</label>
    <input type="text" name="ho_ten" class="form-control"
           value="<?= htmlspecialchars($candidate['ho_ten'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Số điện thoại</label>
    <input type="text" name="sdt" class="form-control"
           value="<?= htmlspecialchars($candidate['s_t'] ?? $candidate['sdt'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Địa chỉ</label>
    <input type="text" name="dia_chi" class="form-control"
           value="<?= htmlspecialchars($candidate['dia_chi'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Giới thiệu ngắn</label>
    <textarea name="mo_ta_ngan" class="form-control" rows="3"><?= htmlspecialchars($candidate['mo_ta_ngan'] ?? '') ?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Mức lương mong muốn</label>
    <input type="text" name="muc_luong_mong_muon" class="form-control"
           value="<?= htmlspecialchars($candidate['muc_luong_mong_muon'] ?? '') ?>">
  </div>
  <button class="btn btn-success">Lưu hồ sơ</button>
</form>
