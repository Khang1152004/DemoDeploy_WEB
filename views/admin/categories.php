<h3 class="text-lg font-semibold text-slate-900 mb-4">Quản lý danh mục</h3>

<ul class="nav nav-tabs mb-3">
  <li class="nav-item">
    <a class="nav-link <?= ($type === 'field') ? 'active' : '' ?>"
       href="index.php?c=Admin&a=categories&type=field">
      Lĩnh vực
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= ($type === 'skill') ? 'active' : '' ?>"
       href="index.php?c=Admin&a=categories&type=skill">
      Kỹ năng
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= ($type === 'location') ? 'active' : '' ?>"
       href="index.php?c=Admin&a=categories&type=location">
      Địa điểm
    </a>
  </li>
</ul>

<div class="mt-3">
  <?php if ($type === 'field'): ?>
    <?php include __DIR__ . '/fields.php'; ?>
  <?php elseif ($type === 'skill'): ?>
    <?php include __DIR__ . '/skills.php'; ?>
  <?php else: ?>
    <?php include __DIR__ . '/locations.php'; ?>
  <?php endif; ?>
</div>
