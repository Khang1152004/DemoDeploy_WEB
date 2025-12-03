<h3 class="text-lg font-semibold text-slate-900 mb-4">Quản lý kỹ năng</h3>

<form method="post" class="row g-2 mb-3">
  <div class="col-md-5">
    <input class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400" name="ten_ky_nang" placeholder="Tên kỹ năng (VD: PHP, Java, Marketing)" required>
  </div>
  <div class="col-md-4">
    <select name="ma_linh_vuc" class="form-select" required>
      <option value="">-- Thuộc lĩnh vực --</option>
      <?php foreach ($fields as $f): ?>
        <option value="<?= (int)$f['ma_linh_vuc'] ?>"><?= htmlspecialchars($f['ten_linh_vuc']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <button class="btn btn-primary w-100">Thêm kỹ năng</button>
  </div>
</form>

<form method="get" class="row g-2 mb-3">
  <input type="hidden" name="c" value="Admin">
  <input type="hidden" name="a" value="skills">
  <div class="col-md-4">
    <select name="field" class="form-select" onchange="this.form.submit()">
      <option value="0">-- Tất cả lĩnh vực --</option>
      <?php foreach ($fields as $f): ?>
        <option value="<?= (int)$f['ma_linh_vuc'] ?>" <?= ($filterFieldId ?? 0) == $f['ma_linh_vuc'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($f['ten_linh_vuc']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
</form>

<table class="min-w-full text-xs border border-slate-200 rounded-lg overflow-hidden">
  <thead class="bg-slate-50 text-slate-600"><tr><th>ID</th><th>Tên kỹ năng</th><th>Lĩnh vực</th><th></th></tr></thead>
  <tbody class="divide-y divide-slate-100">
    <?php foreach ($skills as $s): ?>
      <tr>
        <td><?= (int)$s['ma_ky_nang'] ?></td>
        <td><?= htmlspecialchars($s['ten_ky_nang']) ?></td>
        <td><?= htmlspecialchars($s['ten_linh_vuc'] ?? 'Chưa gắn') ?></td>
        <td>
          <form method="post" style="display:inline" onsubmit="return confirm('Xóa kỹ năng này?');">
            <input type="hidden" name="delete_id" value="<?= (int)$s['ma_ky_nang'] ?>">
            <button class="inline-flex items-center px-2 py-1 rounded-md bg-red-500 text-white text-xs font-medium hover:bg-red-600">Xóa</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
