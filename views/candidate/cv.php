<?php
// Trang quản lý CV của ứng viên
?>
<h3 class="mb-4">Quản lý CV</h3>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e ?? '') ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-md-7 mb-4">
    <h5 class="mb-3">CV đã lưu</h5>
    <?php if (empty($cvs)): ?>
      <p class="text-muted">Chưa có CV nào.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th class="text-center" style="width:60px;">#</th>
              <th>Lĩnh vực</th>
              <th>Kỹ năng</th>
              <th>File</th>
              <th class="text-center" style="width:120px;">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cvs as $i => $cv): ?>
              <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($cv['ten_linh_vuc'] ?? '') ?></td>
                <td><?= htmlspecialchars($cv['ky_nang'] ?? '') ?></td>
                <td>
                  <?php if (!empty($cv['file_cv'])): ?>
                    <a href="<?= htmlspecialchars($cv['file_cv'] ?? '') ?>" target="_blank">Xem CV</a>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <a href="index.php?c=Candidate&a=cv&delete=<?= (int)$cv['ma_cv'] ?>"
                     class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Bạn có chắc muốn xóa CV này?');">
                    Xóa
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <div class="col-md-5">
    <h5 class="mb-3">Thêm CV mới</h5>
    <form method="post" enctype="multipart/form-data" class="border rounded-3 p-3 bg-white">
      <div class="mb-3">
        <label class="form-label">Lĩnh vực</label>
        <select name="ma_linh_vuc" id="fieldSelect" class="form-select" required>
          <option value="">-- Chọn lĩnh vực --</option>
          <?php foreach ($fields as $f): ?>
            <option value="<?= (int)$f['ma_linh_vuc'] ?>"
              <?= (isset($selectedFieldId) && (int)$selectedFieldId === (int)$f['ma_linh_vuc']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($f['ten_linh_vuc'] ?? '') ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="form-text">Chọn lĩnh vực để hiển thị kỹ năng tương ứng.</div>
      </div>

      <div class="mb-3">
        <label class="form-label">Kỹ năng (chọn nhiều)</label>
        <?php if (empty($skills)): ?>
          <p class="text-muted mb-0">Hãy chọn lĩnh vực trước.</p>
        <?php else: ?>
          <div class="border rounded p-2" style="max-height: 200px; overflow-y:auto;">
            <?php foreach ($skills as $skill): ?>
              <div class="form-check">
                <input class="form-check-input"
                       type="checkbox"
                       name="skills[]"
                       value="<?= (int)$skill['ma_ky_nang'] ?>"
                       id="skill<?= (int)$skill['ma_ky_nang'] ?>">
                <label class="form-check-label" for="skill<?= (int)$skill['ma_ky_nang'] ?>">
                  <?= htmlspecialchars($skill['ten_ky_nang'] ?? '') ?>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="mb-3">
        <label class="form-label">File CV (pdf/doc/docx)</label>
        <input type="file" name="cv_file" class="form-control" accept=".pdf,.doc,.docx" required>
      </div>

      <button type="submit" class="btn btn-primary">
        Lưu CV
      </button>
    </form>
  </div>
</div>

<script>
document.getElementById('fieldSelect')?.addEventListener('change', function () {
  var id = this.value || 0;
  var url = new URL(window.location.href);
  url.searchParams.set('field_id', id);
  window.location.href = url.toString();
});
</script>
