<h3 class="mb-4" class="text-lg font-semibold text-slate-900 mb-4">Quản lý CV</h3>

<?php if (!empty($errors)): ?>
  <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-md-7">
    <h5>CV đã lưu</h5>
    <?php if (empty($cvs)): ?>
      <p class="text-muted">Chưa có CV nào.</p>
    <?php else: ?>
      <table class="min-w-full text-xs border border-slate-200 rounded-lg overflow-hidden">
        <thead class="bg-slate-50 text-slate-600"><tr><th>#</th><th>Lĩnh vực</th><th>Kỹ năng</th><th>File</th><th></th></tr></thead>
        <tbody class="divide-y divide-slate-100">
          <?php foreach ($cvs as $i => $cv): ?>
            <tr>
              <td><?= $i+1 ?></td>
              <td><?= htmlspecialchars($cv['ten_linh_vuc']) ?></td>
              <td><?= htmlspecialchars($cv['ky_nang']) ?></td>
              <td><a href="<?= htmlspecialchars($cv['file_cv']) ?>" target="_blank">Xem CV</a></td>
              <td><a class="inline-flex items-center px-2 py-1 rounded-md bg-red-500 text-white text-xs font-medium hover:bg-red-600" onclick="return confirm('Xóa CV này?');" href="index.php?c=Candidate&a=cv&delete=<?= (int)$cv['ma_cv'] ?>">Xóa</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
  <div class="col-md-5">
    <h5>Thêm CV mới</h5>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Lĩnh vực</label>
        <select name="ma_linh_vuc" id="fieldSelect" class="form-select" required>
          <option value="">-- Chọn lĩnh vực --</option>
          <?php foreach ($fields as $f): ?>
            <option value="<?= (int)$f['ma_linh_vuc'] ?>" <?= ($selectedFieldId ?? 0) == $f['ma_linh_vuc'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($f['ten_linh_vuc']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="form-text">Chọn lĩnh vực để hiển thị kỹ năng tương ứng.</div>
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">Kỹ năng (chọn nhiều)</label><br>
        <?php if (empty($skills) && !empty($selectedFieldId)): ?>
          <span class="text-muted">Chưa có kỹ năng nào cho lĩnh vực này.</span>
        <?php elseif (empty($skills)): ?>
          <span class="text-muted">Hãy chọn lĩnh vực trước.</span>
        <?php else: ?>
          <?php foreach ($skills as $s): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="skills[]" id="sk<?= (int)$s['ma_ky_nang'] ?>" value="<?= (int)$s['ma_ky_nang'] ?>">
              <label class="form-check-label" for="sk<?= (int)$s['ma_ky_nang'] ?>"><?= htmlspecialchars($s['ten_ky_nang']) ?></label>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="block text-xs font-medium text-slate-600 mb-1">File CV (pdf/doc/docx)</label>
        <input type="file" name="cv_file" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" required>
      </div>
      <button class="inline-flex items-center px-3 py-2 rounded-lg bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600">Lưu CV</button>
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
