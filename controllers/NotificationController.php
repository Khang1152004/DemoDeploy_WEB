<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends Controller {
    public function fetch() {
        Auth::start();
        header('Content-Type: application/json; charset=utf-8');
        if (!Auth::userId()) { echo json_encode([]); return; }
        $items = Notification::unread(Auth::userId());
        echo json_encode($items);
    }

    public function markRead() {
        Auth::start();
        header('Content-Type: application/json; charset=utf-8');
        if (Auth::userId()) {
            Notification::markAllRead(Auth::userId());
        }
        echo json_encode(['success'=>true]);
    }
}
