<div class="row">
  <div class="col-md-7">
    <h3>CV của bạn</h3>
    <?php if (empty($cvs)): ?>
      <p class="text-muted">Bạn chưa lưu CV nào.</p>
    <?php else: ?>
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>Lĩnh vực</th>
            <th>Kỹ năng</th>
            <th>CV</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cvs as $index => $cv): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($cv['ten_linh_vuc'] ?? '') ?></td>
              <td><?= htmlspecialchars($cv['ky_nang'] ?? '') ?></td>
              <td>
                <?php if (!empty($cv['file_cv'])): ?>
                  <a href="<?= htmlspecialchars($cv['file_cv']) ?>" target="_blank">Xem CV</a>
                <?php endif; ?>
              </td>
              <td>
                <a href="index.php?c=candidate&a=deleteCV&cv_id=<?= (int)$cv['ma_cv'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa CV này?');">Xóa</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
  <div class="col-md-5">
    <h3>Tải lên CV mới</h3>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Lĩnh vực</label>
        <select name="ma_linh_vuc" class="form-select" required>
          <option value="">-- Chọn lĩnh vực --</option>
          <?php foreach ($fields as $f): ?>
            <option value="<?= (int)$f['ma_linh_vuc'] ?>"><?= htmlspecialchars($f['ten_linh_vuc']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Kỹ năng (có thể chọn nhiều)</label><br>
        <?php if (!empty($skills)): ?>
          <?php foreach ($skills as $sk): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="skills[]" id="sk<?= (int)$sk['ma_ky_nang'] ?>" value="<?= (int)$sk['ma_ky_nang'] ?>">
              <label class="form-check-label" for="sk<?= (int)$sk['ma_ky_nang'] ?>"><?= htmlspecialchars($sk['ten_ky_nang']) ?></label>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-muted">Admin chưa cấu hình danh sách kỹ năng.</p>
        <?php endif; ?>
      </div>
      <div class="mb-3">
        <label class="form-label">File CV</label>
        <input type="file" name="cv_file" class="form-control" required>
        <div class="form-text">Chỉ chấp nhận file pdf, doc, docx.</div>
      </div>
      <button type="submit" class="btn btn-primary">Lưu CV</button>
    </form>
  </div>
</div>
