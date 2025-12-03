</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var notifMenuWrapper = document.getElementById('notifMenuWrapper');
  var notifMenu        = document.getElementById('notifMenu');
  var notifCount       = document.getElementById('notifCount');
  var notifEmpty       = document.getElementById('notifEmpty');
  var notifDropdown    = document.getElementById('notifDropdown');
  var userBtn          = document.getElementById('userDropdownBtn');
  var userMenu         = document.getElementById('userDropdownMenu');

  function closeAllMenus(event) {
    if (notifMenuWrapper && !notifMenuWrapper.contains(event.target) && event.target !== notifDropdown) {
      notifMenuWrapper.classList.add('hidden');
    }
    if (userMenu && !userMenu.contains(event.target) && event.target !== userBtn) {
      userMenu.classList.add('hidden');
    }
  }

  document.addEventListener('click', closeAllMenus);

  function toggleNotifMenu() {
    if (!notifMenuWrapper) return;
    notifMenuWrapper.classList.toggle('hidden');

    // Khi mở menu -> đánh dấu tất cả đã đọc + ẩn badge
    if (!notifMenuWrapper.classList.contains('hidden')) {
      fetch('index.php?c=Notification&a=markRead')
        .then(function () {
          if (notifCount) notifCount.classList.add('hidden');
        })
        .catch(function () {});
    }
  }

  function toggleUserMenu() {
    if (!userMenu) return;
    userMenu.classList.toggle('hidden');
  }

  if (notifDropdown) {
    notifDropdown.addEventListener('click', function (e) {
      e.stopPropagation();
      toggleNotifMenu();
    });
  }

  if (userBtn) {
    userBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      toggleUserMenu();
    });
  }

  function loadNotifications() {
    if (!notifMenu) return;

    fetch('index.php?c=Notification&a=fetch')
      .then(function (r) { return r.json(); })
      .then(function (data) {
        var items = (data && Array.isArray(data.items)) ? data.items : [];

        // Xóa các item cũ (giữ lại notifEmpty)
        while (notifMenu.firstChild && notifMenu.firstChild !== notifEmpty) {
          notifMenu.removeChild(notifMenu.firstChild);
        }

        if (items.length === 0) {
          if (notifEmpty) notifEmpty.style.display = 'block';
          if (notifCount) notifCount.classList.add('hidden');
          return;
        }

        if (notifEmpty) notifEmpty.style.display = 'none';

        // Cập nhật badge (ưu tiên count, fallback sang items.length)
        var count = typeof data.count === 'number' ? data.count : items.length;
        if (notifCount) {
          if (count > 0) {
            notifCount.textContent = count;
            notifCount.classList.remove('hidden');
          } else {
            notifCount.classList.add('hidden');
          }
        }

        items.forEach(function (item) {
          var a = document.createElement('a');
          a.className = 'block px-3 py-2 text-xs hover:bg-slate-50';
          a.textContent = item.noi_dung || '';
          a.href = item.link ? item.link : '#';
          notifMenu.appendChild(a);
        });
      })
      .catch(function () {});
  }

  // load lần đầu + auto refresh mỗi 10s
  loadNotifications();
  setInterval(loadNotifications, 10000);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
