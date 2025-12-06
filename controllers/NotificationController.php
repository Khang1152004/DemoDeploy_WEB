<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends Controller
{
    public function fetch()
    {
        Auth::start();
        header('Content-Type: application/json; charset=utf-8');

        if (!Auth::userId()) {
            echo json_encode(['items' => [], 'count' => 0]);
            return;
        }

        $userId = Auth::userId();
        $rows   = Notification::recent($userId, 10);
        $count  = Notification::countUnread($userId);

        echo json_encode([
            'items' => $rows,
            'count' => $count
        ]);
    }

    public function markRead()
    {
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

    public function index()
    {
        Auth::start();
        if (!Auth::userId()) {
            $this->redirect('index.php?c=Auth&a=login');
            return;
        }

        $userId = Auth::userId();
        $items  = Notification::recent($userId, 50);
        $this->render('notification/index', ['items' => $items]);
    }

    public function markAllRead()
    {
        $this->markRead();
    }
}
