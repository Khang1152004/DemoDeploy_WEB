<h3>Thông tin doanh nghiệp</h3>

<form method="POST">
  <div class="mb-3">
    <label class="form-label">Tên công ty</label>
    <input type="text" name="ten_cong_ty" class="form-control"
           value="<?= htmlspecialchars($employer['ten_cong_ty'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Địa chỉ</label>
    <input type="text" name="dia_chi" class="form-control"
           value="<?= htmlspecialchars($employer['dia_chi'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="mo_ta" class="form-control" rows="4"><?= htmlspecialchars($employer['mo_ta'] ?? '') ?></textarea>
  </div>
  <button class="btn btn-success">Lưu thông tin</button>
</form>
