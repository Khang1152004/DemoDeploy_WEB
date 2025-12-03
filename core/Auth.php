<?php
class Auth {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function userId() {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }

    public static function role() {
        self::start();
        return $_SESSION['role'] ?? null;
    }

    public static function email() {
        self::start();
        return $_SESSION['email'] ?? null;
    }

    public static function login($id, $email, $role) {
        self::start();
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
    }

    public static function logout() {
        self::start();
        session_destroy();
    }

    public static function requireRole($roles = []) {
        self::start();
        if (!self::userId() || ($roles && !in_array(self::role(), $roles, true))) {
            header("Location: index.php?c=Auth&a=login");
            exit;
        }
    }
}
