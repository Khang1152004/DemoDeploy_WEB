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
    // KHÔNG mark all read khi mở menu nữa
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

  // Load danh sách thông báo
  function loadNotifications() {
    if (!notifMenu) return;

    fetch('index.php?c=Notification&a=fetch')
      .then(function (r) { return r.json(); })
      .then(function (data) {
        var items = (data && Array.isArray(data.items)) ? data.items : [];

        // Xóa item cũ
        while (notifMenu.firstChild && notifMenu.firstChild !== notifEmpty) {
          notifMenu.removeChild(notifMenu.firstChild);
        }

        if (items.length === 0) {
          if (notifEmpty) notifEmpty.style.display = 'block';
          if (notifCount) notifCount.classList.add('hidden');
          return;
        }

        if (notifEmpty) notifEmpty.style.display = 'none';

        var count = typeof data.count === 'number' ? data.count : 0;
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
          a.className = 'notification-item block px-3 py-2 text-xs hover:bg-slate-50';
          // chưa đọc -> in đậm
          if (String(item.da_xem) === '0') {
            a.classList.add('font-semibold');
          }
          a.dataset.id = item.ma_thong_bao;
          a.href       = item.link ? item.link : '#';
          a.innerHTML  = `
            <div>${item.noi_dung || ''}</div>
            <div class="text-[10px] text-slate-400">${item.thoi_gian_tao || ''}</div>
          `;
          notifMenu.appendChild(a);
        });
      })
      .catch(function () {});
  }

  // Click 1 thông báo -> mark read + đi tới link
  if (notifMenu) {
    notifMenu.addEventListener('click', function (e) {
      var a = e.target.closest('.notification-item');
      if (!a) return;
      e.preventDefault();

      var id   = a.dataset.id;
      var href = a.getAttribute('href') || '#';

      fetch('index.php?c=Notification&a=markRead', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
      })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        // update style
        a.classList.remove('font-semibold');
        if (notifCount && typeof data.unread === 'number') {
          if (data.unread > 0) {
            notifCount.textContent = data.unread;
            notifCount.classList.remove('hidden');
          } else {
            notifCount.classList.add('hidden');
          }
        }
      })
      .catch(function () {})
      .finally(function () {
        if (href && href !== '#') {
          window.location.href = href;
        }
      });
    });
  }

  loadNotifications();
  setInterval(loadNotifications, 10000);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
