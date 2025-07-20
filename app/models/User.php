<?php
require_once 'app/database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = db_connect();
    }

    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM mv_users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function logLogin($user_id) {
        $stmt = $this->conn->prepare("INSERT INTO mv_login_logs (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
    }

    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public function getAllUsers() {
        $stmt = $this->conn->query("SELECT * FROM mv_users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function logSearch($title) {
        $conn = db_connect();
        $userId = $_SESSION['user']['id'] ?? null;
        $stmt = $conn->prepare("INSERT INTO mv_search_logs (movie_title, user_id) VALUES (?, ?)");
        $stmt->execute([$title, $userId]);
        error_log("Search saved in mv_search_logs: $title, user_id=" . ($userId ?? 'guest'));
    }

    public function getAverageRating($title) {
        $stmt = $this->conn->prepare("SELECT AVG(rating) as avg_rating FROM mv_ratings WHERE movie_title = ?");
        $stmt->execute([$title]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && $row['avg_rating'] ? round($row['avg_rating'], 2) : null;
    }

    public function getTopRatedMovies($limit = 10) {
        $stmt = $this->conn->prepare("SELECT movie_title, COUNT(*) as count, AVG(rating) as avg_rating FROM mv_ratings GROUP BY movie_title HAVING count >= 1 ORDER BY avg_rating DESC, count DESC LIMIT ?");
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRatingsByUser($userId) {
        $stmt = $this->conn->prepare("SELECT movie_title, rating, created_at FROM mv_ratings WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
