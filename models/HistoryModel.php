<?php
require_once PATH_MODEL . 'BaseModel.php';

class HistoryModel extends BaseModel
{
    // Preferred table name; will be validated in constructor
    protected $table = 'tour_history';

    public function __construct()
    {
        parent::__construct();

        // Verify the table exists; if not, try to find a history-like table
        try {
            $stmt = self::$pdo->prepare("SHOW TABLES LIKE :tbl");
            $stmt->execute(['tbl' => $this->table]);
            $res = $stmt->fetch();

            if (!$res) {
                // Try to find any table containing 'history'
                $stmt2 = self::$pdo->query("SHOW TABLES");
                $tables = $stmt2->fetchAll(PDO::FETCH_COLUMN);
                $found = null;
                foreach ($tables as $t) {
                    if (stripos($t, 'history') !== false) {
                        $found = $t;
                        break;
                    }
                }

                if ($found) {
                    $this->table = $found;
                }
            }
        } catch (Exception $e) {
            // leave default table name; queries will handle missing table gracefully
        }
    }

    public function getAll($page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = self::$pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteById($id)
    {
        return $this->delete('id = :id', ['id' => $id]);
    }

    public function deleteByIds(array $ids)
    {
        // build placeholders
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM {$this->table} WHERE id IN ($placeholders)";
        $stmt = self::$pdo->prepare($sql);
        return $stmt->execute(array_values($ids));
    }

    public function clearAll()
    {
        return $this->delete();
    }
}
