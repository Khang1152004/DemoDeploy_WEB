<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Headhunter Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container mt-4">
    <?php if (!empty($_SESSION['flash_message'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
        </div>
    <?php endif; ?>

    <?= $content ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var bell = document.getElementById('notifBell');
    var badge = document.getElementById('notifCount');
    var list = document.getElementById('notifList');

    async function loadNotifications() {
        if (!badge || !list) return;
        try {
            const res = await fetch('index.php?c=notification&a=fetch');
            if (!res.ok) return;
            const data = await res.json();
            while (list.firstChild) {
                list.removeChild(list.firstChild);
            }
            if (data.items && data.items.length > 0) {
                data.items.forEach(function (item) {
                    var li = document.createElement('li');
                    var a = document.createElement('a');
                    a.className = 'dropdown-item small';
                    a.textContent = item.message;
                    a.href = item.link && item.link !== '' ? item.link : '#';
                    li.appendChild(a);
                    list.appendChild(li);
                });
            } else {
                var li = document.createElement('li');
                var span = document.createElement('span');
                span.className = 'dropdown-item text-muted small';
                span.textContent = 'Không có thông báo mới';
                li.appendChild(span);
                list.appendChild(li);
            }

            if (data.count && data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'inline-block';
            } else {
                badge.textContent = '';
                badge.style.display = 'none';
            }
        } catch (e) {
            // bỏ qua lỗi
        }
    }

    if (bell) {
        bell.addEventListener('click', function () {
            fetch('index.php?c=notification&a=markAllRead')
                .then(function () {
                    if (badge) {
                        badge.textContent = '';
                        badge.style.display = 'none';
                    }
                })
                .catch(function () {});
        });
    }

    loadNotifications();
    setInterval(loadNotifications, 10000);
});
</script>

</body>
</html>
