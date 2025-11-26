<h3 class="text-lg font-semibold text-slate-900 mb-4">Quản lý lĩnh vực</h3>
<form method="post" class="mb-3 d-flex">
  <input class="form-control me-2" name="ten_linh_vuc" placeholder="Tên lĩnh vực mới">
  <button class="inline-flex items-center px-3 py-2 rounded-lg bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600">Thêm</button>
</form>
<table class="min-w-full text-xs border border-slate-200 rounded-lg overflow-hidden">
  <thead class="bg-slate-50 text-slate-600"><tr><th>ID</th><th>Tên lĩnh vực</th><th></th></tr></thead>
  <tbody class="divide-y divide-slate-100">
    <?php foreach ($fields as $f): ?>
      <tr>
        <td><?= (int)$f['ma_linh_vuc'] ?></td>
        <td><?= htmlspecialchars($f['ten_linh_vuc']) ?></td>
        <td>
          <form method="post" style="display:inline" onsubmit="return confirm('Xóa lĩnh vực này?');">
            <input type="hidden" name="delete_id" value="<?= (int)$f['ma_linh_vuc'] ?>">
            <button class="inline-flex items-center px-2 py-1 rounded-md bg-red-500 text-white text-xs font-medium hover:bg-red-600">Xóa</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
