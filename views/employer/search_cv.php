<h1 class="text-lg font-semibold text-slate-900 mb-4">Tìm kiếm CV</h1>

<div class="mb-5 bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
  <form method="get" class="grid gap-3 md:grid-cols-4 md:items-end">
    <input type="hidden" name="c" value="Employer">
    <input type="hidden" name="a" value="searchCV">

    <div class="md:col-span-2">
      <label class="block text-xs font-medium text-slate-600 mb-1">Lĩnh vực</label>
      <select name="ma_linh_vuc"
              class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
        <option value="0">-- Tất cả --</option>
        <?php foreach ($fields as $f): ?>
          <option value="<?= (int)$f['ma_linh_vuc'] ?>" <?= $fieldId == $f['ma_linh_vuc'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($f['ten_linh_vuc']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="md:col-span-2">
      <label class="block text-xs font-medium text-slate-600 mb-1">Kỹ năng</label>
      <select name="ma_ky_nang"
              class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400">
        <option value="0">-- Tất cả --</option>
        <?php foreach ($skills as $s): ?>
          <option value="<?= (int)$s['ma_ky_nang'] ?>" <?= $skillId == $s['ma_ky_nang'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($s['ten_ky_nang']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="flex gap-2 md:justify-end md:col-span-4">
      <button class="inline-flex items-center px-3 py-2 rounded-lg bg-primary-500 text-white text-xs font-semibold hover:bg-primary-600">
        Lọc
      </button>
      <a href="index.php?c=Employer&a=searchCV"
         class="inline-flex items-center px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50">
        Xóa lọc
      </a>
    </div>
  </form>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
  <h2 class="text-sm font-semibold text-slate-800 mb-3">Kết quả tìm kiếm</h2>

  <?php if (empty($results)): ?>
    <p class="text-xs text-slate-500">Chưa có CV nào phù hợp với bộ lọc hiện tại.</p>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="min-w-full text-xs border border-slate-200 rounded-lg overflow-hidden">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="px-3 py-2 text-left font-medium">Ứng viên</th>
            <th class="px-3 py-2 text-left font-medium">Email</th>
            <th class="px-3 py-2 text-left font-medium">SĐT</th>
            <th class="px-3 py-2 text-left font-medium">Lĩnh vực</th>
            <th class="px-3 py-2 text-left font-medium">Kỹ năng</th>
            <th class="px-3 py-2 text-left font-medium">CV</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <?php foreach ($results as $cv): ?>
            <tr>
              <td class="px-3 py-2"><?= htmlspecialchars($cv['ho_ten'] ?? '') ?></td>
              <td class="px-3 py-2"><?= htmlspecialchars($cv['email'] ?? '') ?></td>
              <td class="px-3 py-2"><?= htmlspecialchars($cv['sdt'] ?? '') ?></td>
              <td class="px-3 py-2"><?= htmlspecialchars($cv['ten_linh_vuc'] ?? '') ?></td>
              <td class="px-3 py-2"><?= htmlspecialchars($cv['ky_nang'] ?? '') ?></td>
              <td class="px-3 py-2">
                <?php if (!empty($cv['file_cv'])): ?>
                  <a href="<?= htmlspecialchars($cv['file_cv']) ?>" target="_blank"
                     class="text-primary-600 hover:text-primary-700 font-medium">Xem CV</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
