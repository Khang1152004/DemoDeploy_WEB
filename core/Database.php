<?php
class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            self::$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            self::$conn->set_charset("utf8mb4");
        }
        return self::$conn;
    }
}
