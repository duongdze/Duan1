<?php
// models/TourLog.php
require_once 'models/BaseModel.php';

class TourLog extends BaseModel {
    protected $table = 'tour_logs';

    public function all(): array {
        $stmt = self::$pdo->query("
            SELECT tl.*, t.name AS tour_name, g.name AS guide_name
            FROM tour_logs tl
            JOIN tours t ON t.id = tl.tour_id
            JOIN guides g ON g.id = tl.guide_id
            ORDER BY tl.date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id): ?array {
        $stmt = self::$pdo->prepare("SELECT * FROM tour_logs WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool {
        $stmt = self::$pdo->prepare("
            INSERT INTO tour_logs (tour_id, guide_id, date, description, issue, solution, customer_feedback)
            VALUES (:tour_id, :guide_id, :date, :description, :issue, :solution, :customer_feedback)
        ");
        return $stmt->execute($data);
    }

    public function updateLog($id, array $data): bool {
        $stmt = self::$pdo->prepare("
            UPDATE tour_logs SET
                tour_id = :tour_id,
                guide_id = :guide_id,
                date = :date,
                description = :description,
                issue = :issue,
                solution = :solution,
                customer_feedback = :customer_feedback
            WHERE id = :id
        ");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function deleteById($id): bool {
        $stmt = self::$pdo->prepare("DELETE FROM tour_logs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
