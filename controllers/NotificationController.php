<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends Controller {
    public function fetch() {
        Auth::start();
        header('Content-Type: application/json; charset=utf-8');
        if (!Auth::userId()) {
            echo json_encode(['items' => [], 'count' => 0]);
            return;
        }
        $userId = Auth::userId();
        $rows = Notification::unread($userId, 10);
        $count = Notification::countUnread($userId);

        $items = [];
        foreach ($rows as $row) {
            $items[] = [
                'id' => isset($row['ma_thong_bao']) ? (int)$row['ma_thong_bao'] : null,
                'message' => $row['noi_dung'] ?? '',
                'link' => $row['link'] ?? '',
                'created_at' => $row['thoi_gian_tao'] ?? ''
            ];
        }

        echo json_encode(['items' => $items, 'count' => $count]);
    }

    public function markAllRead() {
        Auth::start();
        header('Content-Type: application/json; charset=utf-8');
        if (Auth::userId()) {
            Notification::markAllRead(Auth::userId());
        }
        echo json_encode(['success' => true]);
    }
}
