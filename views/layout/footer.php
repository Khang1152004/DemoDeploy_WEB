</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var notifMenuWrapper = document.getElementById('notifMenuWrapper');
  var notifMenu = document.getElementById('notifMenu');
  var notifCount = document.getElementById('notifCount');
  var notifEmpty = document.getElementById('notifEmpty');
  var notifDropdown = document.getElementById('notifDropdown');
  var userBtn = document.getElementById('userDropdownBtn');
  var userMenu = document.getElementById('userDropdownMenu');

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
    if (!notifMenuWrapper.classList.contains('hidden')) {
      fetch('index.php?c=Notification&a=markRead').then(function () {
        if (notifCount) notifCount.classList.add('hidden');
      });
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
        while (notifMenu.firstChild && notifMenu.firstChild !== notifEmpty) {
          notifMenu.removeChild(notifMenu.firstChild);
        }
        if (!Array.isArray(data) || data.length === 0) {
          if (notifEmpty) notifEmpty.style.display = 'block';
          if (notifCount) notifCount.classList.add('hidden');
          return;
        }
        if (notifEmpty) notifEmpty.style.display = 'none';
        if (notifCount) {
          notifCount.textContent = data.length;
          notifCount.classList.remove('hidden');
        }
        data.forEach(function (item) {
          var a = document.createElement('a');
          a.className = 'block px-3 py-2 text-xs hover:bg-slate-50';
          a.textContent = item.noi_dung;
          a.href = item.link ? item.link : '#';
          notifMenu.appendChild(a);
        });
      })
      .catch(function () {});
  }

  loadNotifications();
  setInterval(loadNotifications, 10000);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
