<?php
require_once 'app/database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = db_connect();
    }

    // 🔐 Fetch user by username
    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM mv_users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);  // returns false if not found
    }

    // 🕒 Log login time in mv_login_logs
    public function logLogin($user_id) {
        $stmt = $this->conn->prepare("INSERT INTO mv_login_logs (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
    }

    // 🛡️ Check if current session user is admin
    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // 🧾 Optional: Get all users (for admin report page)
    public function getAllUsers() {
        $stmt = $this->conn->query("SELECT * FROM mv_users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
