<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends Controller {

    // API dữ liệu cho chuông
    public function fetch() {
        Auth::start();
        header('Content-Type: application/json; charset=utf-8');

        if (!Auth::userId()) {
            echo json_encode(['items' => [], 'count' => 0]);
            return;
        }

        $userId = Auth::userId();

        // Lấy lịch sử gần nhất (cả đọc + chưa đọc)
        $rows  = Notification::recent($userId, 10);

        // Badge = số chưa đọc
        $count = Notification::countUnread($userId);

        echo json_encode([
            'items' => $rows,
            'count' => $count
        ]);
    }

    // Đọc 1 cái hoặc tất cả (tùy có gửi id hay không)
    public function markRead() {
        Auth::start();
        header('Content-Type: application/json; charset=utf-8');

        if (!Auth::userId()) {
            echo json_encode(['success' => false, 'unread' => 0]);
            return;
        }

        $userId = Auth::userId();

        if (!empty($_POST['id'])) {
            $id = (int)$_POST['id'];
            Notification::markOneRead($id, $userId);
        } else {
            Notification::markAllRead($userId);
        }

        $remaining = Notification::countUnread($userId);

        echo json_encode([
            'success' => true,
            'unread'  => $remaining
        ]);
    }

    // (optional) trang lịch sử thông báo
    public function index() {
        Auth::start();
        if (!Auth::userId()) {
            $this->redirect('index.php?c=Auth&a=login');
            return;
        }

        $userId = Auth::userId();
        $items  = Notification::recent($userId, 50); // 50 cái mới nhất
        $this->render('notification/index', ['items' => $items]);
    }

    // Giữ lại alias nếu chỗ khác gọi
    public function markAllRead() {
        $this->markRead();
    }
}
