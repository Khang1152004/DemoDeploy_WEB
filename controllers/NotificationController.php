<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends Controller {

    // API lấy dữ liệu cho chuông
    public function fetch() {
        Auth::start();
        header('Content-Type: application/json; charset=utf-8');

        if (!Auth::userId()) {
            echo json_encode(['items' => [], 'count' => 0]);
            return;
        }

        $userId = Auth::userId();
        $rows   = Notification::unread($userId, 10);
        $count  = Notification::countUnread($userId);

        // Trả về đúng structure: { items: [...], count: n }
        echo json_encode([
            'items' => $rows,
            'count' => $count
        ]);
    }

    // API được gọi khi mở dropdown -> đánh dấu tất cả đã đọc
    public function markRead() {
        Auth::start();
        header('Content-Type: application/json; charset=utf-8');

        if (Auth::userId()) {
            Notification::markAllRead(Auth::userId());
        }

        echo json_encode(['success' => true]);
    }

    // Alias, nếu sau này có chỗ nào gọi markAllRead thì vẫn hoạt động
    public function markAllRead() {
        $this->markRead();
    }
}
