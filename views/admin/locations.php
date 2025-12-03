<h3 class="text-lg font-semibold text-slate-900 mb-4">Quản lý địa điểm</h3>

<form method="post" class="mb-3 d-flex">
  <input class="form-control me-2" name="ten_dia_diem" placeholder="Tên địa điểm mới (VD: Hà Nội, Hồ Chí Minh)" required>
  <button class="inline-flex items-center px-3 py-2 rounded-lg border border-transparent bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600">
    Thêm
  </button>
</form>

<table class="min-w-full text-xs border border-slate-200 rounded-lg overflow-hidden">
  <thead class="bg-slate-50 text-slate-600">
    <tr>
      <th class="px-2 py-2 text-left">ID</th>
      <th class="px-2 py-2 text-left">Tên địa điểm</th>
      <th class="px-2 py-2"></th>
    </tr>
  </thead>
  <tbody class="divide-y divide-slate-100">
    <?php if (!empty($locations)): ?>
      <?php foreach ($locations as $loc): ?>
        <tr>
          <td class="px-2 py-2"><?= (int)$loc['ma_dia_diem'] ?></td>
          <td class="px-2 py-2"><?= htmlspecialchars($loc['ten_danh_muc'] ?? '') ?></td>
          <td class="px-2 py-2 text-right">
            <form method="post" style="display:inline" onsubmit="return confirm('Xóa địa điểm này?');">
              <input type="hidden" name="delete_id" value="<?= (int)$loc['ma_dia_diem'] ?>">
              <button class="inline-flex items-center px-2 py-1 rounded bg-red-500 text-white text-xs font-medium hover:bg-red-600">
                Xóa
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="3" class="px-2 py-3 text-center text-slate-500">Chưa có địa điểm nào.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
